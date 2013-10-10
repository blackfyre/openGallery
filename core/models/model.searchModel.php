<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/25/13
 * Time: 6:02 AM
 * To change this template use File | Settings | File Templates.
 */

class searchModel extends modelsHandler {
    /**
     * @return array
     */
    function getProfessions() {
        $query = "SELECT * FROM artist_profession";

        $result = $this->fetchAll($query);

        $newSet[0] = gettext('All');

        foreach ($result AS $row) {
            $newSet[$row['id']] = $row['professionName_' . $_SESSION['lang']];
        }

        return $newSet;
    }

    /**
     * @return array
     */
    function getPeriod() {
        $query = "SELECT * FROM artist_period";

        $result = $this->fetchAll($query);

        $newSet[0] = gettext('All');

        foreach ($result AS $row) {
            $newSet[$row['id']] = $row['periodName_' . $_SESSION['lang']];
        }

        return $newSet;
    }

    function artistSearch($searchData = null) {

        if (is_array($searchData)) {


            $query = "SELECT * FROM artist WHERE (lastName LIKE '%{$searchData['artistName']}%' OR firstName LIKE '%{$searchData['artistName']}%')";

            if (is_numeric($searchData['profession']) AND $searchData['profession']!='0') {
                $query .= " AND profession='{$searchData['profession']}'";
            }

            if (is_numeric($searchData['period']) AND $searchData['period']!='0') {
                $query .= " AND period='{$searchData['period']}'";
            }

            $query .= " AND active='1'";

            return $this->fetchAll($query);
        }

        return null;
    }
}