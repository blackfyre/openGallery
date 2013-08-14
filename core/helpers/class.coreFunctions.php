<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.04.
 * Time: 12:14
 */

/**
 * Class coreFunctions
 * Alap funkciók amit minden osztály számára elérhetővé kell tenni
 * @package blackFyre
 */
class coreFunctions
{


    static function docRoot() {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Megtisztitja az egyszerű tartalmakat, textArea tisztítására NEM ALKALMAS!
     *
     * @param $variable
     * @return mixed
     */
    static function cleanVar($variable)
    {
        $variable = htmlspecialchars(trim(stripcslashes(strip_tags($variable))), ENT_QUOTES);
        return $variable;
    }

    /**
     * @param string $var
     * @return string
     */
    static function cleanTextField($var)
    {
        $var = strip_tags($var, '<br><b><string><ul><ol><li><a><i><em><pre><table><tbody><thead><tfoor><th><tr><td><p><div><img><sub><sup>');
        $var = htmlspecialchars($var, ENT_QUOTES);
        return $var;
    }

    /**
     * @param array $arrayToClean
     * @param bool $keepKeys
     * @return array
     */
    static function removeEmptyValuesFromArray($arrayToClean,$keepKeys=true) {

        if ($keepKeys) {
            foreach ($arrayToClean as $key => $link)
            {
                if ($arrayToClean[$key] == '')
                {
                    unset($arrayToClean[$key]);
                }
            }
        } else {
            $r = null;
            foreach ($arrayToClean as $value) {
                if ($value != '') {
                    $r[] = $value;
                }
            }
            $arrayToClean = $r;
        }


        return $arrayToClean;
    }

    /**
     * multi_array_key_exists function.
     *
     * Source: http://php.net/manual/en/function.array-key-exists.php
     *
     * @param mixed $needle The key you want to check for
     * @param mixed $haystack The array you want to search
     * @return bool
     */
    static function multi_array_key_exists( $needle, $haystack ) {

        foreach ( $haystack as $key => $value )  {
            if ( $needle == $key ) {
                return true;
            }

            if ( is_array( $value ) )  {
                if ( self::multi_array_key_exists( $needle, $value ) == true )
                    return true;
                else
                    continue;
            }
        }

        return false;
    }

    static function multiArrayKeyExists($needle, $haystack) {
        foreach ($haystack as $key=>$value) {
            if ($needle===$key) {
                return $key;
            }
            if (is_array($value)) {
                if(self::multiArrayKeyExists($needle, $value)) {
                    return $key . ":" . self::multiArrayKeyExists($needle, $value);
                }
            }
        }
        return false;
    }


    /**
     *
     * Source: http://php.net/manual/en/function.shuffle.php / ahmad at ahmadnassri dot com
     *
     * @param $array
     * @return bool
     */
    static public function shuffleAssocArray(&$array) {

        $keys = array_keys($array);

        shuffle($keys);

        $new = null;

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code generates a sample text from a larger string.
     * Ideal for intro or sample blocks.
     *
     * @param string $str source string
     * @param int $length
     * @param int $minword
     * @return string sample string
     */
    public static function trimmer($str, $length, $minword = 3)
    {
        $str = strip_tags($str);
        $sub = '';
        $len = 0;

        foreach (explode(' ', $str) as $word) {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);

            if (strlen($word) > $minword && strlen($sub) >= $length) {
                break;
            }
        }

        return $sub . (($len < strlen($str)) ? '...' : '');
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code creates a slugged version of the input string ($str).
     *
     * @param string $str Input string
     * @param array $replace An array of special characters and their replacement
     * @param string $delimiter The delimiter to separate the words
     * @return string The slug version of the input string
     */
    public static function slugger($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array )$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     * 1000000 -> 1 000 000
     *
     * @param $num
     * @return mixed
     */
    function prettyNumbers($num)
    {
        $num = number_format($num, 0, ',', " ");
        $num = str_replace(' ', '&nbsp;', $num);
        return $num;
    }

    /**
     * Az útvonalakat tisztítja meg a kellemetlen dupla // jelektől
     *
     * @param $path
     * @return mixed
     */
    public static function cleanPath($path)
    {
        $path = self::cleanVar($path);
        $path = str_replace('//', '/', $path);
        return $path;
    }

    /**
     * Azonosítja a szövegben elhelyezett URL-eket és HTML linkké alakítja azokat
     * @param string $text
     * @return string
     */
    public static function identifyLinks($text)
    {

        $text = preg_replace('/(((f|ht){1}tp:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i',
            '<a target="_blank" href="\\1">[link]</a>', $text);
        $text = preg_replace('/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i',
            '\\1<a target="_blank" href="http://\\2">[link]</a>', $text);
        $text = preg_replace('/([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/i',
            '<a href="mailto:\\1">[email]</a>', $text);
        return $text;

    }

    /**
     * Beállítja a linkek célját (_blank)
     * @param string $text
     * @return string
     */
    public static function linkTargetSet($text)
    {
        $text = str_replace('<a href=', '<a target="_blank" href=', $text);
        return $text;
    }


    /**
     * Átalakítja a beérkező tömböt, hogy könnyebben lehessen SQL Insertet csinálni
     * @param array $array
     * @return bool|array
     */
    public static function prepareForSQLInsert($array = null)
    {
        if (is_array($array)) {
            $headers = null;
            $values = null;

            foreach ($array as $key => $value) {
                $headers[] = '`' . $key . '`';

                switch ($value) {
                    case 'NOW()':
                        $values[] = $value;
                        break;
                    default:
                        $values[] = "'" . $value . "'";
                        break;
                }


            }

            $r['headers'] = implode(',', $headers);
            $r['values'] = implode(',', $values);

            return $r;

        } else {
            return false;
        }
    }

    /**
     * @param null $value
     * @return bool
     */
    public static function isEmpty($value = null)
    {
        if ($value == null or $value == '') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function found at: http://stackoverflow.com/questions/4312439/php-return-all-dates-between-two-dates-in-an-array
     * @param $strDateFrom
     * @param $strDateTo
     * @return array
     */
    public static function createDateRangeArray($strDateFrom, $strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }


    /**
     * Specifikus taget eltávolít a stringből
     * Forrás: http://altafphp.blogspot.hu/2011/12/remove-specific-tag-from-php-string.html
     * @param string $tag
     * @param string $string
     * @return string
     */
    public static function stripSingleTag($tag,$string){
        $string=preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
        $string=preg_replace('/<\/'.$tag.'>/i', '', $string);
        return $string;
    }

    /**
     * Ellenőrzése, hogy a vizsgált szám ($int) a megadott intervallumon ($min - $max) belül van -e
     * @param int $int
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function testRange($int,$min,$max){
        return ($int>$min && $int<$max);
    }

    public static function killer() {
        unset($_SESSION['errorInfo'],$_SESSION['successInfo']);
        $trace = debug_backtrace();
        $trace = $trace[0];
        echo 'Killed on: ' . $trace['file'] . ' -> ' . $trace['line'];
        die;
    }

    /**
     * A mostani oldal url-e a requestekkel együtt
     * @return string
     */
    public static function currentPage() {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Konstans dekódolása
     *
     * @param null $constant
     * @return bool|mixed
     */
    public static function decodeConstant($constant = null) {
        if (!is_null($constant)) {
            return json_decode($constant,true);
        } else {
            return false;
        }
    }
}
