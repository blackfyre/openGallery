<?php
/**
 * User: Galicz Miklós
 * Date: 9/5/13
 * Time: 6:34 AM
 */

/**
 * Class socialMedia
 *
 * This class creates an array of extensive social media tags based on a few details...
 * The functions return strings that contain the generated meta code, which you can insert anywhere you may want
 *
 * The openGraph elements are based on naillkennedy's examples found at https://github.com/niallkennedy/open-graph-protocol-examples
 *
 */
class socialMedia {

    /**
     *
     * Open Graph website tag
     *
     * Based on http://ogp.me/#type_website
     *
     * @param string $title
     * @param null $desc
     * @param bool $img
     * @return string
     */
    public static function OGWebsiteTag($title = _OG_DEFAULT_TITLE, $desc = null, $img=false) {

        $url = _OG_DEFAULT_URL;
        $locale = $_SESSION['locale'];

        $imgTag = "";
        $descTag = '';

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
        ";

        if (is_string($desc)) {
            $r .= "
            <meta property='og:description' content='$desc' />
            ";
        }

        return $r;
    }

    /**
     * @param null $title
     * @param null $siteName
     * @param null $locale
     * @param null $imgUrl
     * @param null $firstName
     * @param null $lastName
     * @param null $gender
     * @param null $username
     * @param null $ogUrl
     *
     * @return string
     */
    public static function OGProfileTag($title = null, $siteName=null,$locale=null,$imgUrl=null,$firstName=null,$lastName=null,$gender=null,$username=null,$ogUrl = null) {

        if (is_null($ogUrl)) {
            $ogUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }


        $r = "
        <meta property='og:title' content='$title'>
        <meta property='og:site_name' content='$siteName'>
        <meta property='og:type' content='profile'>
        <meta property='og:locale' content='$locale'>
        <link rel='canonical' href='http://examples.opengraphprotocol.us/profile.html'>
        <meta property='og:url' content='$ogUrl'>
        <meta property='og:image' content='$imgUrl'>
        <meta property='profile:first_name' content='$firstName'>
        <meta property='profile:last_name' content='$lastName'>
        <meta property='profile:gender' content='$gender'>
        <meta property='profile:username' content='$username'>
        ";

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

    /**
     * @param null|string $creator
     * @param string $title
     * @param string $desc
     * @param null $images
     * @param null $artistSlug
     *
     * @return string
     */
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

                    $link = 'http://' . $_SERVER['HTTP_HOST'] . '/images/large-thumbnail/' . $t['id'] . '/' . $artistSlug . '-' . $t['titleSlug_' . $_SESSION['lang']] . '.' . coreFunctions::getExtension($t['img']);

                    $r .= "<meta name='twitter:image$i' content='$link'>";

                }

            }
        }

        return $r;

    }

    /**
     *
     * Create a Twitter basic Summary card from the given content
     *
     * @param null|string $title The title of the summary
     * @param null|string $desc The description of the summary, will be clipped at 200 characters
     * @param null|string $creator The twitter id the article creator, if null the global default will be used
     * @param null|string $img full image url
     *
     * @return string
     */
    public static function twitterSummaryCard($title = null, $desc = null, $img = null, $creator = _TWITTER_DEF_CREATOR) {

        $site = _TWITTER_SITE;
        $desc = coreFunctions::trimmer($desc,200);

        $r = "
        <meta name='twitter:card' content='summary'>
        <meta name='twitter:site' content='$site'>
        <meta name='twitter:title' content='$title'>
        <meta name='twitter:description' content='$desc'>
        <meta name='twitter:creator' content='$creator'>
        ";

        if (!is_null($img)) {
            $r .= "<meta name='twitter:image:src' content='$img'>";
        }

        return $r;
    }

    /**
     *
     * Same as the Twitter basic Summary card, but with a bigger image
     *
     * @param null|string $title The title of the summary
     * @param null|string $desc The description of the summary, will be clipped at 200 characters
     * @param null|string $creator The twitter id the article creator, if null the global default will be used
     * @param null|string $img full image url
     *
     * @return string
     */
    public static function twitterLargeImageSummaryCard($title = null, $desc = null, $img = null, $creator = _TWITTER_DEF_CREATOR) {

        $site = _TWITTER_SITE;
        $desc = coreFunctions::trimmer($desc,200);

        $r = "

        <meta name='twitter:card' content='summary_large_image'>
        <meta name='twitter:site' content='$site'>
        <meta name='twitter:title' content='$title'>
        <meta name='twitter:description' content='$desc'>
        <meta name='twitter:creator' content='$creator'>
        ";

        if (!is_null($img)) {
            $r .= "<meta name='twitter:image:src' content='$img'>";
        }

        return $r;
    }

    /**
     * @param null|string $title The title of the summary
     * @param null|string $creator The twitter id the article creator, if null the global default will be used
     * @param null|string $img full image url
     *
     * @return string
     */
    public static function twitterPhotoCard($title = null, $creator = _TWITTER_DEF_CREATOR, $img = null) {

        $site = _TWITTER_SITE;

        $r = "

        <meta name='twitter:card' content='photo'>
        <meta name='twitter:site' content='$site'>
        <meta name='twitter:title' content='$title'>
        <meta name='twitter:creator' content='$creator'>
        ";

        if (!is_null($img)) {
            $r .= "<meta name='twitter:image:src' content='$img'>";
        }

        return $r;
    }

    /**
     * @param null|string $title The title of the summary
     * @param null|string $desc The description of the summary, will be clipped at 200 characters
     * @param null|string $creator The twitter id the article creator, if null the global default will be used
     * @param null|string $img full image url
     * @param null|string $price for example: $5 USD
     * @param null|string $location like San Francisco
     *
     * @return string
     */
    public static function twitterProductCard($title = null, $desc = null, $creator = _TWITTER_DEF_CREATOR, $img = null, $price = null,$location = null) {

        $site = _TWITTER_SITE;
        $desc = coreFunctions::trimmer($desc,200);

        $r = "
        <meta name='twitter:card' content='product'>
        <meta name='twitter:site' content='$site'>
        <meta name='twitter:title' content='$title'>
        <meta name='twitter:creator' content='$creator'>
        <meta name='twitter:description' content='$desc'>
        ";

        if (!is_null($img)) {
            $r .= "<meta name='twitter:image:src' content='$img'>";
        }

        if (!is_null($price)) {
            $r .= "
        <meta name='twitter:data1' content='$price'>
        <meta name='twitter:label1' content='PRICE'>
        ";
        }

        if (!is_null($location)) {
            $r .= "
            <meta name='twitter:data2' content='$location'>
            <meta name='twitter:label2' content='LOCATION'>
            ";
        }

        return $r;
    }

    /**
     * @param null|string $title The title of the summary
     * @param null|string $desc The description of the summary, will be clipped at 200 characters
     * @param null|string $creator The twitter id the article creator, if null the global default will be used
     * @param null|string $youtubeHash The hash ID of the youtube video "o0ZQmDDSFzQ"
     *
     * @return string
     */
    public static function twitterYoutubeVideoCard($title = null, $desc = null, $creator = _TWITTER_DEF_CREATOR, $youtubeHash = null) {
        $site = _TWITTER_SITE;
        $desc = coreFunctions::trimmer($desc,200);

        $r = "
        <meta name='twitter:card' content='product'>
        <meta name='twitter:site' content='$site'>
        <meta name='twitter:title' content='$title'>
        <meta name='twitter:creator' content='$creator'>
        <meta name='twitter:description' content='$desc'>
        ";

            $r .= "<meta name='twitter:image:src' content='http://i1.ytimg.com/vi/$youtubeHash/hqdefault.jpg'>";

        $r .= "
        <meta name='twitter:player' content='https://www.youtube.com/embed/$youtubeHash'>
        ";

        return $r;
    }
}