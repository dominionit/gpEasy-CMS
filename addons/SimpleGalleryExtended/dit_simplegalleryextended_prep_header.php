<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Simpel Gallery extended .. add the configured galleries headers.
  Version : 1.0
*/
defined('is_running') or die('Not an entry point...');

function dit_simplegalleryextended_prep_headers($js_files){
  if( common::LoggedIn() ){
    return;
  }
  global $addonDataFolder; 
  $ditSGE_cfFileName = $addonDataFolder.'/konfigurasie.php'; 
  $DIT_SGEConfig = 'NOTSET';
   if (file_exists($ditSGE_cfFileName)) {
	     include $ditSGE_cfFileName;
   }
  $fancybox = ($DIT_SGEConfig == 'NOTSET' || $DIT_SGEConfig['viewer'] == 'fb');
  
  global $addonRelativeCode,$page;

	 foreach ($page->css_user as $indx => $val) {
	    if (strpos($val,'colorbox.css') > 0 ) {
		   unset($page->css_user[$indx]);
		   break;
		}
	 }

	 foreach ($page->head_js as $indx => $val) {
	    if (strpos($val,'jquery.colorbox.js') > 0 ) {
		   unset($page->head_js[$indx]);
		   break;
		}
	 }
     
	 if ($fancybox) {
         $pluginBasePath = $addonRelativeCode."/fancybox/";	 
		 $page->css_user[] = $pluginBasePath.'source/jquery.fancybox.css';
		 
		 $page->head_js[] = $pluginBasePath.'source/jquery.fancybox.pack.js';
		 $page->head_js[] = $pluginBasePath.'lib/jquery.mousewheel-3.0.6.pack.js';
		 if ($DIT_SGEConfig == 'NOTSET') {
           $scriptVal = '';		   
		 } else {
		   $scriptVal = "prevEffect : '{$DIT_SGEConfig['fb_prevEffect']}',
                    nextEffect : '{$DIT_SGEConfig['fb_nextEffect']}',
                    openEffect : '{$DIT_SGEConfig['fb_openEffect']}',
                    closeEffect : '{$DIT_SGEConfig['fb_closeEffect']}'";
			if (trim($DIT_SGEConfig['fb_extra']) <> '') {
			  $scriptVal .= ",".$DIT_SGEConfig['fb_extra'];
			}
		 }
		 $page->head .= "      <script>
			$(document).ready(function() {
				$('.fancybox').fancybox({
                    $scriptVal });
			});
		</script>";
    } else {
         $pluginBasePath = $addonRelativeCode."/galleryview/";	
		 $page->css_user[] = $pluginBasePath.'css/jquery.galleryview-3.0-dev.css';
		 
		 $page->head_js[] = $pluginBasePath.'js/jquery.timers-1.2.js';
		 $page->head_js[] = $pluginBasePath.'js/jquery.easing.1.3.js';
		 $page->head_js[] = $pluginBasePath.'js/jquery.galleryview-3.0-dev.js';
		 
		 if ($DIT_SGEConfig == 'NOTSET') {
           $scriptVal = '';		   
		 } else {
		   $scriptVal = "pan_images : '{$DIT_SGEConfig['gv_pan_images']}',
                    panel_scale : '{$DIT_SGEConfig['gv_panel_scale']}',
					panel_width : '{$DIT_SGEConfig['width']}',
					panel_height : '{$DIT_SGEConfig['height']}'";
			if (trim($DIT_SGEConfig['gv_extra']) <> '') {
			  $scriptVal .= ",".$DIT_SGEConfig['gv_extra'];
			}
		 }		 
		 $page->head .= " <script>
	                      $(function(){
		                   $('#dit_galleryview').galleryView({
						     $scriptVal
						   });
	                      });
                        </script>";
    }	
}
?>