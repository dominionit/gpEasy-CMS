<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Display the Public Photo Albums that is available on a facebook page (not personal profile) .
  Version : 1.2
*/
defined('is_running') or die('Not an entry point...');

function dit_facebook_pg_getsections($sections){
  $sections['fb_pg_gallery']['label'] = 'Facebook Page Gallery';
  return  $sections;
}

function dit_facebook_pg_defaultcontent($default_content,$type){
  if ($type == 'fb_pg_gallery') {
    require_once('Special_FacebookGallery.php');
    $default_content= Special_FacebookGallery::create_fb_gallery();
  }
return  $default_content;
}

function dit_facebook_pg_sectiontocontent($sections){
  if ($sections['type'] == 'fb_pg_gallery') {
	 //common::ShowingGallery();
     require_once('Special_FacebookGallery.php');
     $sections['content'] = Special_FacebookGallery::create_fb_gallery();
  }	
  return $sections;
}
?>