<?php 

/*
*  Configuration file 
*  by @jetchirag
*/

/***** Credentials for FTP remote site *****/
$ftpHost = ""; // FTP host IP or domain name
$ftpAcct = ""; // FTP account
$ftpPass = ""; // FTP password
$ftpPort = ""; // FTP port - usually 21
$ftpPath = "/"; // The directory on the remote machine that will store the new backup

/***** Email Settings ******/
$enableEmail = true; // Only supports sendgrid as of now, if you don't want to use it, you can simply pipe its output to mail

$Sname = "User"; //Sender's name
$Semail = "user@gmail.com"; //Sender's mail id


$Rname = "User"; //Receiver's name
$Remail = "user@gmail.com"; //Receiver's mail id
$apiKey = "" // Sendgrid API KEY

?>
