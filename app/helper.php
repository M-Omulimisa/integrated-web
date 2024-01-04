<?php  

function changeDateFormate($date,$date_format){

    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);    
}

function decryptData($data, $key) {
  return openssl_decrypt($data, config('app.cipher_2'), $key);
}

function encryptData($data, $key) {
    //key is type column
    return openssl_encrypt($data, config('app.cipher_2'), $key);
}

function DT_lengthMenu()
{
	return [ 25, 50, 100, 250, 500 ];
}

/**
 * @param $modelObject
 * @param string $attributeName
 * @return null|string|string[]
 */
function getDateColumn($modelObject, $attributeName = 'updated_at')
{
    if (config('settings.app.is_human_date_format')) {
        $html = '<p data-toggle="tooltip" data-placement="bottom" title="${date}">${dateHuman}</p>';
    } else {
        $html = '<p data-toggle="tooltip" data-placement="bottom" title="${dateHuman}">${date}</p>';
    }
    if (!isset($modelObject[$attributeName])) {
        return '';
    }
    $dateObj = new Carbon\Carbon($modelObject[$attributeName]);
    $replace = preg_replace('/\$\{date\}/', $dateObj->format(config('settings.app.date_format')), $html);
    $replace = preg_replace('/\$\{dateHuman\}/', $dateObj->diffForHumans(), $replace);
    return $replace;
}

function routeName($group_name)
{
    return str_replace('_', '-', $group_name);
}

function trimToLength($string, $length)
{
    return strlen($string) > $length ? substr($string, 0, $length)."..." : $string;
}

function removeStr($subject, $string)
{
    return str_replace($string, ' ', $subject);
}

function file_type($file_path)
{
    if (! is_null($file_path)) {
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);

        if ($ext == 'doc' || $ext == 'docx') {
            return 'msword';
        }
        elseif ($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv' || $ext == 'xlx') {
            return 'msexcel';
        }
        elseif ($ext == 'pdf') {
            return 'pdf';
        }
        elseif ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
            return 'image';
        }
    }

    return null;    
}

function file_content_type($file_path)
{
    if (! is_null($file_path)) {
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);

        if ($ext == 'doc' || $ext == 'docx') {
            return 'msword';
        }
        elseif ($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv' || $ext == 'xlx') {
            return 'application/vnd.ms-excel';
        }
        elseif ($ext == 'pdf') {
            return 'application/pdf';
        }
        elseif ($ext == 'jpg') {
            return 'image/jpg';
        }
        elseif ($ext == 'jpeg') {
            return 'image/jpeg';
        }
        elseif ($ext == 'png') {
            return 'image/png';
        }
    }

    return null;    
}

function generate_random_str($from, $to, $length)
{
   $pool = array_merge(range($from,$to), range('a', 'z'),range('A', 'Z'));

  $key="";

  for($i=0; $i < $length; $i++) {
      $key .= $pool[mt_rand(0, count($pool) - 1)];
  }
  return $key;
}

function getProviderCode($phone)
{
    //mtn 
    $mtn = preg_match("#^(25677|25678|25676)(.*)$#i", $phone);
    if ($mtn > 0) {
        return 'MTN_UGANDA';
    }
    //airtel
    $airtel = 0;
    $airtel = preg_match("#^(25675|25670|25674)(.*)$#i", $phone);
    if ($airtel > 0) {
        return 'AIRTEL_UGANDA';
    }
    //africel
    $africel = 0;
    $africel = preg_match("#^(25679)(.*)$#i", $phone);
    if ($africel > 0) {
        return 'AFRICEL_UGANDA';
    }
    
    return 'UNKNOWN SERVICE PROVIDER';
}

function reportNaming($type, $reference){
    return config('app.name')."_".$type."_report_".$reference."".date('dmYHis').".pdf";                
}

/**
 * Checks if response from ECW is a blank response
 * 
 * @param String $response The XML response from ECW
 * @return Number       Count of the response contents
 */
function isBlankResponse($response)
{
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($response);

    //Log::info(['xmlCount' => $xml->count()]);

    return $xml->count();
}

/**
 * Determines if the xml response sent from ECW is is a valid 
 * parseable response by interpreting the request string of type XML 
 * into an object and checking for errors
 * 
 * @return boolean     FALSE if it is not XML rather HTML or empty or if there are any errors 
 *                     TRUE if its a valid xml and parseable.
 */
function isValidXML($content)
{
    $content = trim($content);
    if (empty($content)) {
        return false;
    }

    if (stripos($content, '<!DOCTYPE html>') !== false) {
        return false;
    }

    libxml_use_internal_errors(true);
    simplexml_load_string($content);
    $errors = libxml_get_errors();          
    libxml_clear_errors(); 

    //Log::info(['xmlValidation' => empty($errors)]); 

    return empty($errors);
}

/**
 * Enable libxml errors and allow user to fetch error information as needed 
 * Initiate with No Response
 * Check if XML string is well-formed
 * Interpret the request string of XML into an object if string has no erros
 * Set the Response to converted object
 * 
 * @throws XMLException catch error XML string is not well formed 
 * 
 * @return  Response
 */

function processXML($raw_XML, $namespace=null, $registerXPath=null, $xpath=null){

    libxml_use_internal_errors(true);
    $response = false;

    try {
        $xml_response = simplexml_load_string($raw_XML);
        if(!is_null($namespace) && !is_null($registerXPath)) $xml_response->registerXPathNamespace($namespace, $registerXPath);
        if(!is_null($xpath)) $xml_response = $xml_response->xpath($xpath);
        $response     = $xml_response;            
    } catch (Exception $ex) {
        $error_message = 'An Exception was caught by the system';
        foreach (libxml_get_errors as $error_line)
        {
            $error_message.="\t".$error_line->message;
        }
        trigger_error($error_message);
    }
    return $response;
}

function days_to_ymd($days_to_convert)
{
    $years = ($days_to_convert / 365) ; // days / 365 days
    $years = floor($years); // Remove all decimals

    $month = $days_to_convert == 30 ? 1 : ($days_to_convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
    $month = floor($month); // Remove all decimals

    $days = ($days_to_convert % 365) % 30.5; // the rest of days

    $years = $years > 0 ? $years.($years == 1 ? ' Year ' : ' Years ') : '';
    $month = $month > 0 ? $month.($month == 1 ? ' Month ' : ' Months ') : '';
    $days = $days > 0 ? $days.($days == 1 ? ' Day ' : ' Days ') : '';

    // Echo all information set
    return $years.$month.$days;
}

function months_to_ym($months)
{
    if ($months > 0) {
        $years = floor($months/12);
        $months = $months%12;
    }else{
        $years = 0;
        $months = 0;
    }

    $years = $years > 0 ? $years. ' Yrs' : '';
    $months = $months > 0 ? $months. ' Mths' : '';

    return $years.' '.$months;
}

function harshLastChars($identifier, $harshChars=5, $lastChars=2)
{
  return substr($identifier, 0, -($harshChars+$lastChars)).str_repeat('*', $harshChars).''.substr($identifier, -($lastChars));
}

function maskEmail($email, $minLength = 3, $maxLength = 10, $mask = "***") {
    $atPos = strrpos($email, "@");
    $name = substr($email, 0, $atPos);
    $len = strlen($name);
    $domain = substr($email, $atPos);

    if (($len / 2) < $maxLength) $maxLength = ($len / 2);

    $shortenedEmail = (($len > $minLength) ? substr($name, 0, $maxLength) : "");
    return  "{$shortenedEmail}{$mask}{$domain}";
}

function generatePassword($from, $to, $length)
{
    $pool = array_merge(range($from, $to) , range('a', 'z') , range('A', 'Z'));

    $key = "";

    for ($i = 0;$i < $length;$i++)
    {
        $key .= $pool[mt_rand(0, count($pool) - 1) ];
    }
    return $key;
}

function generateCode($length)
{
    $code = '';
    for ($i = 0;$i < $length;$i++)
    {
        $code .= mt_rand(1, 9);
    }
    return $code;
}

function generateRandomString($from, $to, $length, $letters = false, $numbers = false)
{
    if ($letters)
    {
        $pool = array_merge(range('a', 'z') , range('A', 'Z'));
    }
    elseif ($numbers)
    {
        $pool = array_merge(range($from, $to));
    }
    else
    {
        $pool = array_merge(range($from, $to) , range('a', 'z') , range('A', 'Z'));
    }

    $key = "";

    for ($i = 0;$i < $length;$i++)
    {
        $key .= $pool[mt_rand(0, count($pool) - 1) ];
    }
    return $key;
}

function getSubscritionEndDate($frequency, $period, $start_date)
{    
    if ($frequency == 'Weekly' || $frequency == 'Trial') {
        $days       = 7 * $period;
        $end_date   = date("Y-m-d",strtotime($start_date. "+ ".$days." days"));
    }
    elseif ($frequency == 'Monthly') {
        $days       = 30 * $period;
        $end_date   = date("Y-m-d",strtotime($start_date. "+ ".$days." days"));
    }
    elseif ($frequency == 'Yearly') {
        $days       = 365 * $period;
        $end_date   = date("Y-m-d",strtotime($start_date. "+ ".$days." days"));
    }

    return $end_date;
}

function numOfMarketMessages($frequency, $period)
{
    if ($frequency == "Weekly" || $frequency == "Trial") {
        $messages = 1;
    }
    elseif ($frequency == "Monthly") {
        $messages = 4;
    }
    elseif ($frequency == "Yearly") {
        $messages = 48;
    }

    return $messages * $period;
}
