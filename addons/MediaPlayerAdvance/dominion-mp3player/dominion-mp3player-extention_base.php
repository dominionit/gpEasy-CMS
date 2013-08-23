<?php
/*
Extention Name: Mp3 Player Ultimate Base Extention
Description: Base
Version: 1.0
Author: Johannes Pretorius
Author URI: http://www.dominion-it.co.za/
*/
defined('is_running') or die('Not an entry point...');
class DominionMp3Player_ExtentionBase {   
  public function Plugin_ID(){
    return "0";
  }
  
  public function AddPlayer($pluginBasePath,$targetFiles,$options,&$ap_playerID){
    return "Not Implemented Yet";
  }
  
  public function Player_Extention_Header($pluginPath){
    //echo your header here.
  }  
}
?>