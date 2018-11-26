<?php

# Set required PHP settings
date_default_timezone_set('UTC');
set_time_limit(0);

/***** Configuration File *****/
require 'vendor/autoload.php';

/***** Including Cpanel XML Api *****/
require_once "xmlapi.php";

/***** Including Configuration file *****/
require_once "conf.php";

$currentDate = time();
$backupDir = date("Y-m-d", $currentDate);

$fResult = array();
$rFail = array();

$backupDir = $ftpPath . $backupDir;

//Connect to FTP
$ftpConnection = ftp_connect($ftpHost);
$ftpLogin = ftp_login($ftpConnection, $ftpAcct, $ftpPass);

ftp_pasv($ftpConnection, true);

//Create Directory for storing backup
$createFtpDirectory = ftp_mkdir($ftpConnection, $backupDir);

ftp_close($ftpConnection);

function doBackup($whmServerIp, $cpanelServerIp, $whmServerPort, $cpanelServerPort, $whmAccount, $whmPassword){
	global $ftpHost;
	global $ftpAcct;
	global $ftpPass;
	global $ftpPort;
	global $ftpPath;
	global $backupDir;
	global $rFail;
	global $fResult;
	$xmlapi = new xmlapi($whmServerIp);
	$xmlapi->password_auth($whmAccount, $whmPassword);
	$xmlapi->set_port($whmServerPort);
	$xmlapi->set_output('json'); //Convert the output to a json

	$listAccounts = $xmlapi->listaccts();

	$listAccounts = json_decode($listAccounts, true);

	if($listAccounts['status'] == '1')
	{
	/***** Store accounts name in an array *****/
	$accounts = array();
	foreach ($listAccounts['acct'] as $account)
	{
		$accounts[] = $account['user'];
		//echo $account['user'] . "\n"; //TEST - Enable to test storing accounts in an array
		$fResult[$whmAccount]['accts'][$account['user']] = array();
	}

	/***** Initiate The Backup *****/
	$apiArgs = array();
	foreach($accounts as $cPanelAccount)
	{
		$apiArgs = array(
			'passiveftp',
			$ftpHost,
			$ftpAcct,
			$ftpPass,
			"",
			$ftpPort,
			$backupDir
		);
		
		$result = $xmlapi->api1_query($cPanelAccount, 'Fileman', 'fullbackup', $apiArgs);
		$result = json_decode($result, true);
		
		if ($result['event']['result']) {
			$fResult[$whmAccount]['accts'][$cPanelAccount]['status'] = 'success';
			$fResult[$whmAccount]['accts'][$cPanelAccount]['msg'] = '';
		}
		else {
			$fResult[$whmAccount]['accts'][$cPanelAccount]['status'] = 'failure';
			$fResult[$whmAccount]['accts'][$cPanelAccount]['msg'] = $result;
			$rFail[] = $cPanelAccount . " -> " . $whmAccount;
		}

#		break; //TEST - Enable to test one account
		sleep(600); //Pause
	}
}
}

require_once "conf_example.php";
# require_once "conf_name.php";

print_r ($fResult);

if ($enableEmail == true){
  $message = '<html><body>';

  $message .= 'Backups have been completed and files have been transferred to ' . $ftpHost . "<br><br>";
  $message .= 'Backups directory: ' . $backupDir . "<br><br>";

  $message .= "Backup Failed for: <br>";

  foreach ($rFail as $account) {
      $message .= $account . "<br>";
  }
  $message .= "<br><br>";

  $message .= "Full log: <br><br>";
  $message .= "<pre>". print_r($fResult, true) . "</pre>";
  $message .= '</body></html>';
  print_r($rFail);



  $from = new SendGrid\Email($Sname, $Semail);
  $subject = "Backup Process complete";
  $to = new SendGrid\Email($Rname, $Remail);

  $content = new SendGrid\Content("text/html", $message);

  $mail = new SendGrid\Mail($from, $subject, $to, $content);

  $sg = new \SendGrid($apiKey);

  $response = $sg->client->mail()->send()->post($mail);
  echo $response->statusCode();
  print_r($response->headers());
  echo $response->body();

}


?>
