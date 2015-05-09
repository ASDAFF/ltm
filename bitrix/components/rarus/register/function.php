<?php 
if(!function_exists ("cutPhone"))
{
	function cutPhone($phone)
	{
		$newPhone = preg_replace("/[+()\t\n\r\f\v\s-]/", "", $phone);
		if($newPhone)
		{
			$newPhone = "+" . $newPhone;
		}
	
		return $newPhone;
	}
}

if(!function_exists ("getPriorityAreas"))
{
	function getPriorityAreas($areas)
	{
		if(empty($areas))
		{
			return array();
		}
		else
		{
			return $areas;
		}
	}
}

if(!function_exists ("strcode"))
{
	function strcode($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 16 : 4;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
}

if(!function_exists ("GetListFiles"))
{
	function GetListFiles($folder,&$all_files){
		$fp=opendir($folder);
		while($cv_file=readdir($fp)) {
			if(is_file($folder."/".$cv_file)) {
				$all_files[]=$folder."/".$cv_file;
			}elseif($cv_file!="." && $cv_file!=".." && is_dir($folder."/".$cv_file)){
				GetListFiles($folder."/".$cv_file,$all_files);
			}
		}
		closedir($fp);
	}
}

if(!function_exists ("delTreeDir"))
{
	function delTreeDir($dir) { 
	   $files = array_diff(scandir($dir), array('.','..')); 
	    foreach ($files as $file) { 
	      (is_dir("$dir/$file")) ? delTreeDir("$dir/$file") : unlink("$dir/$file"); 
	    } 
	    return rmdir($dir); 
	}
  
}


?>