<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.31.
 * Time: 18:59
 */

/**
 * Class buildingBlocks
 */
class buildingBlocks {

    /**
     * This functions is much like the simpleTableGenerator
     *
     * @param array $keys these are the a 'heads'
     * @param array $infoData these are the data from which to pick from
     * @param null|string $blockName optional, this will be the name (slug(#id)) of the block
     * @return null|string
     */
    static function generateInfo($keys = null, $infoData = null, $blockName = null) {

        if (is_array($keys) AND is_array($infoData)) {

            $infoBox = null;

            if (is_string($blockName)) {

                $slugName = coreFunctions::slugger($blockName);

                $infoBox .= "<h3 id='$slugName'>";
                $infoBox .= $blockName;
                $infoBox .= '</h3>';
            }

            $infoBox .= '<dl>';

            foreach ($keys AS $key=>$name) {
                $infoBox .= '<dt>';
                $infoBox .= $name;
                $infoBox .= '</dt>';
                $infoBox .= '<dd>';
                $infoBox .= $infoData[$key];
                $infoBox .= '</dd>';
            }

            $infoBox .= '</dl>';

            return $infoBox;
        }

        return null;

    }

    /**
     * A standard success message
     * @param string $string
     * @return string
     */
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