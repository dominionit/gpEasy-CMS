<?php

defined('is_running') or die('Not an entry point...');



class Gadget_DominionSlideShow{
    private $config_file;
	private $settings;
	private $images;
	
    function loadConfig(){
	   if (file_exists($this->config_file)) {
	      include $this->config_file;
		  $this->settings = $D_slideShowSettings;
		  $this->images = $D_slideImages;
	   }
	
	}
	
	function Gadget_DominionSlideShow(){
	    global $addonPathData,$dirPrefix;
	    $this->config_file = $addonPathData.'/config.php';
		$this->loadConfig();
		$urlP = $this->settings['slideshow_p'];
		$imgL = "<div id='slideshow' class='slideshow_pics'>";
		$c =0;
		$s_width =$this->settings['slideshow_w'];
		$s_height = $this->settings['slideshow_h'];
		
		foreach ($this->images  as $image) {
		if ($this->settings['slideshow_caption'] == 1) {
		 $comment = substr($image,0,strrpos($image,'.'));
		} else {
		  $comment = '';
        }		
		  
		  $trgImg = $dirPrefix."/data/_uploaded".$urlP."/".$image;
		  if ($c ==0) {
		   $imgL .= "<img src='$trgImg ' width='$s_width' height='$s_height' class='first' alt='$comment'/>";
		  } else {
		    $imgL .= "<img src='$trgImg ' width='$s_width' height='$s_height'  alt='$comment' />";
		  }
		  $c++;
		}
		$imgL .= '</div><p id="caption"></p>';
		echo $imgL;
	}

}
