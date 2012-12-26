<?php
    session_start();
	include_once 'core/class.main.php';
    
    $main = new main;
    $auth = new auth();
    $static = new staticContent();
    $art = new art();



    if (isset($_GET['mode'])) {
        switch ($_GET['mode']) {
            case 'static':
                $static->displayStaticPage($_GET['var1']);
                break;//end of static
            case 'login':
                if ($auth->doLogin()) {
                    //if successful login goto page
                    $static->displayStaticPage('home');
                }
                break;//end of login
            case 'artist-index':
                $art->displayArtistList();
                break; //end of artist-index
            case 'artist':
                $art->displayArtist($_GET['var1']);
                break; //end of artist
            default:
                $static->displayStaticPage('home');
                break; //end of default
        }
    } else {
        $static->displayStaticPage('home');
    }

