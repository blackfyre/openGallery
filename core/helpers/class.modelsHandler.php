<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.05.03.
 * Time: 11:57
 */

class modelsHandler
{
    /**
     * @var null|PDO
     */
    public $db = null;

    /**
     * @var bool|null
     */
    protected $debug = null;

    /**
     * @var errorHandler|null
     */
    protected $error = null;


    function __construct($debug = true)
    {
        $this->debug = $debug;
        $dbh = database::getInstance();
        $this->db = $dbh->getConnection();
        $this->error = new errorHandler($debug);
    }

    /**
     * @param $query
     * @return bool
     */
    protected function checkIfRecordExists($query)
    {
        if ($result = $this->db->query($query)) {
            if ($result->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->error->queryError();
            return false;
        }
    }

    /**
     * A lekérdezés összes sorát visszaadja
     * @param null $query
     * @return array|bool
     */
    protected function fetchAll($query = null)
    {
        if (!is_null($query)) {

            if ($result = $this->db->query($query)) {

                return $result->fetchAll();

            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * A lekérdezés első sorát adja vissza
     * @param null $query
     * @return bool|array
     */
    protected function fetchSingleRow($query = null)
    {
        if (!is_null($query)) {

            if ($result = $this->db->query($query . ' LIMIT 1')) {

                return $result->fetch();

            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * @param array $data az adatok 'oszlop'=>'érték'
     * @param string $tableName a tábla neve
     * @param string $method művelet INSERT|UPDATE
     * @param null $where UPDATE esetén a WHERE helye
     * @param bool $checkForDuplicates
     * @return bool|null
     */
    public function fragger($data, $tableName, $method = 'insert', $where = null,$checkForDuplicates = false)
    {



        /*
         * Ha a sorok száma nuku akkor felejtős
         */
        if (count($data)==0) {
            return false;
        }



        $qry = null;

        if (strtoupper($method) == 'INSERT') {

            /*
             * Így kezdődik az INSERT
             */

            $colNames = null;
            $rowValues = null;
            $setParts = null;

            foreach ($data AS $col => $row) {
                $colNames[] = '`' . $col . '`';

                if ($row == 'NULL') {
                    $rowValues[] = "NULL";
                    $setParts[] = '`' . $col . "`IS NULL";
                } else {
                    $rowValues[] = "'" . htmlspecialchars($row,ENT_QUOTES) . "'";
                    $setParts[] = '`' . $col . "`='" . htmlspecialchars($row,ENT_QUOTES) . "'";
                }



            }

            if ($checkForDuplicates) {

                $check = "SELECT * FROM `$tableName` WHERE " . implode(' AND ', $setParts);

                if ($this->checkIfRecordExists($check)) {
                    return null;
                }
            }

            $qry = 'INSERT INTO `' . $tableName . '` (' . implode(', ', $colNames) . ') VALUES (' . implode(', ', $rowValues) . ')';


        } elseif (strtoupper($method) == 'UPDATE') {

            $setParts = null;

            foreach ($data AS $colName => $rowValue) {
                if ($rowValue == 'NOW()') {
                    $setParts[] = '`' . $colName . "`=" . $rowValue;
                } elseif ($rowValue == 'NULL') {
                    $setParts[] = '`' . $colName . "`=" . $rowValue;
                } else {
                    $setParts[] = '`' . $colName . "`='" . htmlspecialchars($rowValue,ENT_QUOTES) . "'";
                }

            }


            if ($checkForDuplicates) {

                $check = "SELECT * FROM `$tableName` WHERE " . implode(' AND ', $setParts);

                if (!is_null($where)) {
                    $check .= ' AND ' . $where;
                }

                if ($this->checkIfRecordExists($check)) {
                    return null;
                }
            }


                $qry = 'UPDATE `' . $tableName . '` SET ' . implode(', ', $setParts);


            if (!is_null($where)) {
                $qry .= ' WHERE ' . $where;
            }

        }

        if (!is_null($qry)) {

            if ($this->db->query($qry)) {
                return true;
            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            $this->error->errorMSG('Nem jött létre futtatható lekérdezés!');
            return false;
        }

    }

    /**
     * @return string
     */
    public function lastInsertID() {
        return $this->db->lastInsertId();
    }

    /**
     * @return array|bool
     */
    function getActiveLanguages() {
        $query = "SELECT * FROM languages WHERE active='1'";
        return $this->fetchAll($query);
    }
}