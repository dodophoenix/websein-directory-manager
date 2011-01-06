<?php
/**
 * Websein Directory Manager (WbsDirMgr) Copyright (C) 2010 Benjamin Jacob websein UG 
 *
 * This file is part of Websein Directory Manager (WbsDirMgr)
 *
 * Websein Directory Manager (WbsDirMgr) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Websein Directory Manager (WbsDirMgr) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Websein Directory Manager (WbsDirMgr).  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @link http://www.websein.com
 * @license    GNU/GPL
 * @author Benjamin Jacob
 * 
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML view class 
 */
 
class WbsDirMgrViewWbsDirMgr extends JView{
	
	/**
	 * 
	 * @var wbsDirMgrFileEntry
	 */
	private $baseEntry;
	
	/**
	 * 
	 * @var wbsDirMgrFileEntry
	 */
	private $currentItem; 
	/**
	 * @var string path to icons folder
	 */
	private $imgIconBase=null;
	
		
	public function __construct($congig){
		parent::__construct($congig);
		$this->imgIconBase='components'.DS.'com_wbsdirmgr'.DS.'img'.DS.'icons';
	}
	
	
    function display($tpl = null){
    	
//    	$params = JComponentHelper::getParams('com_wbsdirmgr');
//    	$x = $params->get('showFullPath','default');
    	
        $model = &$this->getModel();     
      	$model = $this->toWbsDirMgrModelWbsDirMgr($model);
      	
      	$dirMgr = $model->getDirManager();
      	if($dirMgr==null){
      		return ;
      	}
      	$fileEntry = $dirMgr->getFileEntry();
     	
      	$this->currentItem=&$fileEntry;
        $this->baseEntry=$fileEntry;
               
        JHTML::_('behavior.mootools');
        JHTML::_('behavior.modal');
		$document =& JFactory::getDocument();
        $document->addScriptDeclaration("
		window.addEvent('domready', function() {
			document.preview = SqueezeBox;
		});");
        
        $params = JComponentHelper::getParams('com_wbsdirmgr');
        // the script that allows renaming
        if(intval($params->get('jQuerySuppliedByTemplate',1))==1){
	        $document->addScript('components/com_wbsdirmgr/js/jquery-1.4.2.min.js');
    	    $document->addScriptDeclaration("jQuery.noConflict();");
        }
       	$document->addScript('components/com_wbsdirmgr/js/wbsdirmgr.js');
    	$document->addStyleSheet('components/com_wbsdirmgr/css/wbsdirmgr.css');
        parent::display($tpl);
        
    }
    
	/**
     * 
     * @param WbsDirMgrModelWbsDirMgr $o
     * @return WbsDirMgrModelWbsDirMgr
     */
    private function toWbsDirMgrModelWbsDirMgr(WbsDirMgrModelWbsDirMgr $o){
    	return $o;
    }
    
    /**
     * sotrs the childs of the given entry and returns them.
     * @param wbsDirMgrFileEntry $entry
     * @return array of wbsDirMgrFileEntry items
     */
    public function sortChilds(wbsDirMgrFileEntry $entry){
    	$childs = $entry->getChilds();
    	$result = array();	
    	foreach($childs as $c){
    		$c = WbsDirWbsMgrLib::toWbsDirMgrFileEntry($c);
    		$result[$c->getEntry()]=$c;
    	}
    	ksort($result);
    	return array_values( $result );
    }
    
    /**
     * returns true if the current item should display rename input feald instead of output items
     * @param wbsDirMgrFileEntry $e
     * @return boolean
     */
    public function isToRename(wbsDirMgrFileEntry $e){
    	$model = &$this->getModel();
    	$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($model);
    	$toRename = $model->getToRename();
    	if($toRename==null){
    		return false;
    	}
    	if($toRename->getRelPath()==$e->getRelPath()){
    		return true;
    	}
    	return false;
    }
    
    /**
     * 
     * @return wbsDirMgrFileEntry
     */
    public function getCurrentFileEntry(){
    	return $this->currentItem;
    }
    
    /**
     * checks weather to display controls for the given event or not 
     * @param $event
     * @return boolean
     */
    public function isToDisplay($event){
    	$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
    	return $access->accessCheck($event);
    }
    
    /**
     * returns the class modal which enables the preview
     * if this item is an image
     * @param $f
     * @return unknown_type
     */
    public function getPreviewClassIfPreviewable(wbsDirMgrFileEntry &$f){
    	if($f->isExtensionOneOf(array('png','gif','bmp','tiff','jpg'))){
    		return 'modal';
    	}
    	return '';
    }
    
    public function setCurrentFileEntry(wbsDirMgrFileEntry &$f){
    	$this->currentItem=$f;
    }
    
    /**
     * 
     * @return wbsDirMgrFileEntry
     */
    public function getBaseFileEntry(){
    	return $this->baseEntry;
    }
    
	/**
	 * 
	 * @return String
	 */
	public function getFormatedFileSize(wbsDirMgrFileEntry &$fe){
		$size = $fe->getFileSizeInBytes();
		return $this->toHumanizedSize($size);
	}
	
	private function toHumanizedSize($size){
		$sizes = Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$y = $sizes[0];
		for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) 
		{
			$size = $size / 1024;
			$y  = $sizes[$i];
		}

		// Erik: Adjusted number format
		$dec = max(0, (3 - strlen(round($size))));
		return number_format($size, $dec, ",", " ")." ".$y;
	} 
	
	/**
	 * returns the Image URL for the given fileentry 
	 * @param wbsDirMgrFileEntry $fe
	 * @return string|string
	 */
	public function getFileIconUrl(wbsDirMgrFileEntry &$fe){
		$l = $fe->getfileExtension();
		$l = strtolower($l);
		
		$base = $this->imgIconBase.DS;
		
		if(file_exists($base.$l.'.png')){
			return $base.$l.'.png';
		} else {
			return $base.'unknown.png';
		}
	}
	
	/**
	 * builds the path to given image name
	 * Image is taken from components image folder 
	 * @param unknown_type $imgName
	 * @return unknown_type
	 */
	public function imageIcon($imgName){
		return $this->imgIconBase.DS.$imgName;
	}
	
	public function formUrl(){
		// do it the way weblink component does
		$uri=& JFactory::getURI();
		// remove the former folder var or find 
		//a better way to get an valid form url preserving the itemid  
		$uri->delVar('task');
		$uri->delVar('fo');
		$uri->delVar('option');
		return $formTarget = $uri->toString();
	}
	
	public function getMaxUploadSize(){
		$maxPost = ini_get('post_max_size');
		$maxFSize= ini_get('upload_max_filesize');
		if(intval($maxPost)<intval($maxFSize)){
			return $maxPost;
		}else{
			return $maxFSize;
		}		
	}
	
	/**
	 * constructs and outputs an uri that will run given task on specified folder
	 * @param unknown_type $task
	 * @param unknown_type $folder
	 * @param unknown_type $view
	 */
	public function mkUrl($task,$folder,$view='wbsdirmgr'){
		$uri=&JFactory::getURI();
		$uri->setVar('option','com_wbsdirmgr');
		$uri->setVar('task',$task);
		$uri->setVar('view',$view);
		$uri->setVar('fo',wbsJRequestWrapper::urlEncode('fo',$folder));
		echo $uri->toString();
	}
}


//if(! class_exists('uriWrap')){
//	/**
//	 * simple wrapper that builds and echoes urls Encodings
//	 * @author benni
//	 *
//	 */
//	class uriWrap{
//		private $uri;
//		
//		public function uriWrap(){
//			$this->uri=&JFactory::getURI();
//		}
//		
//		/**
//		 * constructs and outputs an uri that will run given task on specified folder
//		 * @param unknown_type $task
//		 * @param unknown_type $folder
//		 * @param unknown_type $view
//		 */
//		public function mkUrl($task,$folder,$view='wbsdirmgr'){
//			$this->uri->setVar('option','com_wbsdirmgr');
//			$this->uri->setVar('task',$task);
//			$this->uri->setVar('view',$view);
//			$this->uri->setVar('fo',wbsJRequestWrapper::urlEncode('fo',$folder));
//			echo $this->uri->toString();
//		}
//	}
//}
