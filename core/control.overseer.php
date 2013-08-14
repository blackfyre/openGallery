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

            $reRouter['kviz'] = 'quiz';
            $reRouter['hirek'] = 'news';
            $reRouter['tartalom'] = 'content';

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
        $gamerFront = new gamerFront();
        $content = new content();
        $menu = new menu();

        $homeContent = $content->homeContent();

        $data['activeGame'] = $gamerFront->isThereAnActiveGame();
        $data['menu'] = $menu->generateMainNav();

        $data = array_merge($data,$homeContent);

        $gameData = $gamerFront->getActiveGameData();

        if (is_array($gameData)) {
            $data['quizId'] = $gameData['quizId'];
        }

        $this->smarty->addToDisplay($data);
        $this->smarty->displaySelectedPage('front/home.tpl');
    }



}
