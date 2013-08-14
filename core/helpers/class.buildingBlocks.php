<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.31.
 * Time: 18:59
 */

class buildingBlocks {

    /**
     * @param null $keys
     * @param null $infoData
     * @param null $blockName
     * @return null|string
     */
    static function generateInfo($keys = null, $infoData = null, $blockName = null) {

        if (is_array($keys) AND is_array($infoData)) {

            $infoBox = null;

            if (is_string($blockName)) {
                $infoBox .= '<h3>';
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
}