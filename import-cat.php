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

function processDate($date) {

    if ($date == '-') {
        return array('date'=>null,'exact'=>1);
    } else {
        $date = explode(' ', $date);

        if (in_array('körül',$date) OR in_array('évek',$date)) {

            $tempDate = keepNumbersOnly($date[0]);

            $length = strlen($tempDate);

            if ($length != 4) {
                $tempDate = StrDelete($tempDate,2,2);
            }


            return array('date'=>$tempDate,'exact'=>0);
        } else {
            return array('date'=>$date[0],'exact'=>1);
        }
    }



}

function processSize($sizeData) {

    $data = explode(' ', $sizeData);

    var_dump($data);

    return array('x'=>$data[0],'y'=>$data[2]);
}

$row = 1;
if (($handle = fopen("src/catalog_hu.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";","'")) !== FALSE) {

    $date = processDate($data[3]);
        $size = processSize($data[4]);

var_dump($date);
        $tempArray = array(
           'nameSlug'=>slugger($data[0]),
            'title'=>$data[2],
            'dateOfProduction'=>$date['date'],
            'exactYear'=>$date['exact'],
            'materialSlug'=>slugger($data[4]),
            'sizeX'=>$size['x'],
            'sizeY'=>$size['y'],
            'placeOfDisplaySlug'=>slugger($data[6])
       );

        $r[] = $tempArray;
    }
    fclose($handle);
}

//var_dump($r);