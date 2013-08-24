<?php
defined('is_running') or die('Not an entry point...');


class flexiTheme{
     var $config;
	 var $menuPos = array('left','right');
	 var $mainMenuPos = array('above header','inside header','below header');
	 var $popupPos = array('left','right','center');
	 var $repeatOptions = array('','repeat','repeat-x','repeat-y','no-repeat');
	 var $positionOptions = array('','left top','left center','left bottom','right top','right center','right bottom','center top','center center','center bottom');
	 var $imageList;
	 
    private function skepOpsiebokse($huidigeWaarde,$dataArr){
	  $tmpS = '';
	  foreach($dataArr as $waarde) {
	    if ($waarde == $huidigeWaarde) {
		  $tmpS .= "<option selected='selected'>$waarde</option>";
		} else { 
		  $tmpS .= "<option>$waarde</option>";
		}
	  }
	  return $tmpS;
	}
	
	private function skepImgOpsieBoks($huidigeWaarde){
	  $tmpS = '';
	  foreach($this->imageList as $waarde) {
	     if (($huidigeWaarde != '') and (strpos($waarde,$huidigeWaarde) != FALSE)) {
		   $waarde = str_replace("<option>","<option selected='selected'>",$waarde);
		 }
   	     $tmpS .= $waarde;
	  }
	  return $tmpS;
	}
    
	function flexiSave(&$config){
	    global $addonPathData;
		
	     if ((isset($_POST['config_type'])) && ($_POST['config_type'] == 'advance')) {
		   //advance settings save
			$config['side_menu_pos'] = $_POST['side_menu_pos'];
			$config['sub_menu_pos'] = $_POST['sub_menu_pos'];
			$config['main_menu_pos'] = $_POST['main_menu_pos'];
			
			$config['body_background_color'] = $_POST['body_background_color'];
			$config['body_background_image'] = trim($_POST['body_background_image']) != ''?strpos($_POST['body_background_image'],'/')==0?$_POST['body_background_image']:$_POST['body_background_image']:'';
			$config['body_background_position'] = $_POST['body_background_position'];
			$config['body_background_repeat'] = $_POST['body_background_repeat'];

			$config['header_background_color'] = $_POST['header_background_color'];
			$config['header_background_image'] = trim($_POST['header_background_image']) != ''?strpos($_POST['header_background_image'],'/')==0?$_POST['header_background_image']:$_POST['header_background_image']:'';
			$config['header_background_position'] = $_POST['header_background_position'];
			$config['header_background_repeat'] = $_POST['header_background_repeat'];
			
			$config['link_color'] = $_POST['link_color'];
			$config['text_color'] = $_POST['text_color'];
			$config['heading_color'] = $_POST['heading_color'];
			$config['submnu_bkg_color'] = $_POST['submnu_bkg_color'];
			$config['submnu_hover_color'] = $_POST['submnu_hover_color'];
			
			$config['sizes_page_width'] = $_POST['sizes_page_width'];
			$config['sizes_content_width'] = $_POST['sizes_content_width'];
			$config['sizes_sidebar_width'] = $_POST['sizes_sidebar_width'];
			$config['sizes_header_width'] = $_POST['sizes_header_width'];
			$config['sizes_header_height'] = $_POST['sizes_header_height'];
			$config['sizes_margin'] = $_POST['sizes_margin'];
			$config['sizes_inside_padding'] = $_POST['sizes_inside_padding'];

		}	else {
		    //basic settings save
			$config['side_menu_pos'] = $_POST['side_menu_pos'];
			$config['sub_menu_pos'] = $_POST['sub_menu_pos'];
			
			$config['header_background_color'] = $_POST['header_background_color'];
			$config['header_background_image'] = trim($_POST['header_background_image']) != ''?strpos($_POST['header_background_image'],'/')==0?$_POST['header_background_image']:$_POST['header_background_image']:'';
			$config['header_background_position'] = $_POST['header_background_position'];
			$config['header_background_repeat'] = $_POST['header_background_repeat'];
			
			$config['body_background_color'] = $_POST['body_background_color'];			
		}
		
 		if (gpFiles::SaveArray($addonPathData.'/config.php','D_config',$config)) {
		  message('flexiTheme Data Saved');
		}	
	}
	
	function flexiUI_Basic($config){
	  echo "<div id='flexiui_basic'>";
		echo "<form method='post' id='flexiThemeBasic'><input type='hidden' name='config_type' value='basic'>";		  
	  echo "<input type='submit' name='savesettings' value='Save Settings'><input type='submit' name='laaiverstek' value='Load Defaults'>";
		echo "<div><hr/><fieldset><legend>Header Options</legend>";
		echo "Color : <input type='text' value='{$config['header_background_color']}' id='header_background_color' name='header_background_color'><br/>";
		echo "Image : <select class='imgselect' id='header_background_image' name='header_background_image'>".$this->skepImgOpsieBoks($config['header_background_image'])." </select> <br/>";
		echo "Image Position : <select  id='header_background_position' name='header_background_position'>".$this->skepOpsiebokse($config['header_background_position'],$this->positionOptions)."</select><br/>"; 
		echo "Image Repeat : <select id='header_background_repeat' name='header_background_repeat'>".$this->skepOpsiebokse($config['header_background_repeat'],$this->repeatOptions)."</select><br/>";
		echo "</fieldset></div>";//end body options
		
		echo "<div><hr/><fieldset><legend>Postion Options</legend>";
		echo "Side Menu : <select id='side_menu_pos' name='side_menu_pos'>".$this->skepOpsiebokse($config['side_menu_pos'],$this->menuPos)."</select><br/>"; 
		echo "Sub Menu : <select id='sub_menu_pos' name='sub_menu_pos'>".$this->skepOpsiebokse($config['sub_menu_pos'],$this->popupPos)."</select><br/>"; 
		echo "</fieldset></div>";//position options		

		echo "<div><hr/><fieldset><legend>Misc Options</legend>";
		echo "Body Color : <input type='text' value='{$config['body_background_color']}' id='body_background_color' name='body_background_color'><br/>";		
		echo "</fieldset></div>";//position options		

		echo "</form>";			
	  echo "</div>";
	}
	
	function flexiUI_Advance($config){
	   echo "<div id='flexiui_advance'>";
		echo "<form method='post' id='flexiThemeAdvance'><input type='hidden' name='config_type' value='advance'>";	   
		echo "<input type='submit' name='savesettings' value='Save Settings'><input type='submit' name='laaiverstek' value='Load Defaults'>";
		echo "<div><hr/><fieldset><legend>Body Background Options</legend>";
		echo "Color : <input type='text' value='{$config['body_background_color']}' id='body_background_color' name='body_background_color'><br/>";
		echo "Image : <select class='imgselect' id='body_background_image' name='body_background_image'>".$this->skepImgOpsieBoks($config['body_background_image'])." </select>  <br/>";
		echo "Image Position : <select  id='body_background_position' name='body_background_position'>".$this->skepOpsiebokse($config['body_background_position'],$this->positionOptions)."</select><br/>"; 
		echo "Image Repeat : <select id='body_background_repeat' name='body_background_repeat'>".$this->skepOpsiebokse($config['body_background_repeat'],$this->repeatOptions)."</select><br/>";
		echo "</fieldset></div>";//end body options

		echo "<div><hr/><fieldset><legend>Header Options</legend>";
		echo "Color : <input type='text' value='{$config['header_background_color']}' id='header_background_color' name='header_background_color'><br/>";
		echo "Image : <select class='imgselect' id='header_background_image' name='header_background_image'>".$this->skepImgOpsieBoks($config['header_background_image'])." </select> <br/>";
		echo "Image Position : <select  id='header_background_position' name='header_background_position'>".$this->skepOpsiebokse($config['header_background_position'],$this->positionOptions)."</select><br/>"; 
		echo "Image Repeat : <select id='header_background_repeat' name='header_background_repeat'>".$this->skepOpsiebokse($config['header_background_repeat'],$this->repeatOptions)."</select><br/>";
		echo "</fieldset></div>";//end body options
		
		echo "<div><hr/><fieldset><legend>Postion Options</legend>";
		echo "Side Menu : <select id='side_menu_pos' name='side_menu_pos'>".$this->skepOpsiebokse($config['side_menu_pos'],$this->menuPos)."</select><br/>"; 
		echo "Sub Menu : <select id='sub_menu_pos' name='sub_menu_pos'>".$this->skepOpsiebokse($config['sub_menu_pos'],$this->popupPos)."</select><br/>"; 
		echo "Main Menu : <select id='main_menu_pos' name='main_menu_pos'>".$this->skepOpsiebokse(isset($config['main_menu_pos'])?$config['main_menu_pos']:'inside header',$this->mainMenuPos)."</select><br/>"; 
		
		echo "</fieldset></div>";//position options
		
        echo "<div><hr/><fieldset><legend>Color Options</legend>";
		echo "Link Color: <input type='text' value='{$config['link_color']}' id='link_color' name='link_color'><br/>";
		echo "Text Color: <input type='text' value='{$config['text_color']}' id='text_color' name='text_color'><br/>";
		echo "Heading (h2,h3,h4) Color: <input type='text' value='{$config['heading_color']}' id='heading_color' name='heading_color'><br/>";
		echo "Submenu Bkg Color: <input type='text' value='{$config['submnu_bkg_color']}' id='submnu_bkg_color' name='submnu_bkg_color'><br/>";
		echo "Submenu Hover Color: <input type='text' value='{$config['submnu_hover_color']}' id='submnu_hover_color' name='submnu_hover_color'><br/>";
		echo "</fieldset></div>";//color options	
		
        echo "<div><hr/><fieldset><legend>Size Options</legend>";
		echo "Page Width : <input class='just_numbers' type='text' value='{$config['sizes_page_width']}' id='sizes_page_width' name='sizes_page_width'>px<br/>";
		echo "Content Width : <input class='just_numbers' type='text' value='{$config['sizes_content_width']}' id='sizes_content_width' name='sizes_content_width'>px<br/>";
		echo "SideBar Width : <input class='just_numbers' type='text' value='{$config['sizes_sidebar_width']}' id='sizes_sidebar_width' name='sizes_sidebar_width'>px<br/>";
		echo "Header Width  : <input class='just_numbers' type='text' value='{$config['sizes_header_width']}' id='sizes_header_width' name='sizes_header_width'>px<br/>";
		echo "Header Height : <input class='just_numbers' type='text' value='{$config['sizes_header_height']}' id='sizes_header_height' name='sizes_header_height'>px<br/>";
		echo "Space between areas (margin) : <input class='just_numbers' type='text' value='{$config['sizes_margin']}' id='sizes_margin' name='sizes_margin'>px<br/>";		
		echo "Inside Padding (in boxes) : <input class='just_numbers' type='text' value='{$config['sizes_inside_padding']}' id='sizes_inside_padding' name='sizes_inside_padding'>px<br/>";		
		echo "</fieldset></div>";//size options	
		
		echo "</form>";
      echo "</div>"; //advance		
	}
	
	function flexi_Defaults(&$config){
	      $config['side_menu_pos'] = 'right';
		  $config['sub_menu_pos'] = 'left';
		  $config['main_menu_pos'] = 'inside header';
		  
		  
		  $config['body_background_color'] = '#FF9A35';
		  $config['body_background_image'] = '';
		  $config['body_background_position'] = 'left top'; //left top,left center,left bottom,right top,right center,right bottom,center top,center center,center bottom
		  $config['body_background_repeat'] = 'repeat'; //repeat,repeat-x,repeat-y,no-repeat
		  
		  $config['link_color'] = '#AA9A35'; 
		  $config['text_color'] = '#6B7C93'; 
		  $config['heading_color'] = '#001739'; 
		  $config['submnu_bkg_color'] = '#82B1FF'; 
		  $config['submnu_hover_color'] = '#ECE1CF'; 

		  $config['header_background_color'] = '#2C84AF';
		  $config['header_background_image'] = '';
		  $config['header_background_position'] = 'left top'; //left top,left center,left bottom,right top,right center,right bottom,center top,center center,center bottom
		  $config['header_background_repeat'] = 'repeat'; //repeat,repeat-x,repeat-y,no-repeat
		  
		  $config['sizes_page_width'] = '980';
		  $config['sizes_content_width'] = '700';
		  $config['sizes_sidebar_width'] = '280';
		  $config['sizes_header_width'] = '980';
		  $config['sizes_header_height'] = '150';
		  $config['sizes_margin'] = '10';
		  $config['sizes_inside_padding'] = '40';
	}//end verstek
	
	function addImageFiles($targetDir){
	  global $dataDir;
	   if (strpos($targetDir,'/data/_uploaded') != FALSE) {
	     $targetDir = str_replace($dataDir.'/data/_uploaded','',$targetDir);
	   } 
       $baseDir = $dataDir.'/data/_uploaded'.$targetDir;
	  	
		
	  $dirFiles = scandir($baseDir);
		foreach ($dirFiles as $file) {
		  if (($file == '.') or ($file == '..') or ($file == 'thumbnails')) {
		    continue;
		  }
		  if (is_dir($baseDir.'/'.$file)) {
		    $this->addImageFiles($baseDir.'/'.$file);
		  } else {
		    $ext = end(explode('.', $file));
		    if (($ext != 'html') and ($ext != 'htm')) {
  		      $val = $targetDir.'/'.$file;
			  //$img = 'data/_uploaded'.$targetDir.'/'.$file;
		      $this->imageList[] = "<option>$val</option>";
		    }
		  }	
		}
	}
	
	function flexiTheme($loadconfig=true){
	    if ($loadconfig == false) { return; }
		
	    global $addonPathData;
		$baseDir = '/image';
		$this->imageList[] = "<option value='' data-imagesrc='' data-description='None'></option>";
		$this->addImageFiles($baseDir);
		
		$config = array();
		
	    if (!file_exists($addonPathData.'/config.php')) {
		  //verstek waardes
		  $this->flexi_Defaults($config);
		  gpFiles::SaveArray($addonPathData.'/config.php','D_config',$config);
		} else {
		  include $addonPathData.'/config.php';
		  $config = $D_config;
		  
		  
		   if (isset($_POST['laaiverstek'])) {
		     $this->flexi_Defaults($config);
		   } else if (isset($_POST['savesettings'])) {
		    $this->flexiSave($config);
		  }
		  
		}
	    
		echo '<h2>Dominion IT\'s - flexiTheme Plugin</h2>';
		echo "<div><hr/>flexiTheme is in Beta currently, developed by <a href='http://www.dominion-it.co.za' target='dominion'>Dominion IT</a>. <br/> This plugin and theme is NOT allowed to be used with Porno/Adult content. Free for otherwise commercial or personal use.<br/><i>Dedicated to my son who is very sick - May God Help you and Cure this Sickness and Desease of yours.</i> <hr/></div>";		
		echo "<div><p><a href='#basic' id='flexi_basic'>Basic</a> | <a href='#advance' id='flexi_advance'>Advance</a></p></div>";

        $this->flexiUI_Basic($config); 
		$this->flexiUI_Advance($config);
		

		echo "<script type='text/javascript'>
		      $('#header_background_color,#body_background_color,#link_color,#text_color,#submnu_bkg_color,#submnu_hover_color,#heading_color').miniColors({opacity: true});
			  
			  $('#flexiui_advance').hide();
			  
			  $('#flexi_advance').click(function (){
                 $('#flexiui_basic').hide(); 			    
				 $('#flexiui_advance').show('slow');
				 return false;
			  });
			  
			  $('#flexi_basic').click(function(){
                 $('#flexiui_advance').hide(); 			    
				 $('#flexiui_basic').show('slow');
				 return false;
			  });
			  
			  $('.just_numbers').keypress(function (e) {
                   if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
              });
			  
			  $('#sizes_page_width').change(function(){
			     var mr = $('#sizes_margin').val();
				 if (mr == '') { mr = 10; $('#sizes_margin').val(mr);}
				 
			     var pw = $('#sizes_page_width').val();
				 if (pw == '') { pw = 980; $('#sizes_page_width').val(pw);}

			     var pd = $('#sizes_inside_padding').val();
				 if (pd == '') { pd = 10; $('#sizes_inside_padding').val(pd);}
				 pw = parseInt(pw) - (parseInt(mr) + (parseInt(pd) * 4) + 10);
				 
			     $('#sizes_sidebar_width').val(Math.round(pw * 0.30));
				 $('#sizes_content_width').val(Math.round(pw * 0.70));
			  });
			  
			  $('#sizes_content_width').change(function(){
			     var pw = $('#sizes_page_width').val();
				 if (pw == '') { pw = 980; $('#sizes_page_width').val(pw);}
				 var cw =  $('#sizes_content_width').val();
				 if (cw == '') { cw = 700; $('#sizes_content_width').val(cw);}
			     var mr = $('#sizes_margin').val();
				 if (mr == '') { mr = 10; $('#sizes_margin').val(mr);}

			     var pd = $('#sizes_inside_padding').val();
				 if (pd == '') { pd = 10; $('#sizes_inside_padding').val(pd);}
				 cw =parseInt(cw) + (parseInt(mr) + (parseInt(pd) * 4) + 10);
				 
			     $('#sizes_sidebar_width').val(Math.round(pw -cw ));
			  });
			  
			  $('#sizes_sidebar_width').change(function(){
			     var pw = $('#sizes_page_width').val();
				 if (pw == '') { pw = 980; $('#sizes_page_width').val(pw);}
				 var cw =  $('#sizes_sidebar_width').val();
				 if (cw == '') { cw = 280; $('#sizes_sidebar_width').val(cw);}
			     var mr = $('#sizes_margin').val();
				 if (mr == '') { mr = 10; $('#sizes_margin').val(mr);}
			     var pd = $('#sizes_inside_padding').val();
				 if (pd == '') { pd = 10; $('#sizes_inside_padding').val(pd);}

				 cw =parseInt(cw) +(parseInt(mr) + (parseInt(pd) * 4) + 10);
				 
			     $('#sizes_content_width').val(Math.round(pw -cw ));
			  });
			  
			   $('#sizes_margin').change(function(){
			     var mr = $('#sizes_margin').val();
				 if (mr == '') { mr = 10; $('#sizes_margin').val(mr);}
			     var pw = $('#sizes_page_width').val();
				 if (pw == '') { pw = 980; $('#sizes_page_width').val(pw);}
			     var pd = $('#sizes_inside_padding').val();
				 if (pd == '') { pd = 10; $('#sizes_inside_padding').val(pd);}
				 pw = parseInt(pw) - (parseInt(mr) + (parseInt(pd) * 4) + 10);

				 
			     $('#sizes_sidebar_width').val(Math.round(pw * 0.30));
				 $('#sizes_content_width').val(Math.round(pw * 0.70));
			  });
			   $('#sizes_inside_padding').change(function(){
			     var mr = $('#sizes_margin').val();
				 if (mr == '') { mr = 10; $('#sizes_margin').val(mr);}
			     var pw = $('#sizes_page_width').val();
				 if (pw == '') { pw = 980; $('#sizes_page_width').val(pw);}
			     var pd = $('#sizes_inside_padding').val();
				 if (pd == '') { pd = 10; $('#sizes_inside_padding').val(pd);}
				 pw = parseInt(pw) - (parseInt(mr) + (parseInt(pd) * 4) + 10);

				 
			     $('#sizes_sidebar_width').val(Math.round(pw * 0.30));
				 $('#sizes_content_width').val(Math.round(pw * 0.70));
			  });
			  
		</script>";

	}
	
	function Init(){
	  global $addonPathData;
       if (file_exists($addonPathData.'/config.php')) {	  
	     include $addonPathData.'/config.php';
		  $this->config = $D_config;
	   } else {
	     $this->config = array();
       }	   
	}
	function GetHeader(){
	   
	   global $addonRelativeCode;
  	    echo "<link rel='stylesheet' type='text/css' href='$addonRelativeCode/flexiCSS.php'>";
		
	    gpOutput::GetHead();
	    if (common::loggedIn()) {
		  echo "<link rel='stylesheet' type='text/css' href='$addonRelativeCode/admin/minicolors/jquery.miniColors.css'>";
		  echo "<script type='text/javascript' src='$addonRelativeCode/admin/minicolors/jquery.miniColors.min.js'></script>";
		  echo "<style type='text/css'>
		              input,select {margin:3px;}
					  fieldset { padding:2px;}
               </style>";
        }	
        echo "<script type='text/javascript' src='$addonRelativeCode/js/jquery.dropotron-1.0.js'></script>";		
	}

	function GetBody(){
	  global $GP_ARRANGE,$page;
	  echo  "<div id='wrapper'>	";
	  flexiTheme::getBodyHeader();
	  flexiTheme::getBodyContents();
	  echo "</div>"; //wrapper;
	  flexiTheme::getBodyFooter();
	  //return  $tmpBody;
	}
	
	function getBodyHeader(){
	  global $GP_ARRANGE,$page;
	  if ($this->config['main_menu_pos'] == 'above header') {
	  	   flexiTheme::getBodyMenu();
	  }
	  echo "<div id='header'>";
	  echo " <div id='logo'> ";
	  gpOutput::Get('Extra','Header');
	  echo" </div>"; //logo
	  if ($this->config['main_menu_pos'] == 'inside header') {
	   flexiTheme::getBodyMenu();
	  }
	  echo"</div>"; //header
	  if ($this->config['main_menu_pos'] == 'below header') {
	   flexiTheme::getBodyMenu();
	  }
	  
	}
	
	function getBodyMenu(){
	  global $GP_ARRANGE,$page;
	  echo "<div id='menu'>";
	  $GP_ARRANGE = false;		
   	  gpOutput::Get('TopTwoMenu');
	   $submenupos = $this->config['sub_menu_pos'];
	  echo"<br class='clearfix' />			
	                  <script type='text/javascript'>			$('.menu_top').dropotron({alignment:'$submenupos'});		</script>
		        </div>";//menu
	} 
	
	function getBodySideMenu(){
	  global $GP_ARRANGE,$page;
	  echo"<div id='sidebar'>";
			 gpOutput::Get('Extra','Side_Menu'); 
			 gpOutput::GetAllGadgets();
		echo "</div><br class='clearfix' />";//sidebar
	}
	
	function getBodyContents(){
	 global $GP_ARRANGE,$page;
	  	echo "<div id='page'>";
		//if ($this->config['side_menu_pos'] == 'right') {
  		  echo "  <div id='content'>";
		  gpOutput::Get('Extra','Misc Content');
  	        $page->GetContent();
             echo"<br class='clearfix' />";
		  echo " </div>"; //contents
		  flexiTheme::getBodySideMenu();
		/*} else {
  		  flexiTheme::getBodySideMenu();
		  echo "  <div id='content'>";
  	        $page->GetContent();
             echo"<br class='clearfix' />";
		  echo " </div>"; //contents
        }
*/		
		echo	"</div>";//page
	}
	
	function getBodyFooter(){
	 global $GP_ARRANGE,$page;
	 echo "<div id='footer-content-wrapper'>";
 	   echo "<div id='footer-content'>";
	    echo "  <div id='fbox1'>";
			gpOutput::Get('Extra','Footer Box1');
		echo "</div><div id='fbox2'>";
			gpOutput::Get('Extra','Footer Box2');
		echo "</div><div id='fbox3'>";
			gpOutput::Get('Extra','Footer Box3');
		echo "</div>";
	   echo "</div>";
	 echo"</div> ";
	 gpOutput::Get('Extra','Misc Footer');
	 echo "<div id='footer'> <p>&copy; Copyright ";
	 echo date('Y')." . ".$_SERVER['HTTP_HOST']; 
	 echo". All Rights Reserved.<br />    <a href='/index.php'>Home</a> | <a href='/Special_Contact'>Contact</a> | ";
	  gpOutput::GetAdminLink();
	 echo"<br/>	<font size='1'>flexiTheme developed by <a href='http://www.dominion-it.co.za'>Dominion IT</a></font></p>";
     echo"</div>"; //footer

	}
	
}