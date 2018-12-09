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


ignore_user_abort(TRUE);//prevent the script from aborting
set_time_limit(0);//delete max time limit
$email = $argv[1];
$name = substr($argv[2], 0, -4);


shell_exec('python ./hub-master/examples/image_retraining/retrain.py --image_dir ' . $name .'/ --learning_rate=0.0001 --testing_percentage=20 --validation_percentage=20 --train_batch_size=16 --validation_vatch_size=-1 --flip_left_right True --random_scale=30 --eval_step_interval=100 --how_many_training_steps=300 --architectrue mobilenet_1.0_224');
// shell_exec('python hello.py');
// shell_exec('python change_name.py');

python retrain.py --image_dir cats_and_dogs/ --learning_rate=0.0001 --testing_percentage=20 --validation_percentage=20 --train_batch_size=2 --validation_vatch_size=-1 --flip_left_right True --random_scale=30 --eval_step_interval=100 --how_many_training_steps=30--architectrue mobilenet_1.0_224


$myfile = fopen("newresult.txt","w");
fwrite($myfile, $email);
fwrite($myfile, $name);


function sendEmail($email){
	$client = getClient();
	$service = new Google_Service_Gmail($client);

	$mail = new PHPMailer; //$mail->SMTPDebug = 3;                               // Enable verbose debug output 
	$mail->isSMTP(); // Set mailer to use SMTP 
	$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers. 
	$mail->SMTPAuth = true; // Enable SMTP authentication 
	$mail->Username = ''; // SMTP username 
	$mail->Password = ''; // SMTP password  
	$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted 
	$mail->Port = 587; // TCP port to connect to 
	$mail->setFrom('', 'Mailer'); 
	$mail->addAddress($email, 'user'); // Add a recipient 
	$mail->addAttachment('newresult.txt'); // Add attachments 
	$mail->isHTML(true); // Set email format to HTML 
	$mail->Subject = 'Here is the subject'; 
	$mail->Body = 'This is the HTML message body <b>in bold!</b>'; 
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; 

	if(!$mail->send()) { 
	    // echo 'Message could not be sent.'; 
	    // echo 'Mailer Error: ' . $mail->ErrorInfo; 
	    //$myfile = fopen("newresult.txt","w");
		//fwrite($myfile, $mail->ErrorInfo);

	} else { 
	    echo 'Message has been sent\n'; 
	}

}

sendEmail($email);

fclose($myfile);

?>