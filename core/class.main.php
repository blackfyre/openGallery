<?php

include_once 'class.auth.php';
include_once 'class.static.php';
include_once 'class.art.php';
include_once '/libs/Smarty.class.php';

class main
{

    public $db = null;
    public $debug = false;
    public $log = true;
    public $siteLang = null;
    public $docRoot = null;
    public $smarty = null;
    public $spice = 'wga';


    function __construct()
    {

        // Create mysqli connection

        if ($this->db == null) {
            if ($this->db = new mysqli('localhost', 'wga', 'wga', 'opengallery')) {
                $this->debugMSG(gettext('_db_connect_success'));
                $this->forceDBUnicode();
            } else {
                $this->errorMSG(gettext('_db_connect_fail') . ': ' . $this->db->error);
            }
        }

        // Set docroot for relative paths
        $this->docRoot = $_SERVER["DOCUMENT_ROOT"] . '/';

        ini_set("default_charset", "utf-8");
        date_default_timezone_set('Europe/Budapest');

        //initialize smarty
        if ($this->smarty == null) {
            $this->smarty = new Smarty();
            $this->smarty->setTemplateDir($this->cleanPath($this->docRoot . '/templates'));
            $this->smarty->setCompileDir($this->cleanPath($this->docRoot . '/templates_c'));
            $this->smarty->setConfigDir($this->cleanPath($this->docRoot . '/config'));
            $this->smarty->setCacheDir($this->cleanPath($this->docRoot . '/cache/smarty'));

            $this->smarty->assign('title', 'openGallery');
        }

        // SET DEFAULT LANG
        if ($this->siteLang == null) {
            $this->setLang('hu');
        }

        // SET SMARTY STUFF FOR GLOBAL TEMPLATE ELEMENTS
        $this->setSmartGlobalElements();

    }

    private function setSmartGlobalElements()
    {
        $this->getMenu();
        $this->smarty->assign('quickSearch', gettext('QUICKSEARCH'));
        $this->smarty->assign('whatsNew', gettext("What's new"));
        $this->smarty->assign('signUp', gettext('Sign up for the updates'));
        $this->smarty->assign('signUpLegal', gettext("By clicking the 'Sign Up!' button I accept the terms of service."));
        $this->smarty->assign('signUpButton', gettext('Sign Up!'));
        $this->smarty->assign('emailPlace', gettext('you@domain.com'));
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
        $path = $this->clean_var($path);
        $path = str_replace('//', '/', $path);
        return $path;
    }

    function __destruct()
    {
        if ($this->db != null) {
            $this->db->close();
        }
    }

    public function setLang($lang)
    {

        $lang = $this->clean_var($lang);

        // Set conditions for gettext
        putenv("LANG=$lang");
        setlocale(LC_ALL, $lang);

        $domain = 'messages';
        bindtextdomain($domain, $this->cleanPath($this->docRoot . '/locale'));
        textdomain($domain);

        $this->siteLang = $lang;
        $this->smarty->assign('siteLang', $lang);

    }

    public function errorMSG($message = null)
    {
        $message = $this->clean_var($message);
        echo '<div class="errorMSG">';
        echo gettext('ERROR');
        echo '-> ';
        echo $message;
        echo '</div>';
    }

    public function debugMSG($message = null)
    {
        if ($this->debug and !is_null($message)) {
            $message = $this->clean_var($message);
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
    public function clean_var($var)
    {
        $var = strip_tags(stripslashes(trim(rtrim($this->db->real_escape_string($var)))));
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
                    $r[$keyData[1]] = $this->clean_var($value);
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
            while ($row = $result->fetch_assoc()) {

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

        } else {
            $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
            return false;
        }

    }
}

?>