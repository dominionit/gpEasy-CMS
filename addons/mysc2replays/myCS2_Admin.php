<?php
defined('is_running') or die('Not an entry point...');

include "mySC2_db.php";

class mySC2Admin{
    private $mySC2dbObj = null;
	
	function mySC2Admin(){
	  $this->mySC2dbObj = new mySC2_db();
	   if (isset($_GET['userslist'])) {
	     $this->get_userslist();
	  } else if (isset($_GET['forcemetaupdate'])) {
	    $this->meta_update();
	  }	  else {
	   $this->menu();
	  }
	}
	
	function menu(){
	  echo "<div class='sc2admin'>";
	  echo common::Link('Admin_mySC2_Admin','User List','userslist=1')."<br/>";
	  echo common::Link('Admin_mySC2_Admin','Force Meta Update','forcemetaupdate=1')." *only if new updated was loaded.<br/>";
	  
	  echo "</div>";
	
	}
	
	function get_userslist(){
	  $allusers = $this->mySC2dbObj->get_users();
	  //error_log(print_r($allusers,true));
	  echo "<div id='mysc2_admin'>";
	  echo common::Link('Admin_mySC2_Admin','Back');
	  echo "<h1>Users List</h1><br/>";
	  echo "<table width='90%'>";
	  echo "<tr><th>User ID</th><th>Username</th><th>SC2 Nick</th><th>Email</th></tr>";
	  foreach ($allusers['users'] as $user) {
	    echo "<tr><td>". common::Link('mySC2_Special',$user['user_id'],'viewp='.$user['user_id'])."</td><td>". common::Link('mySC2_Special',$user['username'],'viewp='.$user['user_id'])."</td><td>". $user['sc2nick']."</td><td>". $user['email']."</td></tr>";
	  }
	  echo "</table>";
	  echo "</div>";
	}
	
	function meta_update(){
	   $this->mySC2dbObj->force_meta_update();
	    common::Redirect('?done=1' ,302);
	}
}


