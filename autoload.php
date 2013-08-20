<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.05.10.
 * Time: 9:51
 */

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/debug/Kint.class.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/smarty/Smarty.class.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/phpExcel/Classes/PHPExcel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/appConfig.php';


spl_autoload_register(function ($className) {

    $className = basename($className);

    $dirs[] = 'models';
    $dirs[] = 'helpers';
    $dirs[] = 'app';

    $files[] = 'class';
    $files[] = 'model';


    $paths = null;

    foreach ($dirs as $dir) {
        foreach ($files AS $file) {
            $paths[] = $_SERVER['DOCUMENT_ROOT'] . '/core/' . $dir . '/' . $file . '.' . $className . '.php';
        }
    }

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
        }
    }

});

include_once $_SERVER['DOCUMENT_ROOT'] . '/core/control.overseer.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/core/control.throne.php';