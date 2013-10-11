<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.12.
 * Time: 9:31
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $menu = new menu();

    switch (coreFunctions::cleanVar($_GET['menu'])) {
        case 'redirect':
            echo $menu->menuForm(coreFunctions::cleanVar($_GET['lang']));
            break;
        case 'article':
            echo $menu->articleForm(coreFunctions::cleanVar($_GET['lang']));
            break;
        default:
            die;
            break;
    }



} else {
    echo 0;
}