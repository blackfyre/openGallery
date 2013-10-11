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
        $r['newArticle'] = gettext('New Article');

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

                $t['edit'] = '<div class="btn-toolbar"><div class="btn-group">';
                $t['edit'] .= '<a title="' . gettext('Edit article') . '" href="/throne/news/throne_editArticle/' . $t['newsId'] . '.html" type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span></a>';

                if ($t['published']=='1') {
                    $t['edit'] .= '<a title="' . gettext('Unpublish article') . '" href="#" onClick="unpublishArticle(' . $t['newsId'] . ')" type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-eye-close"></span></a>';
                } else {
                    $t['edit'] .= '<a title="' . gettext('Publish article') . '" href="#" onClick="publishArticle(' . $t['newsId'] . ')" type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
                }

                $t['edit'] .= '</div><div class="btn-group">';


                $t['edit'] .= '<a title="' . gettext('Delete article') . '" target="_blank" href="#" type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
                $t['edit'] .= '</div><div class="btn-group">';
                $t['edit'] .= '<a title="' . gettext('View article') . '" target="_blank" href="/' . $t['isoCode'] . '/news/viewArticle/' . $t['newsId'] .'/' . $t['slug'] . '.html" type="button" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-new-window"></span></a>';
                $t['edit'] .= '</div></div>';


                $newData[] = $t;
            }

            $r['content'] = $this->table->createSimpleTable($heads,$newData,null,true,'newsTable');

        } else {
            $r['msg'] = buildingBlocks::infoMSG(gettext('There are no news entries available'),false);
        }


        return $r;

    }

    /**
     * @param null $articleId
     * @param null $state
     * @return bool|null
     */
    function modifyPublishState($articleId = null, $state = null) {

        $data['published'] = $state;

        return $this->model->fragger($data,'content_news','update',"newsId='$articleId'");

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

        if(isset($_SESSION['postBack']['addedOn'])) {
            $this->form->addInput('hidden','addedOn');
            $this->form->addInput('hidden','newsId');
            $this->form->addInput('hidden','published');
        }


        return $this->form->generateForm('saveArticle',gettext('Save'),null,null,'bootstrap-horizontal');
    }

    /**
     * @return string
     */
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

    private function saveArticleEdit() {

        $data = $this->form->validator();

        unset($data['editForm']);



        $user = $this->model->getCurrentThroneUserData();

        $data['editorId'] = $user['uid'];
        $data['slug'] = coreFunctions::slugger($data['title']);

        Kint::dump($data);

        if ($this->model->fragger($data,'content_news_history','insert',null)) {

            $articleId = $data['newsId'];

            unset($data['editorId'],$data['newsId'],$data['published']);

            if ($this->model->fragger($data,'content_news','update',"newsId='$articleId'")) {
                return buildingBlocks::successMSG(gettext('Article successfully saved!'));
            } else {
                $_SESSION['postBack'] = $data;
                return buildingBlocks::formSaveFail();
            }

        } else {
            $_SESSION['postBack'] = $data;
            return buildingBlocks::formSaveFail();
        }

    }

    function throne_editArticle($articleId =  null) {

        if (isset($_POST['submit-saveArticle'])) {
            $r['msg'] = $this->saveArticleEdit();
        }


        $articleId = coreFunctions::cleanVar($articleId);

        $data = $this->model->getArticle($articleId);
        $data['content'] = coreFunctions::decoder($data['content']);

        $_SESSION['postBack'] = $data;

        $r['moduleTitle'] = gettext('Edit article');

        $r['content'] = $this->newsForm();

        return $r;
    }

    function getLatest($lang = null, $count = 5) {

        if (is_null($lang)) {
            $lang = $_SESSION['lang'];
        }

        $data = $this->model->getNews($lang,1,$count);

        $r = null;

        if (is_array($data)) {
            foreach ($data AS $a) {
                $r .= '<h3><a href="/' . $lang .'/news/viewArticle/' . $a['newsId'] . '/' . $a['slug'] . '.html" hreflang="' . $lang . '" title="' . $a['linkAlt'] . '">' . $a['title'] . '</a></h3>';
            }
        }

        return $r;
    }

    /**
     * This method is responsible for viewing the article on the front-end.
     *
     * Keep in mind that there's no filtering, so anyone can access unpublished articles as well if that person has a direct link to the article
     *
     * @param null|int $articleId
     * @param null|string $articleSlug
     * @return mixed
     */
    function viewArticle($articleId = null, $articleSlug = null) {

        $r['content'] = null;
        $r['latestNews'] = gettext('Latest entries');
        $r['latest'] = $this->getLatest();

        if (is_numeric($articleId)) {
            $article = $this->model->getArticle(coreFunctions::cleanVar($articleId));

            $r['title'] = $article['title'];
            $r['created'] = $article['addedOn'];
            $r['metaTitle'] = $article['title'];
            $r['metaDesc'] = $article['metaDesc'];
            $r['content'] = coreFunctions::decoder($article['content']);
            $r['keywords'] = $article['metaKey'];
        }

        return $r;


    }
}