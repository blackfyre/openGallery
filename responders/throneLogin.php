<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 14:47
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if (isset($_POST) AND count($_POST)>0) {

    if ($auth->throneLoginFormAction()) {
        echo 'OK';
    } else {
        echo 'NONO';
    }

} else {
    echo false;
}