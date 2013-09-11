<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@blackworks.org
 * http://blackworks.org
 * Date: 2013.05.13.
 * Time: 18:33
 */

/**
 * Class tableHandler
 */
class tableHandler {
    /**
     * @var errorHandler|null
     */
    private $error = null;

    function __construct($debug = true) {
        $this->error = new errorHandler(true);
    }

    /**
     * Táblázat generátor :)
     *
     * Néhány tömb segítségével generálja az alap táblát...
     * Az 'edit' kulcs az különleges, mert azt jobbra igazítja
     *
     * @deprecated
     *
     * @param array $heads fejlécek $heads['oszlopNév'] = 'Emberi név'
     * @param array $content A tartalom... lekérdezési eredményekre kihegyezve...
     * @param null|array $extraClasses extra CSS osztályok, array(osztály1, osztály2, ...)
     * @param bool $footer Kell -e lábléc? alapértelmezett igen
     * @param null $tableName
     * @return bool|string String ha minden oké|false ha fejre állt...
     */
    function createSimpleTable($heads,$content,$extraClasses = null,$footer = true, $tableName = null) {
        if (is_array($heads) AND is_array($content)) {

            $colsInTable = array_keys($heads);

            $table = null;

            $classes[] = 'table';
            $classes[] = 'table-hover';
            $classes[] = coreFunctions::slugger($tableName);

            if (is_array($extraClasses)) {
                $classes = array_merge($classes,$extraClasses);
            }

            $table .= '<table class="' . implode(' ',$classes) .'">';

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
                    $table .= (isset($row[$colName])?$row[$colName]:'');
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

        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }

    /**
     * Középföldéhez űrlaptáblázat létrehozó metódus
     *
     * @param array $data
     * @param string $formName
     * @param string $submitValue
     * @param string $addToSubmit
     * @param bool $modalForm
     * @return bool|string
     */
    public function createFormTable($data = null, $formName = null, $submitValue = null, $addToSubmit = null, $modalForm = false) {
        if (is_array($data)) {

            $out = '<table class="form form-' . $formName . '">';

            foreach ($data as $row) {

                if (is_null($row['label']) AND $row['required']=='hidden') {
                    $out .= '<tr class="formInputRow hidden">';
                    $out .= '<td colspan="2">';
                    $out .= $row['input'];
                    $out .= '</td>';
                    $out .= '</tr>';
                } else {
                    $out .= '<tr class="formInputRow">';

                    if (is_null($row['label'])) {
                        $out .= '<td class="emptyRow">&nbsp;</td>';
                    } else {
                        $out .= '<td class="formInputLabel">';
                        $out .= $row['label'];
                        $out .= ($row['required']?'<span class="inputRequired">*</span>':'');
                        $out .= '</td>';
                    }

                    if (is_null($row['input'])) {
                        $out .= '<td class="emptyRow">&nbsp;</td>';
                    } else {
                        $out .= '<td class="formInputField">';
                        $out .= $row['input'];
                        $out .= '</td>';
                    }


                    $out .= '</tr>';
                }
            }

            $out .= '<tr class="formInputRow">';
            $out .= '<td class="formSubmit" colspan="2">';
            $out .= '<button class="btn btn-primary" type="submit" name="submit-' . $formName . '">' . $submitValue . '</button>';
            $out .= $addToSubmit;
            $out .= '</td>';
            $out .= '</tr>';

            $out .= '</table>';

            return $out;

        } else {
            return false;
        }
    }

    /**
     * Középföldéhez profil megtekintésére szolgáló táblázat
     *
     * @param array $data
     * @param bool $editLink
     * @return bool|string
     */
    public function profileTable($data, $editLink = false) {
        if (is_array($data)) {

            $out = '<table class="profileTable">';

            if ($editLink) {
                $out .= '<tr class="profileRow">';
                $out .= '<td class="editLink buttonRow" colspan="2">';
                $out .= '<a class="btn" href="' . $_SERVER['REQUEST_URI'] . '&action=edit">Szerkesztés</a>';
                $out .= '</td>';
                $out .= '</tr>';
            }

            foreach ($data as $row) {
                $out .= '<tr class="profileRow">';
                $out .= '<td class="profileLabel">';
                $out .= $row['label'];
                $out .= '</td>';


                $out .= '<td class="profileField ' . ($row['input']==''?'disabledField':'') . '">';
                $out .= $row['input'];
                $out .= '</td>';
                $out .= '</tr>';
            }

            $out .= '<tr class="profileRow">';
            $out .= '<td class="finishRow" colspan="2">';
            $out .= '&nbsp;';
            $out .= '</td>';
            $out .= '</tr>';

            $out .= '</table>';

            return $out;

        } else {
            return false;
        }
    }

    public function simpleTable($heads = null,$content = null, $extraClasses = null,$footer = true) {

        if (is_array($heads) AND is_array($content)) {

            $colsInTable = array_keys($heads);

            $table = null;

            $classes[] = 'simpleTable';

            if (is_array($extraClasses)) {
                $classes = array_merge($classes,$extraClasses);
            }

            $table .= '<table class="' . implode(' ',$classes) .'">';

            $table .= '<thead>';
            $table .= '<tr>';
            foreach ($heads AS $key=>$head) {
                $table .= '<th id="' . coreFunctions::slugger($key) . '">';
                $table .= $head;
                $table .= '</th>';
            }
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';

            foreach ($content AS $row) {
                $table .= '<tr>';


                foreach ($colsInTable AS $colName) {

                    if ($colName == 'edit') {
                        $table .= '<td id="' . coreFunctions::slugger($colName) . '">';
                    } else {
                        $table .= '<td id="' . coreFunctions::slugger($colName) . '">';
                    }

                    $table .= (isset($row[$colName])?$row[$colName]:'');
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

        } else {
            $this->error->isNotArrayError(func_get_args());
            return false;
        }
    }
}