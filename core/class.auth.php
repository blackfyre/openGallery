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

    function __construct()
    {
        parent::__construct();
        $this->userMenu();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    private function formForLogin()
    {

        // check the $_post for a previously submitted form
        if (isset($_POST['commitFormForLogin'])) {
            $user = $_POST['userName'];
            $pass = $_POST['userPass'];
        } else {
            $user = '';
            $pass = '';
        }


        // language variables
        $this->smarty->assign('legend', gettext('Login'));
        $this->smarty->assign('userPlace', gettext('Username'));
        $this->smarty->assign('passPlace', gettext('Password'));
        $this->smarty->assign('submitValue', gettext('Login!'));

        // pass along default data
        $this->smarty->assign('user', $user);
        $this->smarty->assign('pass', $pass);

        //show login form
        $this->smarty->display('form_login.tpl');

    }

    private function commitFormForLogin()
    {
        $data = $this->cleanFormFields();

        $user = $data['userName'];
        $rawPass = $data['userPass'];

        $halfSpicedPass = $this->spicerHalf($rawPass);

        if ($this->checkLogin($user, $halfSpicedPass)) {

            $_SESSION['userName'] = $user;
            $_SESSION['userPass'] = $halfSpicedPass;

            return true;

        } else {
            $_SESSION['userName'] = null;
            $_SESSION['userPass'] = null;

            return false;
        }

    }

    function doLogin()
    {
        if (isset($_POST['commit-FormForLogin'])) {
            if ($this->commitFormForLogin()) {
                return true;
            } else {
                $this->errorMSG(gettext('Login Failed!'));
                $this->formForLogin();
            }
        } else {
            $this->formForLogin();
        }
    }

    public function checkSessionUser()
    {
        if (isset($_SESSION['userName']) AND isset($_SESSION['userPass'])) {
            return $this->checkLogin($_SESSION['userName'], $_SESSION['userPass']);
        } else {
            return false;
        }

    }

    private function checkLogin($user = null, $passHalfSpiced = null)
    {
        if (!is_null($user) AND !is_null($passHalfSpiced)) {

            $user = $this->cleanVar($user);
            $pass = $this->cleanVar($passHalfSpiced);

            $pass = $this->spicerFinish($pass);

            $query = "SELECT id FROM users WHERE userName='$user' AND userPass='$pass' AND active='1'";

            if ($result = $this->db->query($query)) {

                if ($result->rowCount() == 1) {
                    return true;
                } else {
                    return false;
                }

            } else {
                $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            return false;
        }
    }

    private function spicer($pass = null)
    {
        if (is_null($pass)) {
            parent::errorMSG(gettext('Missing variable'));
            return false;
        } else {
            $pass = $this->cleanVar($pass);

            $spiced = sha1(md5($this->spice . $pass . $this->spice));

            return $spiced;

        }
    }

    private function spicerHalf($pass = null)
    {
        if (is_null($pass)) {
            parent::errorMSG(gettext('Missing variable'));
            return false;
        } else {
            $pass = $this->cleanVar($pass);

            $spiced = md5($this->spice . $pass . $this->spice);

            return $spiced;
        }
    }

    private function spicerFinish($passHalfSpiced = null)
    {
        if (is_null($passHalfSpiced)) {
            parent::errorMSG(gettext('Missing variable'));
            return false;
        } else {
            $pass = $this->cleanVar($passHalfSpiced);

            $spiced = sha1($pass);

            return $spiced;
        }
    }

    function userMenu()
    {

        $title = 'title_' . $this->siteLang;
        $alt = 'alt_' . $this->siteLang;

        if ($this->checkSessionUser()) {

            $logged = 1;

        } else {

            $logged = 0;
        }

        $query = "SELECT $title as title, target, link, $alt as alt FROM users_menu WHERE loggedin='$logged' ORDER BY `order`";

        if ($result = $this->db->query($query)) {

            $menu = null;

            while ($row = $result->fetch()) {
                $menu[] = $row;
            }

            //var_dump($menu);

            $this->smarty->assign('userMenu', $menu);

            return true;

        } else {

            $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
            return false;

        }
    }
}
