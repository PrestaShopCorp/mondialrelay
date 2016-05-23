<?php
require_once('lib/nusoap.php');
// Global Settings definition
// Définition des paramètres globaux
$MR_WebSiteId = "BDTEST13";
$MR_WebSiteKey = "PrivateK";
$client = new nusoap_client("http://api.mondialrelay.com/Web_Services.asmx?WSDL", true);
$client->soap_defencoding = 'utf-8';
// We define the parameters as a string array. Each Key/Val represents a parameter of the soap call
// On défini les paramètres dans un tableau de chaînes. Chaque paire Clé/Valeur est un paramètre de l'appel
$params = array(
'Enseigne' => $MR_WebSiteId,
 'Pays' => "FR",
 //'NumPointRelais' => "",
 'Ville' => "LYON",
 'CP' => "69008",
 'Latitude' => "",
 'Longitude' => "",
 'Taille' => "",
 'Poids' => "70000",
 'Action' => "",
 'DelaiEnvoi' => "0",
 'RayonRecherche' => "20",
 //'TypeActivite' => "",
 //'NACE' => "",
);
// We generate the request's security code
// On génère la clé de sécurité de l'appel
$code = implode("", $params);
$ip_address = array("78.192.80.40", "127.0.0.1", "::1");
$ip_key = array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? 'HTTP_X_FORWARDED_FOR' : 'REMOTE_ADDR';
if(in_array($_SERVER[$ip_key], $ip_address)){
    echo "<pre>";
    var_dump($code);
    var_dump($MR_WebSiteKey);
    echo "</pre>";
    die;
}
$code .= $MR_WebSiteKey;
$params["Security"] = strtoupper(md5($code));
// We make the call and load it in the $result var
// On réalise l'appel et stocke le résultat dans la variable $result
$result = $client->call(
 'WSI3_PointRelais_Recherche',
 $params,
 'http://api.mondialrelay.com/',
 'http://api.mondialrelay.com/WSI3_PointRelais_Recherche'
);// We check their is no error during the process
// On vérifie qu'il n'y a pas eu d'erreur
if ($client->fault)
{
echo '<h2>Fault (Expect - The request contains an invalid SOAP body)</h2><pre>';
print_r($result);
echo '</pre>';
}
else
{
$err = $client->getError();
if ($err) { echo '<h2>Error</h2><pre>' . $err . '</pre>'; }
else
{
echo '<h2>Result</h2><pre>';
print_r($result);
echo '</pre>';
}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
?> 