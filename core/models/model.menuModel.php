<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.02.
 * Time: 17:03
 */

/**
 * Class menuModel
 */
class menuModel extends modelsHandler {

    /**
     * @return array|bool
     */
    function getMainMenuElements() {
        $query = "
        SELECT *,menu_elements.id AS `mId` FROM menu_elements
        LEFT JOIN content ON content.id=menu_elements.contentId
        WHERE positionId='1' and deleted='0'
        ";

        return $this->fetchAll($query);
    }

    /**
     * @param null $lang
     * @param int $position
     * @return array|bool
     */
    function getArticles($lang = null, $position = 1) {
        $query = "SELECT * FROM content WHERE editOnly='0' AND id NOT IN (SELECT DISTINCT contentId FROM menu_elements WHERE langCode='$lang' AND contentId IS NOT NULL AND positionId='$position')";

        return $this->fetchAll($query);
    }

    function getMainMenu($lang = null) {
        $query = "
        SELECT *,menu_elements.id AS `mId` FROM menu_elements
        LEFT JOIN content ON content.id=menu_elements.contentId
        WHERE positionId='1' AND deleted='0' AND active='1' AND langCode='$lang'
        ";

        return $this->fetchAll($query);
    }
}