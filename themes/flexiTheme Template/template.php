<?php
/*
  Developed by Johannes Pretorius (www.dominion-it.co.za)
  Date : 11 Oct 2012
  support page : www.dominion-it.co.za/development
  
  License : No Porno/Adult sites are allowed to use this theme and plugin. Otherwise free for Commercial and Public use just
             keep our links and license and notes intact please.
*/
if (!class_exists('flexiTheme')) {
   global $config,$addonFolderName,$addonPathCode,$addonCodeFolder,$dataDir,$addonPathData,$addonDataFolder,
   $addonRelativeCode,$addonRelativeData,$page,$dirPrefix;
   
   $addons = $config['addons'];
   foreach ($addons as $key=>$value) {
      if ($value['name'] == 'flexiTheme') {
	    $themeID = $key;
		break;
	  }
   }
   
   $addonFolderName = $themeID;
   $addonPathCode = $addonCodeFolder = $dataDir.'/data/_addoncode/'.$themeID;
   $addonPathData = $addonDataFolder = $dataDir.'/data/_addondata/'.$themeID;
   $addonRelativeCode = $dirPrefix.'/data/_addoncode/'.$themeID;
   $addonRelativeData = $dirPrefix.'/data/_addondata/'.$themeID;
   
   include $addonPathCode.'/flexitheme.php';
   if (!class_exists('flexiTheme')) {
    //error_log(print_r(get_declared_classes(),true)); 
    echo "<html><head>".gpOutput::GetHead()."</head><body><div><b>flexiTheme Plugin</b> not installed yet </div><div>".$page->GetContent()."</div></body></html>";
    return;
  }
  
} else {
global $config,$addonFolderName,$addonPathCode,$addonCodeFolder,$dataDir,$addonPathData,$addonDataFolder,
   $addonRelativeCode,$addonRelativeData,$page,$dirPrefix;
   
   $addons = $config['addons'];
   foreach ($addons as $key=>$value) {
      if ($value['name'] == 'flexiTheme') {
	    $themeID = $key;
		break;
	  }
   }
   
   $addonFolderName = $themeID;
   $addonPathCode = $addonCodeFolder = $dataDir.'/data/_addoncode/'.$themeID;
   $addonPathData = $addonDataFolder = $dataDir.'/data/_addondata/'.$themeID;
   $addonRelativeCode = $dirPrefix.'/data/_addoncode/'.$themeID;
   $addonRelativeData = $dirPrefix.'/data/_addondata/'.$themeID;
}

$flexiT = new flexiTheme(false);
$flexiT->Init(); //get all ready
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php  $flexiT->GetHeader(); ?>
</head>
<body>
<?php  $flexiT->GetBody(); ?>
</body>
</html>
<?php
  unset($flexiT);
?>
