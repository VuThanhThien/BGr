<?php
if (! function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (! function_exists('countItemsInOrder')) {
    function countItemsInOrder($items) {
        $totalItem = 0;
        if($items) {
            foreach ($items as $value) {
                $totalItem += $value['quantity'];
            }
        }
        return $totalItem;
    }
}

if (! function_exists('calTotalPriceRefund')) {
    function calTotalPriceRefund($input, $commissionType) {
        $total = 0;
        if($commissionType == 2){
            if( !empty($input['transactions']) ) {
                foreach ($input['transactions'] as $value) {
                    $total += $value['amount'];
                }
            }else{
                foreach ($input['refund_line_items'] as $value) {
                    $total += $value['subtotal'];
                }
            }
        }else{
            if(!empty($input['refund_line_items'])) {
                foreach ($input['refund_line_items'] as $value) {
                    $total += $value['subtotal'];
                }

            }else{

                foreach ($input['transactions'] as $value) {
                    $total += $value['amount'];
                }
            }
        }
        return (-1)*$total;


    }
}

if (! function_exists('getItemsRefund')) {
    function getItemsRefund($items) {
        $arr = array();
        foreach ($items as $value) {
            $arr[] = $value['line_item'];
        }
        return json_encode($arr);
    }
}


if (! function_exists('replaceTagEmail')) {
    function replaceTagEmail($tags, $replaces, $content) {
        $finds = [];
        foreach ($tags as $tag) {
            $finds[] = '{'.$tag.'}';
        }
        return str_replace($finds, $replaces, $content);

    }
}

if (!function_exists('cdn_domain')) {

    /**
     * cdn_domain is to make a cdn domain with given path
     *
     * @param string $path
     * @return string
     */
    function cdn_domain($path = null)
    {
        $cdn_domain = config('myconfig.app.cdn_domain');
        if (is_null($path)) {
            return $cdn_domain;
        }
        if (strpos($path, '/') === 0) {
            $path = substr($path, 1);
        }
        return 'https://' . $cdn_domain . '/' . $path;
    }
}



if (!function_exists('getShopUrlByName')) {

    /**
     * cdn_domain is to make a cdn domain with given path
     *
     * @param string $path
     * @return string
     */
    function getShopUrlByName($shopName, $path = null)
    {

        if( $path ) {
            return 'https://'.$shopName.'.myshopify.com/'.$path;
        }else{
            return 'https://'.$shopName.'.myshopify.com';
        }
    }
}

if (!function_exists('genReferralLink')) {


    function genReferralLink($affiliate, $defaultReferralLink)
    {
        if (strpos($defaultReferralLink, '?') !== false) {
            return $defaultReferralLink.'&sca_ref='.$affiliate->id.'.'.$affiliate->hash_code;
        }else{
            return $defaultReferralLink.'?sca_ref='.$affiliate->id.'.'.$affiliate->hash_code;
        }

    }
}


if (!function_exists('symbolCurrency')) {
    function synbolCurrency($code) {
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;');
        return $currency_symbols[$code];
    }
}

function defaultOption($opt, $def) {
    return ($opt === null ? $def : $opt);
}

function formatWithDelimiters($number, $precision, $thousands =null, $decimal=null) {
    $precision = defaultOption($precision, 2);
    $thousands = defaultOption($thousands, ',');
    $decimal   = defaultOption($decimal, '.');

    if (!$number) {
        return 0;
    }

    $number = round(($number/100.0),$precision);
    // dd($precision);
    $parts   = explode(".", $number);
    $dollars =preg_replace('/(\d)(?=(\d\d\d)+(?!\d))/m', '$1'.$thousands, $parts[0]);
    $cents   = isset($parts[1]) ? ($decimal.$parts[1]) : '';
    return $dollars.$cents;
}

if (!function_exists('currency_format')) {

    /**
     * cdn_domain is to make a cdn domain with given path
     *
     * @param string $path
     * @return string
     */
    function currency_format($format, $cents)
    {
        $format = strip_tags($format);
        $cents = $cents*100;
        $placeholderRegex = '/\{\{\s*(\w+)\s*\}\}/';
        preg_match($placeholderRegex, $format, $matches);
        switch($matches[1]) {
            case 'amount':
                $value = formatWithDelimiters($cents, 2);
                break;
            case 'amount_no_decimals':
                $value = formatWithDelimiters($cents, 0);
                break;
            case 'amount_with_comma_separator':
                $value = formatWithDelimiters($cents, 2, '.', ',');
                break;
            case 'amount_no_decimals_with_comma_separator':
                $value = formatWithDelimiters($cents, 0, '.', ',');
                break;
            default: {
                $value = formatWithDelimiters($cents, 2);
            }
        }
        return preg_replace($placeholderRegex, $value, $format);
    }
}

if ( !function_exists('mysql_escape'))
{
    function mysql_escape($inp)
    {
        if(is_array($inp)) return array_map(__METHOD__, $inp);

        if(!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }

        return $inp;
    }
}

if ( !function_exists('get_country'))
{
    function get_country($code)
    {
        $countries = config('myconfig.countries');
        return $countries[$code];
    }
}

if ( !function_exists('countUpper')){
    function countUpper($string) {
        return strlen(preg_replace('/[^A-Z]+/', '', $string));
    }
}

if ( !function_exists('countLower')){
    function countLower($string) {
        return strlen(preg_replace('/[^a-z]+/', '', $string));
    }
}

if ( !function_exists('convertIdToKey')){
    function convertIdToKey($arr) {
        $temp = [];
        foreach ($arr as $v) {
            $temp[$v['id']] = $v;
        }
        return $temp;
    }
}




if ( !function_exists('sca_trans')){
    function sca_trans($group, $item, array $replace = [], $locale = null) {
        $key = $group.'.'.$item;
        $content = trans($key, $replace, $locale);
        if($content == $key){
            $group = explode('.', $key)[0];
            $key = str_replace($group,'df',$key);
            $content = trans($key, $replace, $locale);
        }
        return $content;
    }
}

if ( !function_exists('get_commission_type_text')){
    function get_commission_type_text($type) {

        if( $type == 0 ) {
            return 'Flat Rate Per Order';
        }else if( $type == 1 ) {
            return 'Flat Rate Per Item';
        }else{
            return 'Percent Of Sale';
        }
    }
}

if ( !function_exists('get_commission_amount_text')){
    function get_commission_amount_text($type, $amount, $currency) {

        if( $type == 2 ) {
            return $amount.'%';
        }else{
            return currency_format($currency, $amount);
        }
    }
}

if ( !function_exists('getIdFromGid')){
    function getIdFromGid($gid) {
        $arr = explode("/",$gid);
        return end($arr);
    }
}

if ( !function_exists('collectionGid')){
    function collectionGid($id) {
        return 'gid://shopify/Collection/'.$id;
    }
}

if ( !function_exists('getSubdomainFromUrl')){
    function getSubdomainFromUrl($url) {
        $appUrl = preg_replace("#^[^:/.]*[:/]+#i", "",config('endpoint.main_domain'));
        $url = preg_replace("#^[^:/.]*[:/]+#i", "",$url);
        $subdomain = str_replace('.'.$appUrl, "", $url);
        return $subdomain;
    }
}

if ( !function_exists('getAffiliatePortalLink')){
    function getAffiliatePortalLink($subdomain) {
        $affiliatePortalUrl = 'https://'.$subdomain.'.'.config('endpoint.main_domain');
        return $affiliatePortalUrl;
    }
}
if(!function_exists('couponCodeFromAffiliateName'))
{
    function couponCodeFromAffiliateName($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

       foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }
        return strtoupper(preg_replace('/\s+/', '', $str)) ;
    }
}

function  genTextCommissionType($commissionType) {
    if ($commissionType == 1) {
      return "percent of sale";
    } else if ($commissionType == 2) {
      return "fixed amount per order";
    } else {
      return "fixed amount per item";
    }
  }
  function formatCommissionAmount($commissionType, $amount, $format) {
    if ($commissionType != 1) {
      return currency_format($format,$amount);
    } else {
      return $amount . '%';
    }
  }

if(!function_exists('synConfigUpdateRegistrationData'))
{

    function synConfigUpdateRegistrationData($data){
        $data = (array) $data;
        $dataConfig = config('myconfig.registration');
        foreach($dataConfig as $k=>$d) {
            if(!array_key_exists($k, $data )) {
                $data[$k] = $d;
            }
        }
        return $data;
    }
}

if(!function_exists('synConfigUpdateLoginData'))
{

    function synConfigUpdateLoginData($data){
        $data = json_decode($data, true);
        $dataConfig = config('myconfig.affiliatelogin');
        if($data) {
            foreach($dataConfig as $k=>$d) {
                if(!array_key_exists($k, $data )) {
                    $data[$k] = $d;
                }
            }
            return $data;
        }else{
            return $dataConfig;
        }

    }
}




