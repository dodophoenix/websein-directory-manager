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
$entry = $view->getCurrentFileEntry();


//$openFolderLink =  	JRoute::_( 'index.php?option=com_wbsdirmgr&task=open&view=wbsdirmgr&fo='.$escapedRelPath );
//$deleteLink = 		JRoute::_( 'index.php?option=com_wbsdirmgr&task=del&view=wbsdirmgr&fo='.$escapedRelPath);
//$beginRenameLnk = 	JRoute::_( 'index.php?option=com_wbsdirmgr&task=beginRename&view=wbsdirmgr&fo='.$escapedRelPath);

$canOpenDir  =$view->isToDisplay(WbsDirMgrModelAccess::$EVENT_OPEN_DIR);
$canRenameDir=$view->isToDisplay(WbsDirMgrModelAccess::$EVENT_RENAME_DIR);
$canDeleteDir=$view->isToDisplay(WbsDirMgrModelAccess::$EVENT_DELETE_DIR);
?>

<div class="jsClsentry wbsentry">
	<?php if($canOpenDir){?>
		<a href="<?php $url->mkUrl('open',$entry->getRelPath());?>">
	<?php }?>
		<img src="components/com_wbsdirmgr/img/icons/folder.png" alt="<?php echo wbsJRequestWrapper::formEncode('entryname',$entry->getEntry());?>" />
	<?php if($canOpenDir){?>
		</a>
	<?php }?>
	<span class="renameable">
		<?php if(!$view->isToRename($entry)){?>
			<span class="outText">
				<?php if($canOpenDir){?>
					<a href="<?php $url->mkUrl('open',$entry->getRelPath());?>">
				<?php }
				 //echo $entry->getEntry();
				 echo wbsJRequestWrapper::formEncode('entryname',$entry->getEntry());
				 if($canOpenDir){?>
					</a>
				<?php }?>
			</span>
		<?php }else{?>
			<?php if($canRenameDir){?>
				<span class="inText" >
					<input type="text"   name="newName" value="<?php  echo wbsJRequestWrapper::formEncode('newName',$entry->getEntry())?>" />
					<input type="hidden" name="torename" value="<?php echo wbsJRequestWrapper::formEncode('torename',$entry->getRelPath());?>" />
					<input type="hidden" name="fo" value="<?php echo wbsJRequestWrapper::formEncode('fo',$entry->getRelPath());?>" />
					<a href="javascript:document.forms.jsIDrename.submit();">
						<img alt="ok" src="<?php echo $view->imageIcon('tick.png');?>" class="okrename" />
					</a>			
				</span>
			<?php }?>
		<?php }?>
	</span>

<span class="entryTools">
	<?php if($canRenameDir){?>
		<a href="<?php $url->mkUrl('beginRename',$entry->getRelPath());?>" class="renamelnk">		
			<img alt="rename" src="<?php echo $view->imageIcon("rename.png")?>">
		</a>
	<?php }?>
	<?php if($canDeleteDir){?>
		<a class="Delb" href="<?php $url->mkUrl('del',$entry->getRelPath());?>">
			<img alt="delete" src="<?php echo $view->imageIcon("delete.png")?>">
		</a>
	<?php }?>
</span>
<span class="fsizefield"><?php echo JText::sprintf("CONTAINS_ENTRIES",count($entry->getChilds()));?>&nbsp;&nbsp;&nbsp;</span>
</div>

