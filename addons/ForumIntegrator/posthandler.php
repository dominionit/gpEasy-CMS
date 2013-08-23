<?php
/*
  Developer : Johannes Pretorius - Dominion IT
  Web : http://www.dominion-it.co.za
  License : GPL
  Note : Please leave the header when copying or chaning code. Thank you.
*/
  
	  $datapath = getPluginDataPath();
	  
	  $cfg = unserialize(file_get_contents($datapath));

      $cmd = isset($_GET['file'])?$_GET['file']:'';
	  if ($cmd == '') {
	    header('Location:'.$cfg['gpeasy_url'].'?cmd='.'index.php');	//http://dummysite.co.za/gpEasy_CMS/index.php/Special_Forum_Intigrator?cmd=
		return;
	  }
	  
	  ob_start();  
      register_shutdown_function('shutdown');	
	  $_SERVER['HTTP_REFERER'] = $cfg['flux_url'].$cmd; // 'http://dummysite.co.za/fluxbb-1.4.8/'


	  $pathtoGPE_root = str_replace('\\','/',dirname(__FILE__).'/../../../');
	  $fluxPathfromGPE_Root= $pathtoGPE_root.$cfg['flux_relative'];
	  if (isset($_GET['file'])) {
	    unset($_GET['file']);
	  }
	  if (file_exists($fluxPathfromGPE_Root.$cmd)) {
   	     include $fluxPathfromGPE_Root.$cmd;		
	  }	
	  header('Location:'.$cfg['gpeasy_url'].'?cmd='.'index.php');	//http://dummysite.co.za/gpEasy_CMS/index.php/Special_Forum_Intigrator?cmd=
	  

function shutdown(){
      global $cfg;
	  $html = ob_get_contents();
	  ob_end_clean();
	  include "Forum_Intigrator.php";
	  $html = Forum_Integrator::replaceAnchorsWithPage($html,$cfg['gpeasy_url'].'?cmd=',$cfg['flux_url'],$cmd);
      echo $html;
}

function getPluginDataPath(){
	  $pluginPath = str_replace('\\','/',dirname(__FILE__));
	  $pluginPath = explode('/',$pluginPath);
	  $pluginid = $pluginPath[count($pluginPath)-1];
	  $datapath = '';
	  for ($x =0; $x <count($pluginPath)-2; $x ++) {
	    $datapath .= $pluginPath[$x].'/';
	  }
	  $datapath .= '_addondata/'.$pluginid.'/forum.cfg';
	  return $datapath;
}
?>