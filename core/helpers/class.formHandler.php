<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.15.
 * Time: 12:37
 */
class formHandler
{
    private $error = null;
    private $debug = null;
    private $wizardedForm = null;
    private $normalForm = null;
    private $table = null;

    function __construct($debug = false)
    {
        if (is_null($this->error)) {
            $this->error = new errorHandler($debug);
        }

        $this->table = new tableHandler($debug);

        $this->debug = $debug;
    }

    /**
     * A Validátor meghívása után a kérést továbbítja a tisztító metódusnak majd a visszakapott értékeketet értékei és vagy hibát ad vissza vagy az értéket
     * @param null|array $data
     * @return null|array
     */
    function validator($data = null)
    {
        if (is_null($data)) {
            $data = $this->cleaner();
        } else {
            $data = $this->cleaner($data);
        }


        $returnArray = null;

        foreach ($data as $key => $value) {

            $dataTypeAndKey = explode('-', $key);
            $dataType = $dataTypeAndKey[0];
            $dataKey = $dataTypeAndKey[1];

            if ($dataType != 'submit') {
                switch ($dataType) {
                    case 'text':
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                    case 'textArea':
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                    case 'num':
                        $returnArray[$dataKey] = filter_var($value, FILTER_VALIDATE_INT);
                        break;
                    case 'year':
                        $returnArray[$dataKey] = filter_var($value, FILTER_VALIDATE_INT);
                        break;
                    case 'email':
                        $returnArray[$dataKey] = filter_var($value, FILTER_VALIDATE_EMAIL);
                        break;
                    default:
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                }
            }
        }

        return $returnArray;
    }

    /**
     * Végignyálazza a $_POST-ban található adatokat és mező típusnak megfelelően ellenőrzi a tartalmakat
     * Miután végzett kiüríti a $_POST-ot
     *
     * @param null|array $data
     * @return array
     */
    private function cleaner($data = null)
    {
        if (is_array($data)) {
            $rawData = $data;
        } else {
            $rawData = $_POST;
        }

        $returnArray = array();

        foreach ($rawData AS $rawKey => $data) {
            $dataTypeAndKey = explode('-', $rawKey);

            if (isset($dataTypeAndKey[1])) {
                $dataType = $dataTypeAndKey[0];
                $dataKey = $dataTypeAndKey[1];
            } else {
                $dataType = $dataTypeAndKey[0];
                $dataKey = $dataTypeAndKey[0];
            }


            if ($dataType != 'submit') {
                switch ($dataType) {
                    case 'textArea':
                        $returnArray[$dataType . '-' . $dataKey] = coreFunctions::cleanTextField($data);
                        break;
                    case 'email':
                        $returnArray[$dataType . '-' . $dataKey] = filter_var($data, FILTER_SANITIZE_EMAIL);
                        break;
                    case 'num':
                        $returnArray[$dataType . '-' . $dataKey] = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                        break;
                    case 'year':
                        $returnArray[$dataType . '-' . $dataKey] = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                        break;
                    case 'phone':
                        $sanitize = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                        $sanitize = str_replace(array('+', '-', '/'), '', $sanitize);
                        $returnArray[$dataType . '-' . $dataKey] = $sanitize;
                        break;
                    case 'checklist':
                        $returnArray[$dataType . '-' . $dataKey] = $data;
                        break;
                    default:
                        if (is_array($data)) {
                            $returnArray[$dataType . '-' . $dataKey] = $data;
                        } else {
                            $returnArray[$dataType . '-' . $dataKey] = coreFunctions::cleanVar($data);
                        }
                        break;
                }
            }
        }

        $_POST = null;

        return $returnArray;

    }

    /**
     * Ha van a tömbben hibás false érték akkor igazolja, hogy van hiba a tömbben
     * @param null $dataArray
     * @param bool $editForm Ha true akkor a passWord1 és passWord2 kulcsokra nem vonatkoznak a szabályok
     * @return bool
     */
    public function checkForErrors($dataArray = null, $editForm = false)
    {
        if (is_array($dataArray)) {

            $valid = true;

            foreach ($dataArray AS $key => $value) {
                /*
                 * Hacsak 1 invalid érték is van dobjunk hiba üzenetet
                 * Lehet egy magyarázó tömböt nem lenne baj létrehozni...
                 */

                if ($value === false) {

                    if (!$editForm) {

                        if (isset($_SESSION['lastFormInputs'][$key])) {
                            $name = $_SESSION['lastFormInputs'][$key];
                        } else {
                            $name = $key;
                        }

                        $this->error->errorMSG("A(z) \"{$name}\" mező érvénytelen adatot tartalmaz.");
                        $valid = false;
                    } else {
                        if ($key != 'passWord1' AND $key != 'passWord2') {
                            if (isset($_SESSION['lastFormInputs'][$key])) {
                                $name = $_SESSION['lastFormInputs'][$key];
                            } else {
                                $name = $key;
                            }

                            $this->error->errorMSG("A(z) \"{$name}\" mező érvénytelen adatot tartalmaz.");
                            $valid = false;
                        }
                    }
                }
            }

            unset($_SESSION['lastFormInputs']);
            return $valid;

        } else {
            $this->error->isNotArrayError();
            return true;
        }
    }

    /**
     * array()
     *
     * @param string $formTitle
     * @param array $formArray
     * @param string $formAction
     * @return bool|null|string
     */
    public function generateFormFromArray($formTitle = null, $formArray = null, $formAction = null)
    {
        if (is_array($formArray)) {

            $formContent = null;
            $tabIndex = 0;

            foreach ($formArray as $row) {

                if (isset($row['type'])) {
                    $name = (isset($row['name']) ? $row['type'] . '-' . $row['name'] : null);
                    $value = (isset($row['value']) ? $row['value'] : null);
                    $placeHolder = (isset($row['placeH']) ? $row['placeH'] : null);

                    $formContent .= "\r\n";

                    switch ($row['type']) {
                        case 'text':
                            $formContent .= '<p><input tabindex="' . $tabIndex . '" class="textInput" id="' . $name . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" /></p>';
                            break;
                        case 'textArea':
                            $formContent .= '<p><textarea tabindex="' . $tabIndex . '" id="' . $name . '" name="' . $name . '" placeholder="' . $placeHolder . '">' . $value . '</textarea></p>';
                            break;
                        case 'email':
                            $formContent .= '<p><input tabindex="' . $tabIndex . '" id="' . $name . '" class="textInput" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" /></p>';
                            break;
                        case 'num':
                            $formContent .= '<input tabindex="' . $tabIndex . '" id="' . $name . '" class="textInput" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" />';
                            break;
                        case 'file';
                            break;
                        case 'pass':
                            $formContent .= '<p><input tabindex="' . $tabIndex . '" id="' . $name . '" type="password" class="textInput" name="' . $name . '" placeholder="' . $placeHolder . '"></p>';
                            break;
                        case 'hidden':
                            $formContent .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
                            break;
                        case 'label':
                            $formContent .= '<p  id="' . $name . 'Label' . '" class="formLabel"><label for="' . $name . '">' . $value . '</label></p>';
                            break;
                        default:
                            break;
                    }

                    $formContent .= "\r\n";

                    $tabIndex++;
                }
            }

            if (!is_null($formContent)) {

                $titleSlug = (!is_null($formTitle) ? coreFunctions::slugger($formTitle, array(), '+') : 'form');
                $idSlug = coreFunctions::slugger($formTitle);

                $temp = "\r\n" . '<form id="' . $idSlug . '" accept-charset="utf-8" action="' . $formAction . '" enctype="multipart/form-data"  method="post">';
                $temp .= "\r\n" . $formContent;
                $temp .= '<input class="formButton" type="submit" name="submit-' . $titleSlug . '" />';
                $temp .= '</form>';
            } else {
                $this->error->isNullError();
                return null;
            }


            return $temp;


        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }

    /**
     * @param string $inputType
     * @param string $inputName
     * @param string|array $value
     * @param string $placeholder
     * @param string $labelContent
     * @param bool $required
     */
    public function addInput($inputType = null, $inputName = null, $value = null, $placeholder = null, $labelContent = null, $required = false)
    {
        $input['type'] = $inputType;
        $input['name'] = $inputName;

        if ($inputType != 'dropdownList') {
            $input['value'] = (isset($_SESSION['postBack'][$inputName]) ? $_SESSION['postBack'][$inputName] : $value);
        } else {
            $input['value'] = $value;
        }

        $input['placeholder'] = $placeholder;
        $input['label'] = $labelContent;
        $input['required'] = $required;

        $_SESSION['lastFormInputs'][$inputName] = $labelContent;

        $this->normalForm[] = $input;
    }

    /**
     * @param string $formName
     * @param string $submitText
     * @param string $submitAdd
     * @param string $submitTarget
     * @param string $layoutMode table|bootstrap-horizontal
     * @param bool $modalForm
     * @return bool|string
     */
    public function generateForm($formName = null, $submitText = null, $submitAdd = null, $submitTarget = null, $layoutMode = 'bootstrap-horizontal', $modalForm = false)
    {
        if (is_array($this->normalForm)) {

            $rows = null;

            foreach ($this->normalForm AS $formElement) {

                switch ($formElement['type']) {
                    case 'passwordCheck':
                        /*
                         * Reglap páros jelszó
                         */

                        $input1['label'] = '<label class="control-label" for="text-' . $formElement['name'] . '1">Jelszó</label>';
                        $input1['input'] = '<input type="password" name="text-' . $formElement['name'] . '1" id="text-' . $formElement['name'] . '1" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;

                        $input2['label'] = '<label class="control-label" for="text-' . $formElement['name'] . '2">Jelszó</label>';
                        $input2['input'] = '<input type="password" name="text-' . $formElement['name'] . '2" id="text-' . $formElement['name'] . '2" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input2['required'] = $formElement['required'];

                        $rows[] = $input2;

                        break;
                    case 'textField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="col-lg-2 control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="text" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'urlField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="url" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'numField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="control-label" for="num-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="text" name="num-' . $formElement['name'] . '" id="num-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'dateField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="control-label" for="date-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="text" name="date-' . $formElement['name'] . '" id="date-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'infoText':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = $formElement['label'];
                        $input1['input'] = $formElement['value'];
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'emailField':
                        /*
                         * Módosított szöveges mező az emailekhez
                         */
                        $input1['label'] = '<label class="control-label" for="email-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="email" name="email-' . $formElement['name'] . '" id="email-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;

                        break;
                    case 'phoneField':
                        /*
                         * Módosított szöveges mező az emailekhez
                         */
                        $input1['label'] = '<label class="control-label" for="phone-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="tel" ' . ($formElement['required']==true?'required':'') . ' name="phone-' . $formElement['name'] . '" id="phone-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'password':
                        $input1['label'] = '<label class="col-lg-2 control-label" for="text-' . $formElement['name'] . '">Jelszó</label>';
                        $input1['input'] = '<input class="form-control" type="password" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'textArea':
                        $input1['label'] = '<label class="col-lg-2 control-label" for="textArea-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<textarea class="form-control ckeditor" ' . ($formElement['required']==true?'required':'') . ' name="textArea-' . $formElement['name'] . '" id="textArea-' . $formElement['name'] . '">' . $formElement['value'] . '</textarea>';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'avatarUpload':
                        $input1['label'] = '<label class="control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="file" accept="image/jpeg" name="file-' . $formElement['name'] . '" id="file-' . $formElement['name'] . '" />';
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;
                        break;
                    case 'imageUpload':
                        $input1['label'] = '<label class="control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="file" accept="image/jpeg|image/png" name="file-' . $formElement['name'] . '" id="file-' . $formElement['name'] . '" />';
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;
                        break;
                    case 'fileUpload':
                        $input1['label'] = '<label class="control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="file" name="file-' . $formElement['name'] . '" id="file-' . $formElement['name'] . '" />';
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;
                        break;
                    case 'emptyRow':

                        $input1['label'] = null;
                        $input1['input'] = null;
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;

                        break;
                    case 'divider':
                        $input1['label'] = null;
                        $input1['input'] = null;
                        $input1['required'] = 'divider';
                        $rows[] = $input1;
                        break;
                    case 'hidden':
                        $input1['label'] = null;
                        $input1['input'] = '<input type="hidden" name="' . $formElement['name'] . '" value="' . (isset($_SESSION['postBack'][$formElement['name']]) ? $_SESSION['postBack'][$formElement['name']] : $formElement['value']) . '" />';
                        $input1['required'] = 'hidden';
                        $rows[] = $input1;
                        break;
                    case 'dropdownList':
                        $input1['label'] = '<label class="control-label" for="select-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['required'] = $formElement['required'];

                        $input1['input'] = null;

                        if (is_array($formElement['value'])) {
                            $input1['input'] = '<select ' . ($formElement['required']==true?'required':'') . ' name="select-' . $formElement['name'] . '" id="select-' . $formElement['name'] . '">';

                            foreach ($formElement['value'] AS $key => $value) {

                                $selected = false;

                                if (isset($_SESSION['postBack'][$formElement['name']])) {
                                    if ($_SESSION['postBack'][$formElement['name']] == $key) {
                                        $selected = true;
                                    }
                                }

                                $input1['input'] .= '<option ' . ($selected ? 'selected="selected"' : '') . ' value="' . $key . '">' . $value . '</option>';
                            }

                            $input1['input'] .= '</select>';
                        }


                        $rows[] = $input1;
                        break;
                    default:
                        break;
                }
            }

            $out = '<form role="form" method="POST" ';
            $out .= 'data-async class="form-horizontal" action="' . (is_null($submitTarget)?$_SERVER['PHP_SELF']:$submitTarget) . '" id="form-' . coreFunctions::slugger($formName) . '" accept-charset="utf-8" enctype="multipart/form-data">';

            $render = false;

            switch ($layoutMode) {
                case 'bootstrap-horizontal':
                    $render = $this->bootstrapFormLayout($rows,$formName,$submitText,$submitAdd, $modalForm);
                    break;
            }



            if (is_bool($render)) {
                return null;
            } else {
                $out .= $render;
            }

            if (isset($_SESSION['postBack'])) {
                $out .= '<input type="hidden" name="editForm" value="1" />';
                unset($_SESSION['postBack']);
            }

            $out .= '</form>';



            return $out;

        }
        return false;
    }

    private function bootstrapFormLayout($rows, $formName, $submitText, $submitAdd, $modalForm = false) {
        if (is_array($rows)) {

            $r = null;

            foreach ($rows AS $row) {

                if (is_null($row['label']) AND is_null($row['input']) AND $row['required']=='divider') {
                    $r .= '<hr>';
                } elseif (is_null($row['label']) AND $row['required']=='hidden') {
                    $r .= $row['input'];
                } else {
                    $r .= '<div class="form-group">';
                    $r .= $row['label'];
                    $r .= '<div class="col-lg-10">';
                    $r .= $row['input'];
                    $r .= '</div>';
                    $r .= '</div>';
                }

            }

            if (!$modalForm) {
                $r .= '
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button class="btn btn-primary" type="submit" name="submit-' . $formName . '">' . $submitText . '</button>' . $submitAdd  .'
    </div>
</div>
            ';
            }



            return $r;

        } else {
            return false;
        }
    }
}
