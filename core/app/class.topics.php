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

        $r['moduleTitle'] = gettext('Topic Manager');
        $r['content'] = null;
        $r['control'] = null;

        $r['control'] .= '<a href="/throne/topics/addNewTopic.html" role="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> ' . gettext('New topic') . '</a>';

        $data = $this->model->getTopics();

        if (is_array($data) AND count($data)>0) {



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

        /*
        if ($this->model->insert($data,'content_articles_category')) {
            return buildingBlocks::successMSG(gettext('New topic saved!'));
        } else {
            return buildingBlocks::formSaveFail();
        }
        */

        return null;
    }

    /**
     * @return mixed
     */
    function addNewTopic() {

        if (isset($_POST['submit-topicForm'])) {
            $r['msg'] = $this->saveNewTopic();
        }

        $r['moduleTitle'] = gettext('Topic Manager');
        $r['control'] = null;
        $r['content'] = $this->topicForm();

        return $r;
    }
}