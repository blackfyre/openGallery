<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.09.10.
 * Time: 16:42
 */

/**
 * Class topics
 */
class topics {

    /**
     * @var null|topicsModel
     */
    private $model = null;
    /**
     * @var formHandler|null
     */
    private $form = null;
    /**
     * @var array|bool|null
     */
    private $activeLang = null;

    /**
     * Construct
     */
    function __construct() {
        $this->model = new topicsModel();
        $this->form = new formHandler();

        $this->activeLang = $this->model->getActiveLanguages();
    }

    /**
     * @return mixed
     */
    function throne_listTopics() {

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Topic Manager');
        $r['content'] = null;
        $r['control'] = null;

        $control[] = array('link'=>"/throne/topics/addNewTopic.html","icon"=>'plus-sign',"text"=>gettext('New topic'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $data = $this->model->getTopics();

        if (is_array($data) AND count($data)>0) {

            $newData = null;

            $heads['title'] = gettext('Title');
            $heads['edit'] = gettext('Edit');

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang,$row,'categoryName');
                $t['edit'] = null;

                $t['edit'] .= '<div class="btn-toolbar">';

                /*
                 * List topic articles
                 */
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-info btn-xs" href="/throne/topics/throne_listArticles/' . $t['cacId'] . '.html"><span class="glyphicon glyphicon-bookmark"></span></a>';
                $t['edit'] .= '</div>';

                /*
                 * Edit topic (topic image, topic title, topic excerpt)
                 */
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editTopic/' . $t['cacId'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '<a class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-trash"></span></a>';
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
    function topicForm() {
        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('categoryName_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Category name'),true);
                $this->form->addCKEditor('categoryExcerpt_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Category description'),true);
            }

        } else {
            $this->form->addTextField('categoryName_' . $_SESSION['lang'],null,null,gettext('Category name'),true);
            $this->form->addCKEditor('categoryExcerpt_' . $_SESSION['lang'],null,null,gettext('Category description'),true);
        }

        $this->form->addFileUpload('categoryImg',gettext('Category image'));

        return $this->form->generateForm('topicForm',gettext('Save'));
    }

    /**
     * @return string
     */
    private function saveNewTopic() {
        $data = $this->form->validator();

        if (_MULTILANG) {

            foreach ($this->activeLang as $l) {
                $data['categorySlug_' . $l['isoCode']] = coreFunctions::slugger($data['categoryName_' . $l['isoCode']]);
            }

        } else {
            $data['categorySlug_' . $_SESSION['lang']] = coreFunctions::slugger($data['categoryName_' . $_SESSION['lang']]);
        }


        if ($this->model->insert($data,'content_articles_category')) {
            return buildingBlocks::successMSG(gettext('New topic saved!'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    private function updateTopic($topicId = null) {

        $data = $this->form->validator();

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['categorySlug_' . $l['isoCode']] = coreFunctions::slugger($data['categoryName_' . $l['isoCode']]);
            }

        } else {
            $data['categorySlug_' . $_SESSION['lang']] = coreFunctions::slugger($data['categoryName_' . $_SESSION['lang']]);
        }

        if ($this->model->updater($data,'content_articles_category',"cacId='$topicId'")) {
            return buildingBlocks::successMSG(gettext('Topic update successful!'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }

    /**
     * @param null $topicId
     * @return mixed
     */
    function throne_editTopic($topicId = null) {

        if (isset($_POST['submit-topicForm'])) {
            $r['msg'] = $this->updateTopic($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Edit Topic');


        $control[] = array('link'=>"/throne/topics/throne_listTopics.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);


        $data = $this->model->getTopicDataById($topicId);

        $data = buildingBlocks::decodeForEdit($this->activeLang,$data,'categoryExcerpt');

        $_SESSION['postBack'] = $data;

        $r['content'] = $this->topicForm();

        return $r;
    }

    /**
     * @return mixed
     */
    function addNewTopic() {

        if (isset($_POST['submit-topicForm'])) {
            $r['msg'] = $this->saveNewTopic();
        }

        $r['moduleTitle'] = gettext('Topic Manager');

        $control[] = array('link'=>"/throne/topics/throne_listTopics.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = $this->topicForm();

        return $r;
    }

    /**
     * @param null $topicId
     * @return mixed
     */
    function throne_listArticles($topicId = null) {
        $r['moduleTitle'] = gettext('Article manager');

        $control[] = array('link'=>"/throne/topics/throne_newTextArticle/$topicId.html","icon"=>'file',"text"=>gettext('New text article'));
        $control[] = array('link'=>"/throne/topics/throne_newVideoArticle/$topicId.html","icon"=>'film',"text"=>gettext('New video article'));
        $control[] = array('link'=>"/throne/topics/throne_listTopics.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = null;

        $data = $this->model->getArticleForTopic($topicId);

        if (is_array($data) AND count($data)>0) {

            $toShow = null;

            $heads['title'] = gettext('Title');
            $heads['edit'] = gettext('Edit');

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang,$row,'title');

                $t['edit'] = '<div class="btn-group">';

                if (isset($row['youtube']) OR isset($row['indavideo'])) {
                    $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editVideoArticle/' . $topicId . '/' . $t['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                } else {
                    $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editTextArticle/' . $topicId . '/' . $t['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                }


                $t['edit'] .= '<a class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-trash"></span></a>';
                $t['edit'] .= '</div>';

                $toShow[] = $t;
            }

            $r['content'] = buildingBlocks::createSimpleTable($heads,$toShow);

        } else {
            $r['msg'] = buildingBlocks::noRecords();
        }

        return $r;
    }


/* ------------------------------------------------------------------------------------------------------------------ */
/* ------------------------------------------- TEXT Article --------------------------------------------------------- */
/* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @return bool|string
     */
    private function textArticleForm() {

        $r = null;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('title_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article title'));
                $this->form->addTextField('metaKey_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article meta keys'));
                $this->form->addCKEditor('excerpt_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article excerpt'));
                $this->form->addCKEditor('content_'.$l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article'));
            }

        } else {

            $this->form->addTextField('title_' . $_SESSION['lang'],null,null,gettext('Article title'));
            $this->form->addTextField('metaKey_' . $_SESSION['lang'],null,null,gettext('Article meta keys'));
            $this->form->addCKEditor('excerpt_' . $_SESSION['lang'],null,null,gettext('Article excerpt'));
            $this->form->addCKEditor('content_'.$_SESSION['lang'],null,null,gettext('Article'));

        }

        $r = $this->form->generateForm('saveTextArticle',gettext('Save'));

        return $r;
    }

    /**
     * Save a new text article
     * @param null $topicId
     * @return string
     */
    private function saveNewTextArticle($topicId = null) {

        $data = $this->form->validator();
        $data['topicId'] = $topicId;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {
            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);
        }

        if ($this->model->insert($data,'content_articles')) {
            return buildingBlocks::successMSG(gettext('Text article successfully saved!'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }

    /**
     * @param null $topicId
     * @return mixed
     */
    function throne_newTextArticle($topicId = null) {

        if (isset($_POST['submit-saveTextArticle'])) {
            $r['msg'] = $this->saveNewTextArticle($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-file"></span> ' . gettext('New text article');
        $control[] = array('link'=>"/throne/topics/throne_listArticles/$topicId.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->textArticleForm();

        return $r;
    }

    /**
     * @param null $articleId
     * @return string
     */
    private function updateTextArticle($articleId = null) {
        $data = $this->form->validator();

        unset($data['editForm']);

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {

            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);

        }


        /*
         * Create backup
         */
        $dataArchive = $data;

        $user = $this->model->getCurrentThroneUserData();
        $dataArchive['addedBy'] = $user['uid'];

        $dataArchive['articleId'] = $articleId;

        $this->model->insert($dataArchive,'content_articles_history',false);

        /*
         * Update article
         */

        if ($this->model->updater($data,'content_articles',"id='$articleId'")) {
            return buildingBlocks::successMSG(gettext('Text article update successful!'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @param null $topicId
     * @param null $articleId
     * @return mixed
     */
    function throne_editTextArticle($topicId = null,$articleId = null) {

        if (isset($_POST['submit-saveTextArticle'])) {
            $r['msg'] = $this->updateTextArticle($articleId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Edit text article');

        $data = $this->model->getArticleById($articleId);

        $data = buildingBlocks::decodeForEdit($this->activeLang,$data,'excerpt');
        $data = buildingBlocks::decodeForEdit($this->activeLang,$data,'content');

        $_SESSION['postBack'] = $data;

        $control[] = array('link'=>"/throne/topics/throne_listArticles/$topicId.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = $this->textArticleForm();

        return $r;
    }

/* ------------------------------------------------------------------------------------------------------------------ */
/* ------------------------------------------ Video Article --------------------------------------------------------- */
/* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @return bool|string
     */
    private function videoArticleForm() {
        $r = null;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('title_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article title'));
                $this->form->addTextField('metaKey_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article meta keys'));
                $this->form->addCKEditor('excerpt_' . $l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article excerpt'));
                $this->form->addCKEditor('content_'.$l['isoCode'],null,null,'<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article'));
            }

        } else {

            $this->form->addTextField('title_' . $_SESSION['lang'],null,null,gettext('Article title'));
            $this->form->addTextField('metaKey_' . $_SESSION['lang'],null,null,gettext('Article meta keys'));
            $this->form->addCKEditor('excerpt_' . $_SESSION['lang'],null,null,gettext('Article excerpt'));
            $this->form->addCKEditor('content_'.$_SESSION['lang'],null,null,gettext('Article'));

        }

        $this->form->addTextArea('youtube',null,gettext('Embed code'),'Youtube');
        $this->form->addTextArea('indavideo',null,gettext('Embed code'),'Indavideo');

        $r = $this->form->generateForm('saveVideoArticle',gettext('Save'));

        return $r;
    }

    /**
     * @param $topicId
     * @return string
     */
    private function saveNewVideoArticle($topicId) {

        $data = $this->form->validator();

        $data['topicId'] = $topicId;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {
            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);
        }

        $data['youtubeLink'] = str_replace('//','',strtok(coreFunctions::getIframeSrc(coreFunctions::decoder($data['youtube'])),'?'));
        $data['indavideoLink'] = str_replace('//','',strtok(coreFunctions::getIframeSrc(coreFunctions::decoder($data['indavideo'])),'?'));

        if ($this->model->insert($data,'content_articles')) {
            return buildingBlocks::successMSG('Video article successfully saved!');
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @param null $topicId
     */
    function throne_newVideoArticle($topicId = null) {
        if (isset($_POST['submit-saveVideoArticle'])) {
            $r['msg'] = $this->saveNewVideoArticle($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-file"></span> ' . gettext('New text article');

        $control[] = array('link'=>"/throne/topics/throne_listArticles/$topicId.html","icon"=>'arrow-left',"text"=>gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->videoArticleForm();

        return $r;
    }
}