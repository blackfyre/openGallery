<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.29.
 * Time: 20:57
 */
class throne extends art
{

    function __construct()
    {
        parent::__construct();
    }

    function  __destruct()
    {
        parent::__destruct();
    }

    function viewPage()
    {
        $this->smarty->display('throne/default.tpl');
    }

    function artistList()
    {

        //assign lang values
        $this->smarty->assign('artistName', gettext('Artist Name'));
        $this->smarty->assign('bornDied', gettext('Born-Died'));
        $this->smarty->assign('active', gettext('Active'));
        $this->smarty->assign('preview', gettext('Preview'));
        $this->smarty->assign('edit', gettext('Edit'));
        $this->smarty->assign('previewLinkTitle', gettext('Preview in nem window'));
        $this->smarty->assign('editLinkTitle', gettext('Edit Artist'));
        $this->smarty->assign('artistActive', gettext('The Artist is <b>ACTIVE</b> in the listings!'));
        $this->smarty->assign('artistInactive', gettext('The Artist is <b>INACTIVE</b> in the listings!'));

        $this->smarty->assign('artists', $this->getArtistList(true));

        $this->viewPage();

    }

    function toggleArtistStatus($artistSlug = null)
    {

        if ($artistSlug != null) {
            $artistSlug = $this->cleanVar($artistSlug);

            $checkQuery = "SELECT active FROM artist WHERE `slug`='$artistSlug'";

            if ($checkResult = $this->db->query($checkQuery)) {

                if ($checkResult->rowCount() == 1) {

                    $checkResult = $checkResult->fetch();
                    $checkResult = $checkResult['active'];

                    if ($checkResult == '1') {
                        $toggle = 0;
                    } else {
                        $toggle = 1;
                    }

                    $updateQuery = "UPDATE artist SET `active`='$toggle' WHERE `slug`='$artistSlug'";

                    if ($this->db->query($updateQuery)) {
                        $this->artistList();
                        return true;
                    } else {
                        $this->queryError();
                        return false;
                    }

                } else {
                    $this->errorMSG('toggleArtistStatus->' . gettext('Multiple results'));
                    return false;
                }

            } else {
                $this->queryError();
                return false;
            }

        } else {
            $this->errorMSG('toggleArtistStatus->' . gettext('Missing variable'));
            return false;
        }

    }


}
