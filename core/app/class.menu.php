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
         * Getting the main menu elements
         */
        $data = $this->model->getMainMenuElements();

        $r['content'] = null;


        if (is_array($data) AND count($data)>0) {

            $langArray = null;
            $langAvailable = null;

            /*
             * A couple of empty arrays for sorting the results
             */

            foreach ($this->lang AS $l) {
                $langArray[$l['isoCode']] = null;
                $langAvailable[] = $l['isoCode'];
            }

            /*
             * Sorting the results
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
                         * Modifying the table
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
                         * Add a new line to the end for the controls
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

                        $r['content'] .= $this->table->createSimpleTable($heads,$newTable, array($tblName));

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

                        $r['content'] .= $this->table->createSimpleTable($heads,$newTable, array($tblName));

                        $r['content'] .= '</div>';

                    }
                }




            }

        } else {
            $r['content'] = '<div class="span12"><p>Nincs megjeleníthető adat</p></div>';
        }

        return $r;
    }

    function menuForm($lang = null,$positionId = 1) {

        $form = null;

        $this->form->addInput('hidden','langCode',$lang);

        $formName = 'menuForm';


        $this->form->addInput('textField','linkText',null,null,gettext('Link text'),true);
        $this->form->addInput('textField','linkTitle',null,null,gettext('Link tooltip'),true);
        $this->form->addInput('urlField','linkHref',null,gettext('htttp://(www.)valami.hu'),gettext('Link target'),true);

        /*
         * The content of the dropdown
         */
        $window['_self'] = gettext('Current window');
        $window['_blank'] = gettext('New Window');

        $this->form->addInput('hidden','positionId',$positionId);
        $this->form->addInput('hidden','type',1);

        if (isset($_SESSION['postBack'])) {
            $this->form->addInput('hidden','id',$_SESSION['postBack']['id']);
        }

        $this->form->addInput('dropdownList','linkTarget',$window,null,'Window',true);

        return $this->form->generateForm($formName,'Save',null,'/responders/saveMenu.php','bootstrap-horizontal',true);
    }

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
     * @deprecated Revision needed to use the recursive generator
     * @return null|string
     */
    function generateMainNav() {

        $lang = $_SESSION['lang'];

        $data = $this->model->getMainMenu($lang);

        $r = null;

        if (is_array($data)) {

            foreach ($data AS $m) {
                switch ($m['type']) {
                    case '1':

                        $r .= '<li>';

                        $r .= '<a href="' . $m['linkHref'] . '" hreflang="' . $lang  . '" title="' . $m['linkTitle'] . '" target="' . $m['linkTarget'] . '">';

                        $r .= $m['linkText'];

                        $r .= '</a>';
                        $r .= '</li>';

                        break;
                    case '2':

                        $r .= '<li>';

                        $r .= '<a href="/' . $_SESSION['lang']  . '/content/view/' . $m['slug_' . $lang] . '.html" hreflang="' . $lang  . '" title="" target="' . $m['linkTarget'] . '">';

                        $r .= $m['title_' . $lang];

                        $r .= '</a>';
                        $r .= '</li>';

                        break;
                    default:
                        break;
                }
            }



        }

        return $r;
    }

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
     * Recursive list generator
     *
     * Original by Baba @ http://stackoverflow.com/a/12772019/1012431
     *
     * @param array $array
     * @param int $no
     * @return string
     */
    private function make(array $array, $no = 0) {
        $child = $this->hasChildren($array, $no);
        if (empty($child))
            return "";
        $content = "<ol>\n";
        foreach ( $child as $value ) {
            $content .= sprintf("\t<li>%s</li>\n", $value['title']);
            $content .= $this->make($array, $value['id']);
        }
        $content .= "</ol>\n";
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
            return $var['parent_id'] == $id;
        });
    }
}