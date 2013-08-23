<?php
/*
  Developer : Johannes Pretorius - Dominion IT
  Web : http://www.dominion-it.co.za
  License : GPL
  Note : Please leave the header when copying or chaning code. Thank you.
*/
class Admin_ForumIntegrator{
	function Admin_ForumIntegrator(){ 
	 global $langmessage;
	 //error_log(print_r($_SERVER,true));
	 $host = $_SERVER['HTTP_HOST'];
    $datapath = $this->getPluginDataPath();
	$cfg['gpeasy_url'] = common::getUrl('Special_Forum_Integrator'); //.'?cmd='
    $cfg['flux_url'] = 'http://'.$host.'/fluxbb/';	  
	$cfg['flux_relative'] = "./fluxbb/";	 	
	if (!file_exists(dirname($datapath))) {
	  mkdir(dirname($datapath),0755,true);
	}
	if (isset($_POST['save_forum_data'])) {
	  $cfg['gpeasy_url'] = $_POST['gpeasy_url'];
      $cfg['flux_url'] = $_POST['flux_url'];
	  $cfg['flux_relative'] = $_POST['flux_relative']; 	
	  file_put_contents($datapath,serialize($cfg));
	  message($langmessage['SAVED']);
	  
	} 

	if (file_exists($datapath)) {
	  $cfg = unserialize(file_get_contents($datapath));	  
	}  
	
    echo "<h3>Forum Integrator ".$langmessage['configuration']."</h3>\n";
	echo "<form action=\"".common::getUrl('Admin_Forum_Integrator')."\" method=\"post\">
	    <p>Flux URL (remember http://) <input type='text' name='flux_url' size='100' value='".$cfg['flux_url']."'></p>
		<p>Flux Relative Path (from within the gpEasy path) <input type='text' size='100'  name='flux_relative' value='".$cfg['flux_relative']."'></p>
		<p>gpEasy Special URL  <input type='text' size='100'  name='gpeasy_url' value='".$cfg['gpeasy_url']."'></p>
		<p><br/></p>
		<p>NOTE : To see what changes is required to get FluxBB to integrate correctly with this plugin, please visit  <a href='http://www.dominion-it.co.za/gpeasy.php' title='Forum Integrator Developer page'>this page</a></p>
	   <input type='submit' value='Save' name='save_forum_data'>
	  </form>";
      

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
}
?>