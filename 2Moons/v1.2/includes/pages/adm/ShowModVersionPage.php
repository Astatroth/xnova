<?php

##############################################################################
# *                                                                          #
# * 2MOONS                                                                   #
# *                                                                          #
# * @copyright Copyright (C) 2010 By ShadoX from titanspace.de               #
# * @copyright Copyright (C) 2008 - 2009 By lucky from Xtreme-gameZ.com.ar	 #
# *                                                                          #
# *	                                                                         #
# *  This program is free software: you can redistribute it and/or modify    #
# *  it under the terms of the GNU General Public License as published by    #
# *  the Free Software Foundation, either version 3 of the License, or       #
# *  (at your option) any later version.                                     #
# *	                                                                         #
# *  This program is distributed in the hope that it will be useful,         #
# *  but WITHOUT ANY WARRANTY; without even the implied warranty of          #
# *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           #
# *  GNU General Public License for more details.                            #
# *                                                                          #
##############################################################################

if ($USER['rights'][str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__)] != 1) exit;

function ShowModVersionPage()
{
	global $LNG, $USER;
	$MVC	= array();
	$Files	= scandir(ROOT_PATH.'includes/functions/mvc/');
	foreach($Files as $File) {
		if(substr($File, 0, 4) == 'mvc_')
			require(ROOT_PATH.'includes/functions/mvc/'.$File);
	}
	
	foreach($MVC as &$Mod) {
		$Mod['description']	= $Mod['description'][$USER['lang']];
		$Update	= @simplexml_load_file($Mod['update']);
		$Update	= $Update->$Mod['tag'];
		if(version_compare($Mod['version'], $Update->version, '<')) {
			$Mod['update']		= colorRed($LNG['mvc_update_yes']);
			$Mod['udetails']	= array('version' => $Update->version, 'date' => $Update->date, 'download' => $Update->download, 'announcement' => $Update->announcement);
		} else {
			$Mod['update']		= colorGreen($LNG['mvc_update_no']);
			$Mod['udetails']	= false;
		}		
	}
	
	$template	= new template();
	$template->page_header();
	$template->assign_vars(array(
		'MVC'					=> $MVC,
		'mvc_title'				=> $LNG['mvc_title'],
		'mvc_author'			=> $LNG['mvc_author'],
		'mvc_version'			=> $LNG['mvc_version'],
		'mvc_link'				=> $LNG['mvc_link'],
		'mvc_update_version'	=> $LNG['mvc_update_version'],
		'mvc_update_date'		=> $LNG['mvc_update_date'],
		'mvc_announcement'		=> $LNG['mvc_announcement'],
		'mvc_download'			=> $LNG['mvc_download'],
		'mvc_desc'				=> $LNG['mvc_desc'],
	));
	$template->show('adm/ModVersionPage.tpl');
}

?>