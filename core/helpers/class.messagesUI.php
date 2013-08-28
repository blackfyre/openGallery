<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/28/13
 * Time: 6:49 AM
 * To change this template use File | Settings | File Templates.
 */

class messagesUI {
    static function successMSG($string = null) {

        $string = coreFunctions::cleanVar($string);

        return "
        <div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>SUCCESS!</strong> $string
        </div>
        ";
    }

    static function errorMSG($string = null) {

        $string = coreFunctions::cleanVar($string);

        return "
        <div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>ERROR!</strong> $string
        </div>";
    }
}