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

class WbsDirMgrViewWbsDirOut extends JView{

	/**
	 *
	 * @var wbsDirMgrFileEntry
	 */
	private $baseEntry;
		
	public function __construct($congig){
		parent::__construct($congig);	
	}

	function display($tpl = null){
		 		 
		$model = &$this->getModel();
		$model = $this->toWbsDirMgrModelWbsDirMgr($model);
		 
		$dirMgr = $model->getDirManager();
		if($dirMgr==null){
			return ;
		}
		$fileEntry = &$dirMgr->getFileEntry();
		$this->currentItem=&$fileEntry;

		
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
     * 
     * @return wbsDirMgrFileEntry
     */
    public function getCurrentFileEntry(){
    	return $this->currentItem;
    }
    
    /**
     * 
     * @return JParameter
     */
    public function getParameter(){
    	$params = &JComponentHelper::getParams( 'com_wbsdirmgr' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid){
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		return $params;
    }
}
