<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.25.
 * Time: 10:42
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {
    $opt = new options();

    $table = $opt->lang();
    echo $table['table'];

} else {
    echo 0;
}