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

        if (isset($_GET['lang'])) {

            $this->methodLoader();

        } else {
            $this->displayHome();
        }

    }



    /**
     * Nyitólap megjelenítés
     *
     */
    private function displayHome()
    {
        $content = new content();
        $menu = new menu();
        $news = new news();

        //$homeContent = $content->homeContent();

        $data['newsTitle'] = gettext('News');

        $data['news'] = $news->getLatest();

        $data['menu'] = $menu->generateMainNav();

        $data['openGraph'] = openGraph::websiteTag();


        $this->smarty->addToDisplay($data);
        $this->smarty->displaySelectedPage('front/home.tpl');
    }



}
