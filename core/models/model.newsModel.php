<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 15:36
 */

class newsModel extends modelsHandler {

    /**
     * @param null $slug
     * @return array|bool
     */
    function getNewsBySlug($slug = null) {
        $slug = coreFunctions::cleanVar($slug);

        $slugCol = 'slug_' . $_SESSION['lang'];

        $query = "SELECT * FROM news WHERE $slugCol='$slug'";

        return $this->fetchSingleRow($query);
    }
}