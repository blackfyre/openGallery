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
    <link rel="stylesheet" href="/_bs3_dist/css/bootstrap-admin.css">


    <!--
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    -->
    <link rel="stylesheet" href="/css/main-throne.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/_bs3_assets/js/html5shiv.js"></script>
    <script src="/_bs3_assets/js/respond.min.js"></script>
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
            {if isset($adminMenu)}
                {$adminMenu}
            {/if}
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-home"></span> User <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="glyphicon glyphicon-user"></span> myProfile</a></li>
                        <li><a href="/throne/logout.html"><span class="glyphicon glyphicon-warning-sign"></span> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.navbar-collapse -->
    </div>
</div>

<div class="container">

    {if isset($msg)}
        {$msg}
    {/if}

    <div class="jMsg"></div>