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
class buildingBlocks
{

    /**
     * This functions is much like the simpleTableGenerator
     *
     * @param array $keys these are the a 'heads'
     * @param array $infoData these are the data from which to pick from
     * @param null|string $blockName optional, this will be the name (slug(#id)) of the block
     * @return null|string
     */
    static function generateInfo($keys = null, $infoData = null, $blockName = null)
    {

        if (is_array($keys) AND is_array($infoData)) {

            $infoBox = null;

            if (is_string($blockName)) {

                $slugName = coreFunctions::slugger($blockName);

                $infoBox .= "<h3 id='$slugName' class='infoBoxHead'>";
                $infoBox .= $blockName;
                $infoBox .= '</h3>';
            }

            $infoBox .= '<dl>';

            foreach ($keys AS $key => $name) {
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
     * @param null $videoId
     *
     * @return array|null
     */
    static function getIndavideoDetails($videoId = null) {

        $headers = get_headers("http://indavideo.hu/oembed/$videoId&format=json");

        if ($headers[0] == 'HTTP/1.1 200 OK') {
            $indaData = file_get_contents("http://indavideo.hu/oembed/$videoId&format=json");
            return json_decode($indaData,true);
        }

        return null;
    }

    /**
     * A standard success message
     * @param string $string
     * @return string
     */
    static function successMSG($string = null)
    {

        $string = coreFunctions::cleanVar($string);

        return "
        <div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>" . gettext('SUCCESS!') . "</strong> $string
        </div>
        ";
    }

    /**
     * @return string
     */
    static function formSaveFail()
    {

        $string = gettext('An error occurred, check the error log for more details!');

        return self::errorMSG($string);

    }

    /**
     * @param null $string
     *
     * @return string
     */
    static function errorMSG($string = null)
    {

        $string = coreFunctions::cleanVar($string);

        return "
        <div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>" . gettext('ERROR!') . "</strong> $string
        </div>";
    }

    /**
     * @return string
     */
    static function noRecords()
    {
        return self::infoMSG(gettext('No records in the database.'));
    }

    /**
     * @param null $string
     * @param bool $dismiss
     * @return string
     */
    static function infoMSG($string = null, $dismiss = true)
    {

        $string = coreFunctions::cleanVar($string);

        $dismiss = ($dismiss ? 'alert-dismissable' : '');

        return "
        <div class='alert alert-info $dismiss'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>" . gettext('INFORMATION!') . "</strong> $string
        </div>";
    }

    /**
     * @param array $activeLangData
     * @param array $rowData
     * @param string $title
     * @return string
     */
    static public function langTableDropDown($activeLangData = null, $rowData = null, $title = null)
    {

        if (_MULTILANG) {
            $r = '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/img/flags/flag-' . $_SESSION['lang'] . '.png" class="smallFlag">&nbsp;' . $rowData[$title . '_' . $_SESSION['lang']] . '</a><ul class="dropdown-menu" role="menu">';

            foreach ($activeLangData AS $lang) {
                $r .= '<li>&nbsp;<img class="smallFlag" src="/img/flags/flag-' . $lang['isoCode'] . '.png">&nbsp;' . $rowData[$title . '_' . $lang['isoCode']] . '</li>';
            }

            $r .= '</ul></div>';
        } else {
            $r = $rowData[$title . '_' . $_SESSION['lang']];
        }

        return $r;
    }

    /**
     * @param null $activeLangData
     * @param null $rowData
     * @param null $title
     * @return null
     */
    static public function decodeForEdit($activeLangData = null, $rowData = null, $title = null)
    {

        if (_MULTILANG) {

            foreach ($activeLangData AS $lang) {
                $rowData[$title . '_' . $lang['isoCode']] = coreFunctions::decoder($rowData[$title . '_' . $lang['isoCode']]);
            }

        } else {
            $rowData[$title . '_' . $_SESSION['lang']] = coreFunctions::decoder($rowData[$title . '_' . $_SESSION['lang']]);
        }

        return $rowData;
    }

    /**
     * Table generator
     *
     * @param array       $heads        Table columns title and footer row $heads['columnName'] = 'Column name'
     * @param array       $content      The content array
     * @param null|array  $extraClasses extra CSS classes, array(class1, class2, ...)
     * @param bool        $footer       Show footer
     * @param null        $tableName    An extra class as the name of the table, useful for jQuery ant other JS calls
     * @param bool|string $sortable
     * @param null        $sortId
     *
     * @return null|string String null if there's an error
     */
    public static function createSimpleTable($heads, $content, $extraClasses = null, $footer = true, $tableName = null, $sortable = false,$sortId = null)
    {
        if (is_array($heads) AND is_array($content)) {

            $colsInTable = array_keys($heads);

            $table = null;

            $classes[] = 'table';
            $classes[] = 'table-hover';
            $classes[] = coreFunctions::slugger($tableName);

            if (is_string($sortable)) {
                $classes[] = 'sortableTable';
            }

            if (is_array($extraClasses)) {
                $classes = array_merge($classes, $extraClasses);
            }

            $table .= '<table id="' . coreFunctions::slugger($tableName) . '" class="' . implode(' ', $classes) . '" ' . (!is_null($sortId)?'data-id="' . $sortId . '"':'') . '>';

            $table .= '<thead>';
            $table .= '<tr>';
            foreach ($heads AS $head) {
                $table .= '<th>';
                $table .= $head;
                $table .= '</th>';
            }
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';

            foreach ($content AS $row) {
                $table .= '<tr';

                if (is_string($sortable)) {
                    $table .= ' id="sort_' .  $row[$sortable] .'"';
                }

                if (isset($row['rowClass'])) {
                    $table .= ' class="' . $row['rowClass'] . '"';
                }

                $table .= '>';

                foreach ($colsInTable AS $colName) {

                    if ($colName=='edit') {
                        $table .= '<td style="white-space: nowrap">';
                    } else {
                        $table .= '<td>';
                    }

                    $table .= (isset($row[$colName]) ? $row[$colName] : '');
                    $table .= '</td>';
                }

                $table .= '</tr>';
            }

            $table .= '</tbody>';

            if ($footer) {
                $table .= '<tfoot>';
                $table .= '<tr>';
                foreach ($heads AS $head) {
                    $table .= '<th>';
                    $table .= $head;
                    $table .= '</th>';
                }
                $table .= '</tr>';
                $table .= '</tfoot>';
            }


            $table .= '</table>';

            return $table;

        }

        return null;
    }

    /**
     * Admin sidemenu
     *
     * @param null $array
     * @return null|string
     */
    public static function sideMenu($array = null) {
        if (is_array($array)) {

            $r = "<ul class='nav' role='nav'>";

            foreach ($array AS $a) {

                if (is_array($a)) {
                    $icon = null;

                    if (isset($a['icon'])) {
                        $icon = "<span class='glyphicon glyphicon-{$a['icon']}'></span>  ";
                    }

                    $r .="<li><a href='{$a['link']}'>$icon {$a['text']}</a></li>";
                }

                if (is_string($a)) {
                    $r .= '<li>' . $a . '</li>';
                }


            }

            $r .= "</ul>";


            return $r;

        }

        return null;
    }

    /**
     * @param null   $img
     * @param string $link
     * @param null   $title
     * @param null   $excerpt
     * @param string $linkTarget
     *
     * @return string
     */
    public static function mediaBox($img = null, $link = '#', $title = null, $excerpt = null, $linkTarget='_self') {
        $r = '
        <div class="media">
          <a class="pull-left media-link" href="' . $link . '" target="' . $linkTarget . '">
            <img class="media-object" src="' . $img . '" alt="' . $title . '">
          </a>
          <div class="media-body">';

        if (!is_null($title)) {
            $r .='<h4 class="media-heading">' . $title . '</h4>';
        }

            $r .= '<p>' . $excerpt . '</p>
          </div>
        </div>
        ';

        return $r;
    }

    /**
     * Bootstrap slider generation
     * @param null|array $data
     * @param string $carouselId
     * @return null
     */
    public static function createBasicSlideShow($data = null, $carouselId = 'generic carousel') {
        if (is_array($data)) {

            $carouselId = coreFunctions::slugger($carouselId);

            $counter = 0;

            $indicators = null;
            $slides = null;

            foreach ($data AS $slide) {
                $indicators .= '<li data-target="#' . $carouselId . '" data-slide-to="' . $counter . '" ' . ($counter==0?'class="active"':'') . '></li>';

                $slides .= '<div class="item' . ($counter==0?' active':'') . '">';
                $slides .= '<img class="img-responsive" src="' . $slide['imgPath'] . '"' . (isset($slide['imgAlt'])?' alt="' . $slide['imgAlt'] . '"':'') . '>';

                if (isset($slide['caption'])) {
                    $slides .= '<div class="carousel-caption">';
                    $slides .= $slide['caption'];
                    $slides .= '</div>';
                }

                $slides .= '</div>';

                $counter++;
            }

            /*
             * Carousel begin
             */
            $r = '<div id="' . $carouselId . '" class="carousel slide">';

            /*
             * Indicators begin
             */
            $r .= '<ol class="carousel-indicators">';

            $r .= $indicators;

            /*
             * Indicators end
             */
            $r .= '</ol>';

            /*
             * Content wrapper begin
             */
            $r .= '<div class="carousel-inner">';

            $r .= $slides;

            /*
             * Content wrapper end
             */
            $r .= '</div>';

            $r .= '
<a class="left carousel-control" href="#' . $carouselId . '" data-slide="prev">
    <span class="icon-prev"></span>
</a>
<a class="right carousel-control" href="#' . $carouselId . '" data-slide="next">
    <span class="icon-next"></span>
</a>
            ';

            /*
             * Carousel end
             */
            $r .= '</div>';

            return $r;
        }

        return null;
    }

}