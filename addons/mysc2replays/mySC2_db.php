<?php
class mySC2_db{
    private $mySC2db = null;
	
	function mySC2_db(){
	  global $addonPathData,$addonPathCode;
	  
	   $createDB = !file_exists($addonPathData.'/mysc2.db');
	   if ($createDB) {
		  include "db/mysc2_meta.php";
		  gpFiles::SaveArray($addonPathData.'/mysc2_ver.php','mysc2_dbver',array('ver' => $mysc2_meta_ver));
		  copy($addonPathCode."/db/htaccess",$addonPathData.'/.htaccess');
	   }
	   
       if ($this->mySC2db == null) {
		if (!$this->mySC2db = sqlite_open($addonPathData.'/mysc2.db', 0666)) { 
			die('Error opening mySC2 Database.');
		}  
	   }	
	   
		if ($createDB) {
		  $this->force_meta_update();
		}
    }
	
	function user_exist($username){
	  $result = sqlite_query($this->mySC2db, 'select count(user_id) as isthere from users where username="'.$username.'"');
	  $answ = sqlite_fetch_array($result);
	  return ($answ['isthere'] >= 1); 
	}
	
	function create_user($userObj){
		$username = $userObj['username'];
		$pass   = $userObj['pass1'];
		$sc2nick = $userObj['sc2nick'];
		$email = $userObj['email'];
		$agreetc = $userObj['agreetc'];
		$dt_joined = date('Y-m-d H:i:s');
	
	  $pass = common::hash($pass);
	  $username = sqlite_escape_string($username);
	  $sc2nick = sqlite_escape_string($sc2nick);
 	  $email = sqlite_escape_string($email);
	  sqlite_query($this->mySC2db, "insert into users (username,password,sc2nick,email,agreetc,dt_joined) values ('$username','$pass','$sc2nick','$email','$agreetc','$dt_joined')");
	  $result = sqlite_query($this->mySC2db, 'select user_id from users where username="'.$username.'"');
	  $answ = sqlite_fetch_array($result);
	  return $answ['user_id'];
	}
	
	function update_user($userObj,$userid){
		//$username = $userObj['username'];
		//$pass   = $userObj['pass1'];
		$sc2nick = $userObj['sc2nick'];
		$email = $userObj['email'];
		$char_code = $userObj['char_code'];
		$pic_id = $userObj['pic_id'];
		$pref_race = $userObj['pref_race'];
		$descr = $userObj['descr'];
		$country = $userObj['country'];
		$notify_online_t = isset($userObj['notify_online_t'])?$userObj['notify_online_t']:'0';
		$notify_local_t = isset($userObj['notify_local_t'])?$userObj['notify_local_t']:'0';
		$region = $userObj['region'];
		$league = $userObj['league'];
		
		
		sqlite_query($this->mySC2db, "update users set sc2nick = '$sc2nick' ,
		                                  email ='$email' ,
										  char_code = '$char_code',
										  pic_id = '$pic_id',
										  pref_race = '$pref_race',
										  descr= '$descr' ,
										  country = '$country',
										  notify_online_t = '$notify_online_t',
										  notify_local_t = '$notify_local_t',
										  region = '$region',
										  league = '$league'
										where user_id = $userid");
	  
	}
	
	function update_replay($replayData,$userid){
	 global $addonPathData,$addonPathCode;
	 $storePath = $addonPathData.'/replays/'.$userid;
	 if (!is_dir($storePath)) {
	   mkdir($storePath,0777,true);
	 }
	 $filestored = false;
	 if (isset($_FILES["filename"]["name"])) {
		     $allowedExts = array("sc2replay");
             $extension = strtolower(end(explode(".", $_FILES["filename"]["name"])));
			 if (in_array($extension, $allowedExts) && ($_FILES["filename"]["size"] <= 1024000)) {
			    $targetStore = $storePath."/" . $_FILES["filename"]["name"];
			   if (file_exists($targetStore)){
				  unlink($targetStore);
  			   }
			   move_uploaded_file($_FILES["filename"]["tmp_name"],$targetStore);
			   $filestored = true;
             }			 
     }		
     
      $need_help = isset($_POST['need_help']) ? $_POST['need_help'] : '0';
	  $won = isset($_POST['won']) ? $_POST['won'] : '0';
	  $replay_name = isset($_POST['replay_name']) && trim($_POST['replay_name']) != '' ? $_POST['replay_name'] : 'Need a name';
	  $your_race = isset($_POST['your_race']) ? $_POST['your_race'] : 'terran';
	  $opponent_race = isset($_POST['opponent_race']) ? $_POST['opponent_race'] : 'terran';
	  $game_type = isset($_POST['game_type']) ? $_POST['game_type'] : '1v1';
	  $replay_descr = isset($_POST['replay_descr']) ? $_POST['replay_descr'] : '0';
	  $oppenent_name = isset($_POST['oppenent_name']) ? $_POST['oppenent_name'] : '?';
	  
	  $replayID = isset($_POST['uprepl']) ? $_POST['uprepl'] : '-1';
	  $dt_entered = date('Y-m-d H:i:s');
	  $filenameUpdStr ='';
	  $filename = '';
      if ($filestored) {
	    $filenameUpdStr = ",filename = '".$_FILES["filename"]["name"]."'";
		$filename = $_FILES["filename"]["name"];
	  }
	  
	  $replay_descr = sqlite_escape_string($replay_descr);
	  $replay_name = sqlite_escape_string($replay_name);
	  $oppenent_name = sqlite_escape_string($oppenent_name);
	  if ($replayID < 0) {
	    $upsStr = "insert into replays (user_id,replay_name,replay_descr,need_help,dt_entered,filename,won,your_race,opponent_race,game_type,oppenent_name) values 
		           ('$userid','$replay_name','$replay_descr','$need_help','$dt_entered','$filename','$won','$your_race','$opponent_race','$game_type','$oppenent_name');";
        error_log($upsStr);				   
	    sqlite_query($this->mySC2db,$upsStr);
	    $result = sqlite_query($this->mySC2db, 'select max(replay_id) as m_replay from replays where user_id = "'.$userid.'"');
	    $answ = sqlite_fetch_array($result);
		$replayID = $answ['m_replay'];
		
	  } else {
	    $upsStr = "update replays set replay_name='$replay_name',replay_descr='$replay_descr',need_help='$need_help',won='$won',your_race='$your_race',opponent_race='$opponent_race',game_type='$game_type',oppenent_name='$oppenent_name' $filenameUpdStr where replay_id = $replayID";
	    sqlite_query($this->mySC2db,$upsStr);		
	  }

	  return $replayID;
	  
	}
	
	function get_user($userid){
	   if (!isset($userid)) {
	     return false;
	   }
	  $result = sqlite_query($this->mySC2db, "select * from users where user_id=$userid",SQLITE_ASSOC);
	  $userinfo['user'] = sqlite_fetch_array($result);
	  $result = sqlite_query($this->mySC2db, "select * from replays where user_id=$userid",SQLITE_ASSOC);
	   while ($entry = sqlite_fetch_array($result)){
	     $userinfo['replays'][$entry ['replay_id']] = $entry;
	   }
	  return $userinfo;
	}
	
	function get_replay_user($replayID){
	   $result = sqlite_query($this->mySC2db, "select user_id from replays where replay_id = '$replayID'");
	   $entry = sqlite_fetch_array($result);
	   return $entry['user_id'];
	}
	
    function get_users(){
	  $result = sqlite_query($this->mySC2db, "select user_id,username,sc2nick,email from users");
      while ($entry = sqlite_fetch_array($result, SQLITE_ASSOC)) {
	   $userinfo['users'][] = $entry;
      }	  
	  return $userinfo;
	}	
	
	function login_user($username,$pass){
	  $pass = common::hash($pass);
	  $result = sqlite_query($this->mySC2db, "select user_id from users where username = '$username' and password = '$pass'");
	  $answ = sqlite_fetch_array($result);
	  return $answ['user_id'];
	}
	
	function force_meta_update(){
	  include "db/mysc2_meta.php";
	  foreach ($mysc2_script as $script) {
	    //error_log($script);
	    sqlite_exec($this->mySC2db,$script);
	  }
	}
	
	function country_list($curCountry){
	  $result = sqlite_query($this->mySC2db, "select printable_name from country");
	  $clist = "";
      while ($entry = sqlite_fetch_array($result, SQLITE_ASSOC)) {
	    if ($entry['printable_name'] == $curCountry) {
		  $clist  .= "<option selected='selected'>".$entry['printable_name']."</option>";
		} else {
		  $clist  .= "<option>".$entry['printable_name']."</option>";
		}
      }	  
	  return $clist;
	}
	
	function get_last_replays($maxtoshow = 100){
	 $result = sqlite_query($this->mySC2db, "select r.*,u.sc2nick from replays r,users u where u.user_id = r.user_id order by dt_entered desc limit $maxtoshow");
	  $rlist = array();
      while ($entry = sqlite_fetch_array($result, SQLITE_ASSOC)) {
//	     error_log(print_r($entry,true));
         $replayid = $entry['r.replay_id'];  
	     $rlist[$replayid]['replay_id'] = $entry['r.replay_id'];
		 $rlist[$replayid]['need_help'] = $entry['r.need_help'];
		 $rlist[$replayid]['replay_name'] = $entry['r.replay_name'];
		 $rlist[$replayid]['your_race'] = $entry['r.your_race'];
		 $rlist[$replayid]['opponent_race'] = $entry['r.opponent_race'];
		 //$rlist[$replayid]['sc2nick'] = $entry['u.sc2nick'];		 
		 $rlist[$replayid]['won'] = $entry['r.won'];		 		 
      }	  
	  return $rlist;
	}
	
	function get_replay_comments($replayID){
	 $result = sqlite_query($this->mySC2db, "select r.*,u.sc2nick,u.pic_id from replay_comments r,users u where u.user_id = r.user_id and r.replay_id = $replayID order by comment_id asc");
	  $rlist = array();
      while ($entry = sqlite_fetch_array($result, SQLITE_ASSOC)) {
//	     error_log(print_r($entry,true));
         $comment_id = $entry['r.comment_id'];  
	     $rlist[$comment_id]['replay_id'] = $entry['r.replay_id'];
		 $rlist[$comment_id]['dt_entered'] = $entry['r.dt_entered'];
		 $rlist[$comment_id]['comment'] = $entry['r.comment'];
		 $rlist[$comment_id]['reported'] = $entry['r.reported'];
		 $rlist[$comment_id]['user_id'] = $entry['r.user_id'];
		 $rlist[$comment_id]['sc2nick'] = $entry['u.sc2nick'];		 
		 $rlist[$comment_id]['pic_id'] = $entry['u.pic_id'];		 
      }	  
	  return $rlist;
	}
	
	function get_replay_rating_info($replayID,$userid,&$replayRatingValue){
	  $result = sqlite_query($this->mySC2db, "select avg(rate) as replayv from replay_ratings where replay_id = $replayID");
	  $entry = sqlite_fetch_array($result, SQLITE_ASSOC);
	  $replayRatingValue = $entry['replayv'];
	  $result = sqlite_query($this->mySC2db, "select count(rating_id) as gedoen from replay_ratings where replay_id = $replayID and user_id = $userid");
	  $entry = sqlite_fetch_array($result, SQLITE_ASSOC);
	  return $entry['gedoen'] == 0;
	  
	}
	
	function get_user_rating_info($target_user_id,$userid,&$RatingValue){
	  $result = sqlite_query($this->mySC2db, "select avg(rate) as ratingv from user_ratings where target_user_id = $target_user_id");
	  $entry = sqlite_fetch_array($result, SQLITE_ASSOC);
	  $RatingValue = $entry['ratingv'];
	  $result = sqlite_query($this->mySC2db, "select count(rating_id) as gedoen from user_ratings where target_user_id = $target_user_id and user_id = $userid");
	  $entry = sqlite_fetch_array($result, SQLITE_ASSOC);
	  return $entry['gedoen'] == 0;
	  
	}
	
	function set_replay_rating($replayid,$rating,$userid){
	$dt_entered = date('Y-m-d H:i:s');
	   $result = sqlite_query($this->mySC2db, "insert into replay_ratings(replay_id,user_id,dt_rated,rate) values ($replayid,$userid,'$dt_entered',$rating)");
	}

	function set_user_rating($target_user_id,$rating,$userid){
	$dt_entered = date('Y-m-d H:i:s');
	   $result = sqlite_query($this->mySC2db, "insert into user_ratings(target_user_id,user_id,dt_rated,rate) values ($target_user_id,$userid,'$dt_entered',$rating)");
	}	
	
	function post_comment($replayID,$comment,$userid){
	  $dt_entered = date('Y-m-d H:i:s');
	   	  $comment = sqlite_escape_string($comment);
      sqlite_query($this->mySC2db, "insert into replay_comments (replay_id,user_id,comment,dt_entered,reported) values ('$replayID','$userid','$comment','$dt_entered',0)");	  
	}
}	
?>