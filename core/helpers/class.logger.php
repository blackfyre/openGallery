<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.06.03.
 * Time: 12:41
 */

class logger {
    private $db = null;

    function __construct() {
        $dbi = database::getInstance();
        $this->db = $dbi->getConnection();

        /*
         * Ha nincs meg a loghoz szükséges tábla akkor hozza létre a rendszer automatikusan
         */
        $this->checkForTable();
    }

    function checkForTable() {

        $createQuery = "
CREATE TABLE `_log_error` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`addedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`message` TEXT NOT NULL,
	`trace` TEXT NOT NULL,
	`url` TEXT NOT NULL,
	`post` TEXT NOT NULL,
	`session` TEXT NOT NULL,
	`get` TEXT NOT NULL,
	`server` TEXT NOT NULL,
	`cookie` TEXT NOT NULL,
	`request` TEXT NOT NULL,
	`cData` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
        ";

        $query = "SHOW TABLES LIKE '_log_error'";

        if (!$this->db->query($query)->rowCount()>0) {
            $this->db->query($createQuery);
        }

    }

    private function fragger($data)
    {



        /*
         * Ha a sorok száma nuku akkor felejtős
         */
        if (count($data)==0) {
            return false;
        }



        $qry = null;


            /*
             * Így kezdődik az INSERT
             */

            $colNames = null;
            $rowValues = null;
            $setParts = null;

            foreach ($data AS $col => $row) {
                $colNames[] = '`' . $col . '`';
                $rowValues[] = "'" . $row . "'";
                $setParts[] = '`' . $col . "`='" . $row . "'";
            }


            $qry = 'INSERT INTO `_log_error` (' . implode(', ', $colNames) . ') VALUES (' . implode(', ', $rowValues) . ')';



        if (!is_null($qry)) {

            if ($this->db->query($qry)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /**
     * @param string $message
     * @param null $cData
     * @param null $trace
     *
     * $return void
     */
    function logEntry($message = null, $cData = null, $trace = null) {
        $data['message'] = htmlspecialchars($message,ENT_QUOTES);
        $data['url'] = htmlspecialchars($_SERVER['REQUEST_URI'],ENT_QUOTES);
        $data['post'] = json_encode($_POST);
        $data['session'] = json_encode($_SESSION);
        $data['get'] = json_encode($_GET);
        $data['cookie'] = json_encode($_COOKIE);
        $data['request'] = json_encode($_REQUEST);
        $data['server'] = json_encode($_SERVER);
        $data['cData'] = json_encode($cData);
        $data['trace'] = json_encode($trace);

        $this->fragger($data);
    }
}