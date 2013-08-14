<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.25.
 * Time: 13:09
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $langId = coreFunctions::cleanVar($_GET['lid']);
    $action = coreFunctions::cleanVar($_GET['action']);

    switch ($action) {
        default:
            echo 0;
            die;
            break;
        case 'activate':
            $toState = 1;
            break;
        case 'deactivate':
            $toState = 0;
            break;
    }

    $opt = new options();

    echo $opt->setLang($langId,$toState);

} else {
    echo 0;
}