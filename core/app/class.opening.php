<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/23/13
 * Time: 6:08 AM
 * To change this template use File | Settings | File Templates.
 */

class opening {
    function openPositions($slug = null) {
        $r['positionTitle'] = null;
        $r['signUpButton'] = null;
        $r['description'] = null;
        $r['teaser'] = null;

        switch (coreFunctions::cleanVar($slug)) {
            case 'developer':
                break;
            case 'editor':
                break;
            case 'translator':
                break;
        }

        return $r;
    }
}