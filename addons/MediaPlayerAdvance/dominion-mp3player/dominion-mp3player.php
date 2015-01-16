<?php
/*
Plugin Name: Mp3 Player 
Description: Mp3 player placed where tag specifies. 
Version: 1.0
Author: Johannes Pretorius
Author URI: http://www.dominion-it.co.za/
*/

defined('is_running') or die('Not an entry point...');
$dit_ap_playerID = 1; //this is used per player that gets generated per
                      //conent create call. Note plugins alters this as they 
				  	 //create players if required.
class DominionMp3Player {   
   
                          
  private $activeExtendFileName = '';
  private $activeExtendClassName = '';  
  
  private $dominion_mp3player_extentions;						   
   
  public function replaceTagsinContent($content){   
     global $addonPathData;
	 global $dit_ap_playerID;
	 
     $cfgPath = $addonPathData.'/mp3playersettings.cfg';
	 $cfgSettings = parse_ini_file($cfgPath);
	 //error_log(print_r($cfgSettings,true));
	$bgColor = $cfgSettings['bgcolor'];
	$this->activeExtendFileName = $cfgSettings['player'];
	$this->activeExtendClassName = $cfgSettings['playerclass'];

		$tmpContent = $content;
		preg_match_all('/\(%(.*)mp3(.*):(.*)%\)/i',$tmpContent,$tmpArr,PREG_PATTERN_ORDER);
		
		$AlltoReplace = $tmpArr[count($tmpArr)-1];
		$totalToReplace = count($AlltoReplace);
		for ($x = 0;$x < $totalToReplace;$x++) {
		   $targetMp3= str_replace('&nbsp;',' ',$AlltoReplace[$x]);
		   $targetMp3 = trim($targetMp3);
		   
		   $targetFiles = explode(';',$targetMp3);  //for multiple files
		   
		  $adTeks = $this->playerAdd($targetFiles,$bgColor,$dit_ap_playerID);
		  $targetMp3= str_replace('/','\/',$targetMp3);
		  $tmpContent = preg_replace("/\(%(.*)mp3(.*):(.*)$targetMp3(.*)%\)/i",$adTeks,$tmpContent);
		}
		
	  return $tmpContent;  
  } 
  
   private function playerAdd($targetFile,$bgColor){
       //error_log('TARGET FILE : '.print_r($targetFile,true));
		global $addonPathCode,$addonRelativeCode;
		global $dit_ap_playerID;
		if (empty($dit_ap_playerID)) {
		  $dit_ap_playerID  = 1;
		}
		// Get next player ID 
		if (isset($this->activeExtendFileName)) {
			require_once($addonPathCode.'/dominion-mp3player/plugins/'.$this->activeExtendFileName);
			
			$pluginBasePath = $addonRelativeCode."/dominion-mp3player/player/";
			global  $dataDir,$dirPrefix;
			$mediaDir = $dataDir.'/data/_uploaded/media/';		  //JI?HaNNEs TRKE VAN LEER
			$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
			$targetFiles = array();
			foreach ($targetFile as $theFile) {
			  if (file_exists($mediaDir."$theFile")) {
				 $targetFiles[] = urlencode($schema.$_SERVER['SERVER_NAME'].$dirPrefix."/data/_uploaded/media/$theFile"); //$SITEURL - JOHANNES kyk huier
			  }
			}  
			$options['bgcolor'] = $bgColor;
			$class = $this->activeExtendClassName;
			$mp3player = @new $class();
			return  $mp3player->AddPlayer($pluginBasePath,$targetFiles,$options,$dit_ap_playerID);
		} else {
		  return "";
		}	
   }
   
   public function getPluginsInfo(){
   global $addonPathCode;
      $files = scandir($addonPathCode.'/dominion-mp3player/plugins/');
	  
	  foreach ($files as $file){
	    if (pathinfo($file, PATHINFO_EXTENSION) == 'mp3reg') {
		  $regInfo = parse_ini_file($addonPathCode.'/dominion-mp3player/plugins/'.$file);
		  $this->dominion_mp3player_extentions[] = $regInfo;
		}
	  }
	  return $this->dominion_mp3player_extentions;
   }
   
   public function Mp3_Player_PageHeaderFiles($pluginPath){
    global $addonPathData,$addonPathCode;
	
      $cfgPath = $addonPathData.'/mp3playersettings.cfg';
	  if (!file_exists($cfgPath)) {
	   mkdir($addonPathData);
	   $verstek = $addonPathCode.'/dominion-mp3player/mp3playersettings.cfg';
	   copy($verstek, $cfgPath);
	 }
	  $cfgSettings = parse_ini_file($cfgPath);
	  $this->activeExtendFileName = $cfgSettings['player'];
	  $this->activeExtendClassName = $cfgSettings['playerclass'];
	  if (($cfgSettings['usejquery'] == '1') && (!common::LoggedIn())) {
	    global $page;
	   $page->head .= "<script type='text/javascript' src='{$pluginPath}/jquery-1.6.2.min.js'></script>";
	  }
	  
	  
	  if (isset($this->activeExtendFileName)) {
        require_once($addonPathCode.'/dominion-mp3player/plugins/'.$this->activeExtendFileName);
        $class = $this->activeExtendClassName;
		$mp3player = @new $class();
		$mp3player->Player_Extention_Header($pluginPath);
		unset($mp3player);
	  } 	
   }

}

?>