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

jimport('joomla.application.component.controller');

/**
 * the default controller 
 */
class WbsDirMgrController extends JController{
	/**
	 * Method to prepare the model and do the tasks on the model that are required to be done
	 *
	 * @access    public
	 */
	function display(){
		$v = &$this->getDefaultView();
			
		$access = $this->getModel ( 'access' );
		$access = WbsDirWbsMgrLib::toAccess ( $access );
			
		$v->setModel ( $access );
			
		if(!$access->accessCheck(WbsDirMgrModelAccess::$EVENT_DISPLAY,true,'index.php')){
			return;
		};

		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);
		$v->display();

		
	}

	/**
	 * the open Dir event
	 */
	public function open(){
		$v = &$this->getDefaultView();

		$access = $this->getModel('access');
		$access = WbsDirWbsMgrLib::toAccess($access);
		$v->setModel($access);
			
			
		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));

		if($access->accessCheck(WbsDirMgrModelAccess::$EVENT_OPEN_DIR,true)){
			$model->openEntry();
		}
						
		$v->setModel($model,true);
		$v->display();
		
		
	}


	public function del(){
		$v = &$this->getDefaultView();
		
		$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
		$v->setModel($access);

		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);

		$success = $model->openEntry();
		if($success == false){
			$v->display();
			return ;
		}
		$isDir = $model->getDirManager()->getFileEntry()->isDirectory();
		$model->reInit();
		
		if($isDir && $access->accessCheck(WbsDirMgrModelAccess::$EVENT_DELETE_DIR,true)){
			$model->deleteEntry();
		}
		if(!$isDir && $access->accessCheck(WbsDirMgrModelAccess::$EVENT_DELETE_FILE,true)){
			$model->deleteEntry();
		}
			
		$v->display();
	}


	public function addfolder(){
		JRequest::checkToken() or die( 'Invalid Token' );
		$v = &$this->getDefaultView();

		$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
		$v->setModel($access);
		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);

		if($access->accessCheck(WbsDirMgrModelAccess::$EVENT_CREATE_DIR,true)){
			$model->addFolder();
		}

		$v->display();
	}


	public function upload(){
		JRequest::checkToken() or die( 'Invalid Token' );

		$v = &$this->getDefaultView();
		$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
		$v->setModel($access);
		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);


		if($access->accessCheck(WbsDirMgrModelAccess::$EVENT_UPLOAD_FILE,true)){
			$model->upload();
		}
			
		$v->display();
	}

	public function rename(){
		JRequest::checkToken() or die( 'Invalid Token' );
		$v = &$this->getDefaultView();

		$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
		$v->setModel($access);
		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);

		
		$success = $model->openEntry();
		if($success == false){
			$v->display();
			return ;
		}
		$isDir = $model->getDirManager()->getFileEntry()->isDirectory();
		$model->reInit();
		
		if($isDir && $access->accessCheck(WbsDirMgrModelAccess::$EVENT_RENAME_DIR,true)){
			$model->rename();
		}
		if(!$isDir && $access->accessCheck(WbsDirMgrModelAccess::$EVENT_RENAME_FILE,true)){
			$model->rename();
		}					
		$v->display();
	}

	public function beginRename(){
		
		$v = &$this->getDefaultView();
		$access = WbsDirWbsMgrLib::toAccess($this->getModel('access'));
		$v->setModel($access);
		
		$model = WbsDirWbsMgrLib::toWbsDirMgrModelWbsDirMgr($this->getModel(null,null,array('access'=>$access)));
		$v->setModel($model,true);
			
		$model->beginRename();
		
		
		$v->display();
	}

	public function getDefaultView(){
		$document =& JFactory::getDocument();

		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', 'wbsdirmgr' );
		$viewLayout	= JRequest::getCmd( 'layout', 'default' );
		// the view name is set wbsdirmgr hardcoded becaus this is the only way the JParameter::getComponent('') parameters work with the model
		$v= $this->getView($viewName,$viewType);
		$v->setLayout($viewLayout);
		return $v;
	}
}
