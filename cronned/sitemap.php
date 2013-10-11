<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 10/11/13
 * Time: 6:15 AM
 * To change this template use File | Settings | File Templates.
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/sitemap-php/sitemap-php.php';

$sitemap = new \SitemapPHP\Sitemap('http://' . $_SERVER['HTTP_HOST']);
$sitemap->setPath($_SERVER['DOCUMENT_ROOT'] . '/');

$sitemap->addItem('/', '1.0', 'daily', 'Today');

$artistModel = new modelArtist();

$activeLangs = $artistModel->getActiveLanguages();

$artists = $artistModel->getArtists();

if (is_array($artists)) {
    foreach ($artists AS $a) {
        $link = '/' . _DEFAULT_LANG . '/artist/viewArtist/' . $a['slug'] . '.html';

        $alternates = null;

        foreach ($activeLangs AS $l) {
            if ($l['isoCode']!=_DEFAULT_LANG) {
                $alternates[$l['isoCode']] = '/' . $l['isoCode'] . '/artist/viewArtist/' . $a['slug'] . '.html';
            }
        }

        $sitemap->addItem($link, '1.0', 'monthly', 'Today', $alternates);

        $link = '/' . _DEFAULT_LANG . '/artist/artBy/' . $a['slug'] . '.html';

        $alternates = null;

        foreach ($activeLangs AS $l) {
            if ($l['isoCode']!=_DEFAULT_LANG) {
                $alternates[$l['isoCode']] = '/' . $l['isoCode'] . '/artist/artBy/' . $a['slug'] . '.html';
            }
        }

        $sitemap->addItem($link, '1.0', 'monthly', 'Today', $alternates);
    }
}


$sitemap->createSitemapIndex('http://' . $_SERVER['HTTP_HOST'].'/');