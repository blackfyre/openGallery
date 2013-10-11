<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.05.02.
 * Time: 16:46
 */

class Overseer extends controlHandler
{

    /**
     * Az első dominó, innen indul minden
     */
    public function invoke()
    {

        $menu = new menu();

        $this->smarty->addToDisplay(array('frontMenu'=>$menu->generateMainNav()));

        if (_MULTILANG) {
            if (isset($_GET['lang'])) {
                $this->methodLoader();
            } else {
                $this->displayHome();
            }
        } else {
            if (count($_GET)>1) {
                $this->methodLoader();
            } else {
                $this->displayHome();
            }
        }



    }



    /**
     * Nyitólap megjelenítés
     *
     */
    private function displayHome()
    {
        $news = new news();

        $data['newsTitle'] = gettext('News');

        $data['news'] = $news->getLatest();

        $this->smarty->addToDisplay($data);
        $this->smarty->displaySelectedPage('front/home.tpl');
    }



}
