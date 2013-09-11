<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 17:39
 */

class modelOptions extends modelsHandler {

    /**
     * @return array|bool
     */
    function getLanguages() {
        $query = "SELECT * FROM languages ORDER BY `id` ASC";
        return $this->fetchAll($query);
    }

    /**
     * @return array|bool
     */
    function getDefaultLangColumns() {

        $dbName = _DB_DB;

        $query = "
        SELECT COLUMN_NAME, TABLE_NAME, IS_NULLABLE, COLUMN_TYPE, DATA_TYPE
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE `TABLE_SCHEMA` = '$dbName' AND INSTR(COLUMN_NAME,'_hu')
        ";



        return $this->fetchAll($query);
    }

    /**
     * @return array|bool
     */
    function getUsers() {
        $query = "SELECT * FROM users";

        return $this->fetchAll($query);
    }

    /**
     * @return array|bool
     */
    function getLog() {
        $query = "SELECT * FROM _log_error ORDER BY addedOn DESC";

            return $this->fetchAll($query);
    }

    /**
     * Clears the log
     */
    function clearLog() {
        $query = "TRUNCATE _log_error";
        $this->db->query($query);
    }
}