<?php
defined('is_running') or die('Not an entry point...');


class Admin_DominionSlideShow{
    private $config_file;
	private $settings;
	
	function saveConfig(){
	   $D_slideShowSettings = $this->settings;

        global $dataDir;
		$s = trim($this->settings['slideshow_p']);
		if ($s=='') { $s='/'; }
		if (strpos($s,"../") || strpos($s,"..\\")) { 
		 message("Imagepath - forbidden dot-dot-slash directory traversal! Default loaded.");  $s="/image"; 
		}
		if ($s[0]!='/') { $s='/'.$s;}
		if ($s[strlen($s)-1]=='/') { $s=substr($s,0,-1); }

		if (!is_dir($dataDir.'/data/_uploaded'.$s)) {
			message("Admin: Your image path does not exist on the server!");
		} else {
			// $slide_images = glob($dataDir.'/data/_uploaded'.$s."/*.{jpg,JPG,jpeg,JPEG,gif,GIF,png,PNG}", GLOB_BRACE);
			$slide_images = $this->getAllImages($dataDir.'/data/_uploaded'.$s."/",true);
			if (count($slide_images) == 0) {
  			  message("Admin: Your image path does not contain any image files!");
			}
		}

		if (gpFiles::SaveArray($this->config_file,'D_slideShowSettings',$this->settings,'D_slideImages',$slide_images )) {
			message('Data saved');
		}
	}
	
	function loadConfig(){
	   if (file_exists($this->config_file)) {
	      include $this->config_file;
		  $this->settings = $D_slideShowSettings;
	   }
	
	}
	
	function Admin_DominionSlideShow(){
	    global $addonPathData;
	    $this->config_file = $addonPathData.'/config.php';
		
		if (isset($_POST['slideshow_p'])) {
		 $this->settings['slideshow_p'] =   $_POST['slideshow_p'];
		 $this->settings['slideshow_w'] =   $_POST['slideshow_w'];
		 $this->settings['slideshow_h'] =   $_POST['slideshow_h'];
		 $this->settings['slideshow_i'] =   $_POST['slideshow_i'];
		 $this->settings['slideshow_e'] =   $_POST['slideshow_e'];
		 $this->settings['slideshow_caption'] =   isset($_POST['slideshow_caption'])?1:0;
		 $this->saveConfig();
		}
		
		$this->loadConfig();
		
	    $slideshow_p = isset($this->settings['slideshow_p'])?$this->settings['slideshow_p']:'/images';
		$slideshow_w = isset($this->settings['slideshow_w'])?$this->settings['slideshow_w']:'200';
		$slideshow_h = isset($this->settings['slideshow_h'])?$this->settings['slideshow_h']:'200';
		$slideshow_i = isset($this->settings['slideshow_i'])?$this->settings['slideshow_i']:'4000';
		$slideshow_e = isset($this->settings['slideshow_e'])?$this->settings['slideshow_e']:'fadeZoom';
		$slideshow_caption = isset($this->settings['slideshow_caption'])?($this->settings['slideshow_caption']==1)?'checked="checked"':'':'';
		
	   echo "<form method='post'>";
		echo '<p>Slideshow Folder  <input type="text" name="slideshow_p" value="'.$slideshow_p.'"></p>';
		echo '<p>Width  <input type="text" name="slideshow_w" value="'.$slideshow_w.'"></p>';
		echo '<p>Height  <input type="text" name="slideshow_h" value="'.$slideshow_h.'"></p>';
		echo '<p>Interval (ms)  <input type="text" name="slideshow_i" value="'.$slideshow_i.'"></p>';
		echo "<p>Caption  <input type='checkbox' name='slideshow_caption' $slideshow_caption ></p>";
		echo '<p>Effec  <select name="slideshow_e" >'.$this->SliderEffects($slideshow_e).'</select></p>';
		echo "<p><input type='submit' value='Save' >  Note : Each time new files are added click save again for system to know about them.</p>";
		echo "</form>";
	}
	
	function SliderEffects($slideshow_e){
	  $fadeEffekte = array('blindX','blindY','blindZ','cover','curtainX','curtainY',
							'fade','fadeZoom','growX','growY','none','scrollUp','scrollDown',
							'scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','slideX',
							'slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom');
      $opsieLys = "";							
      foreach ($fadeEffekte as $effek) {
	      if ($effek == $slideshow_e) {
	        $opsieLys .= "<option selected='selected'>$effek</option>";
		  } else {
		    $opsieLys .= "<option>$effek</option>";
          }		  
      }	  
	  return $opsieLys;
	}
	

	function getAllImages($imgdir,$justFilenames=false) {
		$ftypes = array('png','jpg','jpeg','gif'); // file types to add to array
		$dimg = opendir($imgdir);
		while($imgfile = readdir($dimg)) {
			if(in_array(strtolower(substr($imgfile,-3)),$ftypes) OR in_array(strtolower(substr($imgfile,-4)),$ftypes)) {
			   if ($justFilenames) {
			     $allImages[] = $imgfile;
			   } else {
				$allImages[] = $imgdir.$imgfile;
			   }	
			}
		}
		return $allImages;
	}	
}


