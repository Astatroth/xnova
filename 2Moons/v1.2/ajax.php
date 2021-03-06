<?php

##############################################################################
# *                                                                          #
# * 2MOONS                                                                   #
# *                                                                          #
# * @copyright Copyright (C) 2010 By ShadoX from titanspace.de               #
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

define('INSIDE', true );
define('INSTALL', false );
define('AJAX', true );

define('ROOT_PATH', str_replace('\\', '/',dirname(__FILE__)).'/');
	
include_once(ROOT_PATH . 'extension.inc');
include_once(ROOT_PATH . 'common.' . PHP_EXT);
$SESSION       	= new Session();

if(!$SESSION->IsUserLogin() || ($CONF['game_disable'] == 0 && $_SESSION['authlevel'] == 0))
	exit(json_encode(array()));

includeLang('INGAME');
	
$action	= request_var('action', '');
switch($action)
{
	case 'getfleets':
		includeLang('TECH');
		$OwnFleets = $db->query("SELECT DISTINCT * FROM ".FLEETS." WHERE `fleet_owner` = '".$_SESSION['id']."' OR `fleet_target_owner` = '".$_SESSION['id']."';");
		$Record = 0;
		if($db->num_rows($OwnFleets) > 0){
			require_once(ROOT_PATH . 'includes/classes/class.FlyingFleetsTable.' . PHP_EXT);
			$FlyingFleetsTable = new FlyingFleetsTable();
		}
		
		$ACSDone	= array();
		$FleetData 	= array();
		while ($FleetRow = $db->fetch_array($OwnFleets))
		{
			$Record++;
			$IsOwner	= ($FleetRow['fleet_owner'] == $_SESSION['id']) ? true : false;
			
			if ($FleetRow['fleet_mess'] == 0 && $FleetRow['fleet_start_time'] > TIMESTAMP && ($FleetRow['fleet_group'] == 0 || !in_array($FleetRow['fleet_group'], $ACSDone)))
			{
				$ACSDone[]		= $FleetRow['fleet_group'];
				
				$FleetData[$FleetRow['fleet_start_time'].$FleetRow['fleet_id']] = $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 0, $IsOwner, 'fs', $Record, true);
			}

			if ($FleetRow['fleet_mission'] == 10 || ($FleetRow['fleet_mission'] == 4 && $FleetRow['fleet_mess'] == 0))
				continue;

			if ($FleetRow['fleet_mess'] != 1 && $FleetRow['fleet_end_stay'] > TIMESTAMP)
				$FleetData[$FleetRow['fleet_end_stay'].$FleetRow['fleet_id']] = $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 2, $IsOwner, 'ft', $Record);
		
			if ($IsOwner == false)
				continue;
		
			if ($FleetRow['fleet_end_time'] > TIMESTAMP)
				$FleetData[$FleetRow['fleet_end_time'].$FleetRow['fleet_id']] = $FlyingFleetsTable->BuildFleetEventTable($FleetRow, 1, $IsOwner, 'fe', $Record);
		}
		$db->free_result($OwnFleets);
		ksort($FleetData);
		echo json_encode($FleetData);
		exit;
	break;
	case 'fleet1':
		$USER							= $db->uniquequery("SELECT u.`".$resource[124]."`, p.`galaxy`, p.`system`, p.`planet`, p.`planet_type` FROM ".USERS." as u, ".PLANETS." as p WHERE p.`id` = '".$_SESSION['planet']."' AND u.`id` = '".$_SESSION['id']."';");
		$TargetGalaxy 					= request_var('galaxy', $USER['galaxy']);
		$TargetSystem 					= request_var('system', $USER['system']);
		$TargetPlanet					= request_var('planet', $USER['planet']);
		$TargetPlanettype 				= request_var('planet_type', $USER['planet_type']);
		
		if($TargetGalaxy == $USER['galaxy'] && $TargetSystem == $USER['system'] && $TargetPlanet == $USER['planet'] && $TargetPlanettype == $USER['planet_type'])
			exit($LNG['fl_error_same_planet']);
		
		if ($TargetPlanet != 16) {
			$Data	= $db->uniquequery("SELECT u.`urlaubs_modus`, p.`id_level`, p.`destruyed`, p.`der_metal`, p.`der_crystal`, p.`destruyed` FROM ".USERS." as u, ".PLANETS." as p WHERE p.`galaxy` = '".$TargetGalaxy."' AND p.`system` = '".$TargetSystem."' AND p.`planet` = '".$TargetPlanet."'  AND p.`planet_type` = '".(($TargetPlanettype == 2) ? 1 : $TargetPlanettype)."' AND `u`.`id` = p.`id_owner`;");
			if ($TargetPlanettype == 3 && !isset($Data))
				exit($LNG['fl_error_no_moon']);
			elseif ($Data['urlaubs_modus'])
				exit($LNG['fl_in_vacation_player']);
			elseif ($Data['id_level'] > $_SESSION['authlevel'])
				exit($LNG['fl_admins_cannot_be_attacked']);
			elseif ($Data['destruyed'] != 0)
				exit($LNG['fl_error_not_avalible']);
			elseif($TargetPlanettype == 2 && $Data['der_metal'] == 0 && $Data['der_crystal'] == 0)
				exit($LNG['fl_error_empty_derbis']);
		} else {
			if ($USER[$resource[124]] == 0)
				exit($LNG['fl_expedition_tech_required']);
			
			$ActualFleets = $db->uniquequery("SELECT COUNT(*) as state FROM ".FLEETS." WHERE `fleet_owner` = '".$_SESSION['id']."' AND `fleet_mission` = '15';");

			if ($ActualFleets['state'] >= floor(sqrt($USER[$resource[124]])))
				exit($LNG['fl_expedition_fleets_limit']);
		}
		exit('OK');
	break;
	case 'renameplanet':
		$newname        = request_var('newname', '', UTF8_SUPPORT);
		if (!empty($newname))
		{
			if (!CheckName($newname))
				exit((UTF8_SUPPORT) ? $LNG['ov_newname_no_space'] : $LNG['ov_newname_alphanum']);
			else
				$db->query("UPDATE ".PLANETS." SET `name` = '".$db->sql_escape($newname)."' WHERE `id` = '".$_SESSION['planet']. "';");
		}
	break;
	case 'deleteplanet':
		$password =	request_var('password', '', true);
		if (!empty($password))
		{
			$USER		= $db->uniquequery("SELECT u.`password`, u.`id_planet`, p.`galaxy`, p.`system`, p.`planet`, p.`planet_type`, p.`id_luna` FROM ".USERS." as u, ".PLANETS." as p WHERE p.`id` = '".$_SESSION['planet']."' AND u.`id` = '".$_SESSION['id']."';");
			$IfFleets	= $db->uniquequery("SELECT COUNT(*) as state FROM ".FLEETS." WHERE (`fleet_owner` = '".$_SESSION['id']."' AND `fleet_start_galaxy` = '".$USER['galaxy']."' AND `fleet_start_system` = '".$USER['system']."' AND `fleet_start_planet` = '".$USER['planet']."') OR (`fleet_target_owner` = '".$_SESSION['id']."' AND `fleet_end_galaxy` = '".$USER['galaxy']."' AND `fleet_end_system` = '".$USER['system']."' AND `fleet_end_planet` = '".$USER['planet']."');");
			
			if ($IfFleets['state'] > 0)
				exit(json_encode(array('mess' => $LNG['ov_abandon_planet_not_possible'])));
			elseif ($USER['id_planet'] == $_SESSION['planet'])
				exit(json_encode(array('mess' => $LNG['ov_principal_planet_cant_abanone'])));
			elseif (md5($password) != $USER['password'])
				exit(json_encode(array('mess' => $LNG['ov_wrong_pass'])));
			else
			{
				if($USER['planet_type'] == 1) {
					$db->multi_query("UPDATE ".PLANETS." SET `destruyed` = '".(TIMESTAMP+ 86400)."' WHERE `id` = '".$_SESSION['planet']."' LIMIT 1;DELETE FROM ".PLANETS." WHERE `id` = '".$USER['id_luna']."' LIMIT 1;");
				} else {
					$db->multi_query("DELETE FROM ".PLANETS." WHERE `id` = '".$_SESSION['planet']."' LIMIT 1;UPDATE ".PLANETS." SET `id_luna` = '0' WHERE `id_luna` = '".$_SESSION['planet']."' LIMIT 1;");
				}
				$_SESSION['planet']	= $USER['id_planet'];
				exit(json_encode(array('ok' => true, 'mess' => $LNG['ov_planet_abandoned'])));
			}
		}
	break;
	case 'getmessages':
		$MessCategory  	= request_var('messcat', 0);
		$MessageList	= array();
		if($MessCategory == 999)
		{
			$UsrMess = $db->query("SELECT * FROM ".MESSAGES." WHERE `message_sender` = '".$_SESSION['id']."' ORDER BY `message_time` DESC;");
				
			while ($CurMess = $db->fetch_array($UsrMess))
			{
				$CurrUsername	= $db->uniquequery("SELECT `username`, `galaxy`, `system`, `planet` FROM ".USERS." WHERE id = '".$CurMess['message_owner']."';");
				
				$MessageList[$CurMess['message_id']]	= array(
					'time'		=> date("d. M Y, H:i:s", $CurMess['message_time']),
					'from'		=> $CurrUsername['username']." [".$CurrUsername['galaxy'].":".$CurrUsername['system'].":".$CurrUsername['planet']."]",
					'subject'	=> $CurMess['message_subject'],
					'type'		=> $CurMess['message_type'],
					'text'		=> $CurMess['message_text'],
				);
			}		
			$db->free_result($UsrMess);	
			
			echo json_encode($MessageList);
			
			exit;
		}
			
		$UsrMess = $db->query("SELECT * FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."' OR (`message_owner` = '0' AND `message_type` = '50') ORDER BY `message_time` DESC;");
			
		while ($CurMess = $db->fetch_array($UsrMess))
		{
			$MessageList[$CurMess['message_id']]	= array(
				'time'		=> date("d. M Y, H:i:s", $CurMess['message_time']),
				'from'		=> $CurMess['message_from'],
				'subject'	=> stripslashes($CurMess['message_subject']),
				'sender'	=> $CurMess['message_sender'],
				'type'		=> $CurMess['message_type'],
				'text'		=> stripslashes($CurMess['message_text']),
			);
		}
		
		$db->free_result($UsrMess);	
				
		echo json_encode(array(
			'MessageList'						=> $MessageList,
			'LNG'								=> array(
				'mg_message_title'					=> $LNG['mg_message_title'],
				'mg_action'							=> $LNG['mg_action'],
				'mg_date'							=> $LNG['mg_date'],
				'mg_from'							=> $LNG['mg_from'],
				'mg_to'								=> $LNG['mg_to'],
				'mg_subject'						=> $LNG['mg_subject'],
				'mg_show_only_header_spy_reports'	=> $LNG['mg_show_only_header_spy_reports'],
				'mg_delete_marked'					=> $LNG['mg_delete_marked'],
				'mg_delete_type_all'				=> $LNG['mg_delete_type_all'],
				'mg_delete_unmarked'				=> $LNG['mg_delete_unmarked'],
				'mg_delete_all'						=> $LNG['mg_delete_all'],
				'mg_confirm_delete'					=> $LNG['mg_confirm_delete'],
				'mg_game_message'					=> $LNG['mg_game_message'],
			),
		));
		exit;
	break;
	case 'updatemessages':
		$UnRead			= request_var('count', 0);
		$MessCategory  	= request_var('messcat', 0);
		if($MessCategory == 50)
			$db->multi_query("UPDATE ".USERS." SET `new_message` = `new_message` - `new_gmessage`, `new_gmessage` = '0' WHERE `id` = '".$_SESSION['id']."';");			
		elseif($MessCategory == 100)
			$db->multi_query("UPDATE ".USERS." SET `new_message` = '0' WHERE `id` = '".$_SESSION['id']."';UPDATE ".MESSAGES." SET `message_unread` = '0' WHERE `message_owner` = '".$_SESSION['id']."';");			
		else
			$db->multi_query("UPDATE ".USERS." SET `new_message` = GREATEST(`new_message` - '".$UnRead."', 0) WHERE `id` = '".$_SESSION['id']."';UPDATE ".MESSAGES." SET `message_unread` = '0' WHERE `message_owner` = '".$_SESSION['id']."' AND `message_type` = '".$MessCategory."';");
		header('HTTP/1.1 204 No Content');
	break;
	case 'deletemessages':
		$DeleteWhat = request_var('deletemessages','');
		$MessType	= request_var('mess_type', 0);
		
		if($MessType == 100 && $DeleteWhat == 'deletetypeall')
			$DeleteWhat	= 'deleteall';
		
		
		switch($DeleteWhat)
		{
			case 'deleteall':
				$db->query("DELETE FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."';");
			break;
			case 'deletetypeall':
				$db->query("DELETE FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."' AND `message_type` = '".$MessType."';");
			case 'deletemarked':
				if(!empty($_REQUEST['delmes']) && is_array($_REQUEST['delmes']))
				{
					$SQLWhere = array();
					foreach($_REQUEST['delmes'] as $id => $b)
					{
						$SQLWhere[] = "`message_id` = '".(int) $id."'";
					}
					
					$db->query("DELETE FROM ".MESSAGES." WHERE (".implode(" OR ",$SQLWhere).") AND `message_owner` = '".$_SESSION['id']."'".(($MessType != 100)? " AND `message_type` = '".$MessType."' ":"").";");
				}
			break;
			case 'deleteunmarked':
				if(!empty($_REQUEST['delmes']) && is_array($_REQUEST['delmes']))
				{
					$SQLWhere = array();
					foreach($_REQUEST['delmes'] as $id => $b)
					{
						$SQLWhere[] = "`message_id` != '".(int) $id."'";
					}
					
					$db->query("DELETE FROM ".MESSAGES." WHERE (".implode(" AND ",$SQLWhere).") AND `message_owner` = '".$_SESSION['id']."'".(($MessType != 100)? " AND `message_type` = '".$MessType."' ":"").";");
				}
			break;
		}
		header('HTTP/1.1 204 No Content');
	break;
	default:
		header('HTTP/1.1 204 No Content');
	break;
}
?>