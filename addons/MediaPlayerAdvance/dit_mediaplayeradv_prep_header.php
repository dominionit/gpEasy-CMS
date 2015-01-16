<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : media player
  Version : 1.0
*/
defined('is_running') or die('Not an entry point...');

function dit_mediaplayeradv_prep_headers(){ //$js_files
  global $addonPathCode,$addonRelativeCode;
  $pluginBasePath = $addonRelativeCode."/dominion-mp3player/player/";
  //$pluginPath = $SITEURL."plugins/dominion-mp3player/player/";
  require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player.php');
  $mp3 = @new DominionMp3Player();
  $mp3->Mp3_Player_PageHeaderFiles($pluginBasePath);
  unset($mp3);
}
?>