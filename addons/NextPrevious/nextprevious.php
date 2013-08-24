<?php
function np_addlinks(){ 
  global $page,$gp_index,$gp_titles,$gp_menu;
  $index = $page->gp_index;
  $curLevel = $gp_menu[$index]['level'];
  
  $found = false;
  $n = null;
  $p = null;
  $prevLevel =  null;
  foreach ($gp_menu as $key=>$item) {
    if ($key == $index) {
	  $prevLevel = $p;
	  $found = true;
	  continue;
	}
	if ($found == true) {
	  $n = $key;
	  break;
	}
    $p = $key;
  }
  $nextLevel = $n;
  
  if (($prevLevel !== null) && ($gp_menu[$index]['level'] > 0) && ($gp_menu[$prevLevel]['level'] >= 0)) {
    
    echo common::Link($gp_titles[$prevLevel]['label'],'Prev').' / ';
  }

  if (($nextLevel !== null) && ($gp_menu[$nextLevel]['level'] > 0)) {
    echo common::Link($gp_titles[$nextLevel]['label'],'Next');
  }
}
?>