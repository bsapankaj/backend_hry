<?php
if (!function_exists('getIndianCurrency')) {
    function getIndianCurrency(float $number)
    {
        $number = (float) round($number * 100) / 100;
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else
                $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $digit2 = floor(round($decimal) % 10);
        $digit1 = round($decimal - $digit2);
        $paise = ($decimal > 0) ? " and " . ($words[round($digit1)] . " " . $words[$digit2]) . ' Paise ' : '';
        if ($Rupees == '') {
            $Rupees = "Zero " . $Rupees;
        }
        return 'Rupees ' . ucwords(($Rupees ? $Rupees . '' : '') . $paise);
    }
}

if (!function_exists('createImageFromBase64WithouExt')) {
    function createImageFromBase64WithouExt($Base64 = '', $Path = '', $ImageName = '', $URLDecode = true)
    {

        if (empty($Base64)) {
            return true;
        }

        if ($URLDecode) {
            $imageData = base64_decode(urldecode($Base64));
        } else {
            $imageData = base64_decode($Base64);
        }
        // echo $imageData;exit;
        if (!is_dir($Path)) {
            mkdir($Path, 0755, true);
        }

        if (strpos($ImageName, '.jpg') !== false) {
            $ImageName = substr($ImageName, 0, (strlen($ImageName) - 4));
        } else if (strpos($ImageName, '.jpeg') !== false) {
            $ImageName = substr($ImageName, 0, (strlen($ImageName) - 5));
        } else if (strpos($ImageName, '.png') !== false) {
            $ImageName = substr($ImageName, 0, (strlen($ImageName) - 4));
        }

        $filename = $Path . $ImageName . ".jpg";

        if (file_exists($filename)) {
            $destination = $Path . $ImageName . '-' . date('YmdHis') . ".jpg";
            copy($filename, $destination);
        }
        // print_r($filename);exit;
        if (file_put_contents($filename, $imageData)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('createFileFromBase64AllExtn')) {
    function createFileFromBase64AllExtn($Base64 = '', $Path = '', $FileName = '')
    {
        $output = [];
        if (empty($Base64)) {
            return true;
        }
        $shortArray = [
            "PNG" => "data:image/png;base64",
            "PDF" => "data:application/pdf;base64",
            "jpg" => "data:image/jpeg;base64"
        ];
        $FileArray = explode(",", $Base64);
        foreach ($shortArray as $key => $value) {
            if ($value == $FileArray[0]) {
                $ImgBase64 = str_replace($value, '', $Base64);
                $ImgBase64 = str_replace(' ', '+', $ImgBase64);
                $ImgBase64 = base64_decode($ImgBase64);
                $file_org_name = $FileName . "." . strtolower($key);
                $filename = $Path . $file_org_name;

                if (file_exists($filename)) {
                    $file_org_name = $FileName . '-' . date('YmdHis') . "." . strtolower($key);
                    $destination = $Path . $file_org_name;
                    copy($filename, $destination);
                }

                if (file_put_contents($filename, $ImgBase64)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}

if (!function_exists('get_string_between')) {
    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
if (!function_exists('group_by')) {
    function group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
