<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.26.
 * Time: 19:51
 */

ini_set("default_charset", "utf-8");

function slugger($str, $replace = array(), $delimiter = '-')
{
    if (!empty($replace)) {
        $str = str_replace((array )$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;
}

$row = 1;
if (($handle = fopen("../src/bio_hu.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";", "'")) !== FALSE) {

        $fullName = str_replace(',', '', $data[1]);

        $bio = str_replace("'", '&#39;', $data[3]);

        $tempArray = array(
            'slug' => slugger($fullName),
            'bioHu' => $bio
        );

        $r[] = $tempArray;
    }
    fclose($handle);
}

$db = new mysqli('localhost', 'root', '', 'opengallery');

$db->query("SET CHARACTER SET 'utf8'");
$db->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
$db->query("SET character_set_results = 'utf8'");
$db->query("SET character_set_server = 'utf8'");
$db->query("SET character_set_client = 'utf8'");

$query = null;

foreach ($r as $key => $row) {

    $slug = $row['slug'];
    $bio = $row['bioHu'];

    $query = "UPDATE artist SET bioHu='$bio' WHERE slug='$slug'";

    if ($db->query($query)) {
        echo $key . '. -> OK!<br />';
    } else {
        echo $db->error . '<br />';
    }

}

$db->close();

var_dump($r);