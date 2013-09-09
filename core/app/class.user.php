<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 9/8/13
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

class user {

    private $model = null;

    private $form = null;

    function __construct() {
        $this->model = new userModel();
        $this->form = new formHandler();
    }

    function registration() {

    }

    function login() {

    }
}