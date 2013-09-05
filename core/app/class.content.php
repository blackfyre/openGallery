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
        $r['moduleTitle'] = gettext('Articles');
        $r['control'] .= '<p><a href="/throne/content/newArticle.html" role="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> ' . gettext('New Article') . '</a></p>';

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
                $t['edit'] .= '<a class="btn btn-default btn-xs" href="/throne/content/editArticle/' . $row['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $heads['id'] = '#';
            $heads['title'] = 'title';
            $heads['metaKey'] = 'metaKey';
            $heads['linkAlt'] = 'linkAlt';
            $heads['edit'] = '';

            $r['content'] .= $this->table->createSimpleTable($heads,$newData);

        } else {
            $r['content'] = '<p>No data to show!</p>';
        }

        return $r;

    }

    /**
     * Rögzített tartalom Savee
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

    function processFixedContent() {

        $r = null;

        if (isset($_POST['submit-saveFixed'])) {
            $formData = $this->form->validator();

            if ($this->saveFixedContent($formData)) {
                $r = '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Content successfully modified!
                    </div>
                ';
            } else {
                $r = '
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        An error occured!
                    </div>
                ';
            }

        }

        return $r;
    }


    /**
     * A rögzített tartalmak listázása
     * @return array
     */
    function fixedContent() {
        $r['content'] = null;

        $r['msg'] = $this->processFixedContent();

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
                $t['edit'] .= '<a class="btn btn-default btn-xs" href="/throne/content/editFixed/' . $row['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
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
     * Rögzített tartalom szerkesztése
     * @param int $fixedContentId
     * @return mixed
     */
    function editFixed($fixedContentId = null) {

        $r['content'] = null;
        $r['msg'] = null;

        if (is_numeric($fixedContentId)) {

            $data = $this->model->getFixedContentById($fixedContentId);

            if (is_array($this->activeLangs)) {

                foreach ($this->activeLangs AS $l) {
                    $this->form->addInput('textField','title_'.$l['isoCode'],htmlspecialchars_decode($data['title_' . $l['isoCode']]), null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Title',true);
                    $this->form->addInput('textArea','content_'.$l['isoCode'],htmlspecialchars_decode($data['content_' . $l['isoCode']]), null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Content',true);
                }

                $this->form->addInput('hidden','contentId',$fixedContentId);

                $r['content'] = $this->form->generateForm('saveFixed','Save',null,'/throne/content/fixedContent.html');

            }

        }

        return $r;
    }

    /**
     * Cikk szerkesztése/létrehozása űrlap
     * @param bool $edit
     * @return bool|null|string
     */
    private function articleForm($edit = false) {
        if (is_array($this->activeLangs)) {
            foreach ($this->activeLangs AS $l) {

                $this->form->addInput('textField','title_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Title',true);
                $this->form->addInput('textField','metaKey_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Keywords',true);
                $this->form->addInput('textField','metaDesc_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Description',true);
                $this->form->addInput('textField','linkAlt_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Link Alt text',true);
                $this->form->addInput('textArea','content_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Content',true);

                }

            $target = '/throne/content/newArticle.html';

            if ($edit) {
                $target = '/throne/content/editArticle/' . coreFunctions::cleanVar($_GET['var1']) . '.html';
            }

            return $this->form->generateForm('submitArticle','Save',null,$target);
        }

        return null;
    }

    /**
     * @return mixed
     */
    function newArticle() {

        $r['content'] = null;
        $r['msg'] = null;
        $r['moduleTitle'] = gettext('New article');
        $r['navTitle'] = gettext('Navigation');
        $r['backLink'] = gettext('Back');

        $dataToSave = $this->form->validator();

        if (is_array($dataToSave) and count($dataToSave)>0) {
            $r['content'] .= '<p><a href="/throne/content/newArticle.html" role="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus-sign"></span> ' . gettext('New Article') . '</a></p>';

            if (is_array($this->activeLangs)) {

                /*
                 * Slug gyártás
                 */

                foreach ($this->activeLangs AS $l) {

                    $dataToSave['slug_'.$l['isoCode']] = coreFunctions::slugger($dataToSave['title_'.$l['isoCode']]);

                }

                $r['content'] .= '<p><a href="/throne/content/articles.html" role="button" class="btn">Articles</a></p>';

                if ($this->model->fragger($dataToSave,'content')) {
                    $r['msg'] = '
                    <div class="alert alert-success">
                        <strong>SUCCESS!</strong> Article successfully saved!
                    </div>
                ';
                } else {
                    $r['msg'] = '
                    <div class="alert alert-error">
                        <strong>FAIL!</strong> An error occured and could not save the article!
                    </div>
                ';
                }

            } else {
                $r['msg'] = '
                    <div class="alert alert-error">
                        <strong>FAIL!</strong> There are no active languages!
                    </div>
                ';
            }

        } else {
            $r['content'] .= $this->articleForm();
        }

        return $r;
    }

    /**
     * @return mixed
     */
    function homeContent() {
        $data = $this->model->getFixedContentById(1);

        $r['head'] = null;
        $r['lead'] = null;

        if (is_array($data)) {
            $r['head'] = htmlspecialchars_decode($data['title_' . $_SESSION['lang']]);
            $r['lead'] = htmlspecialchars_decode(htmlspecialchars_decode($data['content_' . $_SESSION['lang']]));
        }



        return $r;

    }

    /**
     * Lábléc beszerzése
     * @return mixed
     */
    function getFooter() {
        $data = $this->model->getFixedContentById(2);

        $r['footer'] = htmlspecialchars_decode(htmlspecialchars_decode($data['content_' . $_SESSION['lang']]));

        return $r;
    }

    /**
     * Cikk szerkesztése
     * @param null $articleId
     * @return mixed
     */
    function editArticle($articleId = null) {

        $r['content'] = null;
        $r['msg'] = null;
        $r['moduleTitle'] = gettext('Edit article');
        $r['navTitle'] = gettext('Navigation');
        $r['backLink'] = gettext('Back');

        if (is_numeric($articleId)) {

            $dataToSave = $this->form->validator();

            if (is_array($dataToSave) AND count($dataToSave)>0) {

                unset($dataToSave['editForm']);

                $r['content'] .= '<p><a href="/throne/content/articles.html" role="button" class="btn">Articles</a></p>';

                if ($this->model->fragger($dataToSave,'content','update'," id='$articleId'",false)) {

                    $r['msg'] .= '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        The article has been successfully edited!
                    </div>
                ';
                } else {
                    $r['msg'] .= '
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Failed to edit the article!
                    </div>
                ';

                    $_SESSION['postBack'] = $dataToSave;
                    $r['content'] .= $this->articleForm(true);
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

    function listEmailTemplates() {

        $r['content'] = null;

        $data = $this->model->getEmailTemplates();

        if (is_array($data)) {

            $newData = null;

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png" class="smallFlag">&nbsp;' . $row['title_hu'] . '</a><ul class="dropdown-menu" role="menu">';
                $t['subject'] = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-hu.png" class="smallFlag">&nbsp;' . $row['subject_hu'] . '</a><ul class="dropdown-menu" role="menu">';

                foreach ($this->activeLangs AS $lang) {
                    $t['title'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $row['title_' . $lang['isoCode']] . '</li>';
                    $t['subject'] .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $row['subject_' . $lang['isoCode']] . '</li>';
                }

                $t['title'] .= '</ul></div>';
                $t['subject'] .= '</ul></div>';

                $t['edit'] = '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-default btn-xs" href="/throne/content/editEmail/' . $row['templateId'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $heads['title'] = 'title';
            $heads['subject'] = 'subject';
            $heads['edit'] = 'edit';

            $r['content'] .= $this->table->createSimpleTable($heads,$newData);

        } else {
            $r['content'] = '<p>These are not the email templates you\'re looking for!</p>';
        }


        return $r;

    }

    function processEmailTemplate($templateId = null) {
        $r = null;

        if (isset($_POST['submit-submitEmailTemplate'])) {
            $data = $this->form->validator();

            unset($data['editForm']);

            if ($this->model->fragger($data,'email_templates','update',"templateId='$templateId'")) {
                $r = '
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Template successfully modified!
                    </div>
                ';
            } else {
                $r = '
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        An error occured!
                    </div>
                ';
            }

        }

        return $r;
    }

    function editEmail($templateId = null) {

        $r['content'] = null;
        $r['msg'] = null;

        if (is_numeric(coreFunctions::cleanVar($templateId))) {

            $r['msg'] = $this->processEmailTemplate($templateId);

            $emailModel = new emailNotificationsModel();

            $template = $emailModel->getEmailTemplate($templateId);

            foreach ($this->activeLangs AS $l) {
                $template['content_' . $l['isoCode']] = htmlspecialchars_decode($template['content_' . $l['isoCode']]);
            }

            $_SESSION['postBack'] = $template;

            if (is_array($this->activeLangs)) {
                foreach ($this->activeLangs AS $l) {

                    $this->form->addInput('textField','title_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Title',true);
                    $this->form->addInput('textField','subject_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Subject',true);
                    $this->form->addInput('textArea','content_'.$l['isoCode'],null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;Content',true);

                }

                $target = '/throne/content/editEmail/' . $templateId . '.html';

                $r['content'] = $this->form->generateForm('submitEmailTemplate','Save',null,$target);
            }


        } else {
            $r['msg'] = '
            <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Invalid email template reference!
                    </div>
            ';
        }

        return $r;

    }
}