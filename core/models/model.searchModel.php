<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/25/13
 * Time: 6:02 AM
 * To change this template use File | Settings | File Templates.
 */

class searchModel extends modelsHandler {
    function getProfessions() {
        $query = "SELECT * FROM artist_profession";

        $result = $this->fetchAll($query);

        $newSet[0] = gettext('All');

        foreach ($result AS $row) {
            $newSet[$row['id']] = $row['professionName_' . $_SESSION['lang']];
        }

        return $newSet;
    }

    function getPeriod() {
        $query = "SELECT * FROM artist_period";

        $result = $this->fetchAll($query);

        $newSet[0] = gettext('All');

        foreach ($result AS $row) {
            $newSet[$row['id']] = $row['periodName_' . $_SESSION['lang']];
        }

        return $newSet;
    }
}