<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.05.03.
 * Time: 10:16
 */

class modelAuth extends modelsHandler
{


    /**
     * @param null $user
     * @param null $pass
     * @return bool
     */
    function checkUserData($user = null, $pass = null)
    {
        $query = "SELECT uid FROM users WHERE userName='$user' AND pass='$pass' AND `active`='1'";

        return $this->checkIfRecordExists($query);
    }
}