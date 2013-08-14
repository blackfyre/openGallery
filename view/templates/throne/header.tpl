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
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    <link rel="stylesheet" href="/css/jquery-ui-1.10.3.custom.css">
    <link rel="stylesheet" href="/css/main-throne.css">

    <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">{$chromeFrameContent}</p>
<![endif]-->

<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            {* A mobilos részen a 3 csík... *}
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="/throne/">blackCMS 0.1a Admin</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Játék kezelő <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/throne/gamer/rounds.html">Kvíz kezelő</a></li>
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
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">