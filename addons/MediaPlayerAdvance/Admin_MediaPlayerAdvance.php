<?php
/*
  Programmer : Johannes Pretorius
  Company : Dominion IT
  URL : http://www.dominion-it.co.za
  Purpose : Mp3 Player with tags in pages to place players , also multi players to select from
  Version : 1.0
*/

defined('is_running') or die('Not an entry point...');


class Admin_MediaPlayerAdvance{
	function Admin_MediaPlayerAdvance(){
	//Config,config
	global $addonPathCode,$addonRelativeCode,$addonPathData;

     require_once($addonPathCode.'/dominion-mp3player/dominion-mp3player.php'); 
   $mp3 = @new DominionMp3Player();
	$plugins =  $mp3->getPluginsInfo();
	 global $SITEURL;
	 $screenshotpath = $addonRelativeCode."/dominion-mp3player/plugins/screenshots/";
	 $savePath = $addonPathData.'/mp3playersettings.cfg';
	 if (!file_exists($savePath)) {
	   mkdir($addonPathData,0777,true);
	   $verstek = $addonPathCode.'/dominion-mp3player/mp3playersettings.cfg';
	   copy($verstek, $savePath);
	 }
    if(isset($_POST['stoor']) && $_POST['stoor'] == 'Save') {
            $color  = $_POST['kleur'] ;
			$player  = $_POST['playertouse'];
			$usejquery = isset($_POST['usejquery'])?'1':'0';
			foreach ($plugins as $extend){
			  if ( $extend['filename'] == $player) {
			    $playerclass  = $extend['classname'] ;     
			  }
			}
			
            $filePointer = fopen($savePath,"w");
            fwrite($filePointer,"bgcolor=$color \n\r");
			fwrite($filePointer,"player=$player \n\r");
			fwrite($filePointer,"playerclass=$playerclass \n\r");
			fwrite($filePointer,"playerclass=$playerclass \n\r");
			fwrite($filePointer,"usejquery=$usejquery \n\r"); //removed jquery breaks gpEasy
            fclose($filePointer);
            unset($tmpS,$filePointer);    
			$usejquery =  isset($_POST['usejquery'] )?'checked="checked"':'';
    }    else {
      if (is_file($savePath )) {
	    $cfgSettings = parse_ini_file($savePath );
		$player  = $cfgSettings['player'];
	    $color = $cfgSettings['bgcolor'];
        $usejquery = ($cfgSettings['usejquery'] == '1')?'checked="checked"':'';
      } else {
        $color = '#FFFFFF';
		$usejquery = '';
      }
      
    }
	reset($plugins);
	$players = "";
	
	foreach ($plugins as $extend){
	   $name = $extend['name'];
	   $descr = $extend['description'];
	   $scfreenshot = $extend['screenshot'];
	   if ($scfreenshot != '') {
	     $scfreenshot = $screenshotpath.$scfreenshot;
	  }	 
	   $filename = $extend['filename'];
	   $classname = $extend['classname'];
	   
	   
	   $descr = str_replace("'",'',$descr);
	   $selected =  ($player == $filename)?"selected='selected'":'';
	   
	  $players .= "<option $selected value='$filename' scr='$scfreenshot' descr='$descr'>$name</option>";
	}
  

?>
<form action="<?php	echo $_SERVER ['REQUEST_URI']?>"  method="post" id="management">
  <p><h1>Media Player - by <a href='http://www.dominion-it.co.za'>Dominion IT</a></h1>
     <br/>How  to use the plugin : <br/>
	  Just add anywhere in your page  the following tag  (% mp3:filename %) <br/>
     (The file must exist under uploads/media/) where the filename is the <br/>
	 name of the MP3 file you have uploaded (note it can be case sensitive). <br/>
	 For example if I have uploaded <i>My personal Song.mp3</i> to the media directory under <br/>
	 uploads then the tag will be <br/>
	 <b>(% mp3:My personal Song.mp3 %)</b>.
	 <br/>
	 <br/>
	 Also NOTE : If you want to put in multiple files in 1 player (playlist)<br/> 
	 seperate file names with <b>;
	 </b>
	 <br/>
	 For example : (% mp3:My personal Song.mp3;OtherSong.mp3;Another One.mp3 %)<br/>
	 <br/>
     Version 1.1<br/>
	 Note : Future versions will have beter config for the filenames and playlists.</p>
 <p>
  Use jQuery : <input type='checkbox' name='usejquery' value='1' <?php echo $usejquery; ?>><br/>  
 Background color : <input type='text' name='kleur' value='<?php echo $color; ?>'> (hex color)<br/>
    Player to Use : <select name='playertouse' id='playertouse'><?php echo $players;?></select></br>
	
    <input type='submit' name='stoor' value='Save'>
	<span id='playerinfo'><span><br/></p>
	
</form>
<script>
 $('#playertouse').change(function() {
   var scr = $("#playertouse option:selected").attr('scr');
   var descr = $("#playertouse option:selected").attr('descr');
   if (scr != ''){
    $('#playerinfo').html('<p><img src="'+scr+'"><br/>'+descr+'</p>');
   } else {
     $('#playerinfo').html('<p>'+descr+'</p>');
   }	
});
var scr = $("#playertouse option:selected").attr('scr');
   var descr = $("#playertouse option:selected").attr('descr');
   if (scr != ''){
    $('#playerinfo').html('<p><img src="'+scr+'"><br/>'+descr+'</p>');
   } else {
     $('#playerinfo').html('<p>'+descr+'</p>');
   }	
</script>
<?php  
	}
}