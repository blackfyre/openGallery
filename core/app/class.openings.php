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
<div class="row">
  <div class="col-md-6">
  <h2>Who we are looking for</h2>
    <p>We are looking for people with the following knowledge:</p>
        <ul>
            <li>php 5.3</li>
            <li>mySQL</li>
            <li>Apache2</li>
            <li>Twitter Bootstrap</li>
            <li>jQuery & jQuery UI</li>
            <li>jQuery & jQuery UI</li>
            <li>Any MVC php development framework</li>
        </ul>
  </div>
  <div class="col-md-6">
    <h2>What we can offer</h2>
    <ul>
        <li>The satisfaction in giving something to the community</li>
        <li>Experience</li>
    </ul>
  </div>
</div>

                ';
                $r['teaser'] = 'Are you interested in Art? Do you have a few years of experience with OOP php? Do you want to give something to the community?';

                break;
            case 'editor':

                $r['positionTitle'] = 'Editor';
                $r['description'] = '
<div class="row">
  <div class="col-md-6">
  <h2>Who we are looking for</h2>
    <p>We are looking for people with the following knowledge:</p>
        <ul>
            <li>Content editing with CKEditor</li>
        </ul>
  </div>
  <div class="col-md-6">
    <h2>What we can offer</h2>
    <ul>
        <li>The satisfaction in giving something to the community</li>
        <li>Experience</li>
    </ul>
  </div>
</div>

                ';
                $r['teaser'] = 'Are you interested in Art? Do you have a few years of experience with CKEditor? Do you want to give something to the community?';

                break;
            case 'translator':

                $r['positionTitle'] = 'Translator';
                $r['description'] = '
<div class="row">
  <div class="col-md-6">
  <h2>Who we are looking for</h2>
    <p>We are looking for people who can translate Hungarian and/or English into their native tongues.</p>
  </div>
  <div class="col-md-6">
    <h2>What we can offer</h2>
    <ul>
        <li>The satisfaction in giving something to the community</li>
        <li>Experience</li>
        <li>
            Credit for the tranlation<br/>
            <span class="muted"><em>If you provide your google+ profile url, google will show the results with your profile</em></span>
        </li>
    </ul>
  </div>
</div>

                ';
                $r['teaser'] = 'Are you interested in Art? Do you want to give something to the community?';

                break;
        }

        return $r;
    }
}