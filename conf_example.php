<?php

/***** Credentials for WHM/cPanel account *****/

$whmServerIp = ""; // Server IP or domain name eg: 212.122.3.77 or cpanel.domain.tld
$cpanelServerIp = ""; // Server IP or domain name eg: 212.122.3.77 or cpanel.domain.tld
$whmServerPort = "2087"; // Server Port - Usually 2086 or 2087 for WHM
$cpanelServerPort = "2083"; // Server Port - Usually 2082 or 2083 for Cpanel
$whmAccount = ""; // WHM username
$whmPassword = ""; // WHM password

doBackup($whmServerIp, $cpanelServerIp, $whmServerPort, $cpanelServerPort, $whmAccount, $whmPassword);

?>
