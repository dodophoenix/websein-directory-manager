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
<?php 
$view = WbsDirWbsMgrLib::toWbsDirMgrViewWbsDirMgr($this);
$url = $view;
$entry = $view->getBaseFileEntry();

$formTarget= $view->formUrl();

$canUploadFile	=$view->isToDisplay(WbsDirMgrModelAccess::$EVENT_UPLOAD_FILE);
$canCreateDir	=$view->isToDisplay(WbsDirMgrModelAccess::$EVENT_CREATE_DIR);
?>

<div id='upload' style="clear:both;width:100%;position:relative;">

  <?php if($canCreateDir){?>
  	<div id="folder">
		<form action="<?echo $formTarget;?>" method="post">
			<input type='hidden' name='fo' value="<?php echo wbsJRequestWrapper::formEncode('fo',$entry->getRelPath())?>" />
			<input type="hidden" name="option" value="com_wbsdirmgr" />
			<input type="hidden" name="task" value="addfolder" />
			
			<?php echo JHTML::_( 'form.token' ); ?>
			
			<span class="inputstyle">
				<?php echo JText::_('LBL_FOLDER_NAME')?>
				<input class='text' name='foldername' type='text' />
				<input type='submit'  value="<?php echo JText::_('BTN_ADD_FOLDER')?>" title="<?php echo JText::_('BTN_ADD_FOLDER')?>"/>
			</span>
		
		</form>
	</div>	
	<?php }?>
	
	<?php if($canUploadFile){?>
	<div id="uploadfile">
		<form enctype='multipart/form-data' action="<?php echo $formTarget;?>" method='post'>
			<input type='hidden' name='fo' value="<?php  echo wbsJRequestWrapper::formEncode('fo',$entry->getRelPath());?>" />
			<input type="hidden" name="option" value="com_wbsdirmgr" />
			<input type="hidden" name="task" value="upload" />
			
			<?php echo JHTML::_( 'form.token' ); ?>
			
			<span class="inputstyle">
			  <?php echo JText::_('LBL_UPLOAD_FILE');?>
			  <input id="toopacit" class="file" name="uploadfile" type="file" />
			  <input type="submit" value="<?php echo JText::_('BTN_UPLOAD_FILE')?>" title="<?php echo JText::_('BTN_UPLOAD_FILE')?>" />
		<span class="small"><?php echo JText::sprintf('MAX_SIZE',$view->getMaxUploadSize());?></span>
    	</span> 
			
		</form>
		</div>
	<?php }?>
</div>