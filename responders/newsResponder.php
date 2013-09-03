<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/3/13
 * Time: 6:57 AM
 * To change this template use File | Settings | File Templates.
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

$auth = new authManager();

if ($auth->checkForActiveLogin()) {

    $news = new news();

    if (isset($_GET['action'])) {

        switch ($_GET['action']) {
            default:
                echo buildingBlocks::errorMSG(gettext("Undefined action"));
                break;
            case 'publish':
                if ($news->modifyPublishState(coreFunctions::cleanVar($_GET['articleId']),1)) {
                    echo buildingBlocks::successMSG('Published!');
                } else {
                    echo buildingBlocks::formSaveFail();
                }
            break;
            case 'unpublish':
                if ($news->modifyPublishState(coreFunctions::cleanVar($_GET['articleId']),0)) {
                    echo buildingBlocks::successMSG('Publishment revoked');
                } else {
                    echo buildingBlocks::formSaveFail();
                }
                break;
            case 'getNewTable':
                $data = $news->throne_listNews();
                echo $data['content'];
                break;

        }

    } else {
        echo buildingBlocks::errorMSG(gettext("Missing parameters"));
    }

} else {
    echo buildingBlocks::errorMSG(gettext("You're not authorised to do so!"));
}