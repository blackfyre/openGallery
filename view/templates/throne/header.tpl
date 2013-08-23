<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{$metaTitle}</title>
    <meta name="description" content="{$metaDesc}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/_bootstrap3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/_bootstrap3/dist/css/bootstrap-theme.css">

    <!--
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    -->
    <link rel="stylesheet" href="/css/main-throne.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/_bootstrap3/assets/js/html5shiv.js"></script>
    <script src="/_bootstrap3/assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">{$chromeFrameContent}</p>
<![endif]-->

<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/throne/">blackCMS 0.1b</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Nyitólap</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tartalom <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/throne/content/articles.html">Cikkek</a></li>
                        <li><a href="/throne/content/fixedContent.html">Rögzített</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menü <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/throne/menu/main.html">Fő menü</a></li>
                        <li><a href="/throne/menu/footer.html">Lábléc</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Beállítások <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/throne/options/lang.html">Nyelvek</a></li>
                        <li><a href="/throne/options/users.html">Felhasználók</a></li>
                    </ul>
                </li>
                <li><a href="/throne/logout.html">Kijelentkezés</a></li>
            </ul>
        </div><!--/.navbar-collapse -->
    </div>
</div>



<div class="container">