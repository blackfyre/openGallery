<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.27.
 * Time: 8:02
 */

ini_set("default_charset", "utf-8");

if (($handle = fopen("../src/cat_bilang.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";", '"')) !== FALSE) {

        $r[] = $data;
    }
    fclose($handle);
}

var_dump($r);