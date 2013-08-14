<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 16:40
 */

class content {

    /**
     * @var modelContent|null
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
        $this->model = new modelContent();
        $this->table = new tableHandler();
        $this->form = new formHandler();

        $this->activeLangs = $this->model->getActiveLanguages();
    }


    /**
     * Cikkek listázása
     * @return array
     */
    function articles() {
        $data = $this->model->getArticles();

        $r['content'] = null;

        if (is_array($data) AND count($data)>0) {

            $newData = null;

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png" class="smallFlag">&nbsp;' . $row['title_hu'] . '</a><ul class="dropdown-menu" role="menu">';
                $t['metaKey'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png" class="smallFlag">&nbsp;' . $row['metaKey_hu'] . '</a><ul class="dropdown-menu" role="menu">';
                $t['linkAlt'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png"  class="smallFlag">&nbsp;' . $row['linkAlt_hu'] . '</a><ul class="dropdown-menu" role="menu">';

                foreach ($this->activeLangs AS $lang) {
                    $t['title'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $row['title_' . $lang['isoCode']] . '</li>';
                    $t['metaKey'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $row['metaKey_' . $lang['isoCode']] . '</li>';
                    $t['linkAlt'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $row['linkAlt_' . $lang['isoCode']] . '</li>';
                }

                $t['title'] .= '</ul></div>';
                $t['metaKey'] .= '</ul></div>';
                $t['linkAlt'] .= '</ul></div>';

                $t['edit'] = '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-mini" href="/throne/content/editArticle/' . $row['id'] . '.html"><i class="icon-edit"></i></a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $heads['id'] = '#';
            $heads['title'] = 'title';
            $heads['metaKey'] = 'metaKey';
            $heads['linkAlt'] = 'linkAlt';
            $heads['edit'] = 'edit';

            $r['content'] .= $this->table->createSimpleTable($heads,$newData);

        } else {
            $r['content'] = '<p>Nincs megjeleníthető adat!</p>';
        }

        return $r;

    }

    /**
     * @param array $data
     * @return bool|null
     */
    function saveFixedContent($data = null) {
        if (is_array($data)) {

            $id = $data['contentId'];

            unset($data['contentId']);

            return $this->model->fragger($data,'content_fixed','update'," id='$id'",false);

        }

        return false;
    }

    /**
     * A rögzített tartalmak listázása
     * @return array
     */
    function fixedContent() {
        $r['content'] = null;

        if (isset($_POST['submit-saveFixed'])) {
            $formData = $this->form->validator();

            if ($this->saveFixedContent($formData)) {
                $r['content'] .= '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        A tartalom sikeresen módosításra került!
                    </div>
                ';
            } else {
                $r['content'] .= '
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Nem sikerült módosítani a tartalmat!
                    </div>
                ';
            }

        }


        $data = $this->model->getFixedContent();

        if (is_array($data) AND count($data)>0) {

            $newData = null;

            /*
             * Megjelenítendő oszlopok, és azok kiírt nevei
             */
            $heads['title'] = 'title';
            $heads['edit'] = 'edit';

            /*
             * Pörgessük át a sorokat, és alakítsuk át egy kicsit
             */
            foreach ($data AS $row) {
                $t = $row;

                /*
                 * Dropdown a címekhez
                 */

                $t['title'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png" class="smallFlag">&nbsp;' . htmlspecialchars_decode($row['title_hu']) . '</a><ul class="dropdown-menu" role="menu">';

                foreach ($this->activeLangs AS $lang) {
                    $t['title'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . htmlspecialchars_decode($row['title_' . $lang['isoCode']]) . '</li>';
                }

                $t['title'] .= '</ul></div>';

                /*
                 * Szerkesztési menü
                 */

                $t['edit'] = '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-mini" href="/throne/content/editFixed/' . $row['id'] . '.html"><i class="icon-edit"></i></a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $r['content'] .= $this->table->createSimpleTable($heads,$newData);

        } else {
            $r['content'] .= '<p>Nincs megjeleníthető adat!</p>';
        }

        return $r;
    }

    /**
     * @param int $fixedContentId
     * @return mixed
     */
    function editFixed($fixedContentId = null) {

        $r['content'] = null;

        if (is_numeric($fixedContentId)) {

            $data = $this->model->getFixedContentById($fixedContentId);

            if (is_array($this->activeLangs)) {

                foreach ($this->activeLangs AS $l) {
                    $this->form->addInput('textField','title_'.$l['isoCode'],htmlspecialchars_decode($data['title_' . $l['isoCode']]), null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Cím',true);
                    $this->form->addInput('textArea','content_'.$l['isoCode'],htmlspecialchars_decode($data['content_' . $l['isoCode']]), null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Tartalom',true);
                }

                $this->form->addInput('hidden','contentId',$fixedContentId);

                $r['content'] = $this->form->generateForm('saveFixed','Mentés',null,'/throne/content/fixedContent.html','bootstrap-horizontal');

            }

        }

        return $r;
    }

    private function articleForm($edit = false) {
        if (is_array($this->activeLangs)) {
            foreach ($this->activeLangs AS $l) {

                $this->form->addInput('textField','title_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Cím',true);
                $this->form->addInput('textField','metaKey_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Kulcsszavak',true);
                $this->form->addInput('textField','metaDesc_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Leírás',true);
                $this->form->addInput('textField','linkAlt_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Link segédszöveg',true);
                $this->form->addInput('textArea','content_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" style="width: 28px">&nbsp;Tartalom',true);

                }

            $target = '/throne/content/newArticle.html';

            if ($edit) {
                $target = '/throne/content/editArticle/' . coreFunctions::cleanVar($_GET['var1']) . '.html';
            }

            return $this->form->generateForm('submitArticle','Mentés',null,$target,'bootstrap-horizontal');
        }

        return null;
    }

    /**
     * TODO Megcsinálni az űrlap hiba visszajelzést
     * @return mixed
     */
    function newArticle() {

        $r['content'] = null;

        $dataToSave = $this->form->validator();

        if (is_array($dataToSave) and count($dataToSave)>0) {
            $r['content'] .= '<a href="/throne/content/newArticle.html" role="button" class="btn">Új cikk</a>';

            if (is_array($this->activeLangs)) {

                /*
                 * Slug gyártás
                 */

                foreach ($this->activeLangs AS $l) {

                    $dataToSave['slug_'.$l['isoCode']] = coreFunctions::slugger($dataToSave['title_'.$l['isoCode']]);

                }

                $r['content'] .= '<a href="/throne/content/articles.html" role="button" class="btn">Cikkek</a>';

                if ($this->model->fragger($dataToSave,'content')) {

                    $r['content'] .= '<p>Az új cikk sikeresen mentésre került!</p>';

                } else {

                    $r['content'] .= '<p>Nem sikerült elmenteni a cikket!</p>';

                }

            } else {
                $r['content'] = '<p>Nincs aktív nyelv</p>';
            }

        } else {
            $r['content'] .= $this->articleForm();
        }

        return $r;
    }

    function homeContent() {
        $data = $this->model->getFixedContentById(1);

        $r['head'] = htmlspecialchars_decode($data['title_' . $_SESSION['lang']]);
        $r['lead'] = htmlspecialchars_decode(htmlspecialchars_decode($data['content_' . $_SESSION['lang']]));

        return $r;

    }

    /**
     * Cikk szerkesztése
     * @param null $articleId
     * @return mixed
     */
    function editArticle($articleId = null) {

        $r['content'] = null;

        if (is_numeric($articleId)) {

            $dataToSave = $this->form->validator();

            if (is_array($dataToSave) AND count($dataToSave)>0) {

                unset($dataToSave['editForm']);

                $r['content'] .= '<a href="/throne/content/articles.html" role="button" class="btn">Cikkek</a>';

                if ($this->model->fragger($dataToSave,'content','update'," id='$articleId'",false)) {

                    $r['content'] .= '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        A cikk sikeresen módosításra került!
                    </div>
                ';
                } else {
                    $r['content'] .= '
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Nem sikerült módosítani a cikket!
                    </div>
                ';
                }


            } else {

                $data = $this->model->getArticle($articleId);

                /*
                 * Character decode CKEDITOR miatt, de csak bizonyos mezőkre
                 */

                foreach ($this->activeLangs AS $l) {

                    $data['content_' . $l['isoCode']] = htmlspecialchars_decode($data['content_' . $l['isoCode']]);

                }

                $_SESSION['postBack'] = $data;

                $r['content'] .= $this->articleForm(true);
            }


        }

        return $r;
    }

    function view($slug = null) {

        $lng = coreFunctions::cleanVar($_GET['lang']);
        $data = $this->model->getArticleBySlug($lng,$slug);

        $r['content'] = null;
        $r['title'] = null;

        if (is_array($data)) {

            $title = 'title_' . $lng;
            $content = 'content_' . $lng;

            $r['title'] = $data[$title];
            $r['content'] = htmlspecialchars_decode(htmlspecialchars_decode($data[$content]));

        } else {
            $r['title'] = '404';
            $r['content'] = '<p>A keresett tartalom nem található!</p>';
        }

        return $r;
    }
}