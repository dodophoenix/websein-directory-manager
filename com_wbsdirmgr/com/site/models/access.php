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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

/**
 * Access Controll Model 
 *
 */
class WbsDirMgrModelAccess extends JModel{

	public static $EVENT_DISPLAY		='display';
	public static $EVENT_OPEN_DIR		='opendir';
	public static $EVENT_CREATE_DIR		='createDir';
	public static $EVENT_RENAME_DIR		='renameDir';
	public static $EVENT_DELETE_DIR		='deleteDir';
	public static $EVENT_UPLOAD_FILE	='uploadFile';
	public static $EVENT_RENAME_FILE	='renameFile';
	public static $EVENT_DELETE_FILE	='deleteFile';

	
	private static $group_hierarchie = array(
			'Public Frontend',
			'Public Backend', // public backend is higher than frontend but not higher than any logged in user
			'Registered',
			'Author',
			'Editor',
			'Publisher',
			'Manager',
			'Administrator',
			'Super Administrator');
	
	/*
	 * primary available event definitions
	 */
	private $events = array();

	/**
	 * event dependencies
	 */
	private $event_deps = array();

	/**
	 * @var JParameter
	 */
	private $params;
	/**
	 * @var JUser
	 */
	private $user;

	private $aclGroups=array();

	/**
	 *
	 * @var array mapping events to boolean valus
	 */
	private $accessRights=array();


	/**
	 * re-initializes internal vars
	 * and creates JAuth Setup
	 */
	public function reInit(){
		// define
		$this->events= array(self::$EVENT_DISPLAY,
		self::$EVENT_OPEN_DIR,
		self::$EVENT_CREATE_DIR,
		self::$EVENT_RENAME_DIR,
		self::$EVENT_DELETE_DIR,
		self::$EVENT_UPLOAD_FILE,
		self::$EVENT_RENAME_FILE,
		self::$EVENT_DELETE_FILE);

		// contains all events that are needed additional to the key event
		$this->event_deps=array(
		self::$EVENT_DISPLAY=>array(),
		self::$EVENT_OPEN_DIR=>array(self::$EVENT_DISPLAY),
		self::$EVENT_CREATE_DIR=>array(self::$EVENT_DISPLAY),
		self::$EVENT_RENAME_DIR=>array(self::$EVENT_DISPLAY,self::$EVENT_OPEN_DIR),
			
		self::$EVENT_DELETE_DIR=>array(self::$EVENT_DISPLAY,self::$EVENT_OPEN_DIR),
		self::$EVENT_UPLOAD_FILE=>array(self::$EVENT_DISPLAY),
		self::$EVENT_RENAME_FILE=>array(self::$EVENT_DISPLAY),
		self::$EVENT_DELETE_FILE=>array(self::$EVENT_DISPLAY),
		);


		$this->params = $this->getMergedParameters();
		$this->user =& JFactory::getUser();
		// reinit cache fields
		$this->aclGroups=array();
		$this->accessRights=array();


		$auth =& JFactory::getACL();

		// $auth->addACL('com_userinfo15', 'persuade', 'users', 'super administrator');


		foreach($this->events as $e){
			$gid = $this->params->get($e,null);
			$userType = $this->getUserType($gid);
			$upperLevels = $this->makeUpperLevelGroups($userType);
			foreach ($upperLevels as $ul){
				$auth->addACL('com_wbsdirmgr',$e,'users',$ul);
			}
		}
	}
	/**
	 * 
	 * @param string $beginWith
	 * @return array of joomla group names
	 */
	private function makeUpperLevelGroups( $beginWith){
		$result = array();
		if($beginWith==null){
			return $result;
		}
		$add = false;
		foreach(self::$group_hierarchie as $g){
			if($g== $beginWith){
				$add = true;
			};
			if($add){
				$result[]=$g;
			}
		}
		return $result;
	}

	/**
	 * checks if the given event $event ist allowed to current User 
	 * @param $event string - on of this classes EVENT_XYZ vars
	 * @param $addMessage boolean optional: default=false if true messages will be added
	 * @param $redirect string - unused for now 
	 * @return boolean
	 */
	public function accessCheck($event,$addMessage=false,$redirect=null){
		$user =& JFactory::getUser();
		if($user->id==0){ // setup public fronted as default rule 
			$user->usertype="Public Frontend";
		}
		if (!$user->authorize('com_wbsdirmgr', $event)) {
			if($addMessage){
				$eMsg = 'EVENT_'.strtoupper($event);
				JError::raiseWarning(20,JText::sprintf('ACCESS_DENIED',JText::sprintf($eMsg)));
			}
			return false;
		}

		if(!isset($this->event_deps[$event])){
			throw new Exception("Cannot Check Unknown Event");
		}
		foreach($this->event_deps[$event] as $depEvents){
			if (!$user->authorize('com_wbsdirmgr', $event)) {
				if($addMessage){
					$eMsg = 'EVENT_'.strtoupper($event);
					JError::raiseWarning(20,JText::sprintf('ACCESS_DENIED',JText::sprintf($eMsg)));
				}
				return false;
			}
		}
		return true;
	}

	
	/**
	 * gets the usetType gy given id
	 * @param integer $gid
	 * @return string
	 */
	private function getUserType($gid){
		if(!$gid){
			return null;
		}

		if(!isset($this->aclGroups[$gid])){
			// TODO: this will be deprecated as of the ACL implementation
			$db =& JFactory::getDBO();

			$query = 'SELECT name'
			. ' FROM #__core_acl_aro_groups'
			. ' WHERE id = ' . (int) $gid
			;
			$db->setQuery( $query );
			$this->aclGroups[$gid]= $db->loadResult();
		}
		return $this->aclGroups[$gid];
	}


	/**
	 * loads the component parameters
	 * @return object
	 */
	private function getMergedParameters(){
		$params = &JComponentHelper::getParams( 'com_wbsdirmgr' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid){
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		return $params;
	}

	public function __construct($config){
		parent::__construct($config);
		$this->reInit();
	}
}