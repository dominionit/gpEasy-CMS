<?php
/*
Extention Name: Mp3 Player Ultimate Default Extention
Description: Default Extention of original plugin
Version: 0.1
Author: Johannes Pretorius
Author URI: http://www.dominion-it.co.za/
*/
defined('is_running') or die('Not an entry point...');
global $addonPathCode;
require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player-extention_base.php');
class DominionMp3Player_DefaultPlayer extends DominionMp3Player_ExtentionBase {  
    
    public function Plugin_ID(){
      return "0";
    }
	
	 
   public function AddPlayer($pluginBasePath,$targetFiles,$options,&$ap_playerID){
        $bgColor =  $options['bgcolor'];
		$player      ='';
        foreach ($targetFiles as $targetFile) {
		   
		 $player      .=  "<object id='mp3player_default' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='300' height='120'>
				<param name='movie' value='".$pluginBasePath."default/player.swf' />
				<param name='FlashVars' value='playerID=".$ap_playerID."&amp;bg=0xf8f8f8&amp;leftbg=0xeeeeee&amp;lefticon=0x666666&amp;rightbg=0xcccccc&amp;
           rightbghover=0x999999&amp;righticon=0x666666&amp;righticonhover=0xffffff&amp;text=0x666666&amp;slider=0x666666&amp;
           track=0xFFFFFF&amp;border=0x666666&amp;
           loader=0x9FFFB8&amp;soundFile=$targetFile' /><param name='quality' value='high' /><param name='menu' value='true' />
           <param name='bgcolor' value='$bgColor' /><param name='wmode' value='opaque' />
        		<!--[if !IE]>-->
				<object type='application/x-shockwave-flash' data='".$pluginBasePath."default/player.swf' width='310' height='24' id='audioplayer".$ap_playerID."'>
				<param name='FlashVars' value='playerID=".$ap_playerID."&amp;bg=0xf8f8f8&amp;leftbg=0xeeeeee&amp;lefticon=0x666666&amp;rightbg=0xcccccc&amp;
           rightbghover=0x999999&amp;righticon=0x666666&amp;righticonhover=0xffffff&amp;text=0x666666&amp;slider=0x666666&amp;
           track=0xFFFFFF&amp;border=0x666666&amp;
           loader=0x9FFFB8&amp;soundFile=$targetFile' /><param name='quality' value='high' /><param name='menu' value='true' />
           <param name='bgcolor' value='$bgColor' /><param name='wmode' value='opaque' />
				<!--<![endif]-->
				<div>
					<h1>flash not supported</h1>
					<p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></p>
				</div>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>";
			
			
          /* TEST swfobjects
		  $player      .= "<object type='application/x-shockwave-flash' data='".$pluginBasePath."player.swf'";
           $player    .= " width='310' height='24' id='audioplayer".$ap_playerID."'><param name='movie' value='".$pluginBasePath."player.swf' />";
           $player    .= "<param name='FlashVars' value='playerID=".$ap_playerID."&amp;bg=0xf8f8f8&amp;leftbg=0xeeeeee&amp;lefticon=0x666666&amp;rightbg=0xcccccc&amp;";
           $player    .= "rightbghover=0x999999&amp;righticon=0x666666&amp;righticonhover=0xffffff&amp;text=0x666666&amp;slider=0x666666&amp;";
           $player    .="track=0xFFFFFF&amp;border=0x666666&amp;";
           $player    .="loader=0x9FFFB8&amp;soundFile=$targetFile' /><param name='quality' value='high' /><param name='menu' value='true' />";
           $player    .="<param name='bgcolor' value='$bgColor' /><param name='wmode' value='opaque' /></object>";
*/		   
		   $ap_playerID++; 
	   } 
      return $player;
	}  
	
    public function Player_Extention_Header($pluginPath){
	   global $page;
	   $page->head .= "
	   <script type='text/javascript' src='{$pluginPath}swfobject.js'></script>
		<script type='text/javascript' src='{$pluginPath}default/audio-player.js'></script>";
/*

		<script type='text/javascript'>
  		  swfobject.registerObject('mp3player_default', '9.0.0', '{$pluginPath}expressInstall.swf');
		</script>		

*/		
    }  	
}
?>
