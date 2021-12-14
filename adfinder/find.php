<?php

error_reporting(0);
include 'config.php';

function banner(){
	
	print('Admin Finder');
}

banner();
print "\n target : ";
$target = trim(fgets(STDIN));
$list = 'list/word.txt';

$buka = fopen("$list","r");
$ukuran = filesize("$list");
$baca = fread($buka,$ukuran);
$lists = explode("\n",$baca);

banner();
foreach ($lists as $dir){
	request($target, $dir);
}

function request($target, $dir){
	include 'config.php';
	$log = "$target/$dir";
	$ch = curl_init("$log");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if (!file_exists("result")){
		mkdir("result");
	}
	if($httpcode == 200){
		$handle = fopen("result/$target.txt", "a+");
		fwrite($handle, "$log\n");
		print "\n$okegreen [+]$white $log$okegreen OK";
	} elseif($httpcode == 403){
		print "\n$yellow [-]$white $log$yellow FORBIDDEN";
	} elseif($httpcode == 302){
		print "\n$yellow [?]$white $log$yellow REDIRECTED";
	} else {
		print "\n$red [x]$white $log$red ERROR";
	}
}

?>