<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/25/13
 * Time: 5:53 AM
 * To change this template use File | Settings | File Templates.
 */

class search
{

    /**
     * @var null|formHandler
     */
    private $form = null;

    /**
     * @var null|searchModel
     */
    private $model = null;

    function __construct()
    {
        $this->form = new formHandler();
        $this->model = new searchModel();
    }

    /**
     * This is the search form responsible for finding artists
     * @return string
     */
    private function artistSearchForm() {

        $r = null;

        $this->form->setFormRatio('4:8');
        $this->form->setFormMode('get');

        $this->form->addTextField('artistName',null,null,gettext('Artist name'));
        $this->form->addInput('dropdownList','profession',$this->model->getProfessions(),null,gettext('Profession'));
        $this->form->addInput('dropdownList','period',$this->model->getPeriod(),null,gettext('Period'));

        $r .= $this->form->generateForm('artistSearch',gettext('Search'));

        return $r;
    }

    /**
     * @return string
     */
    private function artSearchForm() {
        $r = null;
        $this->form->setFormRatio('4:8');
        $this->form->setFormMode('get');

        $this->form->addTextField('artTitle',null,null,gettext('Art title'));

        $r .= $this->form->generateForm('artSearch',gettext('Search'));
        return $r;
    }

    /**
     * This method provides the detailed search form
     * @return mixed
     */
    function detailedSearch()
    {

        Kint::$enabled = true;
        Kint::dump($_GET);

        $r['moduleTitle']       = gettext('Deailed Search');

        if (isset($_GET['submit-artistSearch']) OR isset($_GET['submit-artSearch'])) {
            $r['resultHeader'] = gettext('Results');
            $r['queryTitle'] = gettext('Query');
        } else {
            $r['artistSearchTitle'] = gettext('Artist search');
            $r['artSearchTitle']    = gettext('Art search');
            $r['artistSearchForm'] = $this->artistSearchForm();
            $r['artSearchForm'] = $this->artSearchForm();
        }



        return $r;
    }
}