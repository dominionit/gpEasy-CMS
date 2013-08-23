<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Display the Public Photo Albums that is available on a facebook page (not personal profile) .
  Version : 1.2
*/
defined('is_running') or die('Not an entry point...');


class Special_FacebookGallery{
	function Special_FacebookGallery(){
		echo $this->create_fb_gallery();	
	}
	function create_fb_gallery(){
			   global $addonDataFolder;
			   $ditFbG_cfFileName = $addonDataFolder.'/konfigurasie.php'; 
			   if (file_exists($ditFbG_cfFileName)) {
				 include $ditFbG_cfFileName;
			   } else {
				return "<div id='facebook_pg_gallery'><p>First go to plugin admin area and complete the configuration for the plugin.</p></div>";
 		      }	   
			   require_once 'fb-sdk/facebook.php';
				$app_id = $DIT_FbGConfig['app_id'];
				$app_secret = $DIT_FbGConfig['app_secret'];
				$bladsyID = $DIT_FbGConfig['bladsyID'];

				$facebook = new Facebook(array(
						'appId' => $app_id,
						'secret' => $app_secret,
						'cookie' => true
				));
				$FotoLys = '<div id="facebook_pg_gallery">';
				if (!isset($_GET['trekalbum'])) {
					 $albums = $facebook->api("/$bladsyID/albums");
					foreach($albums['data'] as $album){
							// kry al die albums
							if ($album['cover_photo'] <> '') {
								$cover = "https://graph.facebook.com/".$album['cover_photo']."/picture";
								$diealbum = "?trekalbum=".$album['id'];
								$FotoLys .= "<div style='width:163px;height:122px;border:1px solid black;float:left;margin:2px;'><div style='margin-left:2px;margin-top:2px;width:161px;height:120px;display:block;border:1px solid black;background-image:url({$cover});background-color: #EEE;background-position: center 25%;background-repeat: no-repeat;' ><a href='{$diealbum}'  title='{$album['name']}'><div style='width:161px;height:120px;'></div></a></div></div>";
							}						   
					}
				} else {
				  $trekalbum = $_GET['trekalbum'];
				   //Reg bou die foto lys vir die foto's binne die album
				  $photos = $facebook->api("/$trekalbum/photos");
						$FotoLys .= "<a href='?' >Back</a><br />";
						foreach($photos['data'] as $photo){
						  $images = $photo['images'];
								$FotoLys .= "<div style='margin:2px;width:161px;height:120px;display:block;float:left;border:1px solid black;background-image:url({$images[1]['source']});background-color: #EEE;background-position: center 25%;background-repeat: no-repeat;' ><a href='{$photo['source']}' name='gallery' rel='gallery_gallery' title='{$photo['name']}'><div style='width:161px;height:120px;'></div></a></div>";
						}

				}	
				return $FotoLys.'</div>';	
	}
}