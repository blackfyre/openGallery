 <?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/20/13
 * Time: 7:59 PM
 * To change this template use File | Settings | File Templates.
 */

set_time_limit(0);

 include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

header('Content-Type:text/html; charset=utf-8');

 if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
     $locale[] = 'hun.UTF-8';
     $locale[] = 'hungarian.UTF-8';
     $locale[] = 'hun';
     $locale[] = 'hungarian';
 } else {
     $locale[] = 'hu_HU.UTF8';
     $locale[] = 'hu.UTF8';
     $locale[] = 'hu_HU@euro.UTF8';
     $locale[] = 'hun.UTF8';
     $locale[] = 'hungarian.UTF-8';
 }

 setlocale(LC_ALL, $locale);



 ini_set("default_charset", "UTF-8");
 date_default_timezone_set('Europe/Budapest');

 class importerModel extends modelsHandler {
     function getArtistId($slug) {
         $query = "SELECT id FROM artist WHERE slug='$slug'";

         $result = $this->fetchSingleRow($query);

         return $result['id'];
     }

     function getType($type) {
         $query = "SELECT typeId FROM art_type WHERE typeName_en='$type'";

         $result = $this->fetchSingleRow($query);

         return $result['typeId'];
     }
 }

 $model = new importerModel();




$dir = $_SERVER['DOCUMENT_ROOT'] . '/artImg/';

$path = realpath($dir);

$objects = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($path),
    RecursiveIteratorIterator::SELF_FIRST);

$directories = null;

 $data = null;

 function t($text) {
     return iconv('windows-1252','utf-8//TRANSLIT',$text);
 }

foreach($objects as $name => $object){

    if (strpos($name,'.jpg') !== false) {

        $t = null;
        $r = null;

        $row = null;
	
	$t = exif_read_data($name);
	$comment = t($t['COMMENT'][0]);
    $commentOrig = $comment;
    $comment = explode("\n",$comment);


        $commentCount = count($comment);

        $row['origComment'] = $commentOrig;


        $row['artist'] = $model->getArtistId(coreFunctions::slugger($comment[0]));
        $row['title_hu'] = $comment[3];
        $row['titleSlug_hu'] = coreFunctions::slugger($comment[3]);
        $row['yearOfProduction'] = $comment[4];

        if (!is_int(coreFunctions::slugger($row['yearOfProduction']))) {
            $row['exactYear'] = 0;
            $row['yearOfProduction'] = filter_var($row['yearOfProduction'],FILTER_SANITIZE_NUMBER_INT);
        }

        if (coreFunctions::slugger($comment[6]) == 'magantulajdon') {
            $row['placeOfDisplay'] = 4;
        }

        foreach ($comment AS $c) {

            if (strpos($c,'Type:') !== false) {
                $row['type'] = trim(str_replace('Type:','',$c));

                $toInsert = null;
                $toInsert['typeName_en'] = $row['type'];

                $model->fragger($toInsert,'art_type','insert',null,true);

                $row['type'] = $model->getType($row['type']);

            }

            if (strpos($c,'Form:') !== false) {
                $row['category'] = trim(str_replace('Form:','',$c));
            }
        }

        $row['description_hu'] = '';
        $row['description_en'] = '';

        $row['img'] = sha1($name) . '.jpg';

        switch ($commentCount) {
            case 21;

                $row['title_en'] = $comment[12];

                break;

            case 23:

                $row['description_hu'] = $comment[8];
                $row['title_en'] = $comment[14];

                break;

            case 25:

                $row['title_en'] = $comment[14];
                $row['description_hu'] = $comment[8];
                $row['description_en'] = $comment[19];

                break;

            case 27:

                $row['description_hu'] = $comment[8];
                $row['description_hu'] .= $comment[10];

                $row['title_en'] = $comment[16];
                $row['description_en'] = $comment[21];

                break;

            case 29:
                $row['description_hu'] = $comment[8];
                $row['description_hu'] .= $comment[10];

                $row['title_en'] = $comment[16];
                $row['description_en'] = $comment[21];
                $row['description_en'] .= $comment[23];
                break;

            default:
                break;
        }

        $row['titleSlug_en'] = coreFunctions::slugger((isset($row['title_en'])?$row['title_en']:'aaa'));
	
        $r[] = $name;
        $r[] = $comment;
        $r[] = $row;

        $newPath = $_SERVER['DOCUMENT_ROOT'] . '/img/art/' . $row['img'];

        rename($name,$newPath);

        $model->fragger($row,'art','insert',null,true);

        $directories[] = $r;
    }


}

 //var_dump($directories);


