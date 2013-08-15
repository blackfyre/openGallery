<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 17:39
 */

class modelOptions extends modelsHandler {
    function getLanguages() {
        $query = "SELECT * FROM languages ORDER BY `id` ASC";
        return $this->fetchAll($query);
    }

    function getDefaultLangColumns() {
        $query = "
        SELECT COLUMN_NAME, TABLE_NAME, IS_NULLABLE, COLUMN_TYPE, DATA_TYPE
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE `TABLE_SCHEMA` = 'quizzer' AND INSTR(COLUMN_NAME,'_hu')
        ";

        return $this->fetchAll($query);
    }
}