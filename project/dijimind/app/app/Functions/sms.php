<?php 
function sms($msj,$tel,$baslik="DIJIMIND") {
		$tel = str_replace("(","",$tel);
		$tel = str_replace(")","",$tel);
		$tel = str_replace(" ","",$tel);
		$tel = str_replace("-","",$tel);
		$tel = str_replace("+","",$tel);
		$msj = urlencode($msj);
		$tel = urlencode($tel);
		$result = file_get_contents("https://api.netgsm.com.tr/sms/send/get/?usercode=5435265313&password=DD6F-89&gsmno=$tel&message=$msj&msgheader=$baslik");
		return $result;
}


?>