<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.14.
 * Time: 13:49
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $menu = new menu();

    return $menu->setMenu(coreFunctions::cleanVar($_GET['action']),coreFunctions::cleanVar($_GET['id']));

} else {
    echo 0;
}