<?php
/*
Extention Name: Mp3 Player Ultimate Dr Player Extention
Description: DR Player Extention 
Version: 0.1a
Author: Dev Reactor (converter Johannes Pretorius)
Author URI: http://www.dominion-it.co.za/
Player Developer URI : 
*/
defined('is_running') or die('Not an entry point...');
global $addonPathCode;
require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player-extention_base.php');
class DominionMp3Player_DrPlayer extends DominionMp3Player_ExtentionBase {  
    
    public function Plugin_ID(){
      return "0";
    }
	
	 
   public function AddPlayer($pluginBasePath,$targetFiles,$options,&$ap_playerID){
        $bgColor =  $options['bgcolor'];
		$wydte = $options['wydte'];
		$hoogte = $options['hoogte'];
		$player      ='';
		$player = '<div id="dr_playlist" class="dr_playlist">';
        foreach ($targetFiles as $targetFile) {
           $fname = basename(urldecode($targetFile));
		   $fname = str_replace('-',' ',$fname);
		   $fname = str_replace('_',' ',$fname);
		   $fname = ucwords(basename($fname, '.mp3'));

			  $player      .= '<div href="'.$targetFile.'" style="width: '.$wydte.'px;" class="item" id="player'.$ap_playerID.'">';
			  $player      .= '<div>                <div class="fr duration">.</div>';
			  $player      .= '<div class="btn play" style="padding:0px 0px;"></div> <div class="title">'.$fname.'</div>';
			  $player      .= '</div>            <div class="player inactive"></div>        </div>';

			  $ap_playerID++;
	   }
     $player .= '</div">';	   
      return $player;
	}  
	
    public function Player_Extention_Header($pluginPath){
	  	   global $page;
        $page->head .= "<link rel='stylesheet' type='text/css' href='{$pluginPath}/drplayer/drplayer.css' />	
		             <script type='text/javascript' src='{$pluginPath}/drplayer/drplayer.js'></script>
					 <script type='text/javascript' >
		                   $(document).ready(function() {
                                 $('.dr_playlist').playlist({
                                       playerurl: '{$pluginPath}/drplayer/swf/drplayer.swf'
                                 }
            );
        });
		</script>   ";
      
    }  	
}
?>