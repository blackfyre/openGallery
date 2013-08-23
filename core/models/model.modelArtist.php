<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/20/13
 * Time: 10:32 AM
 * To change this template use File | Settings | File Templates.
 */

class modelArtist extends modelsHandler {
    function getArtistBySlug($slug = null) {
        if (is_string($slug)) {

            $query = "SELECT * FROM artist WHERE slug='$slug'";

            return $this->fetchSingleRow($query);

        } else {
            return false;
        }
    }
}