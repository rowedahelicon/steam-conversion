function steamGrabber($steam_id)
{
//First we check what kind of ID it is
$id_result = substr($steam_id, 0,4);
switch($id_result){
case STEA:
$split = explode(":", $steam_id); // STEAM_?:?:??????? format

$x = substr($split[0], 6, 1);
$y = $split[1];
$z = $split[2];

$result = ($z * 2) + 0x0110000100000000 + $y;
break;
case 7656:
$steam_id = ($steam_id - 76561197960265728) / 2;
if(substr($steam_id, -2) == ".5"){ $steam_id = round($steam_id, 0, PHP_ROUND_HALF_DOWN); $result = "STEAM_0:1:$steam_id"; }else{ $result = "STEAM_0:0:$steam_id"; }
break;
default:
}
return $result;
}