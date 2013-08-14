<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 17:39
 */

class options {

    /**
     * @var modelOptions|null
     */
    private $model = null;

    /**
     * @var null|tableHandler
     */
    private $table = null;

    /**
     * @var null|formHandler
     */
    private $form = null;

    /**
     * @var array
     */
    private $locales = array('AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AD' => 'Andorra', 'AO' => 'Angola', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'The Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BR' => 'Brazil', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo, Republic of the', 'CD' => 'Congo, Democratic Republic of the', 'CR' => 'Costa Rica', 'CI' => 'Cote d\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'Timor-Leste', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GR' => 'Greece', 'GD' => 'Grenada', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HN' => 'Honduras', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, North', 'KR' => 'Korea, South', 'ZZ' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar (Burma)', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PL' => 'Poland', 'PT' => 'Portugal', 'QA' => 'Qatar', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States of America', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City (Holy See)', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');

    function __construct() {
        $this->model = new modelOptions();
        $this->table = new tableHandler();
        $this->form = new formHandler();
    }

    /**
     * @return bool|string
     */
    private function addLangForm() {
        $this->form->addInput('textField','isoCode',null,'hu','ISO Kód');
        $this->form->addInput('textField','full',null,'Magyar','Teljes');

        return $this->form->generateForm('addLang','Mentés',null,'/responders/addLang.php','bootstrap-horizontal');
    }

    /**
     * @return mixed
     */
    function lang() {
        $data = $this->model->getLanguages();

        $heads['img'] = '';
        $heads['id'] = '#';
        $heads['isoCode'] = 'isoCode';
        $heads['full'] = 'full';
        $heads['edit'] = 'edit';

        $reRenderedData = null;

        foreach ($data AS $row) {
            $t = $row;
            if (file_exists(_BASE_PATH . '/img/flags/flag-' . $t['isoCode'] . '.png')) {
                $t['img'] = '<img style="width:32px" src="/img/flags/flag-' . $t['isoCode'] . '.png">';
            } else {
                $t['img'] = '';
            }
            $t['edit'] = '<div class="btn-toolbar">';
            $t['edit'] .= '<div class="btn-group">';

            if ($t['active']=='1') {
                $t['edit'] .= '<a class="btn btn-mini" href="#" onclick="deactivateLang(' . $t['id'] . ')"><i class="icon-eye-close"></i> Deaktiválás</a>';
            } else {
                $t['edit'] .= '<a class="btn btn-mini" href="#" onclick="activateLang(' . $t['id'] . ')"><i class="icon-eye-open"></i> Aktiválás</a>';
            }

            $t['edit'] .= '</div>';
            $t['edit'] .= '</div>';

            $reRenderedData[] = $t;
        }

        $extraClass[] = 'table-condensed';

        $r['table'] = $this->table->createSimpleTable($heads,$reRenderedData,$extraClass,true,'langTable');
        $r['form'] = $this->addLangForm();

        return $r;

    }

    /**
     * @return bool|null
     */
    function addLangAction() {
        $data = $this->form->validator();

        if (is_array($data)) {

            if ($this->model->fragger($data,'languages')) {

                $this->updateTables($data['isoCode']);

                return true;

            } else {
                return false;
            }

        } else {
            return false;
        }


    }

    /**
     * @param string $isoCode
     * @return bool
     */
    private function updateTables($isoCode = null) {

        if (is_string($isoCode)) {

            $tables = $this->model->getDefaultLangColumns();

            if (is_array($tables)) {

                foreach ($tables AS $stuff) {

                    $colNameOrig = explode('_',$stuff['COLUMN_NAME']);
                    $colName = $colNameOrig[0] . '_' . $isoCode;

                    $colType = $stuff['COLUMN_TYPE'];

                    $query = "ALTER TABLE {$stuff['TABLE_NAME']} ADD COLUMN `$colName` $colType NULL ";

                    if ($stuff['DATA_TYPE']=='varchar') {
                        $query .= "DEFAULT NULL";
                    }

                    if ($colNameOrig[0]=='slug') {
                        $query .= ",
	ADD UNIQUE INDEX `slug_$isoCode` (`slug_$isoCode`)";
                    }

                    $this->model->db->query($query);

                }

            } else {
                return false;
            }

            return true;

        } else {
            return false;
        }


    }

    function setLang($langId = null,$toState = null) {
        $data['active'] = $toState;
        return $this->model->fragger($data,'languages','update',"id='$langId'",false);
    }
}