<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.17.
 * Time: 10:05
 */
class passHandler
{
    private $secKey = '[cloudEngine]';
    private $db = null;
    private $core = null;
    private $error = null;

    function __construct()
    {

        $this->core = new coreFunctions();

        $dbI = database::getInstance();
        $this->db = $dbI->getConnection();

        $this->error = new errorHandler();

    }


    /**
     * @param null $pass
     * @return string
     */
    function halfSpicer($pass = null)
    {
        $pass = $this->core->cleanVar($pass);
        return sha1($this->secKey . $pass . $this->secKey);
    }

    /**
     * @param null $pass
     * @return string
     */
    function fullSpicer($pass = null)
    {
        $pass = $this->core->cleanVar($pass);
        return md5($this->secKey . $this->halfSpicer($pass) . $this->secKey);
    }

    /**
     * Jelszó kódolásához szükséges metódus
     *
     * @param string $passToEncrypt
     * @return bool|string
     */
    function encryptPass($passToEncrypt = null)
    {
        $passToEncrypt = $this->core->cleanVar($passToEncrypt);
        return $this->fullSpicer($passToEncrypt);
    }

    /**
     * Jelszó visszafejtéshez szükséges metódus
     *
     * @param string $encryptedPass
     * @return bool|string
     * @deprecated Ehhez a projekthez nem kell
     */
    function decryptPass($encryptedPass = null)
    {

        if (!is_null($encryptedPass)) {
            $encryptedPass = $this->core->cleanVar($encryptedPass);
            $query = "SELECT pass FROM users WHERE `fullHash`='$encryptedPass'";

            if ($result = $this->db->query($query)) {

                $result = $result->fetch();

                return $result['pass'];

            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            $this->error->isNullError();
            return false;
        }

    }
}
