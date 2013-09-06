<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 11:13
 */

class smartyManager {
    /**
     * @var Smarty|null
     */
    private $smarty = null;

    private $dataToDisplay = null;

    function __construct() {

        /*
         * DEPENDENCIES
         */
        $this->core = new coreFunctions();

        /*
         * SMARTY INIT
         */
        $this->smartyInit();
    }

    /**
     * Indítsuk el a smarty-t
     */
    private function smartyInit()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(_SMARTY_TPLDIR);
        $this->smarty->setCompileDir(_SMARTY_COMPILEDIR);
        $this->smarty->setConfigDir(_SMARTY_CONFIGDIR);
        $this->smarty->setCacheDir(_SMARTY_CACHE);

        $this->dataToDisplay['metaTitle'] = _DEFAULT_TITLE;
        $this->dataToDisplay['metaDesc'] = _DEFAULT_METADESC;
        $this->dataToDisplay['metaTags'] = _DEFAULT_METATAGS;
        $this->dataToDisplay['chromeFrameContent'] = gettext('Ön egy<strong>elavult</strong> böngészőt használ. Kérjük frissítse!');
        $this->dataToDisplay['googlePublisherLinkText'] = gettext('Find us on Google+');
        $this->dataToDisplay['googlePublisherID'] = _GOOGLE_PUBLISHER_ID;
        $this->dataToDisplay['jQuery'] = null;
        $this->dataToDisplay['jFunctions'] = null;
        $this->dataToDisplay['content'] = null;
        $this->dataToDisplay['openGraph'] = socialMedia::websiteTag();
        $this->dataToDisplay['googlePlusOne'] = socialMedia::googleMobileRecommend();

        $this->smartyAssigner($this->dataToDisplay);

    }

    /**
     * Tömb kulcsok alapján beállítja a megfelelő smarty elemeket
     * @param array $data
     * @return bool
     */
    private function smartyAssigner($data = null)
    {
        if (is_array($data)) {

            foreach ($data AS $key => $value) {
                $this->smarty->assign($key, $value);
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * Üzenet generátor
     * @param $data
     * @param string $type
     * @return bool|null|string
     */
    private function generateMessage($data,$type = 'error')
    {
        if (is_array($data)) {

            $r = null;

            $icon = null;
            $color = null;

            switch ($type) {
                case 'error':
                    $icon = 'icon-warning';
                    $color = 'red-gradient';
                    break;
                case 'success':
                    $icon = 'icon-tick';
                    $color = 'green-gradient';
                    break;
                default:
                    break;
            }

            foreach ($data AS $message) {
                $r .= '
                <p class="message ' . $icon . ' ' . $color . '">
                    <a href="javascript: void(0)" title="Hide message" class="close">✕</a>
                    ' . htmlspecialchars_decode($message,ENT_QUOTES) . '
                </p>
                ';
            }

            return $r;

        } else {
            return false;
        }
    }

    /**
     * A kiválaszott oldal megjelenítése
     * @param string $selectedPage
     */
    public function  displaySelectedPage($selectedPage) {
        if (isset($_SESSION['errorInfo'])) {

            if (isset($this->dataToDisplay['messages'])) {
                $this->dataToDisplay['messages'] .= $this->generateMessage($_SESSION['errorInfo']);
            } else {
                $this->dataToDisplay['messages'] = $this->generateMessage($_SESSION['errorInfo']);
            }

            unset($_SESSION['errorInfo']);
        }

        $this->smartyAssigner($this->dataToDisplay);
        $this->smarty->display($selectedPage);
    }

    /**
     * @param array $arrayToAdd
     * @return void
     */
    public function addToDisplay($arrayToAdd) {

        if (is_array($arrayToAdd)) {
            $this->dataToDisplay = array_merge($this->dataToDisplay, $arrayToAdd);
        }

    }


}