<?php

/**
 * Netatmo2Cosm
 * Requirements: Netatmo-API-PHP
 * 
 * @author Olivier Raggi <olivier@raggi.fr>.
 */

/**
 * Replace all YOUR_ variables with your own information
 */
 
$config = array();
$config['client_id'] = 'YOUR_NETATMO_CLIENT_ID';
$config['client_secret'] = 'YOUR_NETATMO_CLIENT_SECRET';
$username = "YOUR_NETATMO_USERNAME";
$pwd = "YOUR_NETATMO_PASSWORD";
$feed_id = array();
$feed_id['internal'] = 'YOUR_COSM_INTERNAL_FEED_ID'; // for Netatmo internal module
$feed_id['external'] = 'YOUR_COSM_EXTERNAL_FEED_ID'; // for Netatmo external module
$api_key = array("X-ApiKey: YOUR_COSM_API_KEY");

/**
 * No modification needed under this line
 */

date_default_timezone_set('UTC');

require_once("Netatmo-API-PHP/NAApiClient.php");

$client = new NAApiClient($config);
$client->setVariable("username", $username);
$client->setVariable("password", $pwd);
$helper = new NAApiHelper();

try {
  $tokens = $client->getAccessToken();        
} catch(NAClientException $ex) {
  echo "An error happend while trying to retrieve your netatmo tokens\n";
}

$devicelist = $client->api("devicelist", "POST");
$devicelist = $helper->SimplifyDeviceList($devicelist);

$mesures = $helper->GetLastMeasures($client,$devicelist);

$sensors = array('Temperature', 'Humidity');
foreach ($sensors as &$sensor) {
  $url = "http://api.cosm.com/v2/feeds/" . $feed_id['external'] . "/datastreams/" . $sensor . "/datapoints";
  $putString = '{"datapoints":[{"at":"' . date("Y-m-d\TH:i:s\Z", $mesures['0'] ['modules']['1']['time']) . '","value":"' . $mesures['0'] ['modules']['1']['' . $sensor . ''] . '"}]}';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $api_key);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $putString);

  $data = curl_exec($ch);
  curl_close($ch);
  echo $data;
}

$sensors = array('Temperature', 'CO2', 'Humidity', 'Pressure', 'Noise');
foreach ($sensors as &$sensor) {
  $url = "http://api.cosm.com/v2/feeds/" . $feed_id['internal'] . "/datastreams/" . $sensor . "/datapoints";
  $putString = '{"datapoints":[{"at":"' . date("Y-m-d\TH:i:s\Z", $mesures['0'] ['modules']['0']['time']) . '","value":"' . $mesures['0'] ['modules']['0']['' . $sensor .''] . '"}]}';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $api_key);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $putString);

  $data = curl_exec($ch);
  curl_close($ch);
  echo $data;
}

?>
