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
    /**
     * @var null|tableHandler
     */
    private $table = null;
    /**
     * @var formHandler|null
     */
    private $form = null;

    function __construct() {
        $this->model = new modelArtist();
        $this->table = new tableHandler();
        $this->form = new formHandler();
    }

    function view($artistSlug = null) {

        $r['artworkButton'] = gettext('Works');

        $artistSlug = coreFunctions::cleanVar($artistSlug);

        $data = $this->model->getArtistBySlug($artistSlug);

        $r['artistName'] = $data['lastName'] . ' ' . $data['firstName'];
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';

        $r['bio'] = $data['bio_' . $_SESSION['lang']];

        $artData = $this->model->getArt($data['id']);

        $r['artData'] = null;

        for ($i = 0; $i <= 9; $i++) {
            $r['artData'][] = $artData[$i];
        }

        return $r;
    }

    function throne_artistIndex($index = null) {

        $r['moduleTitle'] = gettext('Artist index');
        $r['content'] = null;
        $r['index'] = null;

        $aToZ = range('A','Z');

        $indexNav = null;

        foreach ($aToZ as $char) {

            $t = null;

            $t['active'] = 0;

            if (strtolower($char)==$index) {
                $t['active'] = 1;
            }

            $t['title'] = $char;
            $t['link'] = '/throne/artist/throne_artistIndex/' . strtolower($char) . '.html';

            $indexNav[] = $t;

        }

        $r['index'] = $indexNav;

        if (is_string($index)) {
            $data = $this->model->getArtistIndex($index);

            $head['id'] = '#';
            $head['name'] = 'artistName';
            $head['life'] = 'life';
            $head['professionName'] = 'professionName';
            $head['edit'] = '';

            /*
            $classes[] = 'table-condensed';
            */
            $classes[] = 'table-responsive';


            $newData = null;

            foreach ($data as $row) {
                $t = $row;

                $name = trim($t['lastName'] . ' ' . $t['firstName']);

                if ($row['firstNameFirst']!='1') {
                    $name = trim($t['firstName'] . ' ' . $t['lastName']);
                }

                $t['name'] = $name;
                $t['life'] = '(' . $t['dateOfBirth'] . ', ' . $t['placeOfBirth'] . ' - ' . $t['dateOfDeath'] . ', ' . $t['placeOfDeath'] . ')';

                $t['edit'] = '<div class="btn-group">';
                $t['edit'] .= '<a href="/throne/artist/editArtist/' . $t['id'] . '.html" type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '<a type="button" class="btn btn-default btn-xs">Extra small button</a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;

            }

            $r['content'] = $this->table->createSimpleTable($head,$newData,$classes,true,'artists');
        }

        return $r;
    }
}