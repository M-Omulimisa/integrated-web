<?php
namespace App\Helpers;

use Log;
use File;
use DateTime;
use Carbon\Carbon;
use App\Api\v1\MtnPay;
// use App\Api\v1\DmarkSms;
use App\Api\v2\DmarkSms;
use App\Models\Agent\AgentInfo;
use App\Models\Mail\SystemMailLog;
use App\Models\Settings\Subcounty;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Api\v1\AfricasTalkingGateway;
use App\Api\v1\App\Africastalking\AfricasTalkingGatewayException;

class AppHelper

  {
  protected $sms_username;
  protected $sms_api_key;
  protected $default_sms;
  protected $default_recipient;
  /**
   * Create a new controller instance.
   */
  public function __construct()
    {
    $this->sms_username = "dninsiima";
    $this->sms_api_key = "bdb9c65b240e839716243d722a63c17ec40f088b824151b115ce8461e989bb11";
    $this->default_sms = "TEST SMS";
    $this->default_recipient = "256775666852";
    }

  public function getFileIcon($filename)
    {
    $extension = File::extension($filename);
    if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx')
      {
      return "os-icon os-icon-ui-51";
      }
    elseif ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')
      {
      return "os-icon os-icon-documents-07";
      }
    }

  public static function instance()
    {
    return new AppHelper();
    }

  public function createLog($data, $file)
    {
      Log::useFiles(base_path() . '/public/logs/' . $file . '.log');
      Log::info(['Request' => $data]);
    }

  public function generateAgentCode()
    {
    $excodes = new AgentInfo;
    do
      {
      $code = sprintf('%04d', mt_rand(1000, 9999));
      }

    while (!is_null($excodes->where('code', '=', $code)->first()));
    return $code;
    }

  public function sendTextMessage($recipients = null, $message = null)
    {
    if ($recipients == null)
      {
      $recipients = $this->default_recipient;
      }

    if ($message == null)
      {
      $message = $this->default_sms;
      $recipients = $this->default_recipient;
      }

    //Dmark API
    $dmark = new DmarkSms(new MtnPay);
    $result = $dmark->sendMessage(formatPhoneNumber($recipients, '256', '0'), $message);
    return $result;


    //AfricaIsTalking API
    $username = $this->sms_username;
    $apikey = $this->sms_api_key;
    $gateway = new AfricasTalkingGateway($username, $apikey);
    try
      {
        $results = $gateway->sendMessage($recipients, $message);
      }

    catch(AfricasTalkingGatewayException $e)
      {
        $results[] = null;
      }

      return $results;
    }

  public function sendEmail($to, $subject, $body)
    {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try
      {
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->Host = env('MAIL_HOST'); // Specify main and backup SMTP servers
        $mail->SMTPAuth = env('MAIL_AUTH'); // Enable SMTP authentication
        $mail->Username = env('MAIL_FROM_ADDRESS'); // SMTP username
        $mail->Password = env('MAIL_PASSWORD'); // SMTP password
        $mail->SMTPSecure = env('MAIL_ENCRYPTION'); // Enable TLS encryption, `ssl` also accepted
        $mail->Port = env('MAIL_PORT');

        $mail->SMTPOptions = array(
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );

        // Recipients

        $mail->setFrom(env('MAIL_FROM_ADDRESS') , env('MAIL_FROM_NAME'));
        $mail->addAddress($to);

        // Content

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        // \Log::info([$mail]);

        $mail->send();
        $result = 'Sent';
        $this->saveMail($to, $subject, $result);
        return $result;
      }

    catch(Exception $e)
      {
        $result = 'Not Sent';
        $this->saveMail($to, $subject, $result);
        $result = 'Mailer Error: ' . $mail->ErrorInfo;
        // \Log::info([$result]);
        return $result;
      }
    }

  public function saveMail($recipient, $subject, $response)
    {
    SystemMailLog::create(['recipient' => $recipient, 'subject' => $subject, 'status' => $response]);
    }

  public function expireByHour($time, $format = '')
    {
    $expiry = $time->addHour();
    if ($format == '12Hrs')
      {
      $to_12hrs = new DateTime($expiry);
      return $to_12hrs->format('h:i:s A');
      }

    return $expiry;
    }

  public function isCodeExpired($start_date, $end_date)
    {
    $end = Carbon::parse($end_date);
    $now = Carbon::now();
    return $end > $now ? false : true;
    }

  public function subcountyList()
    {
    $subcounty = Subcounty::get();
    if (count($subcounty) > 0)
      {
      foreach($subcounty as $value)
        {
        $subcountys[$value->id] = $value->name . ' | ' . $value->district->name;
        }
      }
      else
      {
      $subcountys = $subcounty;
      }

    return $subcountys;
    }
  }
