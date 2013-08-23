<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Mp3 Player with tags in pages to place players , also multi players to select from
  Version : 1.0
*/
defined('is_running') or die('Not an entry point...');

function dit_simplegalleryextended_sectiontocontent($sections){
  if ($sections['type'] == 'gallery') { //we look for our tags in the text pages
    global $addonDataFolder; 
    $ditSGE_cfFileName = $addonDataFolder.'/konfigurasie.php'; 
    $DIT_SGEConfig = 'NOTSET';
    if (file_exists($ditSGE_cfFileName)) {
	     include $ditSGE_cfFileName;
    }
  $fancybox = ($DIT_SGEConfig == 'NOTSET' || $DIT_SGEConfig['viewer'] == 'fb');  
      if ($fancybox) {
       $sections['content'] = str_replace('rel="gallery_gallery"','class="fancybox" rel="gallery"',$sections['content']);
	   $sections['content'] = str_replace('name="gallery"','name="fancy_gallery"',$sections['content']);
	  } else {
	   $sections['content'] = preg_replace("/<a.*?>/",'',$sections['content']);
       $sections['content'] = str_replace('class="gp_gallery"','class="gp_gallery" id="dit_galleryview"',$sections['content']);
	   $sections['content'] = str_replace('</a>','',$sections['content']);
      }	  	  
  }	
  return $sections;
}
?>