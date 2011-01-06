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
class WbsDirWbsMgrLib{
		
 	/**
     * IDE Helper 
     * @param WbsDirMgrModelWbsDirMgr $o
     * @return WbsDirMgrModelWbsDirMgr
     */
    public static function toWbsDirMgrModelWbsDirMgr(WbsDirMgrModelWbsDirMgr $o){
    	return $o;
    }
    
    /**
     * IDE Helper Function
     * @param WbsDirMgrModelAccess $o
     * @return WbsDirMgrModelAccess
     */
    public static function toAccess(WbsDirMgrModelAccess $o){
    	return $o;
    }
    
    /**
     * IDE Helper function 
     * @param wbsDirMgrFileEntry $f
     * @return wbsDirMgrFileEntry
     */
    public static function toWbsDirMgrFileEntry(wbsDirMgrFileEntry &$f){
    	return $f;
    }
	
    /**
     * IDE Helper function
     * @param wbsDirMgrDirectoryManager $o
     * @return wbsDirMgrDirectoryManager
     */
    public static function toWbsDirMgrDirectoryManager(wbsDirMgrDirectoryManager &$o){
    	return $o;
    }
    
    /**
     * 
     * @param WbsDirMgrViewWbsDirMgr $o
     * @return WbsDirMgrViewWbsDirMgr
     */
    public static function toWbsDirMgrViewWbsDirMgr(WbsDirMgrViewWbsDirMgr &$o){
    	return $o;
    }
    
    /**
     * loads the template given by name on the given template reference
     */
    public static function loadTemplate(&$tpl,$name){
    	return $tpl->loadTemplate($name);
    }
    
    
	
}