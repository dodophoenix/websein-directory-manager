<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * uses relative url to resize an image
 * mod_thumbsup/image.php/bread.jpg?parameters=@width=50@height=50@cropratio=1:1@quality=90@image=/images/stories/food/bread.jpg
 * @param unknown_type $relurl
 * @return unknown_type
 */
function WBSmakeThumbUrl($relurl,$width,$height){
	$baseUrl = JURI::base(false);
	// TODO: do correct escaping here to allow utf-8 specialchars in filename 
	return $baseUrl.'/modules/mod_thumbsup/image.php?parameters=@width='.$width.'@height='.$height.'@cropratio=1:1@quality=90@image='.$relurl;
}
/**
 * IDE Helper 
 * @return WbsDirMgrViewWbsDirOut
 */
function getView(WbsDirMgrViewWbsDirOut $o){
	return $o;
}

$view = getView($this);
$params = $view->getParameter();
$file = $view->getCurrentFileEntry();

$outputJs	= intval($params->get('outputAsHTML',0))==0?false:true;
$rekursive 	= intval($params->get('rekursive',0))==0?false:true;
$doThumbs   = (boolean) $params->get('doThumbs',1);
$width		= intval($params->get('width',100));
$height		= intval($params->get('height',100));
$jsVar 		= $params->get('jsVar','items');

$filter 	= explode(',',trim($params->get('fileTypes','png,jpg,gif,jpeg'),','));

if(count($filter)<=0){
	$filter=null;
}




// begind output 
$toOutput = array();
if($rekursive){
	$toOutput=$file->getChildsRekursiv($filter);
	
}else{
	$toOutput=$file->getChilds($filter);
}

if(!$outputJs){ // html
	foreach ($toOutput as $f){
		if($f->isDirectory()){
			continue;
		}
		if($doThumbs){
			echo '<a href="'.$f->getAbsUrl().'" target="_window">';
			echo '<img src='.WBSmakeThumbUrl($f->getRelUrl(),$width,$height).' alt="'.$f->getEntry().'">';
			echo '</a>';
		}else{
			echo '<img src='.$f->getAbsUrl().' alt="'.$f->getEntry().'">';
		}
	}
}else{ // jscript
	$script='var '.$jsVar.' = array(';
	$cnt;
	foreach ($toOutput as $f){
		if($f->isDirectory()){
			continue;	
		}	
		if($cnt>0){
			$script.= ",\n";	
		}		
		$script.= "{ ";
		$script.= "url:'".$f->getAbsUrl()."',";
		$script.= "name:'".$f->getEntry()."',";
		$script.= "thumburl:'".WBSmakeThumbUrl($f->getRelUrl(),$width,$height)."'";
		$script.= "}";
		
		$cnt++;
	}	
	$script.=');';	
	
	$document =& JFactory::getDocument();
	$document->addScriptDeclaration($script);	
	echo $this->loadTemplate('jsOut');
}
