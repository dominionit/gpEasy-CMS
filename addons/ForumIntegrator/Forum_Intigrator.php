<?php
/*
  Developer : Johannes Pretorius - Dominion IT
  Web : http://www.dominion-it.co.za
  License : GPL
  Note : Please leave the header when copying or chaning code. Thank you.
*/
class Forum_Integrator{
	function Forum_Integrator(){ 
	   if (!isset($_GET['cmd']))  {
	      if ((isset($_GET['action'])) && ($_GET['action'] == 'search')) {
		    $cmd = 'search.php';
		  } else if (isset($_GET['username']))  {
		    $cmd = 'userlist.php';
		  } else { 
		     $cmd = 'index.php';
		  }
		  
	   } else {
	    $cmd = common::GetCommand();
	    $cmd  = (!empty($cmd))?$cmd:'index.php';
	   }	
       if (strpos($cmd,'?') >= 1) {
	     $cmd = explode('?',$cmd);
		 $cmd = $cmd[0];
	   }
	  $param = '';
       if ((isset($_GET['cmd'])) && (strpos($_GET['cmd'],'?') >= 1)) {
	     $tmpAR = explode('?',$_GET['cmd']);
		 $_GET['cmd'] = $tmpAR[0];
		 $tmpWaarde = explode('=',$tmpAR[1]);
		 $_GET[$tmpWaarde[0]] = $tmpWaarde[1];
	   }

	  if ((isset($_GET)) && (count($_GET) > 1)) {
	    $param = '?';
	    foreach ($_GET as $key => $data) {
		  if ($key == 'cmd') {
		    continue;
		  }
		    $param .= $key.'='.$data.'&';
		}
	    
	  }
	  //GET the config of paths
	  $datapath = $this->getPluginDataPath();
	  $cfg = unserialize(file_get_contents($datapath));	  
	  //end
        $html =  $this->get_file($cfg['flux_url']."$cmd".$param,true);
        echo $this->replaceAnchorsWithPage($html,common::getUrl('Special_Forum_Integrator').'?cmd=',$cfg['flux_url'],$cmd);
	   
	}
	function replaceAnchorsWithPage($html,$baseURL,$forumURL,$cmd) {
	$regex = '/href=[\'"]+?.*?[\'"]/i'; //kry die skakel
    preg_match_all($regex, $html,$tmpArr,PREG_PATTERN_ORDER);
	foreach ($tmpArr[0] as $key => $data) {
	   $newData = $data;
	   if (strpos($newData,'.css'))  {
	     $newData = str_replace('href="','href="'.$forumURL,$newData);
	   } else {
	     $newData = str_replace('?','&',$newData);
	     $newData = str_replace('href="','href="'.$baseURL,$newData);
	   }	 
	     $arrReplace[$key] =  $newData;   
	   $data= str_replace('/','\/',$data);
	   $data= str_replace('?','\?',$data);
	   $regex = "/$data+?/i"; //kry die skakel
	   $html = preg_replace($regex,$newData,$html);
	}
	
		
		$regex = '/action=[\'"]+?.*?[\'"]/i'; //kry die skakel
		preg_match_all($regex, $html,$tmpArr,PREG_PATTERN_ORDER);
		$postDatPath = Forum_Integrator::getPostPathURL();
		foreach ($tmpArr[0] as $key => $data) {
		   $newData = $data;
		   if  (($cmd <> 'userlist.php') && ($cmd <> 'search.php') ) {
		     $newData = str_replace('?','&',$newData);
		     $newData = str_replace('action="','action="'.$postDatPath.'posthandler.php?file=',$newData); 
		   } else {
		     $newData = str_replace('?','&',$newData);
   		     $newData = str_replace('action="','action="'.$baseURL,$newData);
		   }	 
		   $arrReplace[$key] =  $newData;   
		   $data= str_replace('/','\/',$data);
		   $data= str_replace('?','\?',$data);
		   $regex = "/$data+?/i"; //kry die skakel
		   $html = preg_replace($regex,$newData,$html);
		}	
	  $html = str_replace('FluxBB</a>','FluxBB</a> / <a href="http://www.dominion-it.co.za" title="Powered by Dominion IT Forum Integrator">Forum Integrator</a>',$html);
	  
	return $html;
  }
  
	function get_file($remote, $addQuery = false,$use_ssl=false){
		/* get hostname and path of the remote file */
		$host = parse_url($remote, PHP_URL_HOST);
		if ($addQuery == true) { 
		  $path = parse_url($remote, PHP_URL_PATH )."?".parse_url($remote, PHP_URL_QUERY  );
		} else {
		  $path = parse_url($remote, PHP_URL_PATH );
		}	
		
		/* prepare request headers */
		$reqhead = "GET $path HTTP/1.1\r\n"
				 . "Host: $host\r\n";
		if (isset($_SERVER['HTTP_COOKIE'])) {
		  $reqhead .= "Cookie: ".$_SERVER['HTTP_COOKIE']."\r\n";
		}	
		$reqhead .= "Connection: Close\r\n\r\n";
		/* open socket connection to remote host on port 80 */
		if ($use_ssl == true) {
			 $sslhost = "ssl://".$host;
		   $fp = fsockopen($sslhost, 443, $errno, $errmsg, 30);
		} else { 
		  $fp = fsockopen($host, 80, $errno, $errmsg, 30);
		}  
		
		/* check the connection */
		if (!$fp) {
			error_log("Cannot connect to $host!\n");
			return false;
		}
		
		/* send request */
		fwrite($fp, $reqhead);
		/* read response */
		$res = "";
		while(!feof($fp)) {
			$res .= fgets($fp, 4096);
		}		
		fclose($fp);
		
		/* separate header and body */
		$neck = strpos($res, "\r\n\r\n");
		$head = substr($res, 0, $neck);
		$body = substr($res, $neck+4);

		/* check HTTP status */
		$lines = explode("\r\n", $head);
		preg_match('/HTTP\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $lines[0], $m);
		$status = $m[2];

		if ($status == 200) {
			return $body;
		} else {
			return false;
		}
	  }  
	function getPluginDataPath(){
		  $pluginPathStr = str_replace('\\','/',dirname(__FILE__));
		  $pluginPath = explode('/',$pluginPathStr);
		  $pluginid = $pluginPath[count($pluginPath)-1];
		  $datapath = '';
		  for ($x =0; $x <count($pluginPath)-2; $x ++) {
			$datapath .= $pluginPath[$x].'/';
		  }
		  $datapath .= '_addondata/'.$pluginid.'/forum.cfg';
		  return $datapath;
	} 
	
	function getPostPathURL(){
	  if (strpos($_SERVER['SCRIPT_NAME'],'posthandler.php') >= 1) {
	    return '';
	  }
	  $pluginPathStr = str_replace('\\','/',dirname(__FILE__));
	  $pluginPath = explode('/',$pluginPathStr);
	  $pluginid = $pluginPath[count($pluginPath)-1];
	  $datapath = '../';
	  $startlogging = 0;
	  for ($x =0; $x <count($pluginPath); $x ++) {
	    if  ($pluginPath[$x] == 'data') {
		  $startlogging = 1;
		}
		if ($startlogging ==1) {
		  $datapath .= $pluginPath[$x].'/';
		}
	  }
	  return $datapath;
	}
 
}
?>