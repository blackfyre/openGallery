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

    /**
     * Organizes the artists name based on what's it gotten from the DB
     * @param null|array $artistData The full artist data array
     * @return null|string
     */
    private function artistName($artistData = null) {
        if (is_array($artistData)) {

            $name = trim($artistData['lastName'] . ' ' . $artistData['firstName']);

            if (isset($artistData['firstNameFirst']) AND $artistData['firstNameFirst']!='1') {
                $name = trim($artistData['firstName'] . ' ' . $artistData['lastName']);
            }

            return $name;

        }

        return null;
    }

    /**
     * Get the artist page
     * @param string $artistSlug
     * @return array
     */
    function viewArtist($artistSlug = null) {

        $r = null;

        /*
         * Translations
         */
        $r['bioTitle'] = gettext('Bio');
        $r['workTitle'] = gettext('Works');
        $r['artworkButton'] = gettext('Works');
        $r['moreButton'] = gettext('More');

        /*
         * Default values
         */

        $r['showMore'] = false;

        /*
         * The link for the artworks page
         */
        $r['artworkLink'] = '/' . $_SESSION['lang'] .  '/artist/artBy/' . $artistSlug . '.html';

        /*
         * cleaning the artist slug
         */
        $artistSlug = coreFunctions::cleanVar($artistSlug);

        /*
         * getting the artist data
         */
        $data = $this->model->getArtistBySlug($artistSlug);

        /*
         * compiling artist data for the template
         * @TODO watch the firstNameFirst value and change the name accordingly
         */
        $r['artistName'] = $data['lastName'] . ' ' . $data['firstName'];
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';
        $r['bio'] = coreFunctions::decoder($data['bio_' . $_SESSION['lang']]);
        $r['excerpt'] = coreFunctions::decoder($data['excerpt_' . $_SESSION['lang']]);

        /*
         * The page title and meta data
         * May have to add more, depending on SEO requirements
         */
        $r['metaTitle'] = $r['artistName'];
        $r['metaDesc'] = coreFunctions::trimmer($r['excerpt'],160);

        /*
         * TODO add artist bio img to og website img
         */
        $r['openGraph'] = socialMedia::websiteTag($r['metaTitle'],$r['metaDesc']);


        /*
         * Creating the sample works section
         */
        $artData = $this->model->getArt($data['id']);

        if (count($artData)>10) {
            $r['showMore'] = true;
        }

        $r['artData'] = null;

        /*
         * We only need 10 at most
         */
        for ($i = 0; $i <= 9; $i++) {

            if (isset ($artData[$i])) {
                $t = $artData[$i];
                $t['artId'] = $t['id'];
                $t['title'] = $t['title_' . $_SESSION['lang']];
                $t['description'] = coreFunctions::trimmer($t['description_' . $_SESSION['lang']],140);
                $t['link'] = "/{$_SESSION['lang']}/artist/viewArt/{$t['id']}/$artistSlug/" . $t['titleSlug_' . $_SESSION['lang']] . '.html';
                $t['slug'] = $artistSlug . '-' . $t['titleSlug_' . $_SESSION['lang']] . '.' . coreFunctions::getExtension($t['img']);

                $r['artData'][] = $t;
            }

        }

        /*
         * And this is for the langSwitcher
         */
        foreach ($this->activeLangs as $l) {

            if ($l['isoCode']!=$_SESSION['lang']) {
                $t = null;
                $t['url'] =     '/' . $l['isoCode'] .  '/artist/viewArtist/' . $artistSlug . '.html';
                $t['flag'] = $l['isoCode'];
                $t['full'] = $l['full'];

                $r['langSwitch'][] = $t;
            }

        }

        return $r;
    }

    /**
     * Get works page by slug
     *
     * TODO add meta desc
     *
     * @param string $artistSlug
     * @return array
     */
    function artBy($artistSlug = null) {

        $r = null;

        /*
         * Getting the data required for the page based on the slug
         */
        $artistSlug = coreFunctions::cleanVar($artistSlug);
        $data = $this->model->getArtistBySlug($artistSlug);

        $r['bioButton'] = gettext('Biography');
        $r['metaTitle'] = gettext('The works of %s');
        $r['metaTitle'] = str_replace('%s',$this->artistName($data),$r['metaTitle']);

        $r['openGraph'] = socialMedia::websiteTag($r['metaTitle']);

        /*
         * The biography link
         */
        $r['biokLink'] = '/' . $_SESSION['lang'] .  '/artist/viewArtist/' . $artistSlug . '.html';

        /*
         * compiling artist data for the template
         */
        $r['artistName'] = $this->artistName($data);
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';
        $r['excerpt'] = coreFunctions::decoder($data['excerpt_' . $_SESSION['lang']]);

        /*
         * As in viewArtist() we get the art data based on the previously gotten artists data
         */
        $artData = $this->model->getArt($data['id']);

        $r['content'] = null;

        /*
         * A bit more elaborate rendering is required, so I didn't want to do it with smarty
         * This renders bootstrap rows with 4 items in them
         */
        $r['content'] .= '<div class="row">';

        $counter = 1;

        foreach ($artData AS $a) {

            $altText = gettext('An art piece by') . ' ' . $r['artistName'] . ' ' . gettext('titled') . ' ' . $a['title_' . $_SESSION['lang']];

            $r['content'] .= '<a href="/' . $_SESSION['lang'] . '/artist/viewArt/' . $a['id'] . '/' . $data['slug'] . '/' . $a['titleSlug_' . $_SESSION['lang']] . '.html" hreflang="' . $_SESSION['lang'] . '" title="' . $altText . '">';
            $r['content'] .= '<div class="col-md-3">';

            /*
            $r['content'] .= '<img class="img-responsive img-rounded" src="/image.php?width=300&height=300&cropratio=1:1&image=/img/art/' . $a['img'] . '">';
            */
            $r['content'] .= '<img alt="' . $altText . '" class="img-responsive img-rounded" src="/images/large-thumbnail/' . $a['id'] . '/' . $artistSlug . '-' . $a['titleSlug_' . $_SESSION['lang']] . '.' . coreFunctions::getExtension($a['img']) . '">';

            $r['content'] .= '<h3>' . $a['title_' . $_SESSION['lang']] .'</h3>';

            if ($a['description_' . $_SESSION['lang']] != '') {
                $r['content'] .= '<p>' . coreFunctions::trimmer($a['description_' . $_SESSION['lang']],150) . '</p>';
            }

            $r['content'] .= '';

            $r['content'] .= '</div>';

            $r['content'] .= '</a>';

            if ($counter == 4) {
                $counter = 1;
                $r['content'] .= '</div>';
                $r['content'] .= '<div class="row">';
            } else {
                $counter++;
            }


        }

        /*
         * And this is for the langSwitcher
         */
        foreach ($this->activeLangs as $l) {

            if ($l['isoCode']!=$_SESSION['lang']) {
                $t = null;
                $t['url'] =     '/' . $l['isoCode'] .  '/artist/artBy/' . $artistSlug . '.html';
                $t['flag'] = $l['isoCode'];
                $t['full'] = $l['full'];

                $r['langSwitch'][] = $t;
            }

        }

        $r['content'] .= '</div>';

        return $r;
    }

    /**
     * Artist index page for the throne
     * This page is the first step in editing it
     *
     * @param string $index
     * @return array
     */
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

                $t['name'] = $this->artistName($row);
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
     * This funcion creates the artist form
     *
     * The artist data is broken down into multiple forms and this allows partial updates to the artist
     *
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
        $this->form->addInput('onOffBox','firstNameFirst',null,null,gettext('Fist name first'));
        $this->form->addInput('numField','dateOfBirth',null,null,gettext('Year of Birth'));
        $this->form->addInput('numField','dateOfDeath',null,null,gettext('Year of Death'));
        $this->form->addInput('textField','placeOfBirth',null,null,gettext('Place of Birth'));
        $this->form->addInput('textField','placeOfDeath',null,null,gettext('Place of Death'));

        $r['content'] .= $this->form->generateForm('updateArtist',gettext('Update'));

        $r['content'] .= '</div>';

        foreach ($this->activeLangs AS $l) {
            $r['content'] .= '<div class="tab-pane" id="data_' . $l['isoCode'] . '">';

            $this->form->addInput('ckeditor','excerpt_' . $l['isoCode'],null,null,gettext('Excerpt'));
            $this->form->addInput('ckeditor','bio_' . $l['isoCode'],null,null,gettext('Bio'));

            $r['content'] .= $this->form->generateForm('updateArtist',gettext('Update'));

            $r['content'] .= '</div>' . PHP_EOL;
        }

$r['content'] .= '
</div>';


        return $r;
    }

    /**
     * @param null $artistId
     * @return string
     */
    private function processArtistUpdate($artistId = null) {

        $r = null;

        $data = $this->form->validator();

        unset($data['editForm']);


        if ($this->model->fragger($data,'artist','update',"id='$artistId'")) {
            $r = buildingBlocks::successMSG(gettext('Update successful!'));
        } else {
            $r = buildingBlocks::errorMSG(gettext('An error occured, and could not update!'));
        }

        return $r;

    }

    /**
     * @param null $artistId
     * @return array
     */
    function throne_editArtist($artistId = null) {
        $r['moduleTitle'] = gettext('Edit Artist');
        $r['content'] = null;
        $r['msg'] = null;

        if (isset($_POST['submit-updateArtist'])) {
            $r['msg'] = $this->processArtistUpdate($artistId);
        }

        $data = $this->model->getArtistById($artistId);


        foreach ($this->activeLangs as $l) {
            $data['bio_' . $l['isoCode']] = htmlspecialchars_decode($data['bio_' . $l['isoCode']]);
            $data['excerpt_' . $l['isoCode']] = htmlspecialchars_decode($data['excerpt_' . $l['isoCode']]);
        }



        $_SESSION['postBack'] = $data;

        $r = array_merge($r,$this->throne_artistForm());

        return $r;
    }

    /**
     * @param int $artId database id of the art piece
     * @param string $artistSlug slug of the artist name as stored in the database
     * @return array
     *
     * @TODO front-end editor
     * @TODO show all data
     */
    function viewArt($artId = null, $artistSlug = null) {
        $r['artworkButton'] = gettext('Works');
        $r['artworkLink'] = '/' . $_SESSION['lang'] .  '/artist/artBy/' . $artistSlug . '.html';

        $artistSlug = coreFunctions::cleanVar($artistSlug);

        $data = $this->model->getArtistBySlug($artistSlug);

        $r['artistName'] = $this->artistName($data);
        $r['subTitle'] = '(' . $data['dateOfBirth'] . ', ' . $data['placeOfBirth'] . ' - ' . $data['dateOfDeath'] . ', ' . $data['placeOfDeath'] . ')';

        $r['excerpt'] = coreFunctions::decoder($data['excerpt_' . $_SESSION['lang']]);

        $r['bioButton'] = gettext('Biography');
        $r['bioLink'] = '/' . $_SESSION['lang'] .  '/artist/viewArtist/' . $artistSlug . '.html';

        $artData = $this->model->getArtPiece($artId);

        $r['artImg'] = '/images/full/' . $artData['id'] . '/' . $artistSlug . '-' . $artData['titleSlug_' . $_SESSION['lang']] . '.' . coreFunctions::getExtension($artData['img']);
        $r['artTitle'] = $artData['title_' . $_SESSION['lang']];
        $r['artImgAlt'] = '';

        $desc = $artData['description_' . $_SESSION['lang']];

        $r['metaTitle'] = $r['artistName'] . ' - ' . $r['artTitle'];

        $r['openGraph'] = socialMedia::websiteTag($r['metaTitle'],null,'http://' . $_SERVER['HTTP_HOST'] . $r['artImg']);


        /*
         * Content aware default content
         */

        $translation = false;

        foreach ($this->activeLangs AS $l) {
            if (strlen($artData['description_' . $l['isoCode']])>0) {
                $translation = true;
            }
        }

        $defaultText = gettext("There's no content, please help!");

        if ($translation) {
            $defaultText = gettext('Translation needed, please help!');
        }

        $r['artInfo'] = ($desc!=''?$desc:'<p>' . $defaultText . '</p>');

        /*
         * And this is for the langSwitcher
         */
        foreach ($this->activeLangs as $l) {

            if ($l['isoCode']!=$_SESSION['lang']) {
                $t = null;
                $t['url'] =     '/' . $l['isoCode'] .  '/artist/viewArt/' . $artId . '/' . $artistSlug . '/' . $artData['titleSlug_' . $l['isoCode']] . '.html';
                $t['flag'] = $l['isoCode'];
                $t['full'] = $l['full'];

                $r['langSwitch'][] = $t;
            }

        }

        return $r;
    }

    function getArtById($artId = null) {
        $artData = $this->model->getArtPiece($artId);
        return $artData['img'];
    }

    function index($index = null) {

        $r['metaTitle'] = gettext('Artist Index');
        $r['bioTitle'] = gettext('Biography');

        if (is_string($index) AND (strlen(coreFunctions::cleanVar($index))==1 OR coreFunctions::cleanVar($index)=='Anonymus')) {
            $r['metaTitle'] = gettext('Artist Index') . ' / ' . strtoupper($index);

        }

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
            $t['link'] = '/' . $_SESSION['lang'] . '/artist/index/' . strtolower($char) . '.html';

            $indexNav[] = $t;

        }

        $r['index'] = $indexNav;

        if (is_string($index)) {
            $data = $this->model->getArtistIndex($index);

            $newData = null;

            foreach ($data as $row) {
                $t = $row;

                $t['name'] = $this->artistName($row);
                $t['life'] = '(' . $t['dateOfBirth'] . ', ' . $t['placeOfBirth'] . ' - ' . $t['dateOfDeath'] . ', ' . $t['placeOfDeath'] . ')';
                $t['link'] = '/' . $_SESSION['lang'] . '/artist/viewArtist/' . $t['slug'] . '.html';
                $t['background'] = '142a41af4abc885e1c9f08274287f4024aee8605.jpg';

                $newData[] = $t;

            }

            $r['artists'] = $newData;
        }

        return $r;

    }
}