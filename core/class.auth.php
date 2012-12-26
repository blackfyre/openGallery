<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.22.
 * Time: 17:07
 */
class auth extends main
{

    function __construct() {
        parent::__construct();
        $this->setLang($this->siteLang);
    }

    function __destruct() {
        parent::__destruct();
    }

    private function formForLogin() {

        // check the $_post for a previously submitted form
        if (isset($_POST['commitFormForLogin'])) {
            $user = $_POST['userName'];
            $pass = $_POST['userPass'];
        } else {
            $user = '';
            $pass = '';
        }


        // language variables
        $this->smarty->assign('legend',gettext('_login_boxLegend'));
        $this->smarty->assign('userPlace',gettext('_login_userName'));
        $this->smarty->assign('passPlace',gettext('_login_userPass'));
        $this->smarty->assign('submitValue',gettext('_login_submitButton'));

        // pass along default data
        $this->smarty->assign('user',$user);
        $this->smarty->assign('pass',$pass);

        //show login form
        $this->smarty->display('form_login.tpl');

    }

    private function commitFormForLogin() {
        $data = $this->cleanFormFields();

        $user = $data['userName'];
        $rawPass = $data['userPass'];

        $halfSpicedPass = $this->spicerHalf($rawPass);

        if ($this->checkLogin($user,$halfSpicedPass)) {

            $_SESSION['userName'] = $user;
            $_SESSION['userPass'] = $halfSpicedPass;

            return true;

        } else {
            $_SESSION['userName'] = null;
            $_SESSION['userPass'] = null;

            return false;
        }

    }

    function doLogin() {

        if (isset($_POST['commit-FormForLogin'])) {
            if ($this->commitFormForLogin()) {
                return true;
            } else {
                $this->errorMSG(gettext('_login_failed'));
                $this->formForLogin();
            }
        } else {
            $this->formForLogin();
        }
    }

    public function checkSessionUser() {
        return $this->checkLogin($_SESSION['userName'],$_SESSION['userPass']);
    }

    private function checkLogin($user = null, $passHalfSpiced = null) {
        if (!is_null($user) AND !is_null($passHalfSpiced)) {

            $user = $this->clean_var($user);
            $pass = $this->clean_var($passHalfSpiced);

            $pass = $this->spicerFinish($pass);

            $query = "SELECT id FROM users WHERE userName='$user' AND userPass='$pass' AND active='1'";

            if ($result = $this->db->query($query)) {

                if ($result->num_rows == 1) {
                    return true;
                } else {
                    return false;
                }

            } else {
                $this->errorMSG(gettext('_db_queryFailed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            return false;
        }
    }

    private function spicer($pass = null) {
        if (is_null($pass)) {
            parent::errorMSG(gettext('_error_missingVar'));
            return false;
        } else {
            $pass = $this->clean_var($pass);

            $spiced = sha1(md5($this->spice . $pass . $this->spice));

            return $spiced;

        }
    }

    private function spicerHalf($pass = null) {
        if (is_null($pass)) {
            parent::errorMSG(gettext('_error_missingVar'));
            return false;
        } else {
            $pass = $this->clean_var($pass);

            $spiced = md5($this->spice . $pass . $this->spice);

            return $spiced;
        }
    }

    private function spicerFinish($passHalfSpiced = null) {
        if (is_null($passHalfSpiced)) {
            parent::errorMSG(gettext('_error_missingVar'));
            return false;
        } else {
            $pass = $this->clean_var($passHalfSpiced);

            $spiced = sha1($pass);

            return $spiced;
        }
    }
}
