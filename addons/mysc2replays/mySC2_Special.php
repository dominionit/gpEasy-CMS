<?php
defined('is_running') or die('Not an entry point...');
if( !class_exists('gp_recaptcha') ){
	includeFile('tool/recaptcha.php');
}

include "mySC2_db.php";
include "mysc2_replays.php";
include "mysc2_Profiles.php";


class mySC2Special{
	function mySC2Special(){
			if (isset($_GET['register'])) {
			  $this->register();
			} else if (isset($_GET['forgot'])) {
			  $this->forgot();
			} else if (isset($_GET['viewp'])) {
			  $this->show_profile($_GET['viewp']);
			  
			} else if (isset($_GET['vreplays'])) {
			  $this->show_replays($_GET['vreplays']);
			} else if (isset($_GET['sr'])) {
			  $this->display_replay($_GET['sr']);		  
			} else {
			  $this->show_last_replays();
			}
		
	}
	
	function register(){
	  
	  $problem = '';  
	  $newuser =false;
	  $mySC2dbObj = new mySC2_db();
	  if (isset($_POST['what'])) {
	      if ($mySC2dbObj->user_exist($_POST['username'])) {
		    $problem = 'Username already exist, please try another.'; 
		  }
		  
		  if (gp_recaptcha::isActive() ){
			if( !gp_recaptcha::Check() ){
					//recaptcha::check adds message on failure
			   $problem = 'Recaptcha text did not match.';		
			}
		  }
		  
		  if (trim($_POST['username']) == '') {
		       $problem = 'Username must be supplied.';		
		  }
		  
		  if ($_POST['pass1'] <> $_POST['pass2']) {
		    $problem = 'Passwords do not match.';		
		  }

		  $agreetc = isset($_POST['agreetc'])?$_POST['agreetc']:'0';
		  if ($agreetc != '1') {
		    $problem = 'You must agree with terms and conditions to continue.';				  
		  }
		  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		     $problem = 'Please supply a valid e-mail address.';				  
          }
	  
		  if ($problem == '') { //all fine
		    $usertoCreate = $_POST;
			$newuser =  $mySC2dbObj->create_user($usertoCreate);
			if( !class_exists('mysc2_auth') ){
				include "mysc2_auth.php";
			}		   
			$authObj = new mysc2_auth();
			$authObj->login($_POST['username'],$_POST['pass1']);
		  }
		  
      }
 	  
	  if ($newuser == false){
	  //register a new account
	     $mySC2Profiles = new mySC2Profiles();
	     $mySC2Profiles->ProfileRegisterform($problem);
	 } else {
	   common::Redirect('?viewp='.$newuser,302);
	 }
	 
	}
	
	function forgot(){
	 //user forgot his password
	}
	
	function show_profile($profileid){
	   if (isset($_GET['rupn'])) {
	   
	     $tursid = $_GET['rupn'];
		 $rtn = $_GET['rtn'];
		 if( !class_exists('mysc2_auth') ){
            include "mysc2_auth.php";
          }
		  
	      $authObj = new mysc2_auth();  		  
		  if ($authObj->is_logged_in()) {
		    $mySC2dbObj = new mySC2_db();
			$usrinf = $authObj->get_user_data();
			$mySC2dbObj->set_user_rating($tursid,$rtn,$usrinf['user']['user_id']);
			common::Redirect('?viewp='.$profileid,302);
		  } else {
		    die('Logic error, cannot rate if not logged in.');
		  }
	   } else {
	    $mySC2Profiles = new mySC2Profiles();
		$mySC2Profiles->showProfile($profileid,null);
	   }
	}
	
	function show_replays($profileid){
	    $mysc2ReplaysObj = new mysc2Replays();
		$mysc2ReplaysObj->showReplays($profileid,null);
	}
	
	function display_replay($replayID){
	    if (isset($_GET['rtrp'])) {
		  $rid = $_GET['rtrp'];
		  $rt = $_GET['rtn'];
	      if( !class_exists('mysc2_auth') ){
            include "mysc2_auth.php";
          }
		  
	      $authObj = new mysc2_auth();  		  
		  if ($authObj->is_logged_in()) {
		    $mySC2dbObj = new mySC2_db();
			$usrinf = $authObj->get_user_data();
			$mySC2dbObj->set_replay_rating($rid,$rt,$usrinf['user']['user_id']);
			common::Redirect('?sr='.$rid,302);
		  } else {
		    die('Logic error, cannot rate if not logged in.');
		  }
		} else {
	      $mysc2ReplaysObj = new mysc2Replays();
		  $mysc2ReplaysObj->display_replay($replayID);
		}  
	}
	
	function show_last_replays(){
	  $mySC2dbObj = new mySC2_db();
	  $lastReplays =  $mySC2dbObj->get_last_replays(100);
	  $mysc2ReplaysObj = new mysc2Replays();
	  
	  echo "<h1>Newest Replays</h1><br/>";
	  $mysc2ReplaysObj->displayReplayList($lastReplays,false,-1);
	}
}
?>