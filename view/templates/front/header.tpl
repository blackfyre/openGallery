<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    {if $metaTitle eq 'openGallery'}
        <title>openGallery</title>
        {else}
        <title>{$metaTitle} | openGallery</title>
    {/if}

    <meta name="description" content="{$metaDesc}">
    <meta name="keywords" content="{$metaTags}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {* Open Graph *}

    {if isset($openGraph)}
        {$openGraph}
    {/if}

    {if isset($twitterCard)}
        {$twitterCard}
    {/if}


    <link rel="stylesheet" href="/_bs3_dist/css/bootstrap.css">
    <link rel="stylesheet" href="/_bs3_dist/css/bootstrap-theme.css">

    <!--
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    -->
    <link rel="stylesheet" href="/css/main-front.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/_bs3_assets/js/html5shiv.js"></script>
    <script src="/_bs3_assets/js/respond.min.js"></script>
    <![endif]-->

    {if isset($googlePlusOne)}
        {$googlePlusOne}
    {/if}

    {literal}
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-23717600-5', 'opengalleryproject.eu');
            ga('send', 'pageview');

        </script>
    {/literal}

</head>
<body>



<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->


    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">openGallery</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/">Home</a></li>
                    <li><a href="/en/artist/index.html">Artist Index</a></li>
                    <li><a href="/en/artist/viewArtist/aba-novak-vilmos.html">Example Artist</a></li>
                    <li><a href="/en/search/detailedSearch.html">Search</a></li>
                    {*
                    <li><a href="#about">ABC Index</a></li>
                    <li><a href="#contact">Search</a></li>
                    <li><a href="#contact">Guided tours</a></li>
                    <li><a href="#contact">Glossary</a></li>
                    <li><a href="#contact">Postcard</a></li>
                    <li><a href="#contact">Guestbook</a></li>
                    <li><a href="#contact">Information</a></li>
                    <li><a href="#contact">Contact</a></li>
*}
                </ul>

                <ul class="nav navbar-nav pull-right">
                {if isset($langSwitch)}

                        <li>
                            <div class="dropdown">
                                <a data-toggle="dropdown" href="#"><img class="curLangFlag" src="/img/flags/flag-{$smarty.session.lang}.png" alt="current language"></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                    {foreach from=$langSwitch item=i}
                                        <li><a rel="alternate" href="{$i.url}" hreflang="{$i.flag}"><img class="navFlag" src="/img/flags/flag-{$i.flag}.png" alt="content available in {$i.flag}">&nbsp;{$i.full}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        </li>

                {/if}
                    <li><a href="#" hreflang="{$smarty.session.lang}"><span class="glyphicon glyphicon-log-in"></span> Login</a> </li>
                </ul>

                {*
                <ul class="nav navbar-nav pull-right">
                    <li><a href="#">Login</a></li>
                </ul>
*}
            </div><!--/.nav-collapse -->
        </div>
    </div>

