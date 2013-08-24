<?php

 if (!class_exists('mySC2_db')){
   include "mySC2_db.php";
}
 if (!class_exists('gpsession')){
   includeFile('tool/sessions.php'); 
}



class mysc2_auth{
     private $login_cookieid = 'e21qAER12djvc7Ytbb';
	 private $user_cookieid = 'asc4e21qsAERcxw112djvc7Ytbas2ib';
	 
    private $mySC2dbObj = null;
   
   function mysc2_auth(){
     //ss
   }
   
   function login($username,$password,$dbConnection = null){
     if ($dbConnection == null) {
	      $dbConnection = new mySC2_db();
     }
	 
	 if ($userid = $dbConnection->login_user($username,$password)) {
	   global $addonPathData;
	   
	   $nounce = common::new_nonce('loggedin',true);
	   gpsession::cookie($this->login_cookieid,$nounce);
	   $usrinfo = $dbConnection->get_user($userid);
	   
	   $sesFname = common::hash($username.date('dFYHis').$password);
	   if (file_exists($addonPathData.'/sessions/sessions.php') ) {
	     include $addonPathData.'/sessions/sessions.php';
	   } else {
	     $sessions = array();
	   }
	   if (isset($sessions[$sesFname])) {
	     //file already exist for client, this should not bee,.. clear all session data etc in browser for any existing cookies
          gpsession::cookie($this->login_cookieid,'',time()-36000);
	      gpsession::cookie($this->user_cookieid,'',time()-36000);		 
		  return false;
	   } else {
	    $sessions[$sesFname]['f'] = $_SERVER['REMOTE_ADDR']; //set session for IP
		$sessions[$sesFname]['d'] = time();
	   }
	   gpFiles::SaveArray($addonPathData.'/sessions/sessions.php','sessions',$sessions);
	   gpFiles::SaveArray($addonPathData.'/sessions/'.$sesFname.'.php','usrinfo',$usrinfo);
	   gpsession::cookie($this->user_cookieid,$sesFname);
	   
	   return $userid;
	 } else {
	   return false;
	 }
   
   }
   
   function is_logged_in(){
   global $addonPathData;
     $nounce = isset($_COOKIE[$this->login_cookieid])?$_COOKIE[$this->login_cookieid]:'';
	 if  (common::verify_nonce('loggedin',$nounce,true)) {
	   include $addonPathData.'/sessions/sessions.php';
	   return ($sessions[$_COOKIE[$this->user_cookieid]]['f'] == $_SERVER['REMOTE_ADDR']);
	 }
   }
   
   function logout(){
       global $addonPathData;
	    include $addonPathData.'/sessions/sessions.php';
	   unset($sessions[$_COOKIE[$this->user_cookieid]]);
      unlink($addonPathData.'/sessions/'.$_COOKIE[$this->user_cookieid].'.php');
      gpsession::cookie($this->login_cookieid,'',time()-36000);
	  gpsession::cookie($this->user_cookieid,'',time()-36000);
	  gpFiles::SaveArray($addonPathData.'/sessions/sessions.php','sessions',$sessions);
   }
   
   function get_user_data(){
      global $addonPathData;
	  if (file_exists($addonPathData.'/sessions/'.$_COOKIE[$this->user_cookieid].'.php')) {
	    include $addonPathData.'/sessions/'.$_COOKIE[$this->user_cookieid].'.php';
        return $usrinfo;
	  } else {
	    return false;
      }	  
   }
}
?>