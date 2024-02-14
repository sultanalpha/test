<?php
require_once 'jwt/vendor/autoload.php';

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;

AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$clientHints = ClientHints::factory($_SERVER); 
// $userAgent = "Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36";
$dd = new DeviceDetector($userAgent, $clientHints);
// echo json_encode($clientHints);
$dd->parse();
if ($dd->isBot()) {
  $botInfo = $dd->getBot();
} else {
  $clientInfo = $dd->getClient(); 
  $osInfo = $dd->getOs();
  $device = $dd->getDeviceName();
  $brand = $dd->getBrandName();
  $model = $dd->getModel();
  echo json_encode($clientInfo);
  echo "<br><br>";
  echo json_encode($osInfo);
  echo "<br><br><br><br> Device type is: $device <br>";
  echo "<br> Device brand is: $brand <br>";
  echo "<br> Device model is: $model <br>";
}
?>