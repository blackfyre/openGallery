<?php


abstract class main
{

    protected $db = null;
    protected $debug = false;
    protected $log = true;
    protected $siteLang = null;
    protected $docRoot = null;
    protected $smarty = null;
    protected $spice = 'wga';
    protected $dbHost = 'localhost';
    protected $dbUser = 'wga';
    protected $dbPass = 'wga';
    protected $dbName = 'opengallery';


    function __construct()
    {

        // Create PDO connection if required

        if ($this->db == null) {
            $this->connectDB();
        }

        // Set docroot for relative paths if required
        if ($this->docRoot == null) {
            $this->docRoot = $_SERVER["DOCUMENT_ROOT"] . '/';
        }

        ini_set("default_charset", "utf-8");
        date_default_timezone_set('Europe/Budapest');

        // SET DEFAULT LANG
        if ($this->siteLang == null) {
            $this->setLang("hu");
        }

        //initialize smarty if required
        if ($this->smarty == null) {
            $this->smarty = new Smarty();
            $this->smarty->setTemplateDir($this->cleanPath($this->docRoot . '/templates'));
            $this->smarty->setCompileDir($this->cleanPath($this->docRoot . '/templates_c'));
            $this->smarty->setConfigDir($this->cleanPath($this->docRoot . '/config'));
            $this->smarty->setCacheDir($this->cleanPath($this->docRoot . '/cache/smarty'));

            $this->smarty->assign('title', 'openGallery');

            // SET SMARTY STUFF FOR GLOBAL TEMPLATE ELEMENTS
            $this->setSmartyGlobalElements();
        }
    }

    private function connectDB() {
        $this->db = new PDO("mysql:host=" . $this->dbHost . ";dbname=" .  $this->dbName,$this->dbUser,$this->dbPass);
        //$this->db = new PDO("mysql:host=" . $this->dbHost . ";dbname=" .  $this->dbName,$this->dbUser,$this->dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $this->forceDBUnicode();
    }

    private function setSmartyGlobalElements()
    {
        $this->getMenu();

        //general elements
        $this->smarty->assign('siteLang', $this->siteLang);
        $this->smarty->assign('quickSearch', gettext('QUICKSEARCH'));
        $this->smarty->assign('whatsNew', gettext("What's new"));
        $this->smarty->assign('signUp', gettext('Sign up for the updates'));
        $this->smarty->assign('signUpLegal', gettext("By clicking the 'Sign Up!' button I accept the terms of service."));
        $this->smarty->assign('signUpButton', gettext('Sign Up!'));
        $this->smarty->assign('emailPlace', gettext('you@domain.com'));
        $this->smarty->assign('userMenuName', gettext('User menu'));

        //$this->smarty->assign('',gettext(''));
    }

    private function forceDBUnicode()
    {

        $this->db->query("SET CHARACTER SET 'utf8'");
        $this->db->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
        $this->db->query("SET character_set_results = 'utf8'");
        $this->db->query("SET character_set_server = 'utf8'");
        $this->db->query("SET character_set_client = 'utf8'");
    }

    function cleanPath($path)
    {
        $path = $this->cleanVar($path);
        $path = str_replace('//', '/', $path);
        return $path;
    }

    function __destruct()
    {
        if ($this->db != null) {
            $this->db=null;
        }
    }

    public function setLang($lang)
    {

        $lang = $this->cleanVar($lang);

        // Set conditions for gettext
        putenv("LANG=$lang");
        setlocale(LC_ALL, $lang);

        $domain = 'messages';
        bindtextdomain($domain, $this->cleanPath($this->docRoot . '/locale'));
        textdomain($domain);

        $this->siteLang = $lang;

    }

    public function errorMSG($message = null)
    {
        $message = $this->cleanVar($message);
        echo '<div class="errorMSG">';
        echo gettext('ERROR');
        echo '-> ';
        echo $message;
        echo '</div>';
    }


    /**
     * Created and shared by Paul Gobée at http://stackoverflow.com/a/12463381/1012431
     *
     * Gets the caller of the function where this function is called from
     * @param string what to return? (Leave empty to get all, or specify: "class", "function", "line", "class", etc.) - options see: http://php.net/manual/en/function.debug-backtrace.php
     * @return mixed
     */
    function getCaller($what = NULL)
    {
        $trace = debug_backtrace();
        $previousCall = $trace[2]; // 0 is this call, 1 is call in previous function, 2 is caller of that function

        if(isset($what))
        {
            return $previousCall[$what];
        }
        else
        {
            return $previousCall;
        }
    }

    public function queryError() {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('Query failed') . '->' . $caller['class'] . '->' . $caller['function'] . ': ' . $errorInfo[2];
        $this->errorMSG($msg);
    }

    public function debugMSG($message = null)
    {
        if ($this->debug and !is_null($message)) {
            $message = $this->cleanVar($message);
            echo '<div class="debugMSG">';
            echo $message;
            echo '</div>';
        }
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code generates a sample text from a larger string.
     * Ideal for intro or sample blocks.
     *
     * @param string $str source string
     * @param int $length
     * @param int $minword
     * @return string sample string
     */
    public function trimmer($str, $length, $minword = 3)
    {
        $str = strip_tags($str);
        $sub = '';
        $len = 0;

        foreach (explode(' ', $str) as $word) {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);

            if (strlen($word) > $minword && strlen($sub) >= $length) {
                break;
            }
        }

        return $sub . (($len < strlen($str)) ? '...' : '');
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code creates a slugged version of the input string ($str).
     *
     * @param string $str Input string
     * @param array $replace An array of special characters and their replacement
     * @param string $delimiter The delimiter to separate the words
     * @return string The slug version of the input string
     */
    public function slugger($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array )$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * @param string $var string to clean of nasty things
     * @return string cleaned string
     */
    public function cleanVar($var)
    {
        $var = trim(stripcslashes(strip_tags($var)));
        return $var;
    }

    public function logAction($action = null)
    {
        if ($action != null) {
            if ($this->log) {
                $query = "INSERT INTO _log(`uid`,`actionEvent`) VALUES ('','$action')";
            }
        } else {
            $this->errorMSG(gettext('_logAction_error'));
        }
    }

    public function cleanFormFields()
    {

        $r = false;

        foreach ($_POST as $key => $value) {
            $keyData = explode('-', $key);

            $keyType = $keyData[0];

            switch ($keyType) {
                case 'text':
                    $r[$keyData[1]] = $this->cleanVar($value);
                    break;
                case 'area':
                    break;
                case 'file':
                    $r[$keyData[1]] = $value;
                    break;
                case 'commit':
                    break;
                default:
                    break;
            }

        }

        return $r;

    }

    public function getMenu()
    {

        $titleCol = 'title_' . $this->siteLang;

        $altCol = 'alt_' . $this->siteLang;

        $query = "SELECT $titleCol AS title, $altCol AS alt, target, link, place AS side FROM menu ORDER BY `order`";

        if ($result = $this->db->query($query)) {
            while ($row = $result->fetch()) {

                switch ($row['side']) {
                    case 'main':
                        $main[] = $row;
                        break;
                    case 'side':
                        $side[] = $row;
                        break;
                    default:
                        break;
                }

            }

            $this->smarty->assign('mainMenu', $main);
            $this->smarty->assign('sideMenu', $side);

            return true;

        } else {
            $this->queryError();
            return false;
        }

    }
}

?>