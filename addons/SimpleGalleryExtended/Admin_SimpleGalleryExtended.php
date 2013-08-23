<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Simple Gallery Extended
  Version : 1.0
*/

defined('is_running') or die('Not an entry point...');


class Admin_SimpleGalleryExtended{
	function Admin_SimpleGalleryExtended(){	
	  global  $page;
	  global $addonDataFolder;
	  $ditSGE_cfFileName = $addonDataFolder.'/konfigurasie.php'; 

	  if (file_exists($ditSGE_cfFileName)) {
	     include $ditSGE_cfFileName;
	   } else {
	      //fancybox
	      $DIT_SGEConfig['fb_prevEffect'] = 'fade';  
		  $DIT_SGEConfig['fb_nextEffect'] = 'fade';  
		  $DIT_SGEConfig['fb_openEffect'] = 'elastic';  
		  $DIT_SGEConfig['fb_closeEffect'] = 'elastic';  
		  $DIT_SGEConfig['fb_extra'] = "helpers : { title : { type : 'inside' } }";

		  //galleryview
		  $DIT_SGEConfig['gv_pan_images'] = 'true';  
		  $DIT_SGEConfig['gv_panel_scale'] = 'crop';  
		  $DIT_SGEConfig['gv_extra'] = ''; 
		  
		  //default
		  $DIT_SGEConfig['viewer'] = 'fb';  
		  $DIT_SGEConfig['height'] = '700';  
		  $DIT_SGEConfig['width'] = '400';  
		  
	   }	
	   
	  if (isset($_POST['stoor_fb']) || isset($_POST['saveviewer']) || isset($_POST['stoor_gv'])) {
	      if (isset($_POST['stoor_fb'])) {
			  //fancybox
			  $DIT_SGEConfig['fb_prevEffect'] = $_POST['fb_prevEffect'];  
			  $DIT_SGEConfig['fb_nextEffect'] = $_POST['fb_nextEffect'];  
			  $DIT_SGEConfig['fb_openEffect'] = $_POST['fb_openEffect'];  
			  $DIT_SGEConfig['fb_closeEffect'] = $_POST['fb_closeEffect'];  
			  $DIT_SGEConfig['fb_extra'] = $_POST['fb_extra'];  
		  }	  

		  if (isset($_POST['stoor_gv'])) {
			  //galleryview
			  $DIT_SGEConfig['gv_pan_images'] = $_POST['gv_pan_images'];  
			  $DIT_SGEConfig['gv_panel_scale'] = $_POST['gv_panel_scale'];  
			  $DIT_SGEConfig['gv_extra'] = $_POST['gv_extra'];  
	      }		  
		  
		  if (isset($_POST['saveviewer'])) {
		    $DIT_SGEConfig['viewer'] = $_POST['viewer']; 
			$DIT_SGEConfig['height'] = $_POST['height']; 
		    $DIT_SGEConfig['width'] = $_POST['width']; 
		  }	
		  
          gpFiles::SaveArray($ditSGE_cfFileName,'DIT_SGEConfig',$DIT_SGEConfig);
	  }
      $gv_selected = $DIT_SGEConfig['viewer'] == 'gv'?"selected='selected'":'';
	  $fb_selected = $DIT_SGEConfig['viewer'] == 'fb'?"selected='selected'":'';
	?>
	  <h1>Simple Gallery Extended</h1> - by <a target='nuut' href='http://www.dominion-it.co.za'>Dominion IT</a>;
	  
	  <p><form method='post'><p><label>Viewer to use : </label><select name='viewer'>
	  <option value='fb' <?php echo $fb_selected ?>>Fancy box</option>
	  <option value='gv' <?php echo $gv_selected ?>>Gallery Viewer</option>
	  </select><br/>
	  <label>Height</label>  <input type='text' name='height' value='<?php echo $DIT_SGEConfig['height']; ?>'><br/>
	  <label>Width</label>   <input type='text' name='width' value='<?php echo $DIT_SGEConfig['width']; ?>'><br/>
	  <input name='saveviewer' type='submit' value='Save'></p></form></p>
	  <hr/>
	  <p>Note, if you change any of these settings and the viewers are suddenly broken then you must look for funny mistyped characters and review the viewers documentation where needed.</p>
	  <hr/>
	  <p><a id='fancyb' href='#' style='border:1px solid black;background-color:silver;padding:2px;margin-right:3px;'>Fancy Box</a> <a  id='galview' href='#' style='border:1px solid black;padding:2px;margin-right:3px;'>Gallery Viewer</a></p>
	  
	  <div id='fancybbox'><p>Settings for Fancybox viewer. Please refer to <a target='nuut' href='http://fancyapps.com/fancybox/#docs'>fancybox documentation </a>for correct values.</p>
	   <form method='post'>
	     <p>
	      <label>Prev Effect</label>   <input type='text' name='fb_prevEffect' value='<?php echo $DIT_SGEConfig['fb_prevEffect']; ?>'><br/>
		  <label>Next Effect</label>   <input type='text' name='fb_nextEffect' value='<?php echo $DIT_SGEConfig['fb_nextEffect']; ?>'><br/>
		  <label>Open Effect</label>   <input type='text' name='fb_openEffect' value='<?php echo $DIT_SGEConfig['fb_openEffect']; ?>'><br/>
		  <label>Close Effect</label>  <input type='text' name='fb_closeEffect' value='<?php echo $DIT_SGEConfig['fb_closeEffect']; ?>'><br/>
		  <label>Custom settings</label><br/>  <textarea cols='60' rows='10' name='fb_extra'><?php echo $DIT_SGEConfig['fb_extra']; ?></textarea><br/>
		  <input type='submit' name='stoor_fb' value='Save Settings'>
		 </p> 
	   </form>
	  </div>
	  
	  <div id='galviewbox' style='display:none;'><p>Settings for Gallery viewer. Please refer to <a target='nuut' href='http://spaceforaname.com/galleryview/index.html'>gallery viewer documentation </a>for correct values.</p>
	  <form method='post'>
	  <p>
	      <label>Pan Images</label>   <input type='text' name='gv_pan_images' value='<?php echo $DIT_SGEConfig['gv_pan_images']; ?>'><br/>
		  <label>Panel Scale</label>   <input type='text' name='gv_panel_scale' value='<?php echo $DIT_SGEConfig['gv_panel_scale']; ?>'><br/>
		  <label>Custom settings</label><br/>  <textarea cols='60' rows='10' name='gv_extra'><?php echo $DIT_SGEConfig['gv_extra']; ?></textarea><br/>
		  <input type='submit' name='stoor_gv' value='Save Settings'>
		 </p> 
	     
	   </form>
	   </div>
	 <?php 
      $page->head .= "	  <script>
	     $(document).ready(function() {
	     $('#galview').click(function(){
		    $('#fancybbox').hide('fast');
			$('#galviewbox').show('slow');
			$('#galview').css('background-color','silver');
			$('#fancyb').css('background-color','transparent');
		  });
	     $('#fancyb').click(function(){
		    $('#galviewbox').hide('fast');
			$('#fancybbox').show('slow');
			$('#fancyb').css('background-color','silver');
			$('#galview').css('background-color','transparent');
		  });
         });	  
	  </script>
";
	}
}