<?php
//if( common::LoggedIn() ){

//include "mySC2_db.php";


class mySC2Profiles{
     private $mySC2dbObj = null;
	 
	function mySC2Profiles(){
	  //s
	}
	
	function showProfile($userID,$dbConnection = null){
	  if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
      }
	  
	  
	   if ($dbConnection == null) {
	      $dbConnection = new mySC2_db();
       }
	  $userinfo = $dbConnection->get_user($userID);
	  $authObj = new mysc2_auth();
	  $curProfInfo = null;
	  if ($authObj->is_logged_in()) {
	    $curProfInfo = $authObj->get_user_data();
	  }
	  $showEdits = common::LoggedIn() || ((isset($curProfInfo)) && ($curProfInfo['user']['user_id'] == $userinfo['user']['user_id']));
	  $picid = isset($userinfo['user']['pic_id']) && ($userinfo['user']['pic_id'] <> '')?$userinfo['user']['pic_id']:'011';
	  
	  global $addonRelativeCode;
	  $picimg = $addonRelativeCode.'/portraits/portraits'.$picid[0].'.jpg';
	  $picrow = -1 * $picid[2] * 90;
	  $piccol = -1 * $picid[1] * 90;
	  
	  if ($showEdits ==  true) {
	       if (isset($_POST['prfupd'])) {
		      $dbConnection->update_user($_POST,$userinfo['user']['user_id']);
			   common::Redirect('?viewp='.$userinfo['user']['user_id'] ,302);
		   }
		   
		   $notL = isset($userinfo['user']['notify_local_t']) && $userinfo['user']['notify_local_t'] == '1'?"checked='checked'":'';
		   $notOnL = isset($userinfo['user']['notify_online_t']) && $userinfo['user']['notify_online_t'] == '1'?"checked='checked'":'';
		   
	      echo "<div>";
		  echo "<form method='post' action='' id='sc2editprofile'><input type='hidden' name='prfupd' value='1'>";
		  echo "<h1>Profile : ".$userinfo['user']['sc2nick']."</h1>";
		  
		  echo "<table>";
		  echo "<tr><td>Portrait</td><td><input type='hidden' id='pic_id' name='pic_id' value='".$picid."'><input type='hidden' id='picpth' name='picpth' value='".$addonRelativeCode.'/portraits/portraits'."'><a href='javascript:void(0)' id='mysc2left'>&larr;</a><img id='mysc2portraitimg' ' style='background: url(\"$picimg\") ".$piccol."px ".$picrow."px no-repeat; width: 90px; height: 90px;'><a href='javascript:void(0)' id='mysc2right'>&rarr;</a></td></tr>";
		  echo "<tr><td>Username</td><td>".$userinfo['user']['username']."</td></tr>";
		  echo "<tr><td>Sc2 Nick</td><td><input type='text' name='sc2nick' value='".$userinfo['user']['sc2nick']."'></td></tr>";
		  echo "<tr><td>Sc2 character Code</td><td><input type='text' name='char_code' value='".$userinfo['user']['char_code']."'></td></tr>";
		  echo "<tr><td>E-mail</td><td><input type='text' name='email' value='".$userinfo['user']['email']."'></td></tr>";
		  
		  echo "<tr><td>Description</td><td><textarea cols='40' rows='5' name='descr'>".$userinfo['user']['descr']."</textarea></td></tr>";
		  
		  echo "<tr><td>Prefered Race</td><td><select name='pref_race'>".$this->get_pref_race_list($userinfo['user']['pref_race'])."</select></td></tr>";
		  echo "<tr><td>Country</td><td><select name='country'>".$dbConnection->country_list($userinfo['user']['country'])."</select></td></tr>";
		  echo "<tr><td>B.Net Region</td><td><select name='region'>".$this->get_region($userinfo['user']['region'])."</select></td></tr>";
		  echo "<tr><td>League</td><td><select name='league'>".$this->get_league($userinfo['user']['league'])."</select></td></tr>";
		  echo "<tr><td colspan='2'><input type='checkbox' id='notify_local_t' name='notify_local_t' value='1' $notL>  Local tournaments notify </td></tr>";
		  echo "<tr><td colspan='2'><input type='checkbox' id='notify_online_t' name='notify_online_t' value='1' $notOnL> Online Tournaments notify </td></tr>";
		  
  		  echo "<tr><td>Passsword</td><td>".common::Link('mySC2_Special','Change Password','cp=1')."</td></tr>";	    
		  
		  //<form method='post'><input type='hidden' name='cp' value='1'><input type='submit' value='Change'></form>
		  echo "</table>";
		  echo "<script>
		    var imgID = $('#pic_id').val();
			
			$('#mysc2left').click(function() {
				if (imgID != '000')	{
  				  if ((imgID.charAt(0) >= 1) && (imgID.charAt(1) == 0) && (imgID.charAt(2) == 0)) {
				    imgID = (imgID.charAt(0)-1) + '55';
				  } else {
				    if (imgID.charAt(1) >0) {
					  imgID = imgID.charAt(0) + (imgID.charAt(1) - 1) + imgID.charAt(2) ;
					} else {
					  imgID = imgID.charAt(0) + 5 + (imgID.charAt(2) - 1);
					}
					
					if (imgID.charAt(2) < 0) {
					  imgID = imgID.charAt(0) + imgID.charAt(10) +0 ;
					}
					if (imgID.charAt(1) < 0) {
					  imgID = imgID.charAt(0) + 0 +imgID.charAt(2) ;
					}
				  }
				}
				$('#pic_id').val(imgID);
				var row = -1 * parseInt(imgID.charAt(2)) * 90;
				var col = -1 * parseInt(imgID.charAt(1)) * 90;
				$('#mysc2portraitimg').css('background-position-y',row);
				$('#mysc2portraitimg').css('background-position-x',col);
				$('#mysc2portraitimg').css('background-position',col+'px '+row+'px');
				
				//alert($('#picpth').val() + imgID.charAt(0) + '.jpg');
				var imgName =  'url(\"'+$('#picpth').val() + imgID.charAt(0) + '.jpg\")';
				if ($('#mysc2portraitimg').css('background-image') != imgName) {
				  $('#mysc2portraitimg').css('background-image',imgName);
				}  
			});
			
			$('#mysc2right').click(function() {
				if (imgID != '255')	{
  				  if ((imgID.charAt(0) <= 1) && (imgID.charAt(1) == 5) && (imgID.charAt(2) == 5)) {
				    imgID = (parseInt(imgID.charAt(0)) + 1) + '00';
				  } else {
				    if (imgID.charAt(1) < 5) {
					  imgID = imgID.charAt(0) + (parseInt(imgID.charAt(1)) + 1) + imgID.charAt(2) ;
					} else {
					  imgID = imgID.charAt(0) + 0 + (parseInt(imgID.charAt(2)) + 1);
					}
					
					if (imgID.charAt(2) > 5) {
					  imgID = imgID.charAt(0) + parseInt(imgID.charAt(1)) +5 ;
					}
					if (imgID.charAt(1) > 5) {
					  imgID = parseInt(imgID.charAt(0)) + 5 +imgID.charAt(2) ;
					}
				  }
				}
				$('#pic_id').val(imgID);
				var row = -1 * imgID.charAt(2) * 90;
				var col = -1 * imgID.charAt(1) * 90;
				$('#mysc2portraitimg').css('background-position-y',row);
				$('#mysc2portraitimg').css('background-position-x',col);
				$('#mysc2portraitimg').css('background-position',col+'px '+row+'px');
				
				var imgName = 'url('+ $('#picpth').val() + imgID.charAt(0) + '.jpg)';
				$('#mysc2portraitimg').css('background-image',imgName);
			});
			
		  </script>";
		  echo "<input type='submit' name='store' value='Save'></form></div>";
	  } else {
	      $this->show_profile_block($userinfo);
		  echo "<div id='mysc2showreplays'>";
		   $mysc2ReplaysObj = new mysc2Replays();
		   $mysc2ReplaysObj->showReplays($userID,null);
		  echo "</div>";
      }	   
	   
	}
	
	function show_profile_block($userObj){
	  $picid = isset($userObj['user']['pic_id']) && ($userObj['user']['pic_id'] <> '')?$userObj['user']['pic_id']:'000';
	  $userid = $userObj['user']['user_id'];
	  
	  global $addonRelativeCode;
	  $picimg = $addonRelativeCode.'/portraits/portraits'.$picid[0].'.jpg';
	  $picrow = -1 * $picid[2] * 90;
	  $piccol = -1 * $picid[1] * 90;
	  $sc2nick = $userObj['user']['sc2nick'];
	  $league = strtolower($userObj['user']['league']);
      $dbConnection = new mySC2_db();
	   if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
       }
	  
	  $authObj = new mysc2_auth();   
	  $isloggedin = $authObj->is_logged_in(); 
	  
      if ($isloggedin) { 
	    $ratingS = "<span id='mysc2rating'>";
		$usrinf = $authObj->get_user_data();
		$userGedoen = $dbConnection->get_user_rating_info($userid,$usrinf['user']['user_id'],$curRValue);
		for ($x=1;$x < 6;$x++) {
		  if ($x <= $curRValue) {
		    $beeld = $addonRelativeCode."/icons/star_16.png";
		  } else {
		      $beeld = $addonRelativeCode."/icons/star_16_bw.png";
          }		  
		  if (!$userGedoen) {
		     $ratingS .= "<img src='$beeld'>";
		  } else {
		    $ratingS .= common::Link('mySC2_Special',"<img src='$beeld'>",'viewp='.$userid.'&rupn='.$userid.'&rtn='.$x);
		  }	
		}  
		$ratingS .="</span>";
	  } else {
	     $ratingS = "<span id='mysc2rating'>";
		$userGedoen = $dbConnection->get_user_rating_info($userid,-1,$curRValue);
		for ($x=1;$x < 6;$x++) {
		  if ($x <= $curRValue) {
		    $beeld = $addonRelativeCode."/icons/star_16.png";
		  } else {
		      $beeld = $addonRelativeCode."/icons/star_16_bw.png";
          }
		  $ratingS .= "<img src='$beeld'>";
		}
	    $ratingS .="</span>";
      }
	  
	  echo "<div id='mysc2profileblock'>";
	  echo "<div id='mysc2portrait' ><div id='league_$league'></div><img style='background: url(\"$picimg\") ".$piccol."px ".$picrow."px no-repeat; width: 90px; height: 90px;' /></div>";
	  echo "<div id='mysc2profiledata'><table>";
	  echo "<tr><td>".common::Link('mySC2_Special',$sc2nick,'viewp='.$userid)."</td></tr>";
	  
	  
	  echo "</table> </div>";
	  echo "<div>$ratingS</div>";
	  echo "</div>";
	}
	
	function ProfileRegisterform($problem='',$dbConnection = null){
      $username = isset($_POST['username'])?$_POST['username']:'';
	  $pass1 = isset($_POST['pass1'])?$_POST['pass1']:'';
	  $pass2 = isset($_POST['pass2'])?$_POST['pass2']:'';
	  $sc2nick = isset($_POST['sc2nick'])?$_POST['sc2nick']:'';
	  $email = isset($_POST['email'])?$_POST['email']:'';
	  //$char_code = isset($_POST['char_code'])?$_POST['char_code']:'';
	  //$pref_race = isset($_POST['pref_race'])?$_POST['pref_race']:'';
	  $agreetc = isset($_POST['agreetc'])?$_POST['agreetc']:'0';
	  
	  
	  echo "<div id='mysc2register'>";
	  echo "<h1>Register Account at mySC2 Replays</h1><br/><p style='color:red'>$problem</p>";
	  echo "<form method='post' id='f_register'>";
	  echo "<input type='hidden' name='what' value='r'>";
	  echo "<table>";
	  echo "<tr><td>Username *</td><td><input type='text' name='username' id='username' value='$username'></td></tr>";
	  echo "<tr><td>Password *</td><td><input type='password' id='pass1' name='pass1' value='$pass1'></td></tr>";
	  echo "<tr><td>Confirm Password *</td><td><input type='password' id='pass2' name='pass2' value='$pass2'></td></tr>";
	  echo "<tr><td>SC2 Nickname</td><td><input type='text' name='sc2nick' value='$sc2nick'></td></tr>";
	  
	  //echo "<tr><td>SC2 Character Code</td><td><input type='text' name='char_code' value='$char_code'></td></tr>";
	  //echo "<tr><td>Prefered Race</td><td><select name='pref_race'>".$this->get_pref_race_list($pref_race)."</select></td></tr>";
	  echo "<tr><td>E-mail *</td><td><input type='text' name='email' id='email' value='$email'></td></tr>";
	  echo "<tr><td>Agree to T&C *</td><td><input type='checkbox' id='agreetc' name='agreetc' value='1' ></td></tr>";
	  
	  if (gp_recaptcha::isActive()) {
          echo '<tr><td colspan="2"><div>';
			echo gpOutput::GetAddonText('captcha');
		  echo '</div>';
  		  gp_recaptcha::Form();
		  echo '</td></tr>';	  
	  }	
	  echo "<tr><td></td><td><input type='submit' id='submit' name='submit' value='Create'></td></tr>";
	  echo "</table>";
	  echo "</form>";
	  echo "<script>
	  function isValidEmailAddress(emailAddress) {
           var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
          return pattern.test(emailAddress);
     };

	     $('#submit').click(function(){
		    if ($('#pass1').val() != $('#pass2').val()) {
			   alert('Passwords dont match, please retry.');
			   return false;
			}
		    if ($('#pass1').val().length < 6) {
			   alert('Passwords must be at least 6 characters long.');
			   return false;
			}
			
			
			if ($('#username').val().trim() == '') {
			  alert('Please provide a username.');
			  return false;
			}
			if (!$('#agreetc').prop('checked')) {
			  alert('You must agree to our terms and conditions to continue.');
			  return false;
			}
			if ($('#email').val().trim() == '') {
			  alert('Please provide a email address.');
			  return false;
			}
			if( !isValidEmailAddress( $('#email').val() ) ) { 
			  alert('Please provide a valid email address.');
			  return false;
			}
		 });
	  </script>";
	  echo "</div>";
	}
	
	private function get_pref_race_list($prefrace){
	  $racelist = '';
	  if (strtolower($prefrace) == 'terran') {  $racelist .= "<option selected='selected'>Terran</option>"; } else { $racelist .=  "<option>Terran</option>";  };
	  if (strtolower($prefrace) == 'protos') { $racelist .=  "<option selected='selected'>Protos</option>"; } else { $racelist .=  "<option>Protos</option>";  };
	  if (strtolower($prefrace) == 'zerg') { $racelist .=  "<option selected='selected'>Zerg</option>"; } else { $racelist .=  "<option>Zerg</option>";  };
	  if (strtolower($prefrace) == 'random') { $racelist .=  "<option selected='selected'>Random</option>"; } else { $racelist .=  "<option>Random</option>";  };
	  return $racelist;
	}
	
	private function get_region($region){
	  $regionlist = '';
	  if (strtolower($region) == 'us') {  $regionlist .= "<option value='us' selected='selected'>Americas</option>"; } else { $regionlist .=  "<option value='us'>Americas</option>";  };
	  if (strtolower($region) == 'eu') {  $regionlist .= "<option value='eu' selected='selected'>Europe</option>"; } else { $regionlist .=  "<option value='eu'>Europe</option>";  };
	  if (strtolower($region) == 'kr') {  $regionlist .= "<option value='kr' selected='selected'>Korea / Taiwan</option>"; } else { $regionlist .=  "<option value='kr'>Korea / Taiwan</option>";  };
	  if (strtolower($region) == 'sea') {  $regionlist .= "<option value='sea' selected='selected'>Southeast Asia</option>"; } else { $regionlist .=  "<option value='sea'>Southeast Asia</option>";  };
	  //if (strtolower($region) == 'sea') {  $regionlist .= "<option value='sea' selected='selected'>Southeast Asia</option>"; } else { $regionlist .=  "<option value='sea'>Southeast Asia</option>";  };
	  return $regionlist;
	}	

	private function get_league($league){
	  $leaguelist = '';
	  if (strtolower($league) == 'bronze') {  $leaguelist .= "<option selected='selected'>Bronze</option>"; } else { $leaguelist .=  "<option>Bronze</option>";  };
	  if (strtolower($league) == 'silver') {  $leaguelist .= "<option selected='selected'>Silver</option>"; } else { $leaguelist .=  "<option>Silver</option>";  };	  
	  if (strtolower($league) == 'gold') {  $leaguelist .= "<option selected='selected'>Gold</option>"; } else { $leaguelist .=  "<option>Gold</option>";  };	  
	  if (strtolower($league) == 'platinum') {  $leaguelist .= "<option selected='selected'>Platinum</option>"; } else { $leaguelist .=  "<option>Platinum</option>";  };	  
	  if (strtolower($league) == 'diamond') {  $leaguelist .= "<option selected='selected'>Diamond</option>"; } else { $leaguelist .=  "<option>Diamond</option>";  };	  
	  if (strtolower($league) == 'master') {  $leaguelist .= "<option selected='selected' value='master'>Master League</option>"; } else { $leaguelist .=  "<option value='master'>Master League</option>";  };	  
      if (strtolower($league) == 'grand') {  $leaguelist .= "<option selected='selected' value='grand'>Grand Master League</option>"; } else { $leaguelist .=  "<option value='grand'>Grand Master League</option>";  };	  	  
	  return $leaguelist;
	}	
	
}	
?>