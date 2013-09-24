<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.15.
 * Time: 12:37
 */

/**
 * Class formHandler
 */
class formHandler
{
    /**
     * @var errorHandler|null
     */
    private $error = null;
    /**
     * @var bool|null
     */
    private $debug = null;
    /**
     * @var null
     */
    private $normalForm = null;
    /**
     * @var null|tableHandler
     */
    private $table = null;

    private $formLayout = 'bootstrap-horizontal';

    private $formRatio = '2:10';

    private $submitStyle = 'primary';

    /**
     * @param bool $debug
     */
    function __construct($debug = false)
    {
        if (is_null($this->error)) {
            $this->error = new errorHandler($debug);
        }

        $this->table = new tableHandler($debug);

        $this->debug = $debug;
    }

    /**
     * @param string $layout
     */
    function setFormLayout($layout = 'bootstrap-horizontal') {
        $this->formLayout = $layout;
    }

    /**
     * @param string $ratio
     */
    function setFormRatio($ratio = '2:10') {
        $this->formRatio = $ratio;
    }

    /**
     * @param string $style
     */
    function setSubmitStyle($style = 'primary') {
        $this->submitStyle=$style;
    }

    /**
     * Reset the form class variables to their default values
     * return void
     */
    private function resetForm() {
        $this->setSubmitStyle();
        $this->setFormLayout();
        $this->setFormRatio();
    }

    /**
     *
     * The validator() calls the cleaner() to sanitize the input values and evaluates the remaining data
     *
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

        if (isset($_SESSION['postBack'])) {
            unset($_SESSION['postBack']);
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


        if (isset($_FILES) AND count($_FILES)>0) {

            $returnArray = array_merge($returnArray,$this->handleFileUpload());
        }

        return $returnArray;
    }

    /**
     * @return null|array
     */
    private function handleFileUpload() {
        $result = null;

        foreach ($_FILES AS $key=>$f) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            $fileName = sha1($f['name'] . date('Y-m-d H:i:s')) . '.' . $ext;

            $filePath = _UPLOAD_PATH . '/';

            if (!file_exists($filePath)) {
                mkdir($filePath, 777, true);
            }

            $fullPath = $filePath . $fileName;

            $output = null;

            if (move_uploaded_file($f['tmp_name'], $fullPath)) {
                $output = $fileName;
            } else {
                $output = false;

            }

            $key = explode('-',$key);
            $result[$key[1]] = $output;
        }

        return $result;
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
     *
     * This method is responsible for creating error messages, but is largely obsolete
     *
     * @TODO revise
     *
     * @deprecated revision needed
     *
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
     *
     * Add an input to a yet to be created form
     *
     *
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
     *
     * Shortcut for adding a text input to the form
     *
     * @param null $inputName
     * @param null $value
     * @param null $placeholder
     * @param null $labelContent
     * @param bool $required
     */
    public function addTextField($inputName = null, $value = null, $placeholder = null, $labelContent = null, $required = false) {
        $this->addInput('textField', $inputName, $value, $placeholder, $labelContent, $required);
    }

    /**
     *
     * Shortcut for adding a ckEditor Field to the form
     *
     * @param null $inputName
     * @param null $value
     * @param null $placeholder
     * @param null $labelContent
     * @param bool $required
     */
    public function addCKEditor($inputName = null, $value = null, $placeholder = null, $labelContent = null, $required = false) {
        $this->addInput('ckeditor', $inputName, $value, $placeholder, $labelContent, $required);
    }


    /**
     * @param null $inputName
     * @param null $labelContent
     */
    public function addFileUpload($inputName = null, $labelContent = null) {
        $this->addInput('fileUpload', $inputName, null, null, $labelContent, false);
    }

    /**
     * @param null $inputName
     * @param null $value
     * @param null $placeholder
     * @param null $labelContent
     * @param bool $required
     */
    public function addTextArea($inputName = null, $value = null, $placeholder = null, $labelContent = null, $required = false) {
        $this->addInput('textArea',$inputName,$value,$placeholder,$labelContent,$required);
    }

    /**
     *
     * Generate the form, based on the already added inputs
     *
     * @param string $formName
     * @param string $submitText
     * @param string $submitAdd    Addition code to place next to the Submit button (eg. cancel, reset, ...)
     * @param string $submitTarget form target
     * @param string $layoutMode   bootstrap-horizontal|table
     * @param bool   $modalForm
     * @param string $ratio
     *
     * @return bool|string
     */
    public function generateForm($formName = null, $submitText = null, $submitAdd = null, $submitTarget = null, $layoutMode = 'bootstrap-horizontal', $modalForm = false, $ratio='2:10')
    {
        if (is_array($this->normalForm)) {

            $ratio = $this->formRatio;

            $ratio = explode(':',$ratio);
            
            $ratio['label'] = $ratio[0];
            $ratio['input'] = $ratio[1];
            
            $rows = null;

            foreach ($this->normalForm AS $formElement) {

                switch ($formElement['type']) {
                    case 'passwordCheck':
                        /*
                         * Reglap páros jelszó
                         */

                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '1">' . gettext('Password') . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="password" name="text-' . $formElement['name'] . '1" id="text-' . $formElement['name'] . '1" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;

                        $input2['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '2">' . gettext('Password') . '</label>';
                        $input2['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="password" name="text-' . $formElement['name'] . '2" id="text-' . $formElement['name'] . '2" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input2['required'] = $formElement['required'];

                        $rows[] = $input2;

                        break;
                    case 'textField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="text" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'urlField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="url" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'numField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="num-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="number" name="num-' . $formElement['name'] . '" id="num-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;


                        break;
                    case 'dateField':
                        /*
                         * Szöveges mező
                         */

                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="date-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
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
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="email-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input class="form-control" ' . ($formElement['required']==true?'required':'') . ' type="email" name="email-' . $formElement['name'] . '" id="email-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;

                        break;
                    case 'phoneField':
                        /*
                         * Módosított szöveges mező az emailekhez
                         */
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="phone-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="tel" ' . ($formElement['required']==true?'required':'') . ' name="phone-' . $formElement['name'] . '" id="phone-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'password':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '">Jelszó</label>';
                        $input1['input'] = '<input class="form-control" type="password" name="text-' . $formElement['name'] . '" id="text-' . $formElement['name'] . '" value="' . $formElement['value'] . '"  placeholder="' . $formElement['placeholder'] . '">';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'textArea':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="textArea-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<textarea class="form-control" ' . ($formElement['required']==true?'required':'') . ' name="textArea-' . $formElement['name'] . '" id="textArea-' . $formElement['name'] . '"  placeholder="' . $formElement['placeholder'] . '">' . $formElement['value'] . '</textarea>';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'ckeditor':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="textArea-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<textarea class="form-control ckeditor" ' . ($formElement['required']==true?'required':'') . ' name="textArea-' . $formElement['name'] . '" id="textArea-' . $formElement['name'] . '">' . $formElement['value'] . '</textarea>';
                        $input1['required'] = $formElement['required'];

                        $rows[] = $input1;
                        break;
                    case 'avatarUpload':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="file" accept="image/jpeg" name="file-' . $formElement['name'] . '" id="file-' . $formElement['name'] . '" />';
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;
                        break;
                    case 'imageUpload':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="file" accept="image/jpeg|image/png" name="file-' . $formElement['name'] . '" id="file-' . $formElement['name'] . '" />';
                        $input1['required'] = $formElement['required'];
                        $rows[] = $input1;
                        break;
                    case 'fileUpload':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="file-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
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
                    case 'onOffBox':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = '<input type="hidden" name="' . $formElement['name'] . '" value="0" />';
                        $input1['input'] .= '<input type="checkbox" name="' . $formElement['name'] . '" value="1" ' . (isset($_SESSION['postBack'][$formElement['name']]) ? 'checked="checked"' : null) . '>';
                        $rows[] = $input1;
                        break;
                    case 'radio':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="text-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['input'] = null;

                        $counter = 0;

                        foreach ($formElement['value'] as $k=>$v) {
                            $input1['input'] .= '

                                <div class="radio">
                                    <label class="label_radio">
                                        <input type="radio" name="radio-' . $formElement['name'] . '" id="radio-' . $formElement['name'] . '-' . $counter . '" value="' . $k . '" >
                                        ' . $v . '
                                    </label>
                                </div>

                            ';

                            $counter++;
                        }

                        $rows[] = $input1;
                        break;
                    case 'dropdownList':
                        $input1['label'] = '<label class="col-lg-' . $ratio['label']  . ' control-label" for="select-' . $formElement['name'] . '">' . $formElement['label'] . '</label>';
                        $input1['required'] = $formElement['required'];

                        $input1['input'] = null;

                        if (is_array($formElement['value'])) {
                            $input1['input'] = '<select class="form-control" ' . ($formElement['required']==true?'required':'') . ' name="select-' . $formElement['name'] . '" id="select-' . $formElement['name'] . '">';

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
            $out .= 'data-async class="form-horizontal" action="' . (is_null($submitTarget)?'':$submitTarget) . '" id="form-' . coreFunctions::slugger($formName) . '" accept-charset="utf-8" enctype="multipart/form-data">';

            $render = false;

            switch ($this->formLayout) {
                case 'bootstrap-horizontal':
                    $render = $this->bootstrapFormLayout($rows, $ratio['input']);

                    break;
                case 'bootstrap-basic':
                    $render = $this->bootstrapBasicFormLayout($rows, $ratio['input']);
                    break;
            }


            if (is_bool($render)) {
                return null;
            } else {
                $out .= $render;
            }

            if (isset($_SESSION['postBack'])) {
                $out .= '<input type="hidden" name="editForm" value="1" />';
            }

            switch ($this->formLayout) {
                case 'bootstrap-horizontal':
                    if (!$modalForm) {
                        $out .= '
<div class="form-group">
    <div class="col-lg-offset-' . $ratio['label']  . ' col-lg-' . $ratio['input']  . '">
        <button class="btn btn-' . $this->submitStyle . '" type="submit" name="submit-' . $formName . '">' . (is_null($submitText)?gettext('Save'):$submitText) . '</button>' . $submitAdd  .'
    </div>
</div>
            ';
                    }
                    break;
                case 'bootstrap-basic':
                    if (!$modalForm) {
                        $out .= '
<div class="form-group">
    <div class="">
        <button class="btn btn-' . $this->submitStyle . '" type="submit" name="submit-' . $formName . '">' . (is_null($submitText)?gettext('Save'):$submitText) . '</button>' . $submitAdd  .'
    </div>
</div>
            ';
                    }
                    break;



            }

            $out .= '</form>';


            $this->normalForm = null;

            $this->resetForm();

            return $out;

        }
        return false;
    }

    /**
     * @param      $rows
     * @param int  $ratioInput
     *
     * @return bool|null|string
     */
    private function bootstrapFormLayout($rows, $ratioInput = 10) {
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
                    $r .= '<div class="col-lg-' . $ratioInput . '">';
                    $r .= $row['input'];
                    $r .= '</div>';
                    $r .= '</div>';
                }

            }
            return $r;

            }


        return false;
    }

    /**
     * @param $rows
     *
     * @return bool|null|string
     */
    private function bootstrapBasicFormLayout($rows) {
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
                    $r .= $row['input'];
                    $r .= '</div>';
                }

            }
            return $r;

        }


        return false;
    }
}
