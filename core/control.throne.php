<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 14:48
 */

class throne extends controlHandler {

    /**
     * @var null|authManager
     */
    private $auth = null;

    public function invoke() {

        $this->auth = new authManager();

        if ($this->auth->checkForActiveLogin()) {

            $menu = new menu();

            $this->smarty->addToDisplay(array('menu'=>$menu->adminMenu()));
            $this->smarty->addToDisplay(array('metaTitle'=>_SITE_NAME . ' | ' . gettext('Administration')));

            /*
             * If there's no url set, load the dashboard's default view
             */

            if (!isset($_GET['class']) AND !isset($_GET['method'])) {
                $_GET['class'] = 'dashboard';
                $_GET['method'] = 'defaultView';
            } elseif (isset($_GET['class']) AND $_GET['class']=='logout') {
                $this->logOutNow();
            }

            $this->methodLoader(null,'throne');

        } else {
            $this->displayLogin();
        }
    }

    private function displayLogin() {

        $this->smarty->addToDisplay($this->auth->throneLoginForm());

        $this->smarty->displaySelectedPage('throne/login.tpl');
    }
}