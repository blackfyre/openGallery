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

    /**
     * @var array|bool|null
     */
    private $activeLangs = null;

    function __construct() {
        $this->model = new modelArtist();
        $this->table = new tableHandler();
        $this->form = new formHandler();

        $this->activeLangs = $this->model->getActiveLanguages();
    }

    function view($artistSlug = null) {

        $r['artworkButton'] = gettext('Works');
        $r['artworkLink'] = '/' . $_SESSION['lang'] .  '/artist/artBy/' . $artistSlug . '.html';

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

    function artBy($artistSlug = null) {
        $artistSlug = coreFunctions::cleanVar($artistSlug);

        $data = $this->model->getArtistBySlug($artistSlug);

        $r['bioButton'] = gettext('Biography');
        $r['biokLink'] = '/' . $_SESSION['lang'] .  '/artist/view/' . $artistSlug . '.html';

        $r['artistName'] = $data['lastName'] . ' ' . $data['firstName'];
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';

        $artData = $this->model->getArt($data['id']);

        $r['content'] = null;

        $r['content'] .= '<div class="row">';

        $counter = 1;

        foreach ($artData AS $a) {
            $r['content'] .= '<div class="col-md-3">';

            $r['content'] .= '<img class="img-responsive img-rounded" src="/image.php?width=300&height=300&cropratio=1:1&image=/img/art/' . $a['img'] . '">';

            $r['content'] .= '<h2>' . $a['title_' . $_SESSION['lang']].'</h2>';

            if ($a['description_' . $_SESSION['lang']] != '') {
                $r['content'] .= '<p>' . coreFunctions::trimmer($a['description_' . $_SESSION['lang']],150) . '</p>';
            }

            $r['content'] .= '';

            $r['content'] .= '</div>';

            if ($counter == 4) {
                $counter = 1;
                $r['content'] .= '</div>';
                $r['content'] .= '<div class="row">';
            } else {
                $counter++;
            }


        }

        $r['content'] .= '</div>';

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
                $t['edit'] .= '<a href="/throne/artist/throne_editArtist/' . $t['id'] . '.html" type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '<a type="button" class="btn btn-default btn-xs">Extra small button</a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;

            }

            $r['content'] = $this->table->createSimpleTable($head,$newData,$classes,true,'artists');
        }

        return $r;
    }

    /**
     * @return null|array
     */
    private function throne_artistForm() {
        $r = null;

        $r['content'] = '
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#general" data-toggle="tab">General Information</a></li>
';

        foreach ($this->activeLangs AS $l) {
            $r['content'] .= '<li><a href="#data_' . $l['isoCode'] . '" data-toggle="tab"><img class="smallFlag" src="/img/flags/flag-' . $l['isoCode'] . '.png">&nbsp;' . gettext('Artist data') . '</a></li>' . PHP_EOL;
        }

$r['content'] .= '
</ul>
';

        $r['content'] .= '
<div class="tab-content">
  <div class="tab-pane active" id="general">';

        $this->form->addInput('textField','firstName',null,null,gettext('First name'));
        $this->form->addInput('textField','lastName',null,null,gettext('Last name'));
        $this->form->addInput('numField','dateOfBirth',null,null,gettext('Year of Birth'));
        $this->form->addInput('numField','dateOfDeath',null,null,gettext('Year of Death'));
        $this->form->addInput('textField','placeOfBirth',null,null,gettext('Place of Birth'));
        $this->form->addInput('textField','placeOfDeath',null,null,gettext('Place of Death'));

        $r['content'] .= $this->form->generateForm('updateArtist',gettext('Update'));

        $r['content'] .= '</div>';

        foreach ($this->activeLangs AS $l) {
            $r['content'] .= '<div class="tab-pane" id="data_' . $l['isoCode'] . '">';

            $this->form->addInput('textArea','bio_' . $l['isoCode'],null,null,gettext('Bio'));

            $r['content'] .= $this->form->generateForm('updateArtist',gettext('Update'));

            $r['content'] .= '</div>' . PHP_EOL;
        }

$r['content'] .= '
</div>';


        return $r;
    }

    private function processArtistUpdate($artistId = null) {

        $r = null;

        $data = $this->form->validator();

        unset($data['editForm']);

        if ($this->model->fragger($data,'artist','update',"id='$artistId'")) {
            $r = $this->form->updateSuccess();
        } else {
            $r = $this->form->updateError();
        }

        return $r;

    }

    function throne_editArtist($artistId = null) {
        $r['moduleTitle'] = gettext('Edit Artist');
        $r['content'] = null;
        $r['msg'] = null;

        if (isset($_POST['submit-updateArtist'])) {
            $r['msg'] = $this->processArtistUpdate($artistId);
        }

        $data = $this->model->getArtistById($artistId);

        $_SESSION['postBack'] = $data;

        $r = array_merge($r,$this->throne_artistForm());

        return $r;
    }
}