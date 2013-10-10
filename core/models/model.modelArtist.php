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

    function getArtPiece($id = null) {

        if (is_numeric($id)) {
            $query = "SELECT * FROM art WHERE id='$id'";

            return $this->fetchSingleRow($query);
        }

        return null;
    }

    /**
     * @param null $indexChar
     * @return array|bool
     */
    function getArtistIndex($indexChar = null) {

        if (is_string($indexChar)) {
            $query = "SELECT *, (SELECT professionName_" . _DEFAULT_LANG ." FROM artist_profession WHERE id=artist.profession) as professionName FROM artist WHERE lastName LIKE '$indexChar%' AND active='1'";

            return $this->fetchAll($query);
        }

        return false;
    }

    /**
     * Gets the profession names from the DB for the current language
     * @return bool|null
     */
    function getProfessionsForDropdown() {
        $query = "SELECT * FROM artist_profession";

        $result = $this->fetchAll($query);

        $r = null;

        if (is_array($result)) {
            foreach ($result AS $p) {
                $r[$p['id']] = $p['professionName_' . $_SESSION['lang']];
            }

            return $r;
        }

        return false;
    }

    /**
     * @return array|bool
     */
    function getProfessions() {
        $query = "SELECT * FROM artist_profession";

        return $this->fetchAll($query);
    }

    /**
     * @param int $professionId
     * @return array|bool
     */
    function getProfessionById($professionId = null) {
        if (is_numeric($professionId)) {
            $query = "SELECT * FROM artist_profession WHERE id='$professionId'";
            return $this->fetchSingleRow($query);
        }

        return false;
    }
}