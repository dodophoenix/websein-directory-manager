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
$entry = $view->getCurrentFileEntry();

// srap encodings
$url = $view;
$showUpLink = $entry->hasParent();


$params = JComponentHelper::getParams('com_wbsdirmgr');
    

// Breadcrump from template settigns 
$breadCrump = intval($params->get( 'showFullPath',0 ));
$breadCrumpPath = $entry->getEntry();
if($breadCrump==1){
	$breadCrumpPath=$entry->getRelPath();
}



?>
<div id="toolbar" class="wbsDirMgrToolbar">
	<span id="location" class="wbsDirMgrlocTxt"> <?php echo JText::_('LBL_CURRENT_LOCATION');  echo wbsJRequestWrapper::formEncode('entryname',$breadCrumpPath);?></span> 
	<span id="up">
		<?php if($showUpLink){?>
			<a href="<?php $url->mkUrl('open',$entry->getParent()->getRelPath());?>" class="wbsDirMgrUpperDirLnk">
				<img alt="up" src="<?php echo $view->imageIcon("upperdir.png");?>" />
				<?php echo JText::_('BTN_DIRECTORY_UP')?>
			</a>
		<?php }?>
	</span>
</div>

