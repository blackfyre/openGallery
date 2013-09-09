<?php
/**
 * Bejelentkezést kezelő osztály
 *
 * @package WebJob
 * @author Miklós Galicz
 * @copyright Copyright (c) 2012 Galicz Miklós
 * @version 2012
 * @access public
 */
class authManager
{
    /**
     * @var null|PDO
     */
    private $db = null;

    /**
     * @var formHandler|null
     */
    private $form = null;


    /**
     * @var errorHandler|null
     */
    private $error = null;

    /**
     * @var modelAuth|null
     */
    private $model = null;

    function __construct()
    {

        /*
         * DEPENDENCIES
         */

        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->error = new errorHandler(true);
        $this->form = new formHandler();
        $this->model = new modelAuth();




    }


    /**
     * Checks the given credentials to the db, and determines if the user is somehow invalid (not regged, inactive, ...)
     *
     * @param string $user
     * @param string $pass
     * @param bool $encode Szükséges -e kódolni a jelszót?
     * @return bool
     */
    function checkLogin($user = null, $pass = null, $encode = true)
    {

        $user = coreFunctions::cleanVar($user);
        if ($encode) {
            $pass = passHandler::encryptPass(coreFunctions::cleanVar($pass));
        } else {
            $pass = coreFunctions::cleanVar($pass);
        }


        return $this->model->checkUserData($user, $pass);

    }

    /**
     * Logout
     *
     * @return bool
     */
    function destroyEverything()
    {
        if (isset($_SESSION['user'])) {
            $_SESSION['user'] = null;
        }

        if (isset($_SESSION['pass'])) {
            $_SESSION['pass'] = null;
        }

        setcookie('user', '', time() - 3600);
        setcookie('pass', '', time() - 3600);
        session_unset(); //session kiürítése

        return true;
    }

    function throneLoginForm() {

        /*
        $this->form->addInput('textField','user',null,null,'felhasználó');
        $this->form->addInput('password','pass',null,null,'jelszó');

        $r['content'] = '<h2>Bejelentkezés</h2>';

        $r['content'] .= $this->form->generateForm('login','bejelentkezés',null,'/responders/throneLogin.php','bootstrap-horizontal');
        */

        $r['jFunctions'] = '
    function showResponse(responseText, statusText, xhr, $form)  {

        if (responseText=="OK") {
            location.reload();
        } else {
        alert("' . gettext('Invalid login data!') . '");
        }

    }
        ';

        $r['jQuery'] = '

        var options = {
        success: showResponse
        };

        $("#form-login").ajaxForm(options);

        ';

       return $r;

    }

    function throneLoginFormAction() {
        $data = $this->form->validator();

        if (is_array($data)) {
            $result = $this->loginAction($data['user'],$data['pass']);

            if ($result) {
                return true;
            } else {
                $this->error->errorMSG('Érvénytelen bejelentkezési adatok');
                return false;
            }

        } else {
            $this->error->errorMSG('hibás űrlap');
            return false;
        }
    }


    /**
     * Aktív bejelentkezés keresése
     *
     * @return bool
     */
    function checkForActiveLogin()
    {
        if (isset($_SESSION['user']) AND isset($_SESSION['pass'])) {

            $user = coreFunctions::cleanVar($_SESSION['user']);
            $pass = coreFunctions::cleanVar($_SESSION['pass']);

            return $this->checkLogin($user, $pass, false);

        } else {
            $this->destroyEverything();
            return false;
        }
    }

    /**
     * Login
     *
     * @param string $user
     * @param string $pass
     * @return bool
     */
    function loginAction($user = null, $pass = null)
    {

        if (is_null($user) AND is_null($pass)) {
            $data = $this->form->validator();
        } else {
            $data['user'] = $user;
            $data['pass'] = $pass;
        }

        $user = coreFunctions::cleanVar($data['user']);
        $pass = coreFunctions::cleanVar($data['pass']);

        if ($this->checkLogin($user, $pass)) {

            $passEnc = passHandler::encryptPass($pass);

            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $passEnc;
            setcookie('user', $user);
            setcookie('pass', $passEnc);

            return true;

        } else {
            return false;
        }
    }

}

