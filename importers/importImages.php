 <?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/20/13
 * Time: 7:59 PM
 * To change this template use File | Settings | File Templates.
 */

set_time_limit(0);


header('Content-Type:text/html; charset=ISO-8859-2');

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$dir = $_SERVER['DOCUMENT_ROOT'] . '/artImg/kep/';

$path = realpath($dir);

 phpinfo();

$objects = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($path),
    RecursiveIteratorIterator::SELF_FIRST);

$directories = null;

foreach($objects as $name => $object){

    if (strpos($name,'.jpg') !== false) {

        $t = null;
        $r = null;
	
	$t = exif_read_data($name);
	$comment = $t['COMMENT'][0];
    $comment = explode("\n",$comment);

        $commentCount = count($comment);

        $artistName = $comment[0];
        $titleHu = $comment[3];
        $date = $comment[4];

        $descHu = '';
        $descEn = '';

        switch ($commentCount) {
            case 21;

                $titleEn = $comment[12];

                break;

            case 23:

                $descHu = $comment[8];
                $titleEn = $comment[14];

                break;

            case 25:

                $titleEn = $comment[14];

                break;

            case 27:

                $descHu = $comment[8];
                $descHu .= $comment[10];

                $descEn = $comment[21];

                break;

            case 29:
                $descHu = $comment[8];
                $descHu .= $comment[10];

                $descEn = $comment[21];
                $descEn .= $comment[23];
                break;

            default:
                break;
        }
	
	$r[] = $name;
	$r[] = $comment;


        $directories[] = $r;
    }


}

var_dump($directories);
