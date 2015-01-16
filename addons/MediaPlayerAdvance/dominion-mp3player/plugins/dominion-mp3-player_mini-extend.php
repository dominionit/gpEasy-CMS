<?php
/*
Extention Name: Mp3 Player Mini
Description: MP# Player Mini Extention
Version: 0.1
Author: Johannes Pretorius
Player Developer URI: http://flash-mp3-player.net/players/mini/preview/
*/
defined('is_running') or die('Not an entry point...');
global $addonPathCode;
require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player-extention_base.php');
class DominionMp3Player_MiniPlayer extends DominionMp3Player_ExtentionBase {  
    
    public function Plugin_ID(){
      return "0";
    }
	
	 
   public function AddPlayer($pluginBasePath,$targetFiles,$options,&$ap_playerID){
        $bgColor =  $options['bgcolor'];
		$wydte = $options['wydte'];
		$hoogte = $options['hoogte'];
		$player      ='';
        foreach ($targetFiles as $targetFile) {
			$loop = '';
		
			if (($options['loop'] == '1') && ($ap_playerID == 1)) {
			$loop = '&amp;autoplay=1';	
			}
			 $player  .= "<object id='player$ap_playerID' type='application/x-shockwave-flash' data='".$pluginBasePath."mp3_player_mini/player_mp3_mini.swf' width='$wydte' height='$hoogte'>
			 <param name='movie' value='".$pluginBasePath."mp3_player_mini/player_mp3_mini.swf' /> 
			 <param name='bgcolor' value='$bgColor' />
				 <param name='FlashVars' value='mp3=$targetFile&amp;playerID=".$ap_playerID."$loop' /></object><br/>";
			   $ap_playerID++; 
	   } 
      return $player;
	}  
	
    public function Player_Extention_Header($pluginPath){
	   global $page;

	   $page->head .= "<script type='text/javascript' src='{$pluginPath}swfobject.js'></script>";
    }  	
}
?>