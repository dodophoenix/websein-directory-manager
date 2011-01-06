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
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo JText::_('HEADLINE_WEBSEIN_FILE_MANAGER');?></h1>

<?php 
$view = WbsDirWbsMgrLib::toWbsDirMgrViewWbsDirMgr($this);
//var_dump($this);

$canDisplay = $view->isToDisplay(WbsDirMgrModelAccess::$EVENT_DISPLAY);
if($canDisplay===false){
	return; 
}

// topLevel 
$f = $view->getCurrentFileEntry();

$entries= $view->sortChilds($f);
?>
<div class="wbsdirMgrWrap">
<?php echo WbsDirWbsMgrLib::loadTemplate($this,"toolbar");?>


 <?php
// echo the upload form 
echo WbsDirWbsMgrLib::loadTemplate($this,"upload");
?>

<form id="jsIDrename" action="<?echo $view->formUrl();?>" method="post">
<input type="hidden" name="option" value="com_wbsdirmgr" />
<input type="hidden" name="task" value="rename" />  
<?php echo JHTML::_( 'form.token' ); ?>                        

<?php
// first the folders
foreach ($entries as $entry){
	$entry = WbsDirWbsMgrLib::toWbsDirMgrFileEntry($entry);
	if($entry->isDirectory()){
		$view->setCurrentFileEntry($entry);
		echo WbsDirWbsMgrLib::loadTemplate($this,"folder");
	}
}

// now the files 
foreach ($entries as $entry){
	$entry = WbsDirWbsMgrLib::toWbsDirMgrFileEntry($entry);
	if(!$entry->isDirectory()){
		$view->setCurrentFileEntry($entry);
		echo WbsDirWbsMgrLib::loadTemplate($this,"file");
	}
}
?>
</form>


</div>