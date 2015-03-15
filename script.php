<?

//Steam ID confuckulator, made for PHP by Rowedahelicon
//Translates all steam id types into the 64BIT 7656XXXXXXXXXXXXXXX version.

function steam3($steam2) {
    $id = explode(':', $steam2);
    $id3 = ($id[2] * 2) + $id[1];
    return '[U:1:' . $id3 . ']';
}

function steamGrabber($steam_id)
{

//EDIT THIS
$api_key = "";
//DON'T EDIT THE REST

$steam_id = htmlspecialchars($steam_id,ENT_NOQUOTES);
$steam_id = preg_replace('/\s+/', '', $steam_id);

//First let's find what kind of STEAM ID it is.

$id_result = substr($steam_id, 0,4);
switch($id_result){

//Scenario 1 : Using a Steam 32 Bit ID

case STEA:
$split = explode(":", $steam_id); // STEAM_?:?:??????? format
$x = substr($split[0], 6, 1);
$y = $split[1];
$z = $split[2];
$result = ($z * 2) + 0x0110000100000000 + $y;
break;

//Scenario 2 : Using a Steam 64 Bit ID

case 7656:
$result = $steam_id;
break;

//Scenario 3 : Using a vanity URL

case http:
$find_1 = strpos("$steam_id", "//steamcommunity.com/id/"); //Math mode
$find_2 = strpos("$steam_id", "//steamcommunity.com/profiles/"); //Easy mode

if($find_1 >= 1){ 

$xmlstring = "$steam_id?xml=1";
$xml = simplexml_load_file($xmlstring);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$result = $array[steamID64];

}
if($find_2 >= 1){ $result = (explode('/',$steam_id));
$result = $result[4];

 }
break;

//Scenario 4 : Using the Steam3 ID

case "U:1:":
//echo $steam_id;
$steam_id = explode(":", $steam_id);
$result = $steam_id[2] + 0x0110000100000000;

break;

//End Scenario : Attempt to get it from steam community directly or no possible retrieval 

default:

//Using a vanity name will require some extra work, and to help on performance, you can cache the json bits of need be!

define("MAX_CACHE_LIFETIME", 60 * 60); //1 hour

$localJSONCache = "resolve_$steam_id.json.cache";

  $steam_profile_vanity = null;
    if (file_exists($localJSONCache)) {
        if (time() - filemtime($localJSONCache) < MAX_CACHE_LIFETIME) {
            $steam_profile_vanity = @file_get_contents($localJSONCache);
        }
    }
    if (empty($steam_profile_vanity)) {
	$steam_profile_info=@file_get_contents("http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=$api_key&vanityurl=$steam_id");
	file_put_contents($localJSONCache, $steam_profile_vanity);
	}
	$json_profiles=json_decode($steam_profile_vanity);
	$success_key = $json_profiles->response->success;
	if($success_key == 1){ $result = $json_profiles->response->steamid; }else{ $result = null; } //Bad result, meaning the vanity name isn't a real steam id.



}
return $result;
}

?>
