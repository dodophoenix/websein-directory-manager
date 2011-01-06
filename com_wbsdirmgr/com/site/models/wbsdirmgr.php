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
// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.model' );
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * register parameters that are transfered between gui and dirmgr 
 * and setup encodings required to allow special chars in GET / POST s
 */
wbsJRequestWrapper::instance()->registerParam('fo',array('RAWURL'),'GET');
wbsJRequestWrapper::instance()->registerParam('fo',			array('HTMLENTITY',),'POST');
wbsJRequestWrapper::instance()->registerParam('newName',	array('HTMLENTITY',),'POST');
wbsJRequestWrapper::instance()->registerParam('torename',	array('HTMLENTITY',),'POST');
wbsJRequestWrapper::instance()->registerParam('entryname',	array('HTMLENTITY',),'POST');

/**
 * Directory Manager Model and Helpers
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class WbsDirMgrModelWbsDirMgr extends JModel{

	static $PARAM_ALLOWED_EXTENSIONS="allowedUploadFileExtensions";

	/**
	 *
	 * @var wbsDirMgrDirectoryManager
	 */
	private $_dirMgr=null;

	private $_toRename=null;

	/**
	 *
	 * @var WbsDirMgrModelAccess
	 */
	private $access=null;

	public function __construct($config){
		parent::__construct($config);
		if(!isset($config['access'])||$config['access']==null){
			throw new Exception("Access Object is required");
		}
		$this->access=$config['access'];
		$this->reInit();
	}



	/**
	 * reInits the Model
	 * @return
	 */
	public function reInit(){
		$this->_dirMgr=null;
		$this->_toRename=null;

		$params=$this->getMergedParameters();
		try{
			$this->initFileManager($params);
		}catch(Exception $e){
			$this->_errors[]=JError::raiseWarning(7001,$e->getMessage());

		}
	}

	/**
	 * gets the component parameters
	 * @return object
	 */
	public function getMergedParameters(){
		$params = &JComponentHelper::getParams( 'com_wbsdirmgr' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid){
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		return $params;
	}

	/**
	 *
	 * @param $p
	 * @return wbsDirMgrDirectoryManager
	 * @throws Exception
	 */
	public function initFileManager(JParameter &$p){
		if($this->_dirMgr==null){
			$this->_dirMgr = new wbsDirMgrDirectoryManager($p);
		}
		return $this->_dirMgr;
	}

	/**
	 * opens the dir that is set as 'fo' parameter
	 * @return boolean
	 */
	public function openEntry(){
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf('ERR_INIT_ERROR'));
			return false;
		}

		$folder =wbsJRequestWrapper::getVar('fo','');
		//$folder = JPath::clean($folder);
		if(!$this->_dirMgr->openEntry($folder)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$folder));
			$this->reInit();
			return false;
		}

		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($folder));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}
		return true;
	}
	/**
	 * selects the entry given by fo a
	 * deletes it
	 * and selects the parent
	 */
	public function deleteEntry(){
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_INIT_ERROR"));
			return;
		}
		$folder =wbsJRequestWrapper::getVar('fo','');
		//$folder = JPath::clean($folder);
		if(!$this->_dirMgr->openEntry($folder)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$folder));
			$this->reInit();
		}

		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($folder));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}

		$isFile = $this->_dirMgr->getFileEntry()->isFile();
		$name   = $this->_dirMgr->getFileEntry()->getEntry();

		// remove the entry
		try{
			$this->_dirMgr->deleteCurrentEntry();
			$this->_dirMgr->goUp();
			JError::raiseNotice(8002,JText::sprintf('MSG_SUCESSFULLY_DELETED',$name));
			return null;
		}catch(Exception $e){
			$this->_dirMgr->goUp();
			$this->_errors[]=JError::raiseWarning($e->getCode(),$e->getMessage());
			return null;
		};
	}
	/**
	 * sets an item that will be displayed renameable
	 * it will be opend using the fo param
	 */
	public function beginRename(){
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_INIT_ERROR"));
			return;
		}
		// Open the requested entry for renameing
		$folder =wbsJRequestWrapper::getVar('fo','');
		//$folder = JPath::clean($folder);
		// select the entry
		if(!$this->_dirMgr->openEntry($folder)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$folder));
			$this->reInit();
			return null;
		}
		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($folder));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}

		$this->_toRename = $this->getDirManager()->getFileEntry();
		// now select the uper value
		if(!$this->getDirManager()->goUp()){
			$this->_errors[]=JError::raiseWarning(7003,JText::sprintf('CANNOT_RENAME_ROOT_DIR',$this->_toRename->getEntry()));
			$this->_dirMgr->goUp();
			$this->_toRename=null;
			return null;
		};

	}

	/**
	 * uploads a file given with 'uploadfile' to the path given with param fo
	 * @return boolean
	 */
	public function upload(){
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_INIT_ERROR"));
			return;
		}

		// Open the requested entry for renameing
		$folder =wbsJRequestWrapper::getVar('fo','');
		// select the entry
		if(!$this->_dirMgr->openEntry($folder)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$folder));
			$this->reInit();
			return false;
		}

		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($folder));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}

		// get the filename
		$file= JRequest::getVar( 'uploadfile', '', 'files', 'array' );
		$dir = $this->_dirMgr->getFileEntry()->getAbsPath();
		$name = $file['name'];
		$name = wbsDirMgrFileSystemHelper::removeAllBackSlashes($name);
	
		
		
		// get allowed extensions
		$params = $this->getMergedParameters();
		$exts = $params->get(self::$PARAM_ALLOWED_EXTENSIONS,'');
		$a_exts = explode(',',$exts);

		// check valid extension
		if(wbsDirMgrFileSystemHelper::isOneOfIgnoreCase($name,$a_exts)==false){
			$this->_errors[]=JError::raiseWarning(7002,JText::sprintf('ERR_UPLOAD_OF_FILET_YPE_NOT_ALOWED',$name));
			$this->reInit();
			return false;
		}

		$targetFile =  JPath::clean($dir.DS.$name);

		// check if exists
		if(JFile::exists($targetFile)){
			JError::raiseWarning(7015,JText::sprintf('ERR_CANNOT_UPLOAD_CAUSE_FILE_EXISTS',$name));
			return false;
		}

		// check that the targetfile does not need to create directories and if it needs to create dirs we need to check access rights before 
		$baseDir = dirname($targetFile);
		if (!file_exists($baseDir)) {
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_CREATE_DIR,true)){
				$this->reInit();
				return false;
			}			
		}
				
		if(!JFile::upload($file['tmp_name'], $targetFile)){
			return false;
		}
		JError::raiseNotice(8001,JText::sprintf('MSG_FILE_SUCCESFULLY_UPLOADED',$name));

		return true;
	}

	/**
	 * gets the entry that should be renamed if any
	 * @return wbsDirMgrFileEntry
	 */
	public function getToRename(){
		return $this->_toRename;
	}
		
	/**
	 * renames the file given with 'torename'
	 * to name given by 'newName'
	 */
	public function rename(){
		$entry = wbsJRequestWrapper::getVar('torename','');
		$newName= wbsJRequestWrapper::getVar('newName','');
		
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_INIT_ERROR"));
			return;
		}
		$entry = JPath::clean($entry);
		$newName = JPath::clean($newName);
		// select the entry
		if(!$this->_dirMgr->openEntry($entry)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$entry));
			return null;
		}
		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($entry));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}
		$src = $this->_dirMgr->getFileEntry()->getEntry();

		try{
			$this->_dirMgr->getFileEntry()->rename($newName);
			$this->_dirMgr->goUp();
		}catch(Exception $e){
			$this->_errors[]=$ex;
			$this->_toRename=null;
			$this->_dirMgr->goUp();		
			$this->_errors[]=JError::raiseWarning(7002,$e->getMessage());
			return null;
			
		}
		JError::raiseNotice(8001,JText::sprintf('MSG_SUCESSFULLY_RENAMED',$src,$newName));
	}

	/**
	 * adds a in the folder submitted in fo field
	 * @return boolean true when successfull
	 */
	public function addFolder(){
		$folder = wbsJRequestWrapper::getVar('fo','');
		if($this->getDirManager()==null){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_INIT_ERROR"));
			return false;
		}

		if(!$this->_dirMgr->openEntry($folder)){
			$this->_errors[]=JError::raiseWarning(7001,JText::sprintf("ERR_UNKNOWN_DIRECTORY",$folder));
			return false;
		}
		// check if opened is valid and allowed
		$len = JString::strlen(JString::trim($folder));
		if($len>0&&$this->_dirMgr->getFileEntry()->isDirectory()){
			if(!$this->access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
				$this->reInit();
				return false;
			}
		}


		$name = wbsJRequestWrapper::getVar('foldername',null);
		try{
			$this->_dirMgr->addFolder($name);
			return true;
		}catch(Exception $e){
			$this->_errors[]=JError::raiseWarning($e->getCode(),$e->getMessage());
			return false;
		}
		JError::raiseNotice(8002,JText::sprintf('MSG_FOLDER_SUCESSFULLY_CREATED',$name));
		return false;
	}


	/**
	 * gets the initalized filemanger
	 * returns null if not yet initalized
	 * @return wbsDirMgrDirectoryManager or null if error orcured while initalisation
	 */
	public function getDirManager(){
		return $this->_dirMgr;
	}

}

class wbsDirMgrDirectoryManager{

	public static $CUSTOM_BASE_FOLDER='baseFolderOverwrite';

	private $fileEntry;

	/**
	 *
	 * @param JParameter $params
	 * @return wbsDirMgrDirectoryManager
	 * @throws Exception
	 */
	public function __construct(JParameter &$params){
		// TODO: refactor use joomla directory separator here (the  DS  constant ) 
		$baseFolder = $params->get(self::$CUSTOM_BASE_FOLDER,null);
		$baseUrl	= $params->get(self::$CUSTOM_BASE_FOLDER,null);
		if(empty($baseFolder)||empty($baseUrl)){
			throw new Exception(JText::_('ERR_PARAMETERS_NOT_AVAILABLE'));
		}

		// MAKE URL
		$parentUrl=null;
		$baseUrl=JString::trim($baseUrl,'/');
		$baseFolder=JPATH_BASE.DS.JString::rtrim($baseFolder,'/');
		if(strpos($baseUrl,'http://')===false){// relative url
			$parentUrl=JURI::base().$baseUrl;
		}else{ // absolute url
			$parentUrl=$baseUrl;
		}
		$parentUrl 		= $parentUrl;
		// MAKE PARENT DIR
		$parentDir		= $baseFolder;
		// MAKE entry
		$entry		   	= wbsDirMgrFileSystemHelper::getItemFromAbsPath($baseFolder);
			
		$this->fileEntry=new wbsDirMgrFileEntry($entry,$parentDir,$parentUrl);

	}
	/**
	 * opens the relative path an sets it as the manager fileenntry
	 * @param $relPath
	 * @return boolean
	 */
	public function openEntry($relPath){
		if(empty($this->fileEntry)){
			return false;
		}
		$a_path = explode('/',$relPath); 
		$currentEntry = $this->fileEntry;
		foreach($a_path as $p){
			if(empty($p)){
				continue;
			}
			$entry = $currentEntry->getChilds(null,$p);
			if(count($entry)>0){
				$currentEntry=$entry[0];
			}else{
				return false;
			}
		}
		$this->fileEntry=$currentEntry;
		return true;
	}
	/**
	 * selectes the parent as current item
	 * true if successs false if no item
	 * @return boolean
	 */
	public function goUp(){

		if($this->fileEntry->hasParent()){
			$this->fileEntry=$this->fileEntry->getParent();
			return true;
		}
		return false;
	}


	/**
	 * adds folder to current entry
	 * returns false current entry is a file or an error occurs
	 * or given name is empty
	 * @throws JException
	 * @param $name
	 */
	public function addFolder($name=null){
		return $this->fileEntry->addFolder($name);
	}

	/**
	 * deletes the current selected.
	 *
	 * Remember that the current entry is invalid and you should select its parent directory
	 * @return boolean
	 * @throws Exception
	 */
	public function deleteCurrentEntry(){
		$this->fileEntry->delete();
		return false;
	}

	/**
	 *  gets the file entries
	 * @return wbsDirMgrFileEntry
	 */
	public function getFileEntry(){
		return $this->fileEntry;
	}
}

/**
 * A file entry represents a entry in a directory. it can be an directory or an file
 * @author benni
 *
 */
class wbsDirMgrFileEntry{

	private $absPath=null;

	private $absUrl=null;
	
	private $relUrl=null;

	private $relPath=null;

	private $entry=null;

	private $childs=null;

	private $parent=null;

	private $isDirectory=null;

	private $sizeInBytes=null;

	/**
	 * can be constructed either giving
	 * entry and parent
	 * or entry,abstPath and absUrl
	 * @param $entry the file entry
	 * @param unknown_type $absPath
	 * @param $absUrl the Absolute url.
	 * @param $parent a parent
	 * @return wbsDirMgrFileEntry
	 * @throws Exception if given path does not exist
	 */
	public function __construct($entry=null,$absPath=null,$absUrl=null,wbsDirMgrFileEntry $parent=null){
		if($entry===null){
			throw new Exception("Param \$entry cannot be null");
		}


		if($parent===null){
			if(empty($absPath)){
				throw new Exception("Param \$absPath cannot be empty if no \$parent is given");
			}
			if(empty($absUrl)){
				throw new Exception("Param \$absPath cannot be empty if no \$parent is given");
			}
		}else{
			$absPath = $parent->getAbsPath().DS.$entry;
			$absUrl  = $parent->getAbsUrl().DS.$entry;
			$this->parent=$parent;
		}

		if(!wbsDirMgrFileSystemHelper::entryExists($absPath)){
			throw new Exception(JText::sprintf(ERR_ACCESS_DENIED_ON_PATH,$absPath));
		}

		$this->entry	= $entry;
		$this->absPath	= $absPath;
		$this->absUrl 	= $absUrl;

	}
	/**
	 * gets the filesize in Bytes
	 * folders have soze 0 always
	 * @return integer
	 */
	public function getFileSizeInBytes(){
		if($this->sizeInBytes==null){
			if($this->isDirectory()){ // set a dir to 0 Bytes
				$this->sizeInBytes = 0 ;
			}else{
				// read filesize
				$this->sizeInBytes= filesize($this->absPath);
				if (!$this->sizeInBytes) {
					// If filesize() fails (with larger files), try to get the size from unix command line.
					$this->sizeInBytes=exec("ls -l '$file' | awk '{print $5}'");
				}
			}
		}
		return $this->sizeInBytes;
	}

	/**
	 * creates a folder in current entry and throws exception if creation failed
	 * @throws JException
	 * @param string $name
	 */
	public function addFolder($name){
		if(!$this->isValidFolderName($name)){
			throw new Exception(JText::sprintf('ERR_CREATE_FOLDER_NOT_ALLOWED_INVALID_CHAR',$name),7009);
		}
		if($this->isFile()){
			throw new Exception(JText::sprintf('ERR_CREATE_FOLDER_IN_A_FILE_NOT_ALLOWED',$name),70012);
		}

		$path = $this->getAbsPath().DS.$name;

		if(!@mkdir($path, 0777)){
			// Check for existing file with same name and choose different error message [ErikLtz]
			if(file_exists($path)){
				throw new Exception(JText::sprintf('ERR_CREATE_FOLDER_ALREADY_EXIST',$name),7010);
			}else{
				throw new Exception(JText::sprintf('ERR_FOLDER_CANNOT_BE_CREATED',$name),7011);
			}
		}
	}

	/**
	 * unlinks / deletes this entry
	 * and removes and reloads the parent
	 * @Throws Exception
	 */
	public function delete(){
		if(!$this->hasParent()){
			throw new Exception(JText::sprintf('ERR_CANNOT_DELETE_ROOT_DIRECTORY',$this->getEntry()));
		}

		if($this->isDirectory()){
			$rc = @rmdir ($this->getAbsPath());
		}else{
			$rc = @unlink($this->getAbsPath());
		}
		// Check whether directory is gone
		if(file_exists($this->getAbsPath())) {
			throw new Exception(JText::sprintf('ERR_COULDNT_DELETE_ENTRY',$this->getEntry()));
		}else{
			// success
			$parent = $this->getParent();
			$parent->childs=null;
		}
	}

	/**
	 * checks that the this is a valid folder name
	 * containing no / nor . nor \ nor the name is empty
	 * @param stirng $name
	 * @return boolean
	 */
	private function isValidFolderName($name){
		if(empty($name)){
			return false;
		}

		if(strpos( $name, '.' ) !== false||
		strpos( $name, '\\' ) !== false||
		strpos( $name, '/' ) !== false){
			return false; // one of the chars were found
		}
		return true;
	}

	/**
	 * checks that the this is a valid folder name
	 * containing no / nor .. nor \ nor the name is empty
	 * @param stirng $name
	 * @return boolean
	 */
	private function isValidEntryName($name){
		if(empty($name)){
			return false;
		}
		if(mb_strpos( $name, '..' ) !== false||
		mb_strpos( $name, '\\' ) !== false||
		mb_strpos( $name, '/' ) !== false){
			return false; // one of the chars were found
		}
		return true;
	}

	/**
	 * renames the folder to the given nam
	 * @param string $newName
	 */
	public function rename($newName){
		if(!$this->isValidEntryName($newName)){
			throw new Exception(JText::sprintf('ERR_INVALID_NAME_FOR_RENAME',$newName));
		}
		if($this->getParent()==null){
			throw new Exception(JText::sprintf('ERR_ROOT_DIR_CANNOT_BE_RENAMED',$this->getEntry()));
		}
		$newFile = $this->getParent()->getAbsPath().DS.$newName;
		$oldFile = $this->getAbsPath();
		if(@rename($oldFile,$newFile)===false){
			throw new Exception(JTEXT::sprintf('ERR_COULD_NOT_RENAME_ENTRY',$this->getEntry()));
		}

		if(!file_exists($newFile)) {
			throw new Exception(JText::sprintf('ERR_COULD_NOT_RENAME_ENTRY',$this->getEntry()));
		}

		// reset childs to get them reread
		$this->getParent()->childs=null;

	}

	/**
	 * returns false if this item has no parents, true otherwise
	 * @return boolean
	 */
	public function hasParent(){
		return $this->parent!=null;
	}

	/**
	 * gets the parent
	 * returns parent or null if none parent available
	 * @return wbsDirMgrFileEntry
	 */
	public function getParent(){
		return $this->parent;
	}

	/**
	 * gets the entries extension
	 * returns empty string for folders
	 * @return string
	 */
	public function getFileExtension(){
		if($this->isDirectory()){
			return "";
		}
		$a = explode(".", $this->entry);
		$b = count($a);
		return $a[$b-1];
	}


	/**
	 *
	 * @return wbsDirMgrFileEntry
	 */
	public static function toFileEntry(wbsDirMgrFileEntry $o){
		return $o;
	}

	/**
	 * The absolute Path is always initialized
	 * returns it without trailing slashes
	 * @return string
	 */
	public function getAbsPath(){
		if($this->absPath==null){
			$this->absPath = $this->parent->getAbsPath().DS.$this->entry;
		}
		return $this->absPath;
	}


	public function isDirectory(){
		if($this->isDirectory==null){
			$this->isDirectory = wbsDirMgrFileSystemHelper::isDir($this);
		}
		return $this->isDirectory;
	}

	public function isFile(){
		if($this->isDirectory==null){
			$this->isDirectory=wbsDirMgrFileSystemHelper::isDir($this);
		}
		return !$this->isDirectory;
	}

	/**
	 * gets the name of the file ore directory 
	 * @return string
	 */
	public function getEntry(){
		return $this->entry;
	}
	/**
	 * the relativePath to this entry
	 * @return string
	 */
	public function getRelPath(){
		if($this->relPath==null){
			if($this->parent==null){
				$this->relPath='';
			}else{
				$postfix=DS;
				if(!$this->hasChilds()){
					$postfix='';
				}
				$this->relPath=$this->parent->getRelPath().$this->entry.$postfix;
			}
		}
		return $this->relPath;
	}


	public function getAbsUrl(){
		if($this->absUrl==null){
			// url cannot use DS as it might be a \ also
			$this->absUrl=$this->parent->getAbsUrl().'/'.$this->entry;
		}
		return $this->absUrl;
	}
	
	public function getRelUrl(){
		if($this->relUrl==null){
			if($this->parent == null){
				$baseUrl = JURI::base(false);
				//echo "$baseUrl<br> $this->absUrl";
				$relPart = str_replace($baseUrl,'',$this->absUrl);
				$this->relUrl='/'.$relPart;
			}else{
				$this->relUrl=$this->parent->getRelUrl().'/'.urlencode($this->entry);
			}
		}		
		return $this->relUrl;
	}

	/**
	 * checks if this entry begins with given name
	 * a null value is treated as wildcard
	 * @param string $name
	 * @return boolean
	 */
	public function isEntryNamed($name=null){
		if($name==null){
			return true;
		}
		return $name==$this->getEntry();
	}

	/**
	 * gets all childs that filtered by given params
	 * if params are null or not set all childs will be returned
	 * things like .. are not interpreded
	 * so moving up using this function is not possible
	 *
	 *
	 * @return array
	 */
	public function getChilds($a_extensions=null,$name=null){
		if($this->childs==null){
			$this->childs = wbsDirMgrFileSystemHelper::getSubFileEntries($this);
		}
		if($a_extensions!==null||$name!=null){// return filtered
			$result = array();
			foreach ($this->childs as $child){

				$canAdd=true;
				if(!empty($a_extensions)&&!$child->isExtensionOneOf($a_extensions)){
					continue; // doesnt Match extensions filter
				}
				if(!empty($name)&&!$child->isEntryNamed($name)){
					continue; // doesnt match name filter
				}
				$result[]=$child;
			}
			return $result;
		}
		return $this->childs;
	}
	/**
	 * returns true if this entry has children
	 * @return boolean
	 */
	public function hasChilds(){
		return (count($this->getChilds())>0);
	}

	/**
	 * returns true if this is a file and the extension is on of the given extensions (ignoring case)
	 * @param unknown_type $a_extensions
	 * @return boolean
	 */
	public function isExtensionOneOf($a_extensions){
		if($this->isDirectory()){
			return true;
		}

		return wbsDirMgrFileSystemHelper::isOneOfIgnoreCase($this->getEntry(),$a_extensions);
	}

	public function getChildsRekursiv($a_extensionFilter=null){
		$result = array();
		foreach($this->getChilds() as $child ){
			if($a_extensionFilter!==null&&!$child->isExtensionOneOf($a_extensionFilter)){
				continue; // filter those that will be added
			}

			$result[]=$child;

			$schilds = $child->getChildsRekursiv($a_extensionFilter);
			foreach ($schilds as $schild){
				if($a_extensionFilter!=null&&!$child->isExtensionOneOf($a_extensionFilter)){
					continue; // filter those that will be added
				}
				$result[]=$schild;
			}
		}
		return $result;
	}
}

class wbsDirMgrFileSystemHelper{

	/**
	 * returns the last path entry from a path
	 * /bla/blub/foo
	 * returns foo
	 * @param string $absPath
	 * @return string
	 */
	public static function getItemFromAbsPath($absPath){
		$a_entries = explode(DS,$absPath);
		$result= array_pop($a_entries);
		return $result;
	}

	/**
	 * removes last folder entry from path
	 * /home/c12234/cms becomes /home/c12234
	 * @param unknown_type $absPath
	 * @return unknown_type
	 */
	public static function getParentAbsPath($absPath){
		$a_entries = explode(DS,$absPath);
		array_pop($a_entries);
		$result = implode(DS,$a_entries);
		return $result;
	}

	/**
	 * removes the last folder entry from url if any
	 * http://websein.com/cms becomes http://www.websein.com/
	 * @param unknown_type $absUrl
	 * @return unknown_type
	 */
	public static function getParentUrlFrom($absUrl){
		$a_entries = explode('/',$absUrl);
		array_pop($a_entries);
		$result = implode('/',$a_entries);
		return $result;
	}
	
	public static function removeAllBackSlashes($txt){
		return stripslashes($txt);
	}

	/**
	 *
	 * @param wbsDirMgrFileEntry $s
	 * @return wbsDirMgrFileEntry
	 */
	public static function getSubFileEntries(wbsDirMgrFileEntry $s){
		if($s->isFile()){
			return array();
		}
		$result = array();

		$handle = opendir($s->getAbsPath());

		while (false !== ($file = readdir($handle))) {
			if($file=='.'||$file=='..'){
				continue;
			}
			$result[]= new wbsDirMgrFileEntry($file,null,null,$s);
		}
		closedir($handle);
		return $result;

	}

	public static function entryExists($absPath){
		return file_exists($absPath);
	}

	public static function isDir(wbsDirMgrFileEntry $f){
		return is_dir($f->getAbsPath());
	}

	/**
	 * checks weather the given filename has one of the given extensions
	 * ignoring case.
	 * @param string $filename
	 * @param array $a_extensions
	 * @return boolean
	 */
	public static function isOneOfIgnoreCase($filename,$a_extensions){
		$strlen = strlen($filename);
		$ext = substr(strtolower($filename),$strlen-3);
		foreach($a_extensions as $e){
			if(empty($e)){
				continue;
			}
			$e = strtolower($e);
			if(strpos($ext,$e)!==false){
				return true;
			}
		}
		return false;
	}
	
//	/**
//	 * checks if the given filename is a valid filename
//	 * that contains no /,",', or \ 
//	 * @param unknown_type $filename
//	 */
//	public static function isValidFilename($filename){
//		
//	}
	
}

/**
 * ann additional JRequest instance that gets a var and allwos to register encoders by variable name 
 * It encodes and decodes named fields 
 * @author benni
 *
 */
class wbsJRequestWrapper{

	private static $INSTANCE=null;
	
	private $encs=array();
	
	/**
	 * 
	 * @param array $a_encodings
	 */
	private function wbsJRequestWrapper(){}
	
	/**
	 * overwrites encodings defined for a parameter  
	 * @param string $name
	 * @param decodings $a_decodings
	 */
	public function registerParam($name ,$a_decodings,$type=null){
		if($type==null){
			$this->encs[$name]['GET']=$a_decodings;
			$this->encs[$name]['POST']=$a_decodings;
		}else{
			$this->encs[$name][$type]=$a_decodings;
		}
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return string
	 */
	public static function getVar($name,$default){
		return wbsJRequestWrapper::instance()->getVariable($name,$default);
	}
	
	/**
	 * encodes the form values for submit within POST / for usage with formtags 
	 * @param string $name
	 * @param stirng $value
	 */
	public static function formEncode($name,$value){
		return wbsJRequestWrapper::instance()->encodeValueFor($name,$value,'POST');
	}
	
	/**
	 * encodes the given value for GET method using the encode methods registered for the name  
	 * @param string $name
	 * @param string $value
	 */
	public static function urlEncode($name,$value){
		return wbsJRequestWrapper::instance()->encodeValueFor($name,$value,'GET');
	}
	
	public function encodeValueFor($name,$value,$method='GET'){
		if(! isset($this->encs[$name][$method])){
			return $value;
		}
		$result = $value;
		$encs = $this->encs[$name][$method];
		foreach( $encs as $val){
			// Handle the type constraint
			switch (strtoupper($val)){
			
				case 'RAW_URL_DECODE' :
				case 'RAWURL':
					$result = rawurlencode($result);
					break; 
				case 'HTML_SPECIAL_CHARS':
				case 'HTMLSPEC':
					$result = htmlspecialchars($result,ENT_QUOTES,'UTF-8');
					break;
				case 'HTMLENTITY':
					$result = htmlentities($result,ENT_QUOTES,'UTF-8');				
			}
		}
		return $result;
	}
	
	
	/**
	 * @return wbsJRequestWrapper
	 */
	public static function instance(){
		if(self::$INSTANCE === null ){
			self::$INSTANCE = new wbsJRequestWrapper();			
		}
		return self::$INSTANCE;
	}
	
	public function getVariable($name,$default){
		
		// check for the type the var was submitted with  
		$isGet=false;
		$isPost=false;
		$result = JRequest::getVar($name,null,'GET');
		if($result != null){
			$isGet=true;
		}
		$result = JRequest::getVar($name,null,'POST');
		if($result != null){
			$isPost=true;
		}
		
		
		
		$result = JRequest::getVar($name,$default);
		if($result==$default){ // default value used no need for encodes 
			return $result; 
		}

		if(!isset($this->encs[$name])){ // no decoding defined for param  
			return $result; 
		}
		
		
		// select required decodeings 
		$encs = array();
		if($isPost===TRUE && isset($this->encs[$name]['POST'])){
			$encs = $this->encs[$name]['POST'];
		}elseif ($isGet===TRUE&& isset($this->encs[$name]['GET'])){
			$encs = $this->encs[$name]['GET'];
		}
		
		if($encs==null || count($encs)==0){ // no encs defined 
			return $result;
		}
				
		foreach( $encs as $val){
			// Handle the type constraint
			switch (strtoupper($val)){
			
				case 'RAW_URL_DECODE' :
				case 'RAWURL':
					$result = rawurldecode($result);
					break; 
				case 'HTML_SPECIAL_CHARS':
				case 'HTMLSPEC':
					$result = htmlspecialchars_decode($result,ENT_QUOTES,'UTF-8');
					break;
				case 'HTMLENTITY':
					$result = html_entity_decode($result,ENT_QUOTES,'UTF-8');		
			}
		}

		return $result;
	}
}
