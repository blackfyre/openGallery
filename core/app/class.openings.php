<?php
/**
 * Created by JetBrains PhpStorm.
 * User: overlord
 * Date: 8/23/13
 * Time: 6:08 AM
 * To change this template use File | Settings | File Templates.
 */

class openings {
    function openPositions($slug = null) {
        $r['positionTitle'] = null;
        $r['signUpButton'] = 'Sign Up NOW!';
        $r['description'] = null;
        $r['teaser'] = null;

        switch (coreFunctions::cleanVar($slug)) {
            case 'developer':

                $r['positionTitle'] = 'Developer';
                $r['description'] = '
                <p>We are looking for</p>
                ';
                $r['teaser'] = 'Are you interested in Art? Do you have a few years of experience with OOP php? Do you want to give something to the community?';

                break;
            case 'editor':
                break;
            case 'translator':
                break;
        }

        return $r;
    }
}