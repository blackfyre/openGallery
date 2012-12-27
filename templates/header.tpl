<!DOCTYPE html>
<html lang="{$siteLang}">
<head>
    <meta charset="utf-8"/>
    <title>{$title}</title>
    <link rel="stylesheet" href="/style/style.css" type="text/css"/>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> <!--[if lte IE 7]>
    <script src="/js/IE8.js" type="text/javascript"></script><![endif]--> <!--[if lt IE 7]>
    <link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]--> </head>

    <!-- jQuery and jQuery UI -->

    <link href="/js/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" media="all">
    <script src="/js/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
    <script src="/js/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>


<body id="index" class="home">
<div class="contentWrapper">

    <header>
        <nav>
            <ul class="headNav">
            {foreach from=$mainMenu item=i}
                <a href="{$i.link}" title="{$i.alt}" target="{$i.target}" hreflang="{$siteLang}"><li>{$i.title}</li></a>
            {/foreach}
            </ul>
        </nav>
        <p class="quickSearch"><input type="text" placeholder="{$quickSearch}"></p>
    </header>

    <nav class="sideLeft contentBox">
        <ul class="sideNav">
        {foreach from=$sideMenu item=i}
            <a href="{$i.link}" title="{$i.alt}" target="{$i.target}" hreflang="{$siteLang}"><li>{$i.title}</li></a>
        {/foreach}
        </ul>

    </nav>
    <div class="mainContent contentBox">
