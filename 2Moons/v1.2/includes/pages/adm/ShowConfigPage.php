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

function ShowConfigPage()
{
	global $CONF, $LNG, $USER;

	if ($_POST['opt_save'] == "1")
	{
		$Log	.=	"\n".$LNG['log_the_user'].$USER['username'].$LNG['log_sett_no1'].":\n";
		

		if(isset($_POST['closed']) && $_POST['closed'] == 'on') {
			$CONF['game_disable']		= 1;
		} else {
			$CONF['game_disable']		= 0;
		}
		
		if (isset($_POST['noobprotection']) && $_POST['noobprotection'] == 'on') {
			$CONF['noobprotection'] 	= 1;
		} else {
			$CONF['noobprotection'] 	= 0;
		}
		
		if (isset($_POST['debug']) && $_POST['debug'] == 'on') {
			$CONF['debug'] 				= 1;
		} else {
			$CONF['debug'] 				= 0;
		}
		
		if (isset($_POST['adm_attack']) && $_POST['adm_attack'] == 'on') {
			$CONF['adm_attack'] 		= 1;
		} else {
			$CONF['adm_attack'] 		= 0;
		}
		
		if (isset($_POST['newsframe']) && $_POST['newsframe'] == 'on') {
			$CONF['OverviewNewsFrame']  = 1;
			$CONF['OverviewNewsText']   = $_POST['NewsText'];
        } else {
			$CONF['OverviewNewsFrame']  = 0;
			$CONF['OverviewNewsText']   = $_POST['NewsText'];
        }	

		if (isset($_POST['capaktiv']) && $_POST['capaktiv'] == 'on') {
			$CONF['capaktiv'] 			= 1;
		} else {
			$CONF['capaktiv'] 			= 0;
		}

		if (isset($_POST['reg_closed']) && $_POST['reg_closed'] == 'on') {
			$CONF['reg_closed'] = 1;
		} else {
			$CONF['reg_closed'] = 0;
		}
		
		if (isset($_POST['user_valid']) && $_POST['user_valid'] == 'on') {
			$CONF['user_valid'] = 1;
		} else {
			$CONF['user_valid'] = 0;
		}
		
		if (isset($_POST['ga_active']) && $_POST['ga_active'] == 'on') {
			$CONF['ga_active'] = 1;
		} else {
			$CONF['ga_active'] = 0;
		}
		
		if (isset($_POST['bgm_active']) && $_POST['bgm_active'] == 'on') {
			$CONF['bgm_active'] = 1;
		} else {
			$CONF['bgm_active'] = 0;
		}
		
		$CONF['close_reason']			= request_var('close_reason', '', true);
		$CONF['game_name']				= request_var('game_name', '', true);
		$CONF['forum_url'] 				= request_var('forum_url', '', true);
		$CONF['game_speed'] 			= (2500 * request_var('game_speed', 0.0));
		$CONF['fleet_speed'] 			= (2500 * request_var('fleet_speed', 0.0));
		$CONF['resource_multiplier']	= request_var('resource_multiplier', 0.0);
		$CONF['halt_speed']				= request_var('halt_speed', 0.0);
		$CONF['initial_fields']			= request_var('initial_fields', 0);
		$CONF['metal_basic_income']		= request_var('metal_basic_income', 0);
		$CONF['crystal_basic_income']	= request_var('crystal_basic_income', 0);
		$CONF['deuterium_basic_income']	= request_var('deuterium_basic_income', 0);
		$CONF['lang']					= request_var('lang', '');
		$CONF['Defs_Cdr']				= request_var('Defs_Cdr', 0);
		$CONF['Fleet_Cdr']				= request_var('Fleet_Cdr', 0);
		$CONF['noobprotectiontime']		= request_var('noobprotectiontime', 0);
		$CONF['noobprotectionmulti']	= request_var('noobprotectionmulti', 0);
		$CONF['capprivate']				= request_var('capprivate', '');
		$CONF['cappublic']				= request_var('cappublic', '');
		$CONF['min_build_time']			= request_var('min_build_time', 0);
		$CONF['ga_key']					= request_var('ga_key', '', true);
		$CONF['bgm_file']				= request_var('bgm_file', '', true);
		$CONF['smtp_host']				= request_var('smtp_host', '', true);
		$CONF['smtp_port']				= request_var('smtp_port', 0);
		$CONF['smtp_user']				= request_var('smtp_user', '', true);
		$CONF['smtp_sendmail']			= request_var('smtp_sendmail', '', true);
		$CONF['smtp_pass']				= request_var('smtp_pass', '', true);
		$CONF['smtp_ssl']				= request_var('smtp_ssl', '');
		$CONF['ftp_server']				= request_var('ftp_server', '', true);
		$CONF['ftp_user_name']			= request_var('ftp_user_name', '', true);
		$CONF['ftp_root_path']			= request_var('ftp_root_path', '', true);
		
		$Temp							= request_var('ftp_user_pass', '', true);
		$CONF['ftp_user_pass']			= ((str_pad('', strlen($CONF['ftp_user_pass']), 'x') == $Temp) ? $CONF['ftp_user_pass'] : $Temp);

		update_config('noobprotectiontime'		, $CONF['noobprotectiontime']);
		update_config('noobprotectionmulti'		, $CONF['noobprotectionmulti']);
		update_config('noobprotection'			, $CONF['noobprotection']);
		update_config('Defs_Cdr'				, $CONF['Defs_Cdr']);
		update_config('Fleet_Cdr'				, $CONF['Fleet_Cdr']);
		update_config('game_disable'			, $CONF['game_disable']);
		update_config('close_reason'			, $CONF['close_reason']);
        update_config('OverviewNewsFrame'		, $CONF['OverviewNewsFrame']);
		update_config('reg_closed'				, $CONF['reg_closed']);
        update_config('OverviewNewsText'		, $CONF['OverviewNewsText']);
		update_config('game_name'				, $CONF['game_name']);
		update_config('forum_url'				, $CONF['forum_url']);
		update_config('game_speed'				, $CONF['game_speed']);
		update_config('fleet_speed'				, $CONF['fleet_speed']);
		update_config('resource_multiplier'		, $CONF['resource_multiplier']);
		update_config('halt_speed'				, $CONF['halt_speed']);
		update_config('initial_fields'			, $CONF['initial_fields']);
		update_config('metal_basic_income'		, $CONF['metal_basic_income']);
		update_config('crystal_basic_income'	, $CONF['crystal_basic_income']);
		update_config('deuterium_basic_income'	, $CONF['deuterium_basic_income']);
		update_config('debug'					, $CONF['debug']);
		update_config('adm_attack'				, $CONF['adm_attack']);
		update_config('lang'					, $CONF['lang']);
		update_config('capaktiv'				, $CONF['capaktiv']);
		update_config('capprivate'				, $CONF['capprivate']);
		update_config('cappublic'				, $CONF['cappublic']);
		update_config('min_build_time'			, $CONF['min_build_time']);
		update_config('smtp_host'				, $CONF['smtp_host']);
		update_config('smtp_port'				, $CONF['smtp_port']);
		update_config('smtp_user'				, $CONF['smtp_user']);
		update_config('smtp_pass'				, $CONF['smtp_pass']);
		update_config('smtp_ssl'				, $CONF['smtp_ssl']);
		update_config('smtp_sendmail'			, $CONF['smtp_sendmail']);
		update_config('user_valid'				, $CONF['user_valid']);
		update_config('ftp_server'				, $CONF['ftp_server']);
		update_config('ftp_user_name'			, $CONF['ftp_user_name']);
		update_config('ftp_user_pass'			, $CONF['ftp_user_pass']);
		update_config('ftp_root_path'			, $CONF['ftp_root_path']);
		update_config('ga_active'				, $CONF['ga_active']);
		update_config('ga_key'					, $CONF['ga_key']);
		update_config('bgm_active'				, $CONF['bgm_active']);
		update_config('bgm_file'				, $CONF['bgm_file']);
	}
	
	$template	= new template();
	$template->page_header();
	$template->assign_vars(array(
		'se_server_parameters'			=> $LNG['se_server_parameters'],
		'se_name'						=> $LNG['se_name'],
		'se_server_name'				=> $LNG['se_server_name'],
		'se_cookie_name'				=> $LNG['se_cookie_name'],
		'se_cookie_advert'				=> $LNG['se_cookie_advert'],
		'se_lang'						=> $LNG['se_lang'],
		'se_general_speed'				=> $LNG['se_general_speed'],
		'se_fleet_speed'				=> $LNG['se_fleet_speed'],
		'se_halt_speed'					=> $LNG['se_halt_speed'],
		'se_normal_speed'				=> $LNG['se_normal_speed'],
		'se_normal_speed_fleet'			=> $LNG['se_normal_speed_fleet'],
		'se_resources_producion_speed'	=> $LNG['se_resources_producion_speed'],
		'se_normal_speed_resoruces'		=> $LNG['se_normal_speed_resoruces'],
		'se_normal_speed_halt'			=> $LNG['se_normal_speed_halt'],
		'se_forum_link'					=> $LNG['se_forum_link'	],
		'se_server_op_close'			=> $LNG['se_server_op_close'],
		'se_server_status_message'		=> $LNG['se_server_status_message'],
		'se_server_planet_parameters'	=> $LNG['se_server_planet_parameters'],
		'se_initial_fields'				=> $LNG['se_initial_fields'],
		'se_metal_production'			=> $LNG['se_metal_production'],
		'se_admin_protection'			=> $LNG['se_admin_protection'],
		'se_crystal_production'			=> $LNG['se_crystal_production'],
		'se_deuterium_production'		=> $LNG['se_deuterium_production'],
		'se_several_parameters'			=> $LNG['se_several_parameters'],
		'se_min_build_time'				=> $LNG['se_min_build_time'],
		'se_reg_closed'					=> $LNG['se_reg_closed'],
		'se_verfiy_mail'				=> $LNG['se_verfiy_mail'],
		'se_min_build_time_info'		=> $LNG['se_min_build_time_info'],
		'se_verfiy_mail_info'			=> $LNG['se_verfiy_mail_info'],
		'se_fields'						=> $LNG['se_fields'],
		'se_per_hour'					=> $LNG['se_per_hour'],
		'se_debug_mode'					=> $LNG['se_debug_mode'],
		'se_title_admins_protection'	=> $LNG['se_title_admins_protection'],
		'se_debug_message'				=> $LNG['se_debug_message'],
		'se_ships_cdr_message'			=> $LNG['se_ships_cdr_message'],
		'se_def_cdr_message'			=> $LNG['se_def_cdr_message'],
		'se_ships_cdr'					=> $LNG['se_ships_cdr'],
		'se_def_cdr'					=> $LNG['se_def_cdr'],
		'se_noob_protect'				=> $LNG['se_noob_protect'],
		'se_noob_protect3'				=> $LNG['se_noob_protect3'],
		'se_noob_protect2'				=> $LNG['se_noob_protect2'],
		'se_noob_protect_e2'			=> $LNG['se_noob_protect_e2'],
		'se_noob_protect_e3'			=> $LNG['se_noob_protect_e3'],
		'se_news_head'					=> $LNG['se_news_head'],
		'se_news_active'				=> $LNG['se_news_active'],
		'se_news_info'					=> $LNG['se_news_info'],
		'se_news'						=> $LNG['se_news'],
		'se_news_limit'					=> $LNG['se_news_limit'],
		'se_recaptcha_head'				=> $LNG['se_recaptcha_head'],
		'se_recaptcha_active'			=> $LNG['se_recaptcha_active'],
		'se_recaptcha_desc'				=> $LNG['se_recaptcha_desc'],
		'se_recaptcha_public'			=> $LNG['se_recaptcha_public'],
		'se_recaptcha_private'			=> $LNG['se_recaptcha_private'],
		'se_smtp'						=> $LNG['se_smtp'],
		'se_smtp_info'					=> $LNG['se_smtp_info'],
		'se_smtp_host'					=> $LNG['se_smtp_host'],
		'se_smtp_host_info'				=> $LNG['se_smtp_host_info'],
		'se_smtp_ssl'					=> $LNG['se_smtp_ssl'],
		'se_smtp_ssl_info'				=> $LNG['se_smtp_ssl_info'],
		'se_smtp_port'					=> $LNG['se_smtp_port'],
		'se_smtp_port_info'				=> $LNG['se_smtp_port_info'],
		'se_smtp_user'					=> $LNG['se_smtp_user'],
		'se_smtp_pass'					=> $LNG['se_smtp_pass'],
		'se_smtp_sendmail'				=> $LNG['se_smtp_sendmail'],
		'se_smtp_sendmail_info'			=> $LNG['se_smtp_sendmail_info'],
		'se_ftp'						=> $LNG['se_ftp'],
		'se_ftp_info'					=> $LNG['se_ftp_info'],
		'se_ftp_host'					=> $LNG['se_ftp_host'],
		'se_ftp_user'					=> $LNG['se_ftp_user'],
		'se_ftp_pass'					=> $LNG['se_ftp_pass'],
		'se_ftp_dir'					=> $LNG['se_ftp_dir'],
		'se_ftp_dir_info'				=> $LNG['se_ftp_dir_info'],
		'se_google'						=> $LNG['se_google'],
		'se_google_active'				=> $LNG['se_google_active'],
		'se_google_info'				=> $LNG['se_google_info'],
		'se_google_key'					=> $LNG['se_google_key'],
		'se_google_key_info'			=> $LNG['se_google_key_info'],
		'se_bgm_login'					=> $LNG['se_bgm_login'],
		'se_bgm_active'					=> $LNG['se_bgm_active'],
		'se_bgm_info'					=> $LNG['se_bgm_info'],
		'se_bgm_file'					=> $LNG['se_bgm_file'],
		'se_bgm_file_info'				=> $LNG['se_bgm_file_info'],
		'se_google_key_info'			=> $LNG['se_google_key_info'],
		'se_save_parameters'			=> $LNG['se_save_parameters'],
		'game_name'						=> $CONF['game_name'],
		'game_speed'					=> ($CONF['game_speed'] / 2500),
		'fleet_speed'					=> ($CONF['fleet_speed'] / 2500),
		'resource_multiplier'			=> $CONF['resource_multiplier'],
		'halt_speed'					=> $CONF['halt_speed'],
		'forum_url'						=> $CONF['forum_url'],
		'initial_fields'				=> $CONF['initial_fields'],
		'metal_basic_income'			=> $CONF['metal_basic_income'],
		'crystal_basic_income'			=> $CONF['crystal_basic_income'],
		'deuterium_basic_income'		=> $CONF['deuterium_basic_income'],
		'game_disable'					=> $CONF['game_disable'],
		'close_reason'					=> $CONF['close_reason'],
		'debug'							=> $CONF['debug'],
		'adm_attack'					=> $CONF['adm_attack'],
		'cookie'						=> $CONF['COOKIE_NAME'],
		'defenses'						=> $CONF['Defs_Cdr'],
		'shiips'						=> $CONF['Fleet_Cdr'],
		'noobprot'						=> $CONF['noobprotection'],
		'noobprot2'						=> $CONF['noobprotectiontime'],
		'noobprot3'						=> $CONF['noobprotectionmulti'],
		'smtp_host' 					=> $CONF['smtp_host'],
		'smtp_port' 					=> $CONF['smtp_port'],
		'smtp_user' 					=> $CONF['smtp_user'],
		'smtp_pass' 					=> $CONF['smtp_pass'],
		'smtp_sendmail' 				=> $CONF['smtp_sendmail'],
		'smtp_ssl'						=> $CONF['smtp_ssl'],
		'user_valid'           	 		=> $CONF['user_valid'],
	    'newsframe'                 	=> $CONF['OverviewNewsFrame'],
        'reg_closed'                	=> $CONF['reg_closed'],
        'NewsTextVal'               	=> $CONF['OverviewNewsText'],  
		'capprivate' 					=> $CONF['capprivate'],
		'cappublic' 	   				=> $CONF['cappublic'],
		'capaktiv'      	           	=> $CONF['capaktiv'],
		'min_build_time'    	        => $CONF['min_build_time'],
		'ftp_server'          			=> $CONF['ftp_server'],
		'ftp_user_name'           		=> $CONF['ftp_user_name'],
		'ftp_user_pass'     	      	=> str_pad('', strlen($CONF['ftp_user_pass']), 'x'),
		'ftp_root_path'         	  	=> $CONF['ftp_root_path'],
        'ga_active'               		=> $CONF['ga_active'],
		'ga_key'           				=> $CONF['ga_key'],
        'bgm_active'               		=> $CONF['bgm_active'],
		'bgm_file'           			=> $CONF['bgm_file'],
		'Selector'						=> array('langs' => GetLangs(), 'mail' => array('' => $LNG['se_smtp_ssl_1'], 'ssl' => $LNG['se_smtp_ssl_2'], 'tls' => $LNG['se_smtp_ssl_3'])),
		'lang'							=> $CONF['lang'],
	));
	
	$template->show('adm/ConfigBody.tpl');
}

?>