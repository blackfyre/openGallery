<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 11:34
 */


/*
 * Oldal adatok
 */

define('_DEFAULT_TITLE','openGallery');
define('_DEFAULT_METADESC','nincs');
define('_DEFAULT_METATAGS','nincs');

define('_MULTILANG',true);

define('_BASE_PATH',$_SERVER['DOCUMENT_ROOT']);

mb_internal_encoding('UTF-8');

/*
 * Adatbázis cuccok
 */

$testEnvironments[] = '37.139.10.166';
$testEnvironments[] = 'wga.local';
$testEnvironments[] = 'opengallery.local';

define('_TEST_ENV', json_encode($testEnvironments));

/*
 * Nyelv
 */

define('_DEFAULT_LANG','en');

/*
 * DEBUG
 */

$dbUser = 'cmsUser';
$dbPass = 'openWGA';
$dbDb = 'cms';
$dbHost = 'localhost';

Kint::$enabled = false;

if (in_array($_SERVER['HTTP_HOST'],$testEnvironments)) {

    Kint::$enabled = true;
    $dbUser = 'cmsUser';
    $dbPass = 'openWGA';
    $dbDb = 'cms';
}

define('_DB_USER',$dbUser);
define('_DB_PASS',$dbPass);
define('_DB_HOST',$dbHost);
define('_DB_DB',$dbDb);

/*
 * PHPMailer
 */

define('_SEND_NOTIFICATION',TRUE);

define('_SMTP_HOST','smtp.hinora.hu');
define('_SMTP_PORT',25);
define('_SMTP_USER','speedbody_smtp');
define('_SMTP_PASS','w62Yj3@%Vp');
define('_EMAIL_FROM_ADDRESS','nyeremeny@scratchandwin.hu');
define('_EMAIL_FROM_NAME','');
define('_EMAIL_REPLY_ADDRESS','');
define('_EMAIL_REPLY_NAME','');

/*
 * Smarty
 */

define('_SMARTY_TPLDIR', _BASE_PATH . '/view/templates');
define('_SMARTY_COMPILEDIR', _BASE_PATH . '/view/templates_c');
define('_SMARTY_CONFIGDIR', _BASE_PATH . '/plugins/smarty/libs/config');
define('_SMARTY_CACHE', _BASE_PATH . '/cache/smarty');
