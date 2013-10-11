<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.24.
 * Time: 22:16
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

function keepNumbersOnly($string)
{
    $result = preg_replace("/[^0-9]/", "", $string);
    return $result;
}

function StrDelete($aString, $BeginPos, $Length)
{
    $r = '';
    $l = strlen($aString);
    $EndPos = $BeginPos + $Length;
    for ($i = 0; $i < $l; $i++)
        if (($i < $BeginPos) || ($i >= $EndPos))
            $r .= $aString[$i];
    return $r;
}

function processDate($date)
{

    $date = str_replace('(', '', $date);
    $date = str_replace(')', '', $date);

    $date = explode(' - ', $date);

    $birth = explode(', ', $date[0]);
    $death = explode(', ', $date[1]);


    if (count($birth) != 2) {
        var_dump($birth);
    }

    if (count($death) != 2) {
        var_dump($death);
    }

    $dateOfBirth = keepNumbersOnly($birth[0]);

    $dateOfBirth = ($dateOfBirth == '' ? '?' : $dateOfBirth);

    if (!is_bool(strpos($birth[0], 'c.'))) {
        $exactBirth = false;
    } else {
        $exactBirth = true;
    }

    $placeOfBirth = $birth[1];

    $dateOfDeath = keepNumbersOnly($death[0]);

    $dateOfDeath = ($dateOfDeath == '' ? '?' : $dateOfDeath);

    if (!is_bool(strpos($death[0], 'c.'))) {
        $exactDeath = false;
    } else {
        $exactDeath = true;
    }

    $placeOfDeath = $death[1];

    $r = array('dateOfBirth' => $dateOfBirth, 'exactBirth' => $exactBirth, 'dateOfDeath' => $dateOfDeath, 'exactDeath' => $exactDeath, 'placeOfBirth' => $placeOfBirth, 'placeOfDeath' => $placeOfDeath);


    return $r;

}


function processName($fullName)
{
    $name = explode(', ', $fullName);
    return $name;
}

$row = 1;
if (($handle = fopen("../src/bio_en.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";", "'")) !== FALSE) {

        $name = processName($data[1]);
        $fullName = str_replace(',', '', $data[1]);
        $date = processDate($data[2]);

        $bio = str_replace("'", '&#39;', $data[3]);

        $tempArray = array(
            'slug' => slugger($fullName),
            'firstName' => $name[1],
            'lastName' => $name[0],
            'dateOfBirth' => $date['dateOfBirth'],
            'exactBirth' => $date['exactBirth'],
            'placeOfBirth' => $date['placeOfBirth'],
            'dateOfDeath' => $date['dateOfDeath'],
            'exactDeath' => $date['exactDeath'],
            'placeOfDeath' => $date['placeOfDeath'],
            'bioEn' => $bio
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

foreach ($r as $row) {

    $slug = $row['slug'];

    $checkQuery = "SELECT * FROM artist WHERE slug='$slug'";

    if ($checkResult = $db->query($checkQuery)) {

        if ($checkResult->num_rows == 0) {
            foreach ($row as $key => $raw) {
                $cols[] = '`' . $key . '`';
                $vals[] = "'" . $raw . "'";
            }

            $cols = implode(', ', $cols);
            $vals = implode(', ', $vals);

            $query = "REPLACE INTO artist ($cols) VALUES ($vals);";

            if ($db->query($query)) {
                echo 'OK!<br />';
            } else {
                echo $db->error . '<br/>';
            }

            $cols = null;
            $vals = null;
        }

    } else {
        exit ($db->error);
    }
}

$db->close();

//var_dump($r);