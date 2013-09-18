<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.02.
 * Time: 11:12
 */

/**
 * Class menu
 */
class menu {

    /**
     * @var null|menuModel
     */
    private $model = null;

    /**
     * @var formHandler|null
     */
    private $form = null;

    /**
     * @var null|tableHandler
     */
    private $table = null;

    /**
     * @var array|bool|null
     */
    private $lang = null;

    function __construct() {
        $this->model = new menuModel();
        $this->form = new formHandler();
        $this->table = new tableHandler();

        $this->lang = $this->model->getActiveLanguages();
    }

    /**
     * @return array
     */
    function main() {

        /*
         * Szedjük le a menüket
         */
        $data = $this->model->getMainMenuElements();

        $r['content'] = null;


        if (is_array($data) AND count($data)>0) {

            $langArray = null;
            $langAvailable = null;

            /*
             * Hozzuk létre az üres tömböket
             */

            foreach ($this->lang AS $l) {
                $langArray[$l['isoCode']] = null;
                $langAvailable[] = $l['isoCode'];
            }

            /*
             * Az üres tömb megfelelő részeihez adjuk hozzá a lekérdezés eredményeit, nyelv szerint szét válogatva azt
             */

            foreach ($data AS $row) {
                $langArray[$row['langCode']][] = $row;
            }

            $heads['linkText'] = 'linkText';
            $heads['edit'] = 'edit';

            foreach ($langArray AS $lang=>$table) {

                if (in_array($lang,$langAvailable)) {
                    $newTable = null;

                    $tblName = 'menuTable-' . $lang;

                    if (is_array($table) AND count($table)>0) {


                        /*
                         * Pörgessük végig a tömböt, módosítva az egyes oszlopok tartalmát
                         */

                        foreach ($table AS $row) {
                            $t = $row;

                            if ($t['type']=='2') {
                                $t['linkText'] = $t['title_' . $lang];
                            }

                            //$t['linkText'] = '<a href="' . $row['linkHref'] . '" target="_blank">' . $row['linkText'] . '</a>';

                            $t['edit'] = '<div class="btn-group">';


                            if ($t['readOnly']!='1') {

                                $t['edit'] .= '<a class="btn btn-mini" href="javascript:void (0)" onclick="editMenu(' . $row['mId'] . ');"><i class="icon-edit"></i></a>';

                                if ($t['active']=='0') {
                                    $t['edit'] .= '<a class="btn btn-mini btn-success" href="javascript:void (0)" title="Turn visibility ON" onclick="setMenu(\'act\',' . $row['mId'] . ');"><i class="icon-eye-open"></i></a>';
                                } else {
                                    $t['edit'] .= '<a class="btn btn-mini btn-warning" href="javascript:void (0)" title="Turn visibility OFF" onclick="setMenu(\'deact\',' . $row['mId'] . ');"><i class="icon-eye-close"></i></a>';
                                }

                                $t['edit'] .= '<a class="btn btn-mini btn-danger" href="javascript:void (0)" title="Delete Menu" onclick="setMenu(\'del\',' . $row['mId'] . ');"><i class="icon-trash"></i></a>';
                            }

                            $t['edit'] .= '</div>';

                            $newTable[] = $t;
                        }

                        /*
                         * Adjunk hozzá egy új sort a + gomb hozzáadásához
                         */

                        $addRow['id'] = '';
                        $addRow['linkText'] = '<div class="btn-group">';
                        $addRow['linkText'] .= '<a class="btn btn-mini" href="javascript:void (0)" title="Új átirányítás" onclick="addMenuElement(\'' . $lang . '\',\'1\');"><i class="icon-plus"></i></a>';
                        $addRow['linkText'] .= '<a class="btn btn-mini" href="javascript:void (0)" title="Új cikk" onclick="addMenuArticle(\'' . $lang . '\',\'1\');"><i class="icon-bookmark"></i></a>';
                        $addRow['linkText'] .= '</div>';
                        $addRow['active'] = '';

                        $newTable[] = $addRow;

                        $r['content'] .= '<div class="span4">';

                        $r['content'] .= '<img src="/img/flags/flag-' . $lang . '.png" style="width: 32px">';

                        $r['content'] .= buildingBlocks::createSimpleTable($heads,$newTable, array($tblName));

                        $r['content'] .= '</div>';
                    } else {

                        $newTable = null;

                        $addRow['id'] = '';
                        $addRow['linkText'] = '<div class="btn-group">';
                        $addRow['linkText'] .= '<a class="btn btn-mini" href="javascript:void (0)" onclick="addMenuElement(\'' . $lang . '\',\'1\');"><i class="icon-plus"></i></a>';
                        $addRow['linkText'] .= '<a class="btn btn-mini" href="javascript:void (0)" onclick="addMenuArticle(\'' . $lang . '\',\'1\');"><i class="icon-bookmark"></i></a>';
                        $addRow['linkText'] .= '</div>';
                        $addRow['active'] = '';

                        $newTable[] = $addRow;

                        $r['content'] .= '<div class="span4">';

                        $r['content'] .= '<img src="/img/flags/flag-' . $lang . '.png" style="width: 32px">';

                        $r['content'] .= buildingBlocks::createSimpleTable($heads,$newTable, array($tblName));

                        $r['content'] .= '</div>';

                    }
                }
            }

        } else {
            $r['content'] = '<div class="span12"><p>' . buildingBlocks::noRecords() . '</p></div>';
        }

        return $r;
    }

    /**
     * @param null $lang
     * @param int $positionId
     * @return bool|string
     */
    function menuForm($lang = null,$positionId = 1) {

        $form = null;

        $this->form->addInput('hidden','langCode',$lang);

        $formName = 'menuForm';


        $this->form->addInput('textField','linkText',null,null,'Link text',true);
        $this->form->addInput('textField','linkTitle',null,null,'Link tooltip',true);
        $this->form->addInput('urlField','linkHref',null,'htttp://(www.)valami.hu','Link target',true);

        /*
         * A dropdown tartalma
         */
        $window['_self'] = 'Jelenlegi ablak';
        $window['_blank'] = 'Új ablak';

        $this->form->addInput('hidden','positionId',$positionId);
        $this->form->addInput('hidden','type',1);

        if (isset($_SESSION['postBack'])) {
            $this->form->addInput('hidden','id',$_SESSION['postBack']['id']);
        }

        $this->form->addInput('dropdownList','linkTarget',$window,null,'Window',true);

        return $this->form->generateForm($formName,'Save',null,'/responders/saveMenu.php','bootstrap-horizontal',true);
    }

    /**
     * @param null $lang
     * @param int $positionId
     * @return bool|string
     */
    function articleForm($lang = null,$positionId = 1) {
        $form = null;

        $this->form->addInput('hidden','langCode',$lang);

        $formName = 'menuForm';

        if (isset($_SESSION['postBack'])) {
            $this->form->addInput('hidden','edit','1');
        }

        $content = $this->model->getArticles($lang);

        $toShow = null;

        if (is_array($content)) {
            foreach ($content AS $c) {
                $toShow[$c['id']] = $c['title_' . $lang];
            }
        }

        $this->form->addInput('hidden','linkTarget','_self');
        $this->form->addInput('hidden','positionId',$positionId);
        $this->form->addInput('hidden','type',2);

        if (is_array($toShow)) {
            $this->form->addInput('dropdownList','contentId',$toShow,null,'Article',true);
        }

        return $this->form->generateForm($formName,'Save',null,'/responders/saveMenu.php','bootstrap-horizontal',true);
    }

    /**
     * @param null $id
     * @return bool|string
     */
    function editMenu($id = null) {
        if (is_numeric($id)) {

            $_SESSION['postBack'] = $this->model->getMenuElement($id);

            switch ($_SESSION['postBack']['type']) {
                case 1:
                    return $this->menuForm($_SESSION['postBack']['langCode']);
                    break;
                case 2:
                    return $this->articleForm($_SESSION['postBack']['langCode']);
                    break;
                default:
                    return false;
                    break;
            }

        } else {
            return false;
        }
    }

    /**
     * @param array $data optional
     * @return bool|null
     */
    function saveNewMenuForm($data = null) {

        if (!is_array($data)) {
            $data = $this->form->validator();
        }

        if ($data['type']=='2' AND !isset($data['contentId'])) {
            return false;
        }

        if ($data['editForm']=='1') {
            $id = $data['id'];
            unset($data['id'],$data['editForm']);
            $this->model->fragger($data,'menu_elements','update',"id='$id'");
        } else {
            $this->model->fragger($data,'menu_elements','insert',null,false);
        }


        return $data['langCode'];
    }

    /**
     * @return null|string
     */
    function generateMainNav() {

        $lang = $_SESSION['lang'];

        $data = $this->model->getMainMenu($lang);

        $r = null;

        if (is_array($data)) {
            $r = $this->frontMake($data);
        }

        return $r;
    }

    /**
     * @return null|string
     */
    function adminMenu() {
        $data = $this->model->getAdminMenu();

        $r = null;

        if (is_array($data)) {
            $r = $this->make($data);
        }

        return $r;

    }

    /**
     * @param null $action
     * @param null $menuId
     * @return bool|null
     */
    function setMenu($action = null, $menuId = null) {
        switch (coreFunctions::cleanVar($action)) {
            case 'del':
                $data['deleted'] = '1';
                break;
            case 'act':
                $data['active'] = '1';
                break;
            case 'deact':
                $data['active'] = '0';
                break;
            default:
                return false;
                break;
        }

        return $this->model->fragger($data,'menu_elements','update',"id='$menuId'");
    }

    /**
     * @return array
     */
    public function listMenu() {
        $r['moduleTitle'] = "<span class='glyphicon glyphicon-random'></span>&nbsp;" . gettext('Menu manager');
        $r['content'] = null;
        $r['control'] = null;

        $control[] = array('link'=>'/throne/menu/addNewMenu.html','icon'=>'plus-sign','text'=>gettext('New Menu'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $data = $this->model->getPositions();

        if (is_array($data) AND count($data)>0) {

            $newData = null;

            $heads['id'] = gettext('ID');
            $heads['title'] = gettext('Title');
            $heads['edit'] = gettext('Edit');

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = buildingBlocks::langTableDropDown($this->lang,$row,'positionName');

                $t['edit'] = '<div class="btn-toolbar">';
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a href="/throne/menu/editMenu/' . $row['id'] . '.html" role="button" class="btn btn-primary btn-xs" title="' . gettext('Edit item') . '"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '</div>';
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a href="#" role="button" class="btn btn-danger btn-xs" title="' . gettext('Delete item') . '"><span class="glyphicon glyphicon-trash"></span></a>';
                $t['edit'] .= '</div>';
                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $r['content'] = buildingBlocks::createSimpleTable($heads,$newData);


        } else {
            $r['msg'] = buildingBlocks::noRecords();
        }

        return $r;
    }

    /**
     * @return bool|string
     */
    private function addPositionForm() {

        if (!_MULTILANG) {
            $this->form->addTextField('positionName_' . $_SESSION['lang'],null,null,gettext('Menu name'));
        } else {
            foreach ($this->lang AS $l) {
                $this->form->addTextField('positionName_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Menu name'));
            }
        }

        return $this->form->generateForm('positionForm',gettext('Save'));

    }

    /**
     * @return string
     */
    private function saveNewMenu() {
        $data = $this->form->validator();

        if ($this->model->insert($data,'menu_positions')) {
            return buildingBlocks::successMSG(gettext('New menu successfully added!'));
        } else {
            $_SESSION['postBack'] = $data;
            $_SESSION['postBack']['rePost'] = 1;
            return buildingBlocks::errorMSG(gettext('Failed to save the new menu!'));
        }
    }

    /**
     * @return mixed
     */
    public function addNewMenu() {

        if (!isset($_SESSION['postBack']['rePost'])) {
            unset($_SESSION['postBack']);
        }

        if (isset($_POST['submit-positionForm'])) {
            $r['msg'] = $this->saveNewMenu();
        }

        $r['moduleTitle'] = gettext('New Menu');
        $r['content'] = $this->addPositionForm();
        $r['backLink'] = gettext('Back');

        return $r;
    }

    /**
     * Recursive list generator
     *
     * Original by Baba @ http://stackoverflow.com/a/12772019/1012431
     *
     * @param array $array
     * @param int $parentId
     * @return string
     */
    private function make(array $array, $parentId = 0) {

        $child = $this->hasChildren($array, $parentId);

        if (empty($child)) {
            return "";
        }

        if ($parentId == 0) {
            $content = '<ul class="nav navbar-nav">';
        } else {
            $content = '<ul class="dropdown-menu">';
        }


        foreach ( $child as $value ) {

            /*
             * Icon
             */
            $icon = $value['icon'];

            /*
             * If there's child content it will be in here
             */
            $subData = $this->make($array, $value['id']);

            if (strlen($subData)>1) {
                $content .= '<li class="dropdown">';
                $content .= '<a class="dropdown-toggle" data-toggle="dropdown" href="' . $value['link'] . '">' . ($icon!=''?'<span class="glyphicon glyphicon-' . $icon . '"></span> ':'') . $value['title_' . $_SESSION['lang']] . '<b class="caret"></b></a>';
            } else {
                $content .= '<li>';
                $content .= '<a href="' . $value['link'] . '">' . ($icon!=''?'<span class="glyphicon glyphicon-' . $icon . '"></span> ':'') . $value['title_' . $_SESSION['lang']] . '</a>';
            }

            $content .= $subData;

            $content .= '</li>';

        }
        $content .= "</ul>";
        return $content;
    }

    /**
     * Alternative make for front-end
     * @param array $array
     * @param int $parentId
     * @return string
     */
    private function frontMake(array $array, $parentId = 0) {

        $child = $this->hasChildren($array, $parentId);

        if (empty($child)) {
            return "";
        }

        if ($parentId == 0) {
            $content = '<ul class="nav navbar-nav pull-right">';
        } else {
            $content = '<ul class="dropdown-menu">';
        }


        foreach ( $child as $value ) {

            /*
             * Icon
             */
            $icon = $value['icon'];

            /*
             * If there's child content it will be in here
             */
            $subData = $this->make($array, $value['id']);

            if (strlen($subData)>1) {
                $content .= '<li class="dropdown">';
                $content .= '<a class="dropdown-toggle" data-toggle="dropdown" href="' . $value['linkHref'] . '">' . ($icon!=''?'<span class="glyphicon glyphicon-' . $icon . '"></span> ':'') . $value['linkTitle'] . '<b class="caret"></b></a>';
            } else {
                $content .= '<li>';
                $content .= '<a href="' . $value['linkHref'] . '">' . ($icon!=''?'<span class="glyphicon glyphicon-' . $icon . '"></span> ':'') . $value['linkTitle'] . '</a>';
            }

            $content .= $subData;

            $content .= '</li>';

        }
        $content .= "</ul>";
        return $content;
    }

    /**
     *
     * Recursive list generator
     *
     * Original by Baba @ http://stackoverflow.com/a/12772019/1012431
     *
     * @param $array
     * @param $id
     * @return array
     */
    private function hasChildren($array, $id) {
        return array_filter($array, function ($var) use($id) {
            return $var['pid'] == $id;
        });
    }
}