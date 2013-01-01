<?php
session_start();

spl_autoload_register(function ($className) {
    include_once 'core/class.' . $className . '.php';
});

include_once 'libs/Smarty.class.php';

//$main = new main;
$auth = new auth();
$static = new staticContent();
$art = new art();

//For $_GET['mode'] reference, check the .htaccess file

if (isset($_GET['mode'])) {
    switch ($_GET['mode']) {
        case 'static':
            $static->displayStaticPage($_GET['var1']);
            break;
        //end of static
        case 'login':
            if ($auth->doLogin()) {
                //if successful login goto homepage
                $static->displayStaticPage('home');
            }
            break;
        //end of login
        case 'artist-index':
            $art->displayArtistList();
            break; //end of artist-index
        case 'artist':
            $art->displayArtist($_GET['var1']);
            break; //end of artist
        case 'throne':
            $throne = new throne();
            if (isset($_GET['var1'])) {
                switch ($_GET['var1']) {

                    case 'artists':

                        if (isset($_GET['var2'])) {

                            switch ($_GET['var2']) {
                                case 'toggle-active':
                                    $throne->toggleArtistStatus($_GET['var3']);
                                    break;
                            }

                        } else {
                            $throne->artistList();
                        }

                        break;

                    default:
                        $throne->viewPage();
                        break;
                }
            } else {
                $throne->viewPage();
            }

            break; //end of throne
        default:
            $static->displayStaticPage('home');
            break; //end of default
    }
} else {
    $static->displayStaticPage('home');
}

