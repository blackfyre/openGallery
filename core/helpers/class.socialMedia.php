<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/5/13
 * Time: 6:34 AM
 * To change this template use File | Settings | File Templates.
 */

class socialMedia {

    /**
     * @param string $title
     * @param null $desc
     * @param bool $img
     * @return string
     */
    public static function websiteTag($title = _OG_DEFAULT_TITLE, $desc = null, $img=false) {

        $url = _OG_DEFAULT_URL;
        $locale = $_SESSION['locale'];
        $appId = _FACEBOOK_APP_ID;

        $imgTag = "";

        if (!is_string($img)) {
            if (_OG_DEFAULT_IMG != '') {
                $img = _OG_DEFAULT_IMG;
                $imgTag = "<meta property='og:image' content='$img' />";
            }
        }

        if (!is_string($desc)) {
            if (_DEFAULT_METADESC != '') {
                $desc = _DEFAULT_METADESC;
                $descTag = "<meta property='og:description' content='$desc' />";
            }
        }

        $r = "
        <meta property='og:title' content='$title' />
        <meta property='og:type' content='website' />
        <meta property='og:url' content='$url' />
        $imgTag
        $descTag
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

    /**
     * @return string
     */
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

    public static function twitterGalleryCard($creator = _TWITTER_DEF_CREATOR, $title='Random Gallery', $desc='Works of an Artist', $images = null, $artistSlug = null) {

        $site = _TWITTER_SITE;

        $r = "
    <meta name='twitter:card' content='gallery'>
    <meta name='twitter:site' content='$site'>
    <meta name='twitter:creator' content='$creator'>
    <meta name='twitter:title' content='$title'>
    <meta name='twitter:description' content='$desc'>
    ";

        if (is_array($images)) {

            for ($i = 0; $i <= 3; $i++) {

                if (isset ($images[$i])) {
                    $t = $images[$i];

                    $link = 'http://' . $_SERVER['HTTP_HOST'] . '/images/small-thumbnail/' . $artistSlug . '-' . $t['titleSlug_' . $_SESSION['lang']] . '.' . coreFunctions::getExtension($t['img']);

                    $r .= "<meta name='twitter:image$i' content='$link'>";

                }

            }
        }

        return $r;

    }
}