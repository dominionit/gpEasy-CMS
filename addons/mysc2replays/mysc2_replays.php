<?php

class mysc2Replays{
     private $mySC2dbObj = null;
	 
	function mysc2Replays(){
	  //s
	}
	
	function showReplays($userid,$dbConnection = null){
	  if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
      }
	   if ($dbConnection == null) {
	      $dbConnection = new mySC2_db();
       }
	  
	  $replayID = isset($_GET['ri'])?$_GET['ri']:'-1';
	  if ($replayID != '-1') { //is this replay the users replay ?
  	    $tmpuserID = $dbConnection->get_replay_user($replayID);
		if ($tmpuserID != $userid) {
		  echo "<h1>Wrong replay queried for user</h1>";
		  return false;
		}
	   }	
	  $userinfo = $dbConnection->get_user($userid);
	  $authObj = new mysc2_auth();
	  $curProfInfo = null;
	  if ($authObj->is_logged_in()) {
	    $curProfInfo = $authObj->get_user_data();
	  }
	  $showEdits = common::LoggedIn() || ((isset($curProfInfo)) && ($curProfInfo['user']['user_id'] == $userinfo['user']['user_id']));
	  echo "<h1>Replays for : ".$userinfo['user']['sc2nick']."</h1>";
	  $notH = isset($userinfo['replays'][$replayID]['need_help']) && $userinfo['replays'][$replayID]['need_help'] == '1'?"checked='checked'":''; 
	  $notW = isset($userinfo['replays'][$replayID]['won']) && $userinfo['replays'][$replayID]['won'] == '1'?"checked='checked'":''; 
	  $replay_name = isset($userinfo['replays'][$replayID]['replay_name'])?$userinfo['replays'][$replayID]['replay_name']:'';
	  $your_race = isset($userinfo['replays'][$replayID]['your_race'])?$userinfo['replays'][$replayID]['your_race']:'terran';
	  $opponent_race = isset($userinfo['replays'][$replayID]['opponent_race'])?$userinfo['replays'][$replayID]['opponent_race']:'terran';
	  $game_type = isset($userinfo['replays'][$replayID]['game_type'])?$userinfo['replays'][$replayID]['game_type']:'1v1';
	  $replay_descr = isset($userinfo['replays'][$replayID]['replay_descr'])?$userinfo['replays'][$replayID]['replay_descr']:'Enter your description/view/experience of the game here...';
	  $oppenent_name = isset($userinfo['replays'][$replayID]['oppenent_name'])?$userinfo['replays'][$replayID]['oppenent_name']:'?';
	  $currReplayFile = isset($userinfo['replays'][$replayID]['filename'])?$userinfo['replays'][$replayID]['filename']:'';

	  if ($showEdits) {
	    $problem = '';
		if (isset($_POST['addnewr'])) {
		common::Redirect('?vreplays='.$userinfo['user']['user_id'] ,302);		
		}
	    if (isset($_POST['uprepl'])) {
		   if (isset($_FILES["filename"]["name"]) && $_FILES["filename"]["name"] != '') {
		     $allowedExts = array("sc2replay");
             $extension = strtolower(end(explode(".", $_FILES["filename"]["name"])));
			 if (in_array($extension, $allowedExts)) {
			   if ($_FILES["filename"]["error"] > 0) {
			     $problem = "Error: " . $_FILES["filename"]["error"] . "<br>";
			   } else if ($_FILES["filename"]["size"] > 1024000) {
			     $problem = 'File is to large. Over 1mb. Contact us to see if we can upload this file manually for you.';
			   } else {
		         $replayID = $dbConnection->update_replay($_POST,$userinfo['user']['user_id']);
			     common::Redirect('?vreplays='.$userinfo['user']['user_id'].'&ri='.$replayID ,302);
			   }	 
			 } else {
			   $problem = 'The file is not a sc2 replay file. Please only upload .sc2replay files.';
			 }
		   } else {
		       $replayID = $dbConnection->update_replay($_POST,$userinfo['user']['user_id']);
			   common::Redirect('?vreplays='.$userinfo['user']['user_id'].'&ri='.$replayID ,302);
		   }	   
		  $notH = isset($_POST['need_help']) ? $_POST['need_help'] : '0';
		  $notW = isset($_POST['won']) ? $_POST['won'] : '0';
		  $replay_name = isset($_POST['replay_name']) ? $_POST['replay_name'] : '';
		  $your_race = isset($_POST['your_race']) ? $_POST['your_race'] : 'terran';
		  $opponent_race = isset($_POST['opponent_race']) ? $_POST['opponent_race'] : 'terran';
		  $game_type = isset($_POST['game_type']) ? $_POST['game_type'] : '1v1';
		  $replay_descr = isset($_POST['replay_descr']) ? $_POST['replay_descr'] : '0';		   
		  $oppenent_name = isset($_POST['oppenent_name']) ? $_POST['oppenent_name'] : '?';		   
		} 
		
	    echo "<div  id='mysc2replayedit'>";
		echo "<p style='color:red'>$problem</p>";
		echo "<form method='post' enctype='multipart/form-data' id='mysc2replayform'><input type='hidden' name='uprepl' value='$replayID'><fieldset><legend>Add/Edit Replay:</legend><table>";
		echo "<tr><td>Replay Name</td> <td><input type='text' name='replay_name' value='".$replay_name."' width='130px'></td></tr>";
		echo "<tr><td>Need help</td> <td><input type='checkbox' id='need_help' name='need_help' value='1' $notH></td></tr>";
		echo "<tr><td>Did you win</td> <td><input type='checkbox' id='won' name='won' value='1' $notW></td></tr>";
		echo "<tr><td>Your Race</td> <td><select name='your_race'>".$this->get_race_list($your_race )."</select></td></tr>";
		echo "<tr><td>Opponent Race</td> <td><select name='opponent_race'>".$this->get_race_list($opponent_race)."</select></td></tr>";
		echo "<tr><td>Opponent Name</td> <td><input type='text' name='oppenent_name' value='".$oppenent_name."' width='130px'></td></tr>";
		echo "<tr><td>Game Type</td> <td><select name='game_type'>".$this->get_game_types($game_type)."</select></td></tr>";
		echo "<tr><td>Replay Description</td> <td><textarea cols='40' rows='10' name='replay_descr'>".$replay_descr."</textarea></td></tr>";
		echo "<tr><td>Replay File <font size='2px'>(ONLY .SC2Replay files - max size : 1mb)</font></td> <td><input type='file' name='filename' id='filename'></td></tr>";
		echo "<tr><td>Current Replay</td> <td>$currReplayFile</td></tr>";
		
		echo "</table><input type='submit' name='stoor' value='Save Replay'><input type='submit' name='addnewr' value='Add New'></fieldset></form>";
		echo "</div>";
	    echo "<h1>Replays list</h1>";
	  }
	 $this->displayReplayList($userinfo['replays'],$showEdits,$userinfo['user']['user_id']);
	}
	
	function displayReplayList($listtoShow,$showEdits = false,$userid = -1){
	  echo "<div id='mysc2replaylist'><table width='100%'>";
	  echo "<tr><th>Name</th><th>Won</th><th>Race</th><th>Oppenent</th><th>Need help</th></tr>";
	  foreach ($listtoShow as $replay) {
	  if ($showEdits) {
	    $name = common::Link('mySC2_Special',$replay['replay_name'],'vreplays='.$userid.'&ri='.$replay['replay_id']);
	  } else {
	    $name = common::Link('mySC2_Special',$replay['replay_name'],'sr='.$replay['replay_id']);
	    
	  }
	    global $addonRelativeCode;
	    $winimg =  $addonRelativeCode."/icons/win.png";
	    $loseimg =  $addonRelativeCode."/icons/lose.png";	  
		$helpimg = "<img src='".$addonRelativeCode."/icons/help_30.png' title='need help please'>";
		

		$need_help = $replay['need_help']==1?$helpimg:"";
		$your_race = ucwords($replay['your_race']);
		$opponent_race = ucwords($replay['opponent_race']);
	    $your_race_img = $addonRelativeCode."/icons/".strtolower($your_race)."_icon_small_30.png";
	    $opponent_race_img = $addonRelativeCode."/icons/".strtolower($opponent_race)."_icon_small_30.png";
		$won = $replay['won']==1?$winimg:$loseimg;		
		
	    echo "<tr><td>$name</td><td><img src='$won'></td><td><img src='$your_race_img' title='$your_race'></td><td><img src='$opponent_race_img' title='$opponent_race'></td><td>$need_help</td></tr>";
	  }
	  echo "</table></div>";
	}
	function display_replay($replayId,$dbConnection = null) {
	   if ($dbConnection == null) {
	      $dbConnection = new mySC2_db();
       }
	   
	   if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
       }
	  
	  $authObj = new mysc2_auth();   
	  $isloggedin = $authObj->is_logged_in(); 
	   
	  $tmpuserID = $dbConnection->get_replay_user($replayId);
	  
	  $userinfo = $dbConnection->get_user($tmpuserID);
	  $sc2nick = $userinfo['user']['sc2nick'];
	  $replay_name = $userinfo['replays'][$replayId]['replay_name'];
	  $your_race = $userinfo['replays'][$replayId]['your_race'];
	  $opponent_race = $userinfo['replays'][$replayId]['opponent_race'];
	  $replay_descr = $userinfo['replays'][$replayId]['replay_descr'];
	   $oppenent_name = $userinfo['replays'][$replayId]['oppenent_name'];
	  //$mySC2Profiles = new mySC2Profiles();
	  global $addonRelativeCode,$addonRelativeData;
	  $your_race_img = $addonRelativeCode."/icons/".strtolower($your_race)."_icon.png";
	  $opponent_race_img = $addonRelativeCode."/icons/".strtolower($opponent_race)."_icon.png";
	  $win =  $addonRelativeCode."/icons/win.png";
	  $lose =  $addonRelativeCode."/icons/lose.png";
	  $you_winlose = $userinfo['replays'][$replayId]['won'] == 1 ?$win :$lose;
      $opponet_winlose = $userinfo['replays'][$replayId]['won'] == 1 ?$lose:$win;
	  $filename =  $addonRelativeData."/replays/$tmpuserID/".$userinfo['replays'][$replayId]['filename'];
	  
	  if ($isloggedin) { 
	    $ratingS = "<span id='mysc2rating'>";
		$usrinf = $authObj->get_user_data();
		$userGedoen = $dbConnection->get_replay_rating_info($replayId,$usrinf['user']['user_id'],$curRValue);
		for ($x=1;$x < 6;$x++) {
		  if ($x <= $curRValue) {
		    $beeld = $addonRelativeCode."/icons/star_16.png";
		  } else {
		      $beeld = $addonRelativeCode."/icons/star_16_bw.png";
          }		  
		  if (!$userGedoen) {
		     $ratingS .= "<img src='$beeld'>";
		  } else {
		    $ratingS .= common::Link('mySC2_Special',"<img src='$beeld'>",'sr='.$replayId.'&rtrp='.$replayId.'&rtn='.$x);
		  }	
		}  
		$ratingS .="</span>";
	  } else {
	     $ratingS = "<span id='mysc2rating'>";
		$userGedoen = $dbConnection->get_replay_rating_info($replayId,-1,$curRValue);
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
	  echo "<div id='mysc2replay'>";
	  echo "<h1>Replay : $replay_name</h1>";
	  echo "<div id='mysc2replaybox'>";
	  
	  echo "<div id='mysc2VSimg'><div><img src='$you_winlose'><img src='$your_race_img'> <span>VS<span> <img  src='$opponent_race_img'><img src='$opponet_winlose'></div>
	  <div><span id='mycs2yourname'>".common::Link('mySC2_Special',$sc2nick,'viewp='.$tmpuserID)."</span><span id='mycs2opponentname'>$oppenent_name</span></div></div>";
	  echo "<div style='padding-top:10px;clear:both;'><span><a href='$filename'>Download Replay</a></span>$ratingS</div>";
	 echo "<p>$replay_descr<p>";
	  //echo "<div id='mysc2VSimg'><img style='z-order:999;background-image:url($you_winlose);background-position:0px 0px; background-repeat:no-repeat;' src='$your_race_img'> <span>VS<span> <img style='z-order:999;background-image:url($opponet_winlose);background-position:60px 0px; background-repeat:no-repeat;' src='$opponent_race_img'></div>";
	  echo "</div>";
	  
	  //$mySC2Profiles->show_profile_block($userinfo);
	  echo "</div>";
	  $this->show_replay_comments($replayId,$dbConnection);
	
	}
	
	function show_replay_comments($replayID,$dbConnection = null){
	   if ($dbConnection == null) {
	      $dbConnection = new mySC2_db();
       }
	  if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
      }
	  
	  $authObj = new mysc2_auth();
	  $isloggedin = $authObj->is_logged_in(); 
	  
	   if (isset($_POST['rcomment']) && $isloggedin){
	      $curProfInfo = $authObj->get_user_data();
	      $dbConnection->post_comment($replayID,$_POST['rcomment'],$curProfInfo['user']['user_id']);
	   }
	   
	   global $addonRelativeCode,$addonRelativeData;
	   
	  echo "<div id='mysc2replaycomments'>";
	  echo "<h1>Comments..</h1><br/>";
	  $tmpcomments = $dbConnection->get_replay_comments($replayID);
	  //error_log(print_r($tmpcomments,true));
	  $isloggedin = $authObj->is_logged_in();
	  foreach ($tmpcomments as $commentid => $replay) {
	      $picid = $replay['pic_id'];
		  $userid = $replay['user_id'];
		  $sc2nick = $replay['sc2nick'];
		  $replay_id = $replay['replay_id'];
	     $picimg = $addonRelativeCode.'/portraits/portraits'.$picid[0].'.jpg';
	     $picrow = -1 * $picid[2] * 90;
	     $piccol = -1 * $picid[1] * 90;
	  
	    echo "<div class='replay_comment_item'>";
		echo "<div class='replay_comment_usrprfl'><img id='mysc2portraitimg' ' style='background: url(\"$picimg\") ".$piccol."px ".$picrow."px no-repeat; width: 90px; height: 90px;'><br/>
		<a href='?viewp=$userid'> $sc2nick </a></div>";
		$reportComment = common::Link('mySC2_Special','Report','sr='.$replay_id.'&repc='.$commentid,"class='reportcomment'");//"<a href='?repc=$commentid' class='reportcomment'>Report Comment</a>";
		if ($isloggedin) {
		  echo "<div class='replay_comment_msg'><p>".htmlentities($replay['comment'])."</p>$reportComment</div>";
		} else {
		 echo "<div class='replay_comment_msg'><p>".htmlentities($replay['comment'])."</p></div>";
		} 
		echo "</div>";
	  }
	  
	  if ($isloggedin) {
	    //$curProfInfo = $authObj->get_user_data();
		echo "<div id='mysc2addcomment'><form method='post'>";
		echo "<p>Comment</p>";
		echo "<textarea name='rcomment' cols='60' rows='10'></textarea><br/>";
		echo "<input type='submit' name='scom' value='Post Comment'>";
		echo "</form></div>";
		echo "<script>
		  $('.reportcomment').click(function(){
		     return confirm('Are you sure you want to report this comment. Note comment is not deleted, we are just notified to look at it.');
		  });
		</script>";
	  }
	  echo "</div>";
	}
	private function get_race_list($prefrace){
	  $racelist = '';
	  if (strtolower($prefrace) == 'terran') {  $racelist .= "<option selected='selected'>Terran</option>"; } else { $racelist .=  "<option>Terran</option>";  };
	  if (strtolower($prefrace) == 'protos') { $racelist .=  "<option selected='selected'>Protos</option>"; } else { $racelist .=  "<option>Protos</option>";  };
	  if (strtolower($prefrace) == 'zerg') { $racelist .=  "<option selected='selected'>Zerg</option>"; } else { $racelist .=  "<option>Zerg</option>";  };
	  if (strtolower($prefrace) == 'random') { $racelist .=  "<option selected='selected'>Random</option>"; } else { $racelist .=  "<option>Random</option>";  };
	  return $racelist;
	}	
	
	private function get_game_types($gametype){
	  $gameTlist = '';
	  if (strtolower($gametype) == '1v1') {  $gameTlist .= "<option selected='selected'>1v1</option>"; } else { $gameTlist .=  "<option>1v1</option>";  };
	  if (strtolower($gametype) == '2v2') {  $gameTlist .= "<option selected='selected'>2v2</option>"; } else { $gameTlist .=  "<option>2v2</option>";  };	  
      if (strtolower($gametype) == '3v3') {  $gameTlist .= "<option selected='selected'>3v3</option>"; } else { $gameTlist .=  "<option>3v3</option>";  };	  	  
      if (strtolower($gametype) == '4v4') {  $gameTlist .= "<option selected='selected'>4v4</option>"; } else { $gameTlist .=  "<option>4v4</option>";  };	  	  	  
	  return $gameTlist;
	}	
	
}	
?>