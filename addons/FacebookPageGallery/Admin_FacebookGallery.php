<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Display the Public Photo Albums that is available on a facebook page (not personal profile) .
  Version : 1.2
*/

defined('is_running') or die('Not an entry point...');


class Admin_FacebookGallery{
	function Admin_FacebookGallery(){
	//Config,config
	   global $addonDataFolder;
	   $ditFbG_cfFileName = $addonDataFolder.'/konfigurasie.php'; 
	   if (isset($_POST['stoor'])) {
	      $DIT_FbGConfig['app_id'] = $_POST['app_id'];  //;"296490443746778";
		  $DIT_FbGConfig['app_secret'] = $_POST['app_secret'];  //;"d56836cb34d6c19ab19d3ecd335728cd";
		  $DIT_FbGConfig['bladsyID'] = $_POST['bladsyID'];  //;"201936129898074";
          gpFiles::SaveArray($ditFbG_cfFileName,'DIT_FbGConfig',$DIT_FbGConfig);
		  //error_log("Save array : $ditFbG_cfFileName");
	   } 
	   if (file_exists($ditFbG_cfFileName)) {
	     include $ditFbG_cfFileName;
	   } else {
	     $DIT_FbGConfig['app_id'] = 'APPLICATION ID';
		  $DIT_FbGConfig['app_secret'] = 'APPLICATION SECRECT CODE';
		  $DIT_FbGConfig['bladsyID'] = 'PAGE ID';
	   }
	   echo "<h1>Facebook Page Gallery - </h1>by <a href='http://www.dominion-it.co.za'>Dominion It</a><br/>
	   <br/> To Start using the plugin you must first register yourself as a developer at Facebook and create a Application, there is alot of
	   information on the facebook page <a href='http://developers.facebook.com/'>http://developers.facebook.com/</a> . As soon as you have created your 
	   facebook Application you will get a Application ID and Application Secret code. This you enter in the plugins config. Now you must retrieve the public page's ID. 
	   This is any public page. You can google '' or view <a href='http://rieglerova.net/how-to-get-a-facebook-fan-page-id/'>this page</a> on how to get the ID. Enter this id into the plugin config.";
	   echo "<form method='post'><p><label style='width:150px;'>Application ID </label><input type='text' name='app_id' value='{$DIT_FbGConfig['app_id']}'></p>
	   <p><label style='width:150px;'>Application Secret Code </label><input type='text' name='app_secret' size='50' value='{$DIT_FbGConfig['app_secret']}'></p>
	   <p><label style='width:150px;'>Facebook Page ID </label><input type='text' name='bladsyID' value='{$DIT_FbGConfig['bladsyID']}'></p>
	   <p><center><input type='submit' name='stoor' value='Save'></center></p>
	   </form>";
	   
	  
	}
}