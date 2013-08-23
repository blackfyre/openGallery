<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 15:15
 */

class controlHandler {

    protected $smarty = null;

    /**
     * Konstruktor
     */
    function __construct()
    {
        /*
         * LANG SETTINGS
         */

        $this->setLang();
        $this->setLangEnv();

        /*
         * DEPENDENCIES
         */
        $this->smarty = new smartyManager();
    }

    /**
     * Nyelvi környezet beállítása ha szükség van rá, az appConfig.php -ban állítható
     * @return void
     */
    private function setLangEnv() {
        /*
         * TODO: a többnyelvű tömböket hozzá kell adni, mert ez így nem lesz kóser...
         * TODO: UNIX/Winfos gettext -re megoldást találni
         */

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $locale[] = 'hun.UTF-8';
            $locale[] = 'hungarian.UTF-8';
            $locale[] = 'hun';
            $locale[] = 'hungarian';
        } else {
            $locale[] = 'hu_HU.UTF8';
            $locale[] = 'hu.UTF8';
            $locale[] = 'hu_HU@euro.UTF8';
            $locale[] = 'hun.UTF8';
            $locale[] = 'hungarian.UTF-8';
        }

        setlocale(LC_ALL, $locale);

        ini_set("default_charset", "UTF-8");
        date_default_timezone_set('Europe/Budapest');

        $pathToLangFile = './locale/' . $_SESSION['lang'];

        if (!file_exists($pathToLangFile)) {
            mkdir($pathToLangFile, 0777, true);
        }

        bindtextdomain('messages',$pathToLangFile);
        textdomain('messages');
    }

    public function methodLoader($reRouter = null, $tplFolder = 'front') {

        $data = $_GET;

        if (isset($data['lang'])) {
            unset($data['lang']);
        }

        if (is_array($reRouter)) {
            $objName = $reRouter[$data['class']];
        } else {
            $objName = $data['class'];
        }

        $functionToCall = $data['method'];

        unset($data['class'], $data['method']);

        $obj = null;

        if (class_exists($objName)) {
            $obj = new $objName;
        }

        /*
         * If the called function and the required tpl file exists
         * This is a security feature, to prevent the function from running if the required tpl file does not exists
         */
        if (method_exists($objName,$functionToCall) AND $this->tplFileExists($tplFolder,$objName,$functionToCall)) {
            /*
             * Run the method
             */
            $this->smarty->addToDisplay(call_user_func_array(array($obj, $functionToCall), $data));

            /*
             * Show the template
             */
            $this->displayPage($tplFolder,$objName,$functionToCall);

        } else {
            /*
             * If the method is not allowed to run, present the user with a 404 page
             */
            $this->throw404();
        }
    }

    /**
     * Form watching...
     *
     * @todo Make it watch the session for form handling
     *
     * @param array $formActionToCall array($classToCall, 'method') The class and fuction to call
     * @param array $formToCall array($classToCall, 'method') Form displaying method
     * @param array $successCall array($classToCall, 'method') The function to call on success
     * @param bool $logic Is the successful run of the action is required?
     * @internal param string $submitToWatch
     */
    protected function handleSingleForm($formActionToCall, $formToCall, $successCall, $logic = false)
    {

            if (count($_POST)>=1) {

                if ($logic) {

                    if (call_user_func($formActionToCall)) {
                        $this->smarty->addToDisplay(call_user_func($successCall));
                    } else {
                        $this->smarty->addToDisplay(call_user_func($formToCall));
                    }

                } else {

                    call_user_func($formActionToCall);
                    $this->smarty->addToDisplay(call_user_func($successCall));

                }

            } else {
                $this->smarty->addToDisplay(call_user_func($formToCall));
            }

    }


    /**
     * Refresh the current page from php
     * @return void
     */
    private function reloadPage()
    {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    }

    /**
     * YOU'RE DRUNK, GO HOME!!!
     */
    protected function logOutNow()
    {
        unset($_POST, $_SESSION);
        session_destroy();
        $this->youReDrunkGoHome();
    }

    /**
     * 404 page
     */
    protected function throw404() {
        header("HTTP/1.0 404 Not Found");
        include_once $_SERVER['DOCUMENT_ROOT'] . '/404.php';
    }

    /**
     * Redirect to site root
     */
    private function youReDrunkGoHome()
    {
        header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
        die;
    }

    /**
     * Set the default lang in session
     */
    private function setLang()
    {
        if (isset($_GET['lang'])) {
            $_SESSION['lang'] = coreFunctions::cleanVar(strtolower($_GET['lang']));
        } elseif (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = _DEFAULT_LANG;
        }
    }

    /**
     * @param string $tplFolder
     * @param null $classLoaded
     * @param null $methodLoaded
     * @return bool
     */
    protected function tplFileExists($tplFolder = 'front', $classLoaded = null, $methodLoaded = null) {
        $page = $tplFolder . '/' . $classLoaded . '/' . $methodLoaded . '.tpl';

        return file_exists(_SMARTY_TPLDIR . '/' . $page);
    }

    /**
     * @param string $tplFolder
     * @param null $classLoaded
     * @param null $methodLoaded
     */
    protected function displayPage($tplFolder = 'front', $classLoaded = null, $methodLoaded = null) {
        $page = $tplFolder . '/' . $classLoaded . '/' . $methodLoaded . '.tpl';

        if ($this->tplFileExists($tplFolder,$classLoaded,$methodLoaded)) {
            $this->smarty->displaySelectedPage($page);
        } else {
            $this->throw404();
        }
    }
}