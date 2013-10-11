<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.16.
 * Time: 10:05
 */

/**
 * Class emailNotificationsModel
 */
class emailNotificationsModel extends modelsHandler {

    function getEmailTemplate($templateId = null) {
        if (is_numeric($templateId)) {
            $query = "SELECT * FROM email_templates WHERE templateId='$templateId'";

            return $this->fetchSingleRow($query);
        }

        return false;
    }

    function checkTable() {

        $check = "SHOW TABLES  LIKE 'email_templates'";

        if ($this->db->query($check)->rowCount()==0) {
            $this->createTable();
        }

    }

    private function createTable() {
        $query = "

        CREATE TABLE `email_templates` (
            `templateId` INT(11) NOT NULL AUTO_INCREMENT,
            `title_hu` VARCHAR(200) NULL DEFAULT NULL,
            `subject_hu` VARCHAR(200) NULL DEFAULT NULL,
            `content_hu` TEXT NULL,
            PRIMARY KEY (`templateId`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB

        ";

        $this->db->query($query);
    }
}