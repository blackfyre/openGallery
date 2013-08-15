<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.12.
 * Time: 9:12
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $menu = new menu();
    $form = new formHandler();

    $edit = false;

    if (isset($_POST['editForm']) AND $_POST['editForm']=='1') {
        $edit = true;
        unset($_POST['editForm']);
    }


    $data = $form->validator($_POST);


    if ($edit) {

    } else {

    }

} else {
    echo 0;
}