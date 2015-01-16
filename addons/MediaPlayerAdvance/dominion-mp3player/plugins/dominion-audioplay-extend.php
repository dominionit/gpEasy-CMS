<?php
/*
Extention Name: Audio Play
Description: Audio  Play - Simply one button player.
Version: 0.1
Author: Johannes Pretorius
Player Developer URI:http://www.strangecube.com/audioplay/
*/
defined('is_running') or die('Not an entry point...');
global $addonPathCode;
require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player-extention_base.php');
class DominionAudio_Play extends DominionMp3Player_ExtentionBase {  
    
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
			$loop = '&auto=yes';	
			}
			
			 $player  .= '
			 <div><object id="audioPlayer'.$ap_playerID.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="'.$wydte.'" height="'.$hoogte.'">
<PARAM 
NAME=movie 
VALUE="'.$pluginBasePath.'audioplay/audioplay.swf?file='.$targetFile.$loop.'&sendstop=yes&repeat=0&buttondir='.$pluginBasePath.'audioplay/buttons/negative&bgcolor='.$bgColor.'&mode=playpause">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=wmode VALUE=transparent>
<embed src="'.$pluginBasePath.'audioplay/audioplay.swf?file='.$targetFile.$loop.'&sendstop=yes&repeat=0&buttondir='.$pluginBasePath.'audioplay/buttons/negative&bgcolor='.$bgColor.'&mode=playpause" 
quality=high 
wmode=transparent 
width="'.$wydte.'" 
height="'.$hoogte.'" 
align="" 
TYPE="application/x-shockwave-flash" 
pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object></div>';
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