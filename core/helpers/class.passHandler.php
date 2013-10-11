<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.17.
 * Time: 10:05
 */
class passHandler
{

    private static $secKey = 'oWGA-HA';

    /**
     * @param null $pass
     * @return string
     */
    function halfSpicer($pass = null)
    {
        $pass = coreFunctions::cleanVar($pass);
        return sha1(self::$secKey . $pass . self::$secKey);
    }

    /**
     * @param null $pass
     * @return string
     */
    function fullSpicer($pass = null)
    {
        $pass = coreFunctions::cleanVar($pass);
        return md5(self::$secKey . self::halfSpicer($pass) . self::$secKey);
    }

    /**
     *
     * This creates the encoded password...
     *
     * @param string $passToEncrypt
     * @return bool|string
     */
    public static function encryptPass($passToEncrypt = null)
    {
        $passToEncrypt = coreFunctions::cleanVar($passToEncrypt);
        return self::fullSpicer($passToEncrypt);
    }
}
