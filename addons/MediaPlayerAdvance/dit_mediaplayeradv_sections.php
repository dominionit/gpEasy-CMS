<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Mp3 Player with tags in pages to place players , also multi players to select from
  Version : 1.0
*/
defined('is_running') or die('Not an entry point...');

function dit_mediaplayeradv_defaultcontent($default_content,$type){
  if ($type == 'text') { //we look for our tags in the text pages
     global $addonPathCode;
     require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player.php');
     $dObj = new DominionMp3Player();
     $default_content = $dObj->replaceTagsinContent($default_content);
	 unset($dObj);
  }
return  $default_content;
}

function dit_mediaplayeradv_sectiontocontent($sections){
  if ($sections['type'] == 'text') { //we look for our tags in the text pages
     global $addonPathCode;
     require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player.php');
     $dObj = new DominionMp3Player();
     $sections['content'] = $dObj->replaceTagsinContent($sections['content']);
	 unset($dObj);
  }	
  return $sections;
}
?>