<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/20/13
 * Time: 10:31 AM
 * To change this template use File | Settings | File Templates.
 */

class artist {

    /**
     * @var null|modelArtist
     */
    private $model = null;

    function __construct() {
        $this->model = new modelArtist();
    }

    function view($artistSlug = null) {
        $artistSlug = coreFunctions::cleanVar($artistSlug);

        $data = $this->model->getArtistBySlug($artistSlug);

        Kint::dump($data);

        $r['artistName'] = $data['lastName'] . ' ' . $data['firstName'];
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';

        $r['bio'] = $data['bio_' . $_SESSION['lang']];


        return $r;
    }
}