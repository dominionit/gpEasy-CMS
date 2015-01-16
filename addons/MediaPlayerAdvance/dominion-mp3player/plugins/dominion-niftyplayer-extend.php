<?php
/*
Extention Name: Nifty Player
Description: Nifty Player
Version: 0.1
Author: Johannes Pretorius
Player Developer URI:http://www.varal.org/media/niftyplayer/
*/
defined('is_running') or die('Not an entry point...');
global $addonPathCode;
require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player-extention_base.php');
class DominionNifty_Player extends DominionMp3Player_ExtentionBase {  
    
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
			$loop = '&as=1';	
			}
			
			 $player  .= '
			 <object id="niftyPlayer'.$ap_playerID.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'.$wydte.'" 
			  height="'.$hoogte.'" align="">
<param name=movie value="'.$pluginBasePath.'niftyplayer/niftyplayer.swf?file='.$targetFile.$loop.'">
<param name=quality value=high>
<param name=bgcolor value='.$bgColor.'>
<embed src="'.$pluginBasePath.'niftyplayer/niftyplayer.swf?file='.$targetFile.$loop.'" quality=high bgcolor='.$bgColor.' width="'.$wydte.'" height="'.$hoogte.'" name="niftyPlayer'.$ap_playerID.'" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
</embed>
</object><br/>';
			   $ap_playerID++; 
	   } 
      return $player;
	}  
	
    public function Player_Extention_Header($pluginPath){
       global $page;
	   $page->head .= "<script type='text/javascript' src='{$pluginPath}swfobject.js'></script>
		<script type='text/javascript' src='{$pluginPath}niftyplayer/niftyplayer.js'></script>";
    }  	
}
?>