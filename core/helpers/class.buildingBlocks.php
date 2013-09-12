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

                $infoBox .= "<h3 id='$slugName'>";
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

    static function formSaveFail()
    {

        $string = gettext('An error occurred, check the error log for more details!');

        return self::errorMSG($string);

    }

    static function errorMSG($string = null)
    {

        $string = coreFunctions::cleanVar($string);

        return "
        <div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <strong>" . gettext('ERROR!') . "</strong> $string
        </div>";
    }

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
     * @param array $heads Table columns title and footer row $heads['columnName'] = 'Column name'
     * @param array $content The content array
     * @param null|array $extraClasses extra CSS classes, array(class1, class2, ...)
     * @param bool $footer Show footer
     * @param null $tableName An extra class as the name of the table, useful for jQuery ant other JS calls
     * @return null|string String null if there's an error
     */
    public static function createSimpleTable($heads, $content, $extraClasses = null, $footer = true, $tableName = null)
    {
        if (is_array($heads) AND is_array($content)) {

            $colsInTable = array_keys($heads);

            $table = null;

            $classes[] = 'table';
            $classes[] = 'table-hover';
            $classes[] = coreFunctions::slugger($tableName);

            if (is_array($extraClasses)) {
                $classes = array_merge($classes, $extraClasses);
            }

            $table .= '<table class="' . implode(' ', $classes) . '">';

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

                if (isset($row['rowClass'])) {
                    $table .= ' class="' . $row['rowClass'] . '"';
                }

                $table .= '>';

                foreach ($colsInTable AS $colName) {
                    $table .= '<td>';
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
}