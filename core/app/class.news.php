<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 15:31
 */

class news {

    /**
     * @var null|newsModel
     */
    private $model = null;

    /**
     * @var null|formHandler
     */
    private $form = null;

    private $table = null;

    /**
     * @var null|array
     */
    private $activeLangs = null;

    function __construct() {
        $this->model = new newsModel();
        $this->form = new formHandler();
        $this->table = new tableHandler();

        $this->activeLangs = $this->model->getActiveLanguages();
    }

    function throne_listNews($listType = null) {

        $r = null;

        $r['moduleTitle'] = gettext('List News');
        $r['newArticleLink'] = '/throne/news/throne_addNewArticle.html';
        $r['newArticle'] = gettext('New article');

        switch ($listType) {
            default:
                $listType = null;
                break;
            case 'published':
                $listType = 1;
                break;
            case 'unpublished':
                $listType = 0;
                break;
        }


        $data = $this->model->getNews(null,$listType);

        if (count($data)>0) {

            $heads['flag'] = '';
            $heads['newsId'] = 'id';
            $heads['isoCode'] = 'isoCode';
            $heads['title'] = 'title';
            $heads['metaKey'] = 'metaKey';
            $heads['edit'] = 'edit';

            $newData = null;

            foreach ($data AS $row) {
                $t = $row;

                $t['flag'] = '<img class="tblFlag" src="/img/flags/flag-' . $t['isoCode'] . '.png">';

                $t['edit'] = '<div class="btn-group">';
                $t['edit'] .= '<a title="' . gettext('Edit article') . '" href="/throne/news/throne_editArticle/' . $t['newsId'] . '.html" type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '<a title="' . gettext('View article') . '" target="_blank" href="/' . $t['isoCode'] . '/artist/' . $t['slug'] . '.html" type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-new-window"></span></a>';
                $t['edit'] .= '</div>';


                $newData[] = $t;
            }

            $r['content'] = $this->table->createSimpleTable($heads,$newData);

        } else {
            $r['msg'] = buildingBlocks::infoMSG(gettext('There are no news entries available'),false);
        }


        return $r;

    }

    function throne_addNewArticle() {

        if (isset($_POST['submit-saveArticle'])) {
            $r['msg'] = $this->saveNewNews();
        }

        $r['moduleTitle'] = gettext('Add new article');

        $r['content'] = $this->newsForm();


        return $r;

    }

    /**
     * Generate the news form for creating and editing articles
     *
     * @TODO AJAX TAG Manager for the metaKey field
     *
     * @return bool|string
     */
    private function newsForm() {

        $langs = null;

        foreach ($this->activeLangs AS $l) {
            $langs[$l['isoCode']] = $l['full'];
        }

        $this->form->addInput('dropdownList','isoCode',$langs,null,gettext('Language'));
        $this->form->addInput('textField','title',null,null,gettext('Title'),true);
        $this->form->addInput('textField','metaKey',null,null,gettext('Meta keys'),true);
        $this->form->addInput('textArea','metaDesc',null,null,gettext('Meta description'),true);
        $this->form->addInput('textArea','linkAlt',null,null,gettext('Link tooltip'),true);
        $this->form->addInput('ckeditor','content',null,null,gettext('Content'),true);

        return $this->form->generateForm('saveArticle',gettext('Save'),null,null,'bootstrap-horizontal');
    }

    private function saveNewNews() {
        $data = $this->form->validator();

        if (in_array(false,$data,true)) {
            $_SESSION['postBack'] = $data;
            return buildingBlocks::errorMSG(gettext('Invalid input!'));
        }

        $data['slug'] = coreFunctions::slugger($data['title']);

        if ($this->model->fragger($data,'content_news','insert',null,true)) {
            return buildingBlocks::successMSG(gettext('New article successfully created!'));
        } else {
            $_SESSION['postBack'] = $data;
            return buildingBlocks::formSaveFail();
        }
    }
}