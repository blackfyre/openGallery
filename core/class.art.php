<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.23.
 * Time: 22:20
 */
class art extends auth
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    private function getArtistList()
    {

        $profession = 'artist_profession.professionName_' . $this->siteLang;

        $query = "
        SELECT artist.id AS artistID,
        firstName, lastName,
        CONCAT(artist.firstName, ', ', artist.lastName) AS artistFullName,
        dateOfBirth, dateOfDeath, exactBirth, exactDeath, placeOfBirth, placeOfDeath, slug,
        artist_period.periodName AS period,
        $profession AS profession,
        artist_school.schoolName AS school
        FROM artist
        LEFT JOIN artist_period ON artist_period.id=artist.period
        LEFT JOIN artist_profession ON artist_profession.id=artist.profession
        LEFT JOIN artist_school ON artist_school.id=artist.school
        ";

        if ($result = $this->db->query($query)) {

            $r = false;

            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }

            return $r;


        } else {
            $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
            return false;
        }

    }

    function displayArtistList()
    {

        $this->smarty->assign('title', gettext('Artist Index'));

        $this->smarty->assign('artistName', gettext('Artist Name'));
        $this->smarty->assign('bornDied', gettext('Born-Died'));
        $this->smarty->assign('period', gettext('Period'));
        $this->smarty->assign('school', gettext('School'));

        $this->smarty->assign('artists', $this->getArtistList());

        $this->smarty->display('artist_list.tpl');

    }

    private function getArtistData($artistSlug = null)
    {
        if (!is_null($artistSlug)) {

            $artistSlug = $this->clean_var($artistSlug);
            $bio = 'bio_' . $this->siteLang;
            $profession = 'artist_profession.professionName_' . $this->siteLang;

            $query = "
            SELECT artist.id AS artistID,
            firstName, lastName,
            CONCAT(artist.firstName, ', ', artist.lastName) AS artistFullName,
            dateOfBirth, dateOfDeath, exactBirth, exactDeath, placeOfBirth, placeOfDeath, $bio AS bio,
            artist_period.periodName AS period,
            $profession AS profession,
            artist_school.schoolName AS school
            FROM artist
            LEFT JOIN artist_period ON artist_period.id=artist.period
            LEFT JOIN artist_profession ON artist_profession.id=artist.profession
            LEFT JOIN artist_school ON artist_school.id=artist.school
            WHERE artist.slug='$artistSlug'
            ";

            if ($result = $this->db->query($query)) {

                if ($result->num_rows == 1) {

                    return $result->fetch_assoc();

                } else {
                    $this->errorMSG(gettext('Multiple results'));
                    return false;
                }


            } else {
                $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            $this->errorMSG(gettext('Missing variable'));
            return false;
        }
    }

    private function getArtistCategoriesNumber($artistSlug = null)
    {
        if (!is_null($artistSlug)) {

            $query = "
            SELECT category FROM art
            LEFT JOIN artist ON artist.id=art.artist
            WHERE artist.slug='$artistSlug'
            GROUP BY category
            ";

            if ($result = $this->db->query($query)) {

                return $result->num_rows;

            } else {
                $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            $this->errorMSG(gettext('Missing variable'));
            return false;
        }
    }

    private function getWorks($artistSlug = null)
    {
        if (!is_null($artistSlug)) {

            $artistSlug = $this->clean_var($artistSlug);

            $query = "
            SELECT * FROM art
            LEFT JOIN artist ON artist.id=art.artist
            WHERE artist.slug='$artistSlug'
            ORDER BY title
            ";

            if ($result = $this->db->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    $r[] = $row;
                }

                return $r;

            } else {
                $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            $this->errorMSG(gettext('Missing variable'));
            return false;
        }
    }

    function displayArtist($artistSlug = null)
    {

        if (!is_null($artistSlug)) {
            $artistSlug = $this->clean_var($artistSlug);

            $data = $this->getArtistData($artistSlug);
            $workCategoryCount = $this->getArtistCategoriesNumber($artistSlug);

            $this->smarty->assign('artistFirstName', $data['firstName']);
            $this->smarty->assign('artistLastName', $data['lastName']);

            if ($data['exactBirth'] == 0) {
                $dateOfBirth = 'ca. ' . $data['dateOfBirth'];
            } else {
                $dateOfBirth = $data['dateOfBirth'];
            }

            $this->smarty->assign('dateOfBirth', $dateOfBirth);

            if ($data['exactDeath'] == 0) {
                $dateOfDeath = 'ca. ' . $data['dateOfDeath'];
            } else {
                $dateOfDeath = $data['dateOfDeath'];
            }

            $this->smarty->assign('dateOfDeath', $dateOfDeath);
            $this->smarty->assign('qualification', $data['school'] . ' ' . $data['profession']);
            $this->smarty->assign('bio', $data['bio']);

            $placeOfDeath = ($data['placeOfDeath'] == '' ? '?' : $data['placeOfDeath']);
            $this->smarty->assign('placeOfDeath', $placeOfDeath);

            $placeOfBirth = ($data['placeOfBirth'] == '' ? '?' : $data['placeOfBirth']);
            $this->smarty->assign('placeOfBirth', $placeOfBirth);

            $this->smarty->assign('works', gettext('Works'));

            $this->smarty->display('artist.tpl');
        }

        $this->errorMSG(gettext('Missing variable'));
        return false;
    }
}
