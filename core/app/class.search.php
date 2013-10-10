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
        $this->form  = new formHandler();
        $this->model = new searchModel();
    }

    /**
     * This is the search form responsible for finding artists
     * @return string
     */
    private function artistSearchForm()
    {

        $r = null;

        $this->form->setFormRatio('4:8');
        $this->form->setFormMode('get');

        $this->form->addTextField('artistName', null, null, gettext('Artist name'));
        $this->form->addInput('dropdownList', 'profession', $this->model->getProfessions(), null, gettext('Profession'));
        $this->form->addInput('dropdownList', 'period', $this->model->getPeriod(), null, gettext('Period'));

        $r .= $this->form->generateForm('artistSearch', gettext('Search'));

        return $r;
    }

    /**
     * @return string
     */
    private function artSearchForm()
    {
        $r = null;
        $this->form->setFormRatio('4:8');
        $this->form->setFormMode('get');

        $this->form->addTextField('artTitle', null, null, gettext('Art title'));

        $r .= $this->form->generateForm('artSearch', gettext('Search'));

        return $r;
    }

    /**
     * This method provides the detailed search form
     * @return mixed
     */
    function detailedSearch()
    {

        $r['moduleTitle'] = gettext('Deailed Search');

        if (isset($_GET['submit-artistSearch']) OR isset($_GET['submit-artSearch'])) {
            $r['resultHeader'] = gettext('Results');
            $r['queryTitle']   = gettext('Query');
            $r['queryParam'] = null;

            if (isset($_GET['submit-artistSearch'])) {

                $r['result']   = $this->artistSearch();
                $r['bioTitle'] = gettext('Biography');

                $searchParam = $this->getSearchParameters();
                $professions = $this->model->getProfessions();
                $periods = $this->model->getPeriod();

                $keys['artistName'] = gettext('Artist name');
                $keys['profession'] = gettext('Profession');
                $keys['period'] = gettext('Period');

                $searchParam['profession'] = $professions[$searchParam['profession']];
                $searchParam['period'] = $periods[$searchParam['period']];

                $r['queryParam'] = buildingBlocks::generateInfo($keys,$searchParam);

            } elseif (isset($_GET['submit-artSearch'])) {

            } else {

            }



        } else {
            $r['artistSearchTitle'] = gettext('Artist search');
            $r['artSearchTitle']    = gettext('Art search');
            $r['artistSearchForm']  = $this->artistSearchForm();
            $r['artSearchForm']     = $this->artSearchForm();
        }


        return $r;
    }

    /**
     * @return array|null
     */
    private function getSearchParameters()
    {
        $data = $_GET;
        unset($data['lang'], $data['class'], $data['method']);

        return $this->form->validator($data);
    }

    /**
     * TODO: Search parameter highlight: http://hu1.php.net/fnmatch
     * @return array|null
     */
    private function artistSearch()
    {

        $search = $this->getSearchParameters();

        $data = $this->model->artistSearch($search);

        $newData = null;

        foreach ($data as $row) {
            $t = $row;

            $t['name']    = artist::artistName($row);
            $t['life']    = artist::artistDateControl($row);
            $t['link']    = '/' . $_SESSION['lang'] . '/artist/viewArtist/' . $t['slug'] . '.html';
            $t['excerpt'] = strip_tags(coreFunctions::decoder($t['excerpt_' . $_SESSION['lang']]));


            if ($t['bioImg'] != '') {
                $t['bioImgTitle'] = gettext('A portrait of %s');
                $t['bioImgTitle'] = str_replace('%s', $t['name'], $t['bioImgTitle']);
            }

            $newData[] = $t;

        }

        return $newData;

    }
}