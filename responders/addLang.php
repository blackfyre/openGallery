<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.25.
 * Time: 10:40
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $opt = new options();

    if ($opt->addLangAction()) {
        echo 'ok';
    } else {
        echo 'fail';
    }

} else {
    echo 0;
}