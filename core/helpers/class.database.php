<?php
/**
 * Created by JetBrains PhpStorm.
 * User: galiczmiklos
 * Date: 2013.01.02.
 * Time: 11:18
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class database
 * Adatbáziskezelő osztály
 * @package blackFyre
 */
class database
{
    /**
     * @var null|PDO
     */
    private $connection = null;

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @return database|null
     */
    public static function getInstance()
    {
        if (!self::$instance) {

            self::$instance = new self();

        }

        return self::$instance;
    }

    /**
     * Konstruktor
     */
    function __construct()
    {

        try {

            $pdoString = "mysql:host=" . _DB_HOST . ";dbname=" . _DB_DB . ";" . "charset=utf8";

            $this->connection = new PDO($pdoString, _DB_USER, _DB_PASS);


            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


            $this->connection->query("SET CHARACTER SET 'utf8'");
            $this->connection->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
            $this->connection->query("SET character_set_results = 'utf8'");
            $this->connection->query("SET character_set_server = 'utf8'");
            $this->connection->query("SET character_set_client = 'utf8'");


        } catch (PDOException $e) {

            if (in_array($_SERVER['HTTP_HOST'],coreFunctions::decodeConstant(_TEST_ENV))) {
                $msg = '<pre>';
                $msg .= $e;
                $msg .= '</pre>';
            } else {
                $msg = 'HIBA AZ ADATBÁZIS KAPCSOLATBAN, A RENDSZERGAZADA ELÉRHETŐSÉGE: galicz.miklos@hinora.hu';
            }
            exit($msg);
        }


    }

    /**
     * Klónozás esetén
     */
    function __clone()
    {
    }

    /**
     * @return null|PDO
     */
    function getConnection()
    {
        return $this->connection;
    }


}
