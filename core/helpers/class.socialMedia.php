<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/5/13
 * Time: 6:34 AM
 * To change this template use File | Settings | File Templates.
 */

class socialMedia {
    public static function websiteTag($title = _OG_DEFAULT_TITLE, $desc = null, $img=false) {

        $url = _OG_DEFAULT_URL;
        $locale = $_SESSION['locale'];
        $appId = _FACEBOOK_APP_ID;

        $imgTag = "";

        if (!is_string($img)) {
            if (_OG_DEFAULT_IMG != '') {
                $imgTag = "<meta property='og:image' content='$img' />";
            }
        }

        $r = "
        <meta property='og:title' content='$title' />
        <meta property='og:type' content='website' />
        <meta property='og:url' content='$url' />
        $imgTag
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

    public static function googleMobileRecommend() {

        $publisher = _GOOGLE_PUBLISHER_ID;

        return "
<script type='text/javascript'>
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js?publisherid=$publisher';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
        ";
    }
}