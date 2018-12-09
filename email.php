<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Gmail($client);

$mail = new PHPMailer; //$mail->SMTPDebug = 3;                               // Enable verbose debug output 
$mail->isSMTP(); // Set mailer to use SMTP 
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers. 这里改成smtp.gmail.com 
$mail->SMTPAuth = true; // Enable SMTP authentication 
$mail->Username = 'your email address'; // SMTP username 这里改成自己的gmail邮箱，最好新注册一个，因为后期设置会导致安全性降低 
$mail->Password = 'your password'; // SMTP password 这里改成对应邮箱密码 
$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 587; // TCP port to connect to 
$mail->setFrom('your email', 'Mailer'); 
$mail->addAddress('destination email address', 'user'); // Add a recipient 这里改成用于接收邮件的测试邮箱 
$mail->addAttachment('/home/ece-student/Downloads/spo5q1n66gg11.jpg'); // Add attachments 
$mail->isHTML(true); // Set email format to HTML 
$mail->Subject = 'Here is the subject'; 
$mail->Body = 'This is the HTML message body <b>in bold!</b>'; 
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; 
if(!$mail->send()) { 
    echo 'Message could not be sent.'; 
    echo 'Mailer Error: ' . $mail->ErrorInfo; 
} else { 
    echo 'Message has been sent\n'; 
}


?>