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
class topics
{

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
    function __construct()
    {
        $this->model = new topicsModel();
        $this->form  = new formHandler();

        $this->activeLang = $this->model->getActiveLanguages();
    }

    /**
     * @param null $articleId
     *
     * @return bool|null
     */
    function killArticle($articleId = null)
    {
        $data['active'] = 0;

        return $this->model->updater($data, 'content_articles', "id='$articleId'");
    }

    /**
     * @param null $topicId
     * @param null $articleId
     */
    function featureArticle($topicId = null, $articleId=null) {
        /*
         * Kill all featured in the topic
         */
        $data['featured'] = 0;

        $this->model->updater($data,'content_articles',"topicId='$topicId'");

        /*
         * Set featured article
         */
        $data['featured'] = 1;

        $this->model->updater($data,'content_articles',"id='$articleId'");
    }

    /* ------------------------------------------------------------------------------------------------------------------ */
    /* ----------------------------------------------- TOPIC ------------------------------------------------------------ */
    /* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @return mixed
     */
    function throne_listTopics()
    {

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-folder-open"></span> ' . gettext('Topic Manager');
        $r['content']     = null;
        $r['control']     = null;

        $control[] = array('link' => "/throne/topics/addNewTopic.html", "icon" => 'plus-sign', "text" => gettext('New topic'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $data = $this->model->getTopics();

        if (is_array($data) AND count($data) > 0) {

            $newData = null;

            $heads['title'] = gettext('Title');
            $heads['edit']  = gettext('Edit');

            foreach ($data AS $row) {
                $t = $row;

                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang, $row, 'categoryName');
                $t['edit']  = null;

                $t['edit'] .= '<div class="btn-toolbar">';

                /*
                 * List topic articles
                 */
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-info btn-xs" href="/throne/topics/throne_listArticles/' . $t['cacId'] . '.html"><span class="glyphicon glyphicon-bookmark"></span></a>';
                $t['edit'] .= '<a class="btn btn-info btn-xs" href="/throne/topics/throne_listTopicMenuItems/' . $t['cacId'] . '.html"><span class="glyphicon glyphicon-random"></span></a>';
                $t['edit'] .= '</div>';

                /*
                 * Edit topic (topic image, topic title, topic excerpt)
                 */
                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editTopic/' . $t['cacId'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '<a class="btn btn-danger btn-xs" onclick="deleter(\'' . $t['cacId'] . '\',\'topic\')" href="#"><span class="glyphicon glyphicon-trash"></span></a>';
                $t['edit'] .= '</div>';

                $t['edit'] .= '</div>';

                $newData[] = $t;
            }

            $r['content'] = buildingBlocks::createSimpleTable($heads, $newData);

        } else {
            $r['msg'] = buildingBlocks::noRecords();
        }

        return $r;

    }

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_editTopic($topicId = null)
    {

        if (isset($_POST['submit-topicForm'])) {
            $r['msg'] = $this->updateTopic($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Edit Topic');


        $control[] = array('link' => "/throne/topics/throne_listTopics.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);


        $data = $this->model->getTopicDataById($topicId);

        $data = buildingBlocks::decodeForEdit($this->activeLang, $data, 'categoryExcerpt');

        $_SESSION['postBack'] = $data;

        $r['content'] = $this->topicForm();

        return $r;
    }

    /**
     * @param null $topicId
     *
     * @return string
     */
    private function updateTopic($topicId = null)
    {

        $data = $this->form->validator();

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['categorySlug_' . $l['isoCode']] = coreFunctions::slugger($data['categoryName_' . $l['isoCode']]);
            }

        } else {
            $data['categorySlug_' . $_SESSION['lang']] = coreFunctions::slugger($data['categoryName_' . $_SESSION['lang']]);
        }

        if ($this->model->updater($data, 'content_articles_category', "cacId='$topicId'")) {
            return buildingBlocks::successMSG(gettext('Topic update successful!'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }

    /**
     * @return bool|string
     */
    function topicForm()
    {
        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('categoryName_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Category name'), true);
                $this->form->addCKEditor('categoryExcerpt_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Category description'), true);
            }

        } else {
            $this->form->addTextField('categoryName_' . $_SESSION['lang'], null, null, gettext('Category name'), true);
            $this->form->addCKEditor('categoryExcerpt_' . $_SESSION['lang'], null, null, gettext('Category description'), true);
        }

        $this->form->addFileUpload('categoryImg', gettext('Category image'));

        return $this->form->generateForm('topicForm');
    }

    /**
     * @return mixed
     */
    function addNewTopic()
    {

        if (isset($_POST['submit-topicForm'])) {
            $r['msg'] = $this->saveNewTopic();
        }

        $r['moduleTitle'] = gettext('Topic Manager');

        $control[] = array('link' => "/throne/topics/throne_listTopics.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = $this->topicForm();

        return $r;
    }

    /**
     * @return string
     */
    private function saveNewTopic()
    {
        $data = $this->form->validator();

        if (_MULTILANG) {

            foreach ($this->activeLang as $l) {
                $data['categorySlug_' . $l['isoCode']] = coreFunctions::slugger($data['categoryName_' . $l['isoCode']]);
            }

        } else {
            $data['categorySlug_' . $_SESSION['lang']] = coreFunctions::slugger($data['categoryName_' . $_SESSION['lang']]);
        }


        if ($this->model->insert($data, 'content_articles_category')) {
            return buildingBlocks::successMSG(gettext('New topic saved!'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_listArticles($topicId = null)
    {
        $r['moduleTitle'] = "<span class='glyphicon glyphicon-bookmark'></span> " . gettext('Article manager');

        $control[] = array('link' => "/throne/topics/throne_newTextArticle/$topicId.html", "icon" => 'file', "text" => gettext('New text article'));
        $control[] = array('link' => "/throne/topics/throne_newVideoArticle/$topicId.html", "icon" => 'film', "text" => gettext('New video article'));
        $control[] = array('link' => "/throne/topics/throne_listTopicMenuItems/$topicId.html", "icon" => 'random', "text" => gettext('Edit menu'));
        $control[] = array('link' => "/throne/topics/throne_listTopics.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = null;

        $data = $this->model->getArticleForTopic($topicId);

        if (is_array($data) AND count($data) > 0) {

            $toShow = null;

            $heads['icon'] = '';
            $heads['title'] = gettext('Title');
            $heads['edit']  = gettext('Edit');

            foreach ($data AS $row) {
                $t = $row;


                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang, $row, 'title');

                $t['edit'] = '<div class="btn-group">';

                if (isset($row['youtube']) OR isset($row['indavideo'])) {
                    $t['icon'] = '<span class="glyphicon glyphicon-film"></span>';

                    $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editVideoArticle/' . $topicId . '/' . $t['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                } else {
                    $t['icon'] = '<span class="glyphicon glyphicon-file"></span>';
                    $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editTextArticle/' . $topicId . '/' . $t['id'] . '.html"><span class="glyphicon glyphicon-edit"></span></a>';
                }

                if ($row['featured'] == '1') {
                    $t['edit'] .= '<a title="' . gettext('Featured article') . '" class="btn btn-xs btn-success" href="#"><span class="glyphicon glyphicon-ok"></span></a>';
                } else {
                    $t['edit'] .= '<a title="' . gettext('Feature article') . '" class="btn btn-xs btn-danger" href="javascript:void (0)" onclick="setFeaturedArticle(\'' .$topicId . '\',\'' . $t['id'] . '\')"><span class="glyphicon glyphicon-remove"></span></a>';
                }

                $t['edit'] .= '<a class="btn btn-danger btn-xs" onclick="deleter(\'' . $t['id'] . '\',\'article\')" href="#"><span class="glyphicon glyphicon-trash"></span></a>';
                $t['edit'] .= '</div>';

                $toShow[] = $t;
            }

            $r['content'] = buildingBlocks::createSimpleTable($heads, $toShow);

        } else {
            $r['msg'] = buildingBlocks::noRecords();
        }

        return $r;
    }

    /**
     * @param null $topicId
     *
     * @return bool|null
     */
    function killTopic($topicId = null)
    {
        $data['active'] = 0;

        return $this->model->updater($data, 'content_articles_category', "cacId='$topicId'");
    }

    /* ------------------------------------------------------------------------------------------------------------------ */
    /* -------------------------------------------- TOPIC MENU ---------------------------------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_listTopicMenuItems($topicId = null)
    {

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-random"></span> ' . gettext('Topic menu');
        $control[]        = array('link' => "/throne/topics/throne_addArticleToMenu/$topicId.html", "icon" => 'bookmark', "text" => gettext('Add article'));
        $control[]        = array('link' => "/throne/topics/throne_addLinkToMenu/$topicId.html", "icon" => 'flag', "text" => gettext('Add link'));
        $control[]        = array('link' => "/throne/topics/throne_listArticles/$topicId.html", "icon" => 'bookmark', "text" => gettext('Edit articles'));
        $control[]        = array('link' => "/throne/topics/throne_listTopics.html", "icon" => 'arrow-left', "text" => gettext('Back'));
        $r['control']     = buildingBlocks::sideMenu($control);

        $data = $this->model->getMenuItemsForTopic($topicId);

        $heads['icon'] = '';
        $heads['title'] = 'Menu';
        $heads['edit']  = '';

        $newData = null;

        foreach ($data as $row) {
            $t = $row;

            if (isset($t['text_' . $_SESSION['lang']])) {
                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang, $row, 'text');
            } else {
                $t['title'] = buildingBlocks::langTableDropDown($this->activeLang, $row, 'title');
            }



            $t['edit'] = '<div class="btn-toolbar pull-right">';

            /*
             * Edit topic (topic image, topic title, topic excerpt)
             */

            if (isset($t['articleId'])) {

                if (isset($row['youtube']) OR isset($row['indavideo'])) {
                    $t['icon'] = '<span class="glyphicon glyphicon-film"></span>';
                } else {
                    $t['icon'] = '<span class="glyphicon glyphicon-file"></span>';
                }


                $t['edit'] .= '<div class="btn-group">';
                $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editArticle/' . $t['articleId'] . '.html" title="' . gettext('Edit article') . '"><span class="glyphicon glyphicon-edit"></span></a>';
                $t['edit'] .= '</div>';
            } else {
                $t['icon'] = '<span class="glyphicon glyphicon-flag"></span>';
            }

            $t['edit'] .= '<div class="btn-group">';

            $t['edit'] .= '<a class="btn btn-warning btn-xs" href="/throne/topics/throne_editMenuItem/' . $t['menuId'] . '.html" title="' . gettext('Edit menu item') . '"><span class="glyphicon glyphicon-edit"></span></a>';
            $t['edit'] .= '<a class="btn btn-danger btn-xs" onclick="deleter(\'' . $t['menuId'] . '\',\'topicMenu\')" href="#" title="' . gettext('Delete menu item') . '"><span class="glyphicon glyphicon-trash"></span></a>';
            $t['edit'] .= '</div>';

            $t['edit'] .= '</div>';

            $newData[] = $t;
        }

        $r['content'] = buildingBlocks::createSimpleTable($heads, $newData, array('sortableTable'), true, null, 'menuId');

        $r['info'] = gettext('You can re-order the menu elements by dragging them up/down on the list.');

        $r['jQuery'] = "
if ($('table').hasClass('sortableTable')) {
    var sorted = $('.sortableTable tbody').sortable({
        cursor: 'move',
        stop: function( event, ui ) {
            $.ajax({
                url: '/responders/topicMenuSorter.php?topicId=$topicId&'+sorted.sortable('serialize'),
                success: function(data) {
                    $.jGrowl('Ok!');
                },
                error: function () {
                    alert(jSCommunicationError);
                }
            });
        }
    });
}
        ";


        return $r;
    }

    /**
     * @param null $menuItemId
     *
     * @return bool|null
     */
    function killMenuItem($menuItemId = null)
    {
        $data['active'] = 0;
        return $this->model->updater($data, 'content_articles_category_menu', "id='$menuItemId'");
    }

    /**
     * @param null $menuId
     *
     * @return mixed
     */
    function throne_editMenuItem($menuId = null)
    {

        if (isset($_POST['submit-addArticle'])) {
            $r['msg'] = $this->updateMenuArticle($menuId);
        }

        if (isset($_POST['submit-menuLink'])) {
            $r['msg'] = $this->updateMenuLink($menuId);
        }

        $data                 = $this->model->getMenuItem($menuId);
        $_SESSION['postBack'] = $data;

        $r['content'] = null;

        if (isset($data['articleId'])) {
            $r['moduleTitle'] = '<span class="glyphicon glyphicon-flag"></span> ' . gettext('Edit link');
            $r['content']     = $this->addArticleForm($data['cacId'], $menuId);
        } else {
            $r['moduleTitle'] = '<span class="glyphicon glyphicon-flag"></span> ' . gettext('Edit link');
            $r['content']     = $this->menuLinkForm();
        }

        $control[] = array('link' => "/throne/topics/throne_listTopicMenuItems/" . $data['cacId'] . ".html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['info']    = null;

        return $r;

    }

    /**
     * @param null $menuId
     *
     * @return string
     */
    private function updateMenuArticle($menuId = null)
    {

        $data = $this->form->validator();

        unset($data['editForm']);

        if ($this->model->updater($data, 'content_articles_category_menu', "menuId='$menuId'")) {
            return buildingBlocks::successMSG(gettext('Article added to the menu'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }

    /**
     * @param null $menuId
     *
     * @return string
     */
    private function updateMenuLink($menuId = null)
    {

        $data = $this->form->validator();

        unset($data['editForm']);

        if ($this->model->updater($data, 'content_articles_category_menu', "menuId='$menuId'")) {
            return buildingBlocks::successMSG(gettext('Article added to the menu'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @param null $topicId
     * @param null $excludeId
     *
     * @return bool|string
     */
    private function addArticleForm($topicId = null, $excludeId = null)
    {
        $availableArticles = $this->model->getAvailableArticlesForTopic($topicId, $excludeId);

        if (is_array($availableArticles) AND count($availableArticles) > 0) {
            $target['_self']  = gettext('This window');
            $target['_blank'] = gettext('New window');

            $dropdown = null;

            foreach ($availableArticles AS $a) {
                $dropdown[$a['id']] = $a['title_' . $_SESSION['lang']];
            }

            $this->form->addInput('dropdownList', 'articleId', $dropdown, null, gettext('Article title'));

            if (_MULTILANG) {
                foreach ($this->activeLang AS $l) {
                    $this->form->addTextField('text_' . $l['isoCode'], null, null, gettext('Title'));
                }
            } else {
                $this->form->addTextField('text_' . $_SESSION['lang'], null, null, gettext('Title'));
            }

            $this->form->addInput('dropdownList', 'target', $target, null, gettext('Open in'));

            return $this->form->generateForm('addArticle');
        }

        return buildingBlocks::noRecords();

    }

    /**
     * @return bool|string
     */
    private function menuLinkForm()
    {
        $target['_self']  = gettext('This window');
        $target['_blank'] = gettext('New window');
        $this->form->addInput('dropdownList', 'target', $target, null, gettext('Open in'));

        if (_MULTILANG) {
            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('text_' . $l['isoCode'], null, null, gettext('Title'));
                $this->form->addTextField('title_' . $l['isoCode'], null, null, gettext('Tooltip'));
            }
        } else {
            $this->form->addTextField('text_' . $_SESSION['lang'], null, null, gettext('Title'));
            $this->form->addTextField('title_' . $_SESSION['lang'], null, null, gettext('Tooltip'));
        }

        $this->form->addTextField('href', null, 'http://www.valami.hu', gettext('URL'));

        return $this->form->generateForm('menuLink');
    }

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_addArticleToMenu($topicId = null)
    {
        if (isset($_POST['submit-addArticle'])) {

            $r['msg'] = $this->saveNewMenuArticle($topicId);

        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-bookmark"></span> ' . gettext('Add article');
        $control[]        = array('link' => "/throne/topics/throne_listTopicMenuItems/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->addArticleForm($topicId);

        return $r;
    }

    /**
     * @param null $topicId
     *
     * @return null
     */
    private function saveNewMenuArticle($topicId = null)
    {

        $data          = $this->form->validator();
        $data['cacId'] = $topicId;

        unset($data['editForm']);

        if (_MULTILANG) {
            foreach ($this->activeLang AS $l) {
                $data['text_' . $l['isoCode']] = (is_bool($data['text_' . $l['isoCode']]) ? 'NULL' : $data['text_' . $l['isoCode']]);
            }
        } else {
            $data['text_' . $_SESSION['lang']] = (is_bool($data['text_' . $_SESSION['lang']]) ? 'NULL' : $data['text_' . $_SESSION['lang']]);
        }

        if ($this->model->insert($data, 'content_articles_category_menu', false)) {
            return buildingBlocks::successMSG(gettext('Article added to the menu'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_addLinkToMenu($topicId = null)
    {
        if (isset($_POST['submit-menuLink'])) {

            $r['msg'] = $this->saveMenuLink($topicId);

        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-flag"></span> ' . gettext('Add link');
        $control[]        = array('link' => "/throne/topics/throne_listTopicMenuItems/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->menuLinkForm();
        $r['info']    = null;

        return $r;
    }

    /**
     * @param null $topicId
     *
     * @return string
     */
    private function saveMenuLink($topicId = null)
    {
        $data          = $this->form->validator();
        $data['cacId'] = $topicId;

        if ($this->model->insert($data, 'content_articles_category_menu', false)) {
            return buildingBlocks::successMSG(gettext('Link added to the menu'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }


    /* ------------------------------------------------------------------------------------------------------------------ */
    /* ------------------------------------------- TEXT Article --------------------------------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @param null $topicId
     *
     * @return mixed
     */
    function throne_newTextArticle($topicId = null)
    {

        if (isset($_POST['submit-saveTextArticle'])) {
            $r['msg'] = $this->saveNewTextArticle($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-file"></span> ' . gettext('New text article');
        $control[]        = array('link' => "/throne/topics/throne_listArticles/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->textArticleForm();

        return $r;
    }

    /**
     * Save a new text article
     *
     * @param null $topicId
     *
     * @return string
     */
    private function saveNewTextArticle($topicId = null)
    {

        $data            = $this->form->validator();
        $data['topicId'] = $topicId;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {
            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);
        }

        if ($this->model->insert($data, 'content_articles')) {
            return buildingBlocks::successMSG(gettext('Text article successfully saved!'));
        } else {
            return buildingBlocks::formSaveFail();
        }
    }

    /**
     * @return bool|string
     */
    private function textArticleForm()
    {

        $r = null;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('title_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article title'));
                $this->form->addTextField('metaKey_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article meta keys'));
                $this->form->addCKEditor('excerpt_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article excerpt'));
                $this->form->addCKEditor('content_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article'));
            }

        } else {

            $this->form->addTextField('title_' . $_SESSION['lang'], null, null, gettext('Article title'));
            $this->form->addTextField('metaKey_' . $_SESSION['lang'], null, null, gettext('Article meta keys'));
            $this->form->addCKEditor('excerpt_' . $_SESSION['lang'], null, null, gettext('Article excerpt'));
            $this->form->addCKEditor('content_' . $_SESSION['lang'], null, null, gettext('Article'));

        }

        $r = $this->form->generateForm('saveTextArticle');

        return $r;
    }

    /**
     * @param null $topicId
     * @param null $articleId
     *
     * @return mixed
     */
    function throne_editTextArticle($topicId = null, $articleId = null)
    {

        if (isset($_POST['submit-saveTextArticle'])) {
            $r['msg'] = $this->updateTextArticle($articleId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Edit text article');

        $data = $this->model->getArticleById($articleId);

        $data = buildingBlocks::decodeForEdit($this->activeLang, $data, 'excerpt');
        $data = buildingBlocks::decodeForEdit($this->activeLang, $data, 'content');

        $_SESSION['postBack'] = $data;

        $control[] = array('link' => "/throne/topics/throne_listArticles/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);

        $r['content'] = $this->textArticleForm();

        return $r;
    }

    /**
     * @param null $articleId
     *
     * @return string
     */
    private function updateTextArticle($articleId = null)
    {
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

        $user                   = $this->model->getCurrentThroneUserData();
        $dataArchive['addedBy'] = $user['uid'];

        $dataArchive['articleId'] = $articleId;

        $this->model->insert($dataArchive, 'content_articles_history', false);

        /*
         * Update article
         */

        if ($this->model->updater($data, 'content_articles', "id='$articleId'")) {
            return buildingBlocks::successMSG(gettext('Text article update successful!'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /* ------------------------------------------------------------------------------------------------------------------ */
    /* ------------------------------------------ Video Article --------------------------------------------------------- */
    /* ------------------------------------------------------------------------------------------------------------------ */

    /**
     * @param null $topicId
     */
    function throne_newVideoArticle($topicId = null)
    {
        if (isset($_POST['submit-saveVideoArticle'])) {
            $r['msg'] = $this->saveNewVideoArticle($topicId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-file"></span> ' . gettext('New text article');

        $control[] = array('link' => "/throne/topics/throne_listArticles/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));

        $r['control'] = buildingBlocks::sideMenu($control);
        $r['content'] = $this->videoArticleForm();

        return $r;
    }

    /**
     * @param $topicId
     *
     * @return string
     */
    private function saveNewVideoArticle($topicId)
    {

        $data = $this->form->validator();

        $data['topicId'] = $topicId;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {
            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);
        }

        $url = $data['youtube'];
        parse_str(parse_url($url, PHP_URL_QUERY), $getVars);

        $data['youtubeLink']   = $getVars['v'];
        $data['indavideoLink'] = str_replace('//', '', strtok(coreFunctions::getIframeSrc(coreFunctions::decoder($data['indavideo'])), '?'));

        if ($this->model->insert($data, 'content_articles')) {
            return buildingBlocks::successMSG('Video article successfully saved!');
        } else {
            return buildingBlocks::formSaveFail();
        }

    }

    /**
     * @return bool|string
     */
    private function videoArticleForm()
    {
        $r = null;

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $this->form->addTextField('title_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article title'));
                $this->form->addTextField('metaKey_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article meta keys'));
                $this->form->addCKEditor('excerpt_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article excerpt'));
                $this->form->addCKEditor('content_' . $l['isoCode'], null, null, '<img src="/img/flags/flag-' . $l['isoCode'] . '.png" class="inputFlag">&nbsp;' . gettext('Article'));
            }

        } else {

            $this->form->addTextField('title_' . $_SESSION['lang'], null, null, gettext('Article title'));
            $this->form->addTextField('metaKey_' . $_SESSION['lang'], null, null, gettext('Article meta keys'));
            $this->form->addCKEditor('excerpt_' . $_SESSION['lang'], null, null, gettext('Article excerpt'));
            $this->form->addCKEditor('content_' . $_SESSION['lang'], null, null, gettext('Article'));

        }

        $this->form->addTextField('youtube', null, gettext('http://www.youtube.com/watch?v=ZKLnhuzh9uY'), 'Youtube');
        $this->form->addTextArea('indavideo', null, gettext('Embed code'), 'Indavideo');

        $r = $this->form->generateForm('saveVideoArticle');

        return $r;
    }

    /**
     * @param null $topicId
     * @param null $articleId
     *
     * @return mixed
     */
    function throne_editVideoArticle($topicId = null, $articleId = null)
    {

        if (isset($_POST['submit-saveVideoArticle'])) {
            $r['msg'] = $this->updateVideoArticle($articleId);
        }

        $r['moduleTitle'] = '<span class="glyphicon glyphicon-edit"></span> ' . gettext('Edit video article');
        $control[]        = array('link' => "/throne/topics/throne_listArticles/$topicId.html", "icon" => 'arrow-left', "text" => gettext('Back'));
        $r['control']     = buildingBlocks::sideMenu($control);

        $_SESSION['postBack'] = $this->model->getArticleById($articleId);

        $_SESSION['postBack'] = buildingBlocks::decodeForEdit($this->activeLang, $_SESSION['postBack'], 'excerpt');
        $_SESSION['postBack'] = buildingBlocks::decodeForEdit($this->activeLang, $_SESSION['postBack'], 'content');

        $r['content'] = $this->videoArticleForm();

        $r['info'] = null;

        if (isset($_SESSION['postBack']['youtubeLink']) AND $_SESSION['postBack']['youtubeLink'] != '') {
            $r['info'] .= "<img src='http://i1.ytimg.com/vi/{$_SESSION['postBack']['youtubeLink']}/maxresdefault.jpg' class='img-responsive'>";
            $r['info'] .= "<hr>";
            $r['info'] .= "<div class='embed-container'><iframe src='http://www.youtube.com/embed/{$_SESSION['postBack']['youtubeLink']}?rel=0' frameborder='0' allowfullscreen></iframe></div>";
        }

        return $r;
    }

    /**
     * @param null $articleId
     *
     * @return string
     */
    private function updateVideoArticle($articleId = null)
    {
        $data = $this->form->validator();

        unset($data['editForm']);

        if (_MULTILANG) {

            foreach ($this->activeLang AS $l) {
                $data['slug_' . $l['isoCode']] = coreFunctions::slugger($data['title_' . $l['isoCode']]);
            }

        } else {

            $data['slug_' . $_SESSION['lang']] = coreFunctions::slugger($data['title_' . $_SESSION['lang']]);

        }

        $url = $data['youtube'];
        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);

        $data['youtubeLink']   = $my_array_of_vars['v'];
        $data['indavideoLink'] = str_replace('//', '', strtok(coreFunctions::getIframeSrc(coreFunctions::decoder($data['indavideo'])), '?'));


        /*
         * Create backup
         */
        $dataArchive = $data;

        $user                   = $this->model->getCurrentThroneUserData();
        $dataArchive['addedBy'] = $user['uid'];

        $dataArchive['articleId'] = $articleId;

        $this->model->insert($dataArchive, 'content_articles_history', false);

        /*
         * Update article
         */

        if ($this->model->updater($data, 'content_articles', "id='$articleId'")) {
            return buildingBlocks::successMSG(gettext('Text article update successful!'));
        } else {
            return buildingBlocks::formSaveFail();
        }

    }
}