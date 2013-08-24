<?php

defined('is_running') or die('Not an entry point...');



class mySC2Gadge{

	function mySC2Gadge(){
	  if( !class_exists('mysc2_auth') ){
          include "mysc2_auth.php";
      }

		   $authObj = new mysc2_auth();

   	    $loggedIn = $authObj->is_logged_in();
	    if (isset($_POST['sc2login'])) {
		   if ($userid = $authObj->login($_POST['username'],$_POST['pass'])) {
		     $loggedIn = true;
			 common::Redirect('?viewp='.$userid ,302);
		   }
		}
		if (isset($_GET['logout'])) {
		   $authObj->logout();
		        $loggedIn = false;
				common::Redirect('?loggedout=1',302);
		}
		
		if (!$loggedIn) {
			echo '<h2>Account Login</h2>';
			echo '<div id="sc2login">';
			echo '<form method="post"><input type="hidden" name="sc2login" value="1"> <table>
			<tr><td><label>Username</label> </td><td><input type="text" name="username"></td></tr>
			<tr><td><label>Password </label></td><td><input type="password" name="pass"></td></tr>
			<tr><td colspan="2"><input type="submit" value="login"></td></tr></table></form>';
			
			echo "<p>".common::Link('mySC2_Special','Register','register=1')." | ".common::Link('mySC2_Special','Forgot Password','forgot=1');
			echo '</div>';
		} else {
		   $usrinfo = $authObj->get_user_data();
		  echo '<h2>Profile</h2>';
		  echo '<div id="sc2profile">';
		  echo common::Link('mySC2_Special','View Profile','viewp='.$usrinfo['user']['user_id']);
		  echo "<br/>";
		  echo common::Link('mySC2_Special','Manage Replays','vreplays='.$usrinfo['user']['user_id']);
		  echo "<br/>";
		  
		  echo common::Link('mySC2_Special','Logout','logout=1');
		  echo '</div>';
        }		
	}

}
