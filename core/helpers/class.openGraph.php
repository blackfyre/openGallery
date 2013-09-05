<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/5/13
 * Time: 6:34 AM
 * To change this template use File | Settings | File Templates.
 */

class openGraph {
    public static function websiteTag($title = _OG_DEFAULT_TITLE, $desc = null, $img=_OG_DEFAULT_IMG) {

        $url = _OG_DEFAULT_URL;
        $locale = $_SESSION['locale'];
        $appId = _FACEBOOK_APP_ID;

        $r = "
        <meta property='og:title' content='$title' />
        <meta property='og:type' content='website' />
        <meta property='og:url' content='$url' />
        <meta property='og:image' content='$img' />
        <meta property='og:site_name' content='openGallery project' />
        <meta property='og:locale' content='$locale' />
        <meta property='og:app_id' content='$appId' />
        ";

        if (is_string($desc)) {
            $r .= "
            <meta property='og:description' content='$desc' />
            ";
        }

        return $r;
    }
}