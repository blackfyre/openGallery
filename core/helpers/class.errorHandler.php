<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.04.
 * Time: 12:07
 */

/**
 * Class errorHandler
 * Hiba kezelő osztály
 * @package blackFyre
 */
class errorHandler
{

    /**
     * @var null|PDO
     */
    private $db = null;

    /**
     * @var bool|null
     */
    private $debug = null;

    /**
     * @var logger|null
     */
    private $logger = null;

    /**
     * @var null|array
     */
    private $data = null;


    /**
     * Szükséges osztályok betöltése
     */
    function __construct($debug = false)
    {
        $this->debug = $debug;
        $dbi = database::getInstance();
        $this->db = $dbi->getConnection();
        $this->logger = new logger();

    }

    /**
     * Created and shared by Paul Gobée at http://stackoverflow.com/a/12463381/1012431
     * Gets the caller of the function where this function is called from
     *
     * @internal param null $what to return? (Leave empty to get all, or specify: "class", "function", "line", "class", etc.) - options see: http://php.net/manual/en/function.debug-backtrace.php
     * @return mixed
     */
    private function getCaller()
    {

        $trace = debug_backtrace();
        $previousCall = $trace[2]; // 0 is this call, 1 is call in previous function, 2 is caller of that function

        $this->data = $trace;

        return $previousCall;

    }

    /**
     * Adatbázis hiba megjelenítés!
     * Az errorMSG() több infóval szolgál a megjelenítés miatt
     */
    public function queryError()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = 'Sikertelen lekérdezés-> ' . $caller['class'] . '-> ' . $caller['function'] . ': ' . $errorInfo[2];
        $this->errorMSG($msg, $this->db);
    }

    /**
     * Null hiba
     */
    public function isNullError($cData = null)
    {
        $caller = $this->getCaller();
        $msg = 'NULL hiba->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg,$cData);
    }

    /**
     * Nem várt eredmény
     */
    public function notExpectedResult()
    {
        $caller = $this->getCaller();
        $msg = 'Nem várt eredmény->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Hiányzó változó
     */
    public function variableMissing()
    {
        $caller = $this->getCaller();
        $msg = 'Szükséges változó hiányzik!->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Nem engedélyezett változó típus
     */
    public function isNotAcceptedVar()
    {
        $caller = $this->getCaller();
        $msg = 'Nem engedélyezett változó típus->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * A szükséges változó nem szám!
     */
    public function isNotIntError($cData = null)
    {
        $caller = $this->getCaller();
        $msg = 'A szükséges változó nem szám!->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg,$cData);
    }

    /**
     * A vártnál több eredmény!
     */
    public function moreResultsThanExpected()
    {
        $caller = $this->getCaller();
        $msg = 'A vártnál több eredmény!->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Az ürlap hibásan került kitöltésre!
     */
    public function formValidationError()
    {
        $caller = $this->getCaller();
        $msg = 'Az ürlap hibásan került kitöltésre!->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Nem tömb
     */
    public function isNotArrayError($cData = null)
    {
        $caller = $this->getCaller();
        $msg = 'Nem tömb->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg,$cData);
    }

    /**
     * A változó típusa eltér a várttól
     */
    public function notRequiredVar()
    {
        $caller = $this->getCaller();
        $msg = 'A változó típusa eltér a várttól->' . $caller['class'] . '-> ' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * @param null $string A HIBA ÜZENET
     * @param mixed $cData A naplóba rögzítendő extra tartalom... lehet bármi
     */
    public function errorMSG($string = null,$cData = null)
    {

        $string = coreFunctions::cleanVar($string);

        $this->logger->logEntry($string,$cData);

        if ($this->debug) {
            $string = coreFunctions::cleanVar($string);

            if (!isset($_SESSION['errorInfo']) OR !is_array($_SESSION['errorInfo'])) {
                 $_SESSION['errorInfo'] = array();
            }

            $_SESSION['errorInfo'][] = $string;


        }
    }

    /**
     * @param null $string
     * @param mixed $cData A naplóba rögzítendő extra tartalom... lehet bármi
     */
    public function alwaysShowError($string = null,$cData = null) {
        $string = coreFunctions::cleanVar($string);

        $this->logger->logEntry($string,$cData,$this->data);

        if (!isset($_SESSION['errorInfo']) OR !is_array($_SESSION['errorInfo'])) {
            $_SESSION['errorInfo'] = array();
        }

        $_SESSION['errorInfo'][] = $string;
    }

    /**
     * @param null $string A HIBA ÜZENET
     */
    public function successMSG($string = null)
    {
        if (!isset($_SESSION['successInfo']) OR !is_array($_SESSION['successInfo'])) {
            $_SESSION['successInfo'] = array();
        }

        $_SESSION['successInfo'][] = coreFunctions::cleanVar($string);
    }
}
