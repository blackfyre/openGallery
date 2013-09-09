<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 17:39
 */

class options {

    /**
     * @var modelOptions|null
     */
    private $model = null;

    /**
     * @var null|tableHandler
     */
    private $table = null;

    /**
     * @var null|formHandler
     */
    private $form = null;

    private $activeLangs = null;

    /**
     *
     * Got this list from the Wordpress Polylang plugin
     * http://wordpress.org/plugins/polylang/
     *
     * @var array
     */
    private $locales = array(
        array('af', 'af', 'Afrikaans'),
        array('ar', 'ar', 'العربية', 'rtl'),
        array('be', 'be_BY', 'Беларуская мова'),
        array('bg', 'bg_BG', 'български'),
        array('bs', 'bs_BA', 'Bosanski'),
        array('ca', 'ca', 'Català'),
        array('cs', 'cs_CZ', 'Čeština'),
        array('cy', 'cy', 'Cymraeg'),
        array('da', 'da_DK', 'Dansk'),
        array('de', 'de_DE', 'Deutsch'),
        array('el', 'el', 'Ελληνικά'),
        array('en', 'en_GB', 'English'),
        array('eo', 'eo', 'Esperanto'),
        array('es', 'es_CL', 'Español'),
        array('es', 'es_ES', 'Español'),
        array('es', 'es_PE', 'Español'),
        array('es', 'es_VE', 'Español'),
        array('et', 'et', 'Eesti'),
        array('fa', 'fa_AF', 'فارسی', 'rtl'),
        array('fa', 'fa_IR', 'فارسی', 'rtl'),
        array('fi', 'fi', 'Suomi'),
        array('fo', 'fo', 'Føroyskt'),
        array('fr', 'fr_FR', 'Français'),
        array('fy', 'fy', 'Frysk'),
        array('gd', 'gd', 'Gàidhlig'),
        array('gl', 'gl_ES', 'Galego'),
        array('he', 'he_IL', 'עברית', 'rtl'),
        array('hi', 'hi_IN', 'हिन्दी'),
        array('hr', 'hr', 'Hrvatski'),
        array('hu', 'hu_HU', 'Magyar'),
        array('id', 'id_ID', 'Bahasa Indonesia'),
        array('is', 'is_IS', 'Íslenska'),
        array('it', 'it_IT', 'Italiano'),
        array('ja', 'ja', '日本語'),
        array('jv', 'jv_ID', 'Basa Jawa'),
        array('ka', 'ka_GE', 'ქართული'),
        array('kk', 'kk', 'Қазақ тілі'),
        array('ko', 'ko_KR', '한국어'),
        array('ku', 'ckb', 'کوردی', 'rtl'),
        array('lo', 'lo', 'ພາສາລາວ'),
        array('lt', 'lt_LT', 'Lietuviškai'),
        array('lv', 'lv', 'Latviešu valoda'),
        array('mk', 'mk_MK', 'македонски јазик'),
        array('mn', 'mn', 'Монгол хэл'),
        array('ms', 'ms_MY', 'Bahasa Melayu'),
        array('my', 'my_MM', 'ဗမာစာ'),
        array('nb', 'nb_NO', 'Norsk Bokmål'),
        array('ne', 'ne_NP', 'नेपाली'),
        array('nl', 'nl_NL', 'Nederlands'),
        array('nn', 'nn_NO', 'Norsk Nynorsk'),
        array('pl', 'pl_PL', 'Polski'),
        array('pt', 'pt_BR', 'Português'),
        array('pt', 'pt_PT', 'Português'),
        array('ro', 'ro_RO', 'Română'),
        array('ru', 'ru_RU', 'Русский'),
        array('si', 'si_LK', 'සිංහල'),
        array('sk', 'sk_SK', 'Slovenčina'),
        array('sl', 'sl_SI', 'Slovenščina'),
        array('so', 'so_SO', 'Af-Soomaali'),
        array('sq', 'sq', 'Shqip'),
        array('sr', 'sr_RS', 'Српски језик'),
        array('su', 'su_ID', 'Basa Sunda'),
        array('sv', 'sv_SE', 'Svenska'),
        array('ta', 'ta_LK', 'தமிழ்'),
        array('th', 'th', 'ไทย'),
        array('tr', 'tr_TR', 'Türkçe'),
        array('ug', 'ug_CN', 'Uyƣurqə'),
        array('uk', 'uk', 'Українська'),
        array('ur', 'ur', 'اردو', 'rtl'),
        array('uz', 'uz_UZ', 'Oʻzbek'),
        array('vec', 'vec', 'Vèneto'),
        array('vi', 'vi', 'Tiếng Việt'),
        array('zh', 'zh_CN', '中文'),
        array('zh', 'zh_HK', '香港'),
        array('zh', 'zh_TW', '台灣'),
    );

    function __construct() {
        $this->model = new modelOptions();
        $this->table = new tableHandler();
        $this->form = new formHandler();

        $this->activeLangs = $this->model->getLanguages();
    }

    /**
     * @return bool|string
     */
    private function addLangForm() {

        $dropDown = null;

        $activeISO = null;

        foreach ($this->activeLangs AS $l) {
            $activeISO[] = $l['isoCode'];
        }

        foreach ($this->locales AS $v) {

            if (!in_array($v[0],$activeISO)) {
                $dropDown[] = $v[2] . ' - ' . $v[0] . ' (' . $v[1] . ')';
            }

        }

        $this->form->addInput('dropdownList','lang',$dropDown,null,gettext('Available languages'));

        return $this->form->generateForm('addLang',gettext('Save'),null,null,'bootstrap-horizontal');
    }

    /**
     * View available languages
     * @return mixed
     */
    function lang() {
        $data = $this->model->getLanguages();

        $r['langsAvailable'] = gettext('Available languages');
        $r['addLanguage'] = gettext('Add language');

        if (isset($_POST['submit-addLang'])) {
            if ($this->addLangAction()) {
                buildingBlocks::successMSG(gettext('Successfully added a new language!'));
            } else {
                buildingBlocks::errorMSG(gettext('An error occured, and failed to add the new language, check the error log for what went wrong!'));
            }
        }


        $heads['img'] = '';
        $heads['id'] = '#';
        $heads['isoCode'] = 'isoCode';
        $heads['locale'] = 'locale';
        $heads['full'] = 'full';
        $heads['edit'] = 'edit';

        $reRenderedData = null;

        foreach ($data AS $row) {
            $t = $row;
            if (file_exists(_BASE_PATH . '/img/flags/flag-' . $t['isoCode'] . '.png')) {
                $t['img'] = '<img style="width:22px" src="/img/flags/flag-' . $t['isoCode'] . '.png">';
            } else {
                $t['img'] = '';
            }
            $t['edit'] = '<div class="btn-toolbar">';
            $t['edit'] .= '<div class="btn-group">';

            if ($t['active']=='1') {
                $t['edit'] .= '<a class="btn btn-warning btn-xs" href="#" onclick="deactivateLang(' . $t['id'] . ')"><span class="glyphicon glyphicon-eye-close"></span></a>';
            } else {
                $t['edit'] .= '<a class="btn btn-success btn-xs" href="#" onclick="activateLang(' . $t['id'] . ')"><span class="glyphicon glyphicon-eye-open"></span></a>';
            }

            $t['edit'] .= '</div>';
            $t['edit'] .= '</div>';

            $reRenderedData[] = $t;
        }

        /*
        $extraClass[] = 'table-condensed';
        */

        $r['table'] = $this->table->createSimpleTable($heads,$reRenderedData,null,true,'langTable');
        $r['form'] = $this->addLangForm();

        return $r;

    }

    /**
     * @return bool|null
     */
    function addLangAction() {
        $data = $this->form->validator();

        $toInsert = $this->locales[$data['lang']];

        $dataToInsert['isoCode'] = $toInsert[0];
        $dataToInsert['full'] = $toInsert[2];
        $dataToInsert['locale'] = $toInsert[1];

        if (is_array($data)) {

            if ($this->model->fragger($dataToInsert,'languages')) {

                $this->updateTables($dataToInsert['isoCode']);

                return true;

            } else {
                return false;
            }

        } else {
            return false;
        }


    }

    /**
     * @param string $isoCode
     * @return bool
     */
    private function updateTables($isoCode = null) {

        if (is_string($isoCode)) {

            $tables = $this->model->getDefaultLangColumns();

            if (is_array($tables)) {

                foreach ($tables AS $stuff) {

                    $colNameOrig = explode('_',$stuff['COLUMN_NAME']);
                    $colName = $colNameOrig[0] . '_' . $isoCode;

                    $colType = $stuff['COLUMN_TYPE'];

                    $query = "ALTER TABLE {$stuff['TABLE_NAME']} ADD COLUMN `$colName` $colType NULL ";

                    if ($stuff['DATA_TYPE']=='varchar') {
                        $query .= "DEFAULT NULL";
                    }

                    if ($colNameOrig[0]=='slug') {
                        $query .= ",
	ADD UNIQUE INDEX `slug_$isoCode` (`slug_$isoCode`)";
                    }

                    $this->model->db->query($query);

                }

            } else {
                return false;
            }

            return true;

        } else {
            return false;
        }


    }

    function setLang($langId = null,$toState = null) {
        $data['active'] = $toState;
        return $this->model->fragger($data,'languages','update',"id='$langId'",false);
    }

    /**
     * List users
     * @return array
     */
    function listUsers() {
        $r['content'] = null;
        $r['moduleTitle'] = gettext('User Manager');

        $data = $this->model->getUsers();

        $heads['uid'] = '#';
        $heads['userName'] = 'userName';
        $heads['email'] = 'email';

        $r['content'] = $this->table->createSimpleTable($heads,$data);

        return $r;

    }

    /**
     *
     * New user create view
     *
     * @return mixed
     */
    function newUser() {
        $r['content'] = null;
        $r['moduleTitle'] = gettext('Add user');

        $r['content'] = $this->userForm();

        return $r;
    }

    /**
     * Form to create/edit users
     * @return bool|string
     */
    private function userForm() {

        $r = null;


        $this->form->addInput('textField','userName',null,gettext('johnDoe'),gettext('Username'));
        $this->form->addInput('textField','email',null,gettext('john.doe@example.com'),gettext('email'),true);
        $this->form->addInput('passwordCheck','pass',null,null,null,true);

        $r = $this->form->generateForm('newUser',gettext('Save'),null,null,'bootstrap-horizontal');

        return $r;
    }

    /**
     * View log
     *
     * @return mixed
     */
    function logView() {
        $r['moduleTitle'] = gettext('LogViewer');
        $r['control'] = null;
        $r['content'] = null;

        $data = $this->model->getLog();

        $newData = null;

        foreach ($data AS $d) {
            $t = $d;

            $t['message'] = htmlspecialchars_decode($t['message']);

            $newData[] = $t;
        }

        $heads['addedOn'] = 'addedOn';
        $heads['message'] = 'message';

        $r['content'] = $this->table->createSimpleTable($heads,$newData);

        return $r;
    }
}