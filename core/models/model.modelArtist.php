<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/20/13
 * Time: 10:32 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class modelArtist
 */
class modelArtist extends modelsHandler {

    /**
     * This method fetches all the artist data matching the slug, useful for URL based queries
     * @param null $slug
     * @return array|bool
     */
    function getArtistBySlug($slug = null) {
        if (is_string($slug)) {

            $query = "SELECT * FROM artist WHERE slug='$slug'";

            return $this->fetchSingleRow($query);

        } else {
            return false;
        }
    }

    /**
     * This method fetches all the artist data matching the id, useful for URL based queries
     * @param null $id
     * @return array|bool
     */
    function getArtistById($id = null) {
        if (is_numeric($id)) {

            $query = "SELECT * FROM artist WHERE id='$id'";

            return $this->fetchSingleRow($query);

        } else {
            return false;
        }
    }

    function getArt($artistId = null) {

        if (is_numeric($artistId)) {
            $query = "SELECT * FROM art WHERE artist='$artistId'";

            return $this->fetchAll($query);
        }

        return null;
    }

    /**
     * @param null $indexChar
     * @return array|bool
     */
    function getArtistIndex($indexChar = null) {

        if (is_string($indexChar)) {
            $query = "SELECT *, (SELECT professionName_" . _DEFAULT_LANG ." FROM artist_profession WHERE id=artist.profession) as professionName FROM artist WHERE lastName LIKE '$indexChar%'";

            return $this->fetchAll($query);
        }

        return false;
    }
}