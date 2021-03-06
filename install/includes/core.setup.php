<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------*
| Filename: core.setup.php
| Author: PHP-Fusion Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
// Installs 33 Core Tables and Inserts
if (isset($_POST['uninstall'])) {

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."mlt_tables");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin_resetlog");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcodes");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blacklist");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."captcha");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."comments");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."errors");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."flood_control");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."infusions");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages_options");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."new_users");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."email_verify");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."online");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."panels");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."permalinks_alias");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."permalinks_method");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."permalinks_rewrites");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings_inf");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."smileys");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."submissions");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."suspends");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."theme");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_field_cats");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_fields");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_log");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."email_templates");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."theme");

	// drop all custom tables.
	if (file_exists(BASEDIR.'install/includes/articles_setup.php')) include BASEDIR.'install/includes/articles_setup.php';
	if (file_exists(BASEDIR.'install/includes/blog_setup.php')) include BASEDIR.'install/includes/blog_setup.php';
	if (file_exists(BASEDIR.'install/includes/downloads_setup.php')) include BASEDIR.'install/includes/downloads_setup.php';
	if (file_exists(BASEDIR.'install/includes/eshop_setup.php')) include BASEDIR.'install/includes/eshop_setup.php';
	if (file_exists(BASEDIR.'install/includes/faqs_setup.php')) include BASEDIR.'install/includes/faqs_setup.php';
	if (file_exists(BASEDIR.'install/includes/forums_setup.php')) include BASEDIR.'install/includes/forums_setup.php';
	if (file_exists(BASEDIR.'install/includes/news_setup.php')) include BASEDIR.'install/includes/news_setup.php';
	if (file_exists(BASEDIR.'install/includes/photo_setup.php')) include BASEDIR.'install/includes/photo_setup.php';
	if (file_exists(BASEDIR.'install/includes/polls_setup.php')) include BASEDIR.'install/includes/polls_setup.php';
	if (file_exists(BASEDIR.'install/includes/weblinks_setup.php')) include BASEDIR.'install/includes/weblinks_setup.php';

} else {
	$result = dbquery("CREATE TABLE ".$db_prefix."admin (
				admin_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				admin_rights CHAR(4) NOT NULL DEFAULT '',
				admin_image VARCHAR(50) NOT NULL DEFAULT '',
				admin_title VARCHAR(50) NOT NULL DEFAULT '',
				admin_link VARCHAR(100) NOT NULL DEFAULT 'reserved',
				admin_page TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				PRIMARY KEY (admin_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."mlt_tables (
				mlt_rights CHAR(4) NOT NULL DEFAULT '',
				mlt_title VARCHAR(50) NOT NULL DEFAULT '',
				mlt_status VARCHAR(50) NOT NULL DEFAULT '',
				PRIMARY KEY (mlt_rights)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."admin_resetlog (
				reset_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
				reset_admin_id mediumint(8) unsigned NOT NULL default '1',
				reset_timestamp int(10) unsigned NOT NULL default '0',
				reset_sucess text NOT NULL,
				reset_failed text NOT NULL,
				reset_admins varchar(8) NOT NULL default '0',
				reset_reason varchar(255) NOT NULL,
				PRIMARY KEY (reset_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."bbcodes (
				bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				bbcode_name VARCHAR(20) NOT NULL DEFAULT '',
				bbcode_order SMALLINT(5) UNSIGNED NOT NULL,
				PRIMARY KEY (bbcode_id),
				KEY bbcode_order (bbcode_order)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."blacklist (
				blacklist_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				blacklist_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				blacklist_ip VARCHAR(45) NOT NULL DEFAULT '',
				blacklist_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				blacklist_email VARCHAR(100) NOT NULL DEFAULT '',
				blacklist_reason TEXT NOT NULL,
				blacklist_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				PRIMARY KEY (blacklist_id),
				KEY blacklist_ip_type (blacklist_ip_type)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."captcha (
				captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				captcha_ip VARCHAR(45) NOT NULL DEFAULT '',
				captcha_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
				captcha_string VARCHAR(15) NOT NULL DEFAULT '',
				KEY captcha_datestamp (captcha_datestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."custom_pages (
				page_id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				page_title VARCHAR(200) NOT NULL DEFAULT '',
				page_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
				page_content TEXT NOT NULL,
				page_keywords VARCHAR(250) NOT NULL DEFAULT '',
				page_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				page_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				page_language VARCHAR(50) NOT NULL DEFAULT '".$_POST['localeset']."',
				PRIMARY KEY (page_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."comments (
				comment_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				comment_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				comment_type CHAR(2) NOT NULL DEFAULT '',
				comment_cat MEDIUMINT(8) NOT NULL DEFAULT '0',
				comment_name VARCHAR(50) NOT NULL DEFAULT '',
				comment_message TEXT NOT NULL,
				comment_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				comment_ip VARCHAR(45) NOT NULL DEFAULT '',
				comment_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				comment_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				PRIMARY KEY (comment_id),
				KEY comment_datestamp (comment_datestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."errors (
				error_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
				error_level smallint(5) unsigned NOT NULL,
				error_message text NOT NULL,
				error_file varchar(255) NOT NULL,
				error_line smallint(5) NOT NULL,
				error_page varchar(200) NOT NULL,
				error_user_level smallint(3) NOT NULL,
				error_user_ip varchar(45) NOT NULL default '',
				error_user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				error_status tinyint(1) NOT NULL default '0',
				error_timestamp int(10) NOT NULL,
				PRIMARY KEY (error_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."flood_control (
				flood_ip VARCHAR(45) NOT NULL DEFAULT '',
				flood_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				flood_timestamp INT(5) UNSIGNED NOT NULL DEFAULT '0',
				KEY flood_timestamp (flood_timestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."infusions (
				inf_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				inf_title VARCHAR(100) NOT NULL DEFAULT '',
				inf_folder VARCHAR(100) NOT NULL DEFAULT '',
				inf_version VARCHAR(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (inf_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."messages (
				message_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				message_to MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				message_from MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				message_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				message_subject VARCHAR(100) NOT NULL DEFAULT '',
				message_message TEXT NOT NULL,
				message_smileys CHAR(1) NOT NULL DEFAULT '',
				message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
				PRIMARY KEY (message_id),
				KEY message_datestamp (message_datestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."messages_options (
				user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				pm_email_notify tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
				pm_save_sent tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
				pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				PRIMARY KEY (user_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."new_users (
				user_code VARCHAR(40) NOT NULL,
				user_name VARCHAR(30) NOT NULL,
				user_email VARCHAR(100) NOT NULL,
				user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				user_info TEXT NOT NULL,
				KEY user_datestamp (user_datestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."email_verify (
				user_id MEDIUMINT(8) NOT NULL,
				user_code VARCHAR(32) NOT NULL,
				user_email VARCHAR(100) NOT NULL,
				user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				KEY user_datestamp (user_datestamp)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."ratings (
				rating_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				rating_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				rating_type CHAR(1) NOT NULL DEFAULT '',
				rating_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				rating_vote TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				rating_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				rating_ip VARCHAR(45) NOT NULL DEFAULT '',
				rating_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				PRIMARY KEY (rating_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."online (
				online_user VARCHAR(50) NOT NULL DEFAULT '',
				online_ip VARCHAR(45) NOT NULL DEFAULT '',
				online_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				online_lastactive INT(10) UNSIGNED NOT NULL DEFAULT '0'
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."panels (
				panel_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				panel_name VARCHAR(100) NOT NULL DEFAULT '',
				panel_filename VARCHAR(100) NOT NULL DEFAULT '',
				panel_content TEXT NOT NULL,
				panel_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				panel_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				panel_type VARCHAR(20) NOT NULL DEFAULT '',
				panel_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
				panel_display TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				panel_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				panel_url_list TEXT NOT NULL,
				panel_restriction TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				panel_languages VARCHAR(200) NOT NULL DEFAULT '".$enabled_languages."',
				PRIMARY KEY (panel_id),
				KEY panel_order (panel_order)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."permalinks_alias (
				alias_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				alias_url VARCHAR(200) NOT NULL DEFAULT '',
				alias_php_url VARCHAR(200) NOT NULL DEFAULT '',
				alias_type VARCHAR(10) NOT NULL DEFAULT '',
				alias_item_id INT(10) UNSIGNED NOT NULL DEFAULT '0',
				PRIMARY KEY (alias_id),
				KEY alias_id (alias_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."permalinks_method (
				pattern_id INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				pattern_type INT(5) UNSIGNED NOT NULL,
				pattern_source VARCHAR(200) NOT NULL DEFAULT '',
				pattern_target VARCHAR(200) NOT NULL DEFAULT '',
				pattern_cat VARCHAR(10) NOT NULL DEFAULT '',
				PRIMARY KEY (pattern_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."permalinks_rewrites (
				rewrite_id INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				rewrite_name VARCHAR(50) NOT NULL DEFAULT '',
				PRIMARY KEY (rewrite_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."settings (
				settings_name VARCHAR(200) NOT NULL DEFAULT '',
				settings_value TEXT NOT NULL,
				PRIMARY KEY (settings_name)
				) ENGINE=MYISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."settings_inf (
				settings_name VARCHAR(200) NOT NULL DEFAULT '',
				settings_value TEXT NOT NULL,
				settings_inf VARCHAR(200) NOT NULL DEFAULT '',
				PRIMARY KEY (settings_name)
				) ENGINE=MYISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."site_links (
				link_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				link_name VARCHAR(100) NOT NULL DEFAULT '',
				link_url VARCHAR(200) NOT NULL DEFAULT '',
				link_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
				link_position TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				link_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				link_order SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
				link_language VARCHAR(50) NOT NULL DEFAULT '".$_POST['localeset']."',
				PRIMARY KEY (link_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."smileys (
				smiley_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				smiley_code VARCHAR(50) NOT NULL,
				smiley_image VARCHAR(100) NOT NULL,
				smiley_text VARCHAR(100) NOT NULL,
				PRIMARY KEY (smiley_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."submissions (
				submit_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				submit_type CHAR(1) NOT NULL,
				submit_user MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
				submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				submit_criteria TEXT NOT NULL,
				PRIMARY KEY (submit_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."suspends (
				suspend_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				suspended_user MEDIUMINT(8) UNSIGNED NOT NULL,
				suspending_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
				suspend_ip VARCHAR(45) NOT NULL DEFAULT '',
				suspend_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				suspend_date INT(10) NOT NULL DEFAULT '0',
				suspend_reason TEXT NOT NULL,
				suspend_type TINYINT(1) NOT NULL DEFAULT '0',
				reinstating_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
				reinstate_reason TEXT NOT NULL,
				reinstate_date INT(10) NOT NULL DEFAULT '0',
				reinstate_ip VARCHAR(45) NOT NULL DEFAULT '',
				reinstate_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				PRIMARY KEY (suspend_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."user_field_cats (
				field_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
				field_cat_name VARCHAR(200) NOT NULL ,
				field_parent MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				field_cat_db VARCHAR(100) NOT NULL,
				field_cat_index VARCHAR(200) NOT NULL,
				field_cat_class VARCHAR(50) NOT NULL,
				field_cat_order SMALLINT(5) UNSIGNED NOT NULL ,
				PRIMARY KEY (field_cat_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."user_fields (
				field_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				field_title VARCHAR(50) NOT NULL,
				field_name VARCHAR(50) NOT NULL,
				field_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
				field_type VARCHAR(25) NOT NULL,
				field_default TEXT NOT NULL,
				field_options TEXT NOT NULL,
				field_error VARCHAR(50) NOT NULL,
				field_required TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				field_log TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				field_registration TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				field_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				field_config TEXT NOT NULL,
				PRIMARY KEY (field_id),
				KEY field_order (field_order)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."user_groups (
				group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
				group_name VARCHAR(100) NOT NULL,
				group_description VARCHAR(200) NOT NULL,
				PRIMARY KEY (group_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."user_log (
				userlog_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				userlog_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				userlog_field VARCHAR(50) NOT NULL DEFAULT '',
				userlog_value_new TEXT NOT NULL,
				userlog_value_old TEXT NOT NULL,
				userlog_timestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
				PRIMARY KEY (userlog_id),
				KEY userlog_user_id (userlog_user_id),
				KEY userlog_field (userlog_field)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."users (
				user_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_name VARCHAR(30) NOT NULL DEFAULT '',
				user_algo VARCHAR(10) NOT NULL DEFAULT 'sha256',
				user_salt VARCHAR(40) NOT NULL DEFAULT '',
				user_password VARCHAR(64) NOT NULL DEFAULT '',
				user_admin_algo VARCHAR(10) NOT NULL DEFAULT 'sha256',
				user_admin_salt VARCHAR(40) NOT NULL DEFAULT '',
				user_admin_password VARCHAR(64) NOT NULL DEFAULT '',
				user_email VARCHAR(100) NOT NULL DEFAULT '',
				user_hide_email TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				user_timezone VARCHAR(50) NOT NULL DEFAULT 'Europe/London',
				user_avatar VARCHAR(100) NOT NULL DEFAULT '',
				user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
				user_threads TEXT NOT NULL,
				user_joined INT(10) UNSIGNED NOT NULL DEFAULT '0',
				user_lastvisit INT(10) UNSIGNED NOT NULL DEFAULT '0',
				user_ip VARCHAR(45) NOT NULL DEFAULT '0.0.0.0',
				user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
				user_rights TEXT NOT NULL,
				user_groups TEXT NOT NULL,
				user_level TINYINT(3) UNSIGNED NOT NULL DEFAULT '101',
				user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				user_actiontime INT(10) UNSIGNED NOT NULL DEFAULT '0',
				user_theme VARCHAR(100) NOT NULL DEFAULT 'Default',
				user_location VARCHAR(50) NOT NULL DEFAULT '',
				user_birthdate DATE NOT NULL DEFAULT '0000-00-00',
				user_skype VARCHAR(100) NOT NULL DEFAULT '',
				user_aim VARCHAR(16) NOT NULL DEFAULT '',
				user_icq VARCHAR(15) NOT NULL DEFAULT '',
				user_yahoo VARCHAR(100) NOT NULL DEFAULT '',
				user_web VARCHAR(200) NOT NULL DEFAULT '',
				user_sig VARCHAR(255) NOT NULL DEFAULT '',
				user_language VARCHAR(50) NOT NULL DEFAULT '".$_POST['localeset']."',
				PRIMARY KEY (user_id),
				KEY user_name (user_name),
				KEY user_joined (user_joined),
				KEY user_lastvisit (user_lastvisit)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."email_templates (
				template_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				template_key VARCHAR(10) NOT NULL,
				template_format VARCHAR(10) NOT NULL,
				template_active TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				template_name VARCHAR(300) NOT NULL,
				template_subject TEXT NOT NULL,
				template_content TEXT NOT NULL,
				template_sender_name VARCHAR(30) NOT NULL,
				template_sender_email VARCHAR(100) NOT NULL,
				template_language VARCHAR(50) NOT NULL,
				PRIMARY KEY (template_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	$result = dbquery("CREATE TABLE ".$db_prefix."theme (
				theme_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				theme_name VARCHAR(50) NOT NULL,
				theme_title VARCHAR(50) NOT NULL,
				theme_file VARCHAR(200) NOT NULL,
				theme_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				theme_user MEDIUMINT(8) UNSIGNED NOT NULL,
				theme_active TINYINT(1) UNSIGNED NOT NULL,
				theme_config TEXT NOT NULL,
				PRIMARY KEY (theme_id)
				) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
	if (!$result) {
		$fail = TRUE;
	}
	// System Inserts
	$siteurl = rtrim(dirname(getCurrentURL()), '/').'/';
	$siteurl = str_replace('install/', '', $siteurl);
	$url = parse_url($siteurl);
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitename', 'PHP-Fusion Powered Website')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteurl', '".$siteurl."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_protocol', '".$url['scheme']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_host', '".$url['host']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_port', '".(isset($url['port']) ? $url['port'] : "")."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_path', '".(isset($url['path']) ? $url['path'] : "")."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_seo', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner', 'images/php-fusion-logo.png')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner1', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner2', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteemail', '".$email."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteusername', '".$username."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteintro', '<div style=\'text-align:center\'>".$locale['setup_3650']."</div>')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('description', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('keywords', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('footer', '<div style=\'text-align:center\'>Copyright &copy; ".@date("Y")."</div>')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('opening_page', 'home.php')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_image_readmore', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_image_frontpage', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_thumb_ratio', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_image_link', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_photo_w', '400')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_photo_h', '300')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_thumb_w', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_thumb_h', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_photo_max_w', '1800')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_photo_max_h', '1600')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blog_photo_max_b', '150000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_ratio', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_link', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_w', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_h', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_w', '1800')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_h', '1600')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_b', '150000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('locale', '".stripinput($_POST['localeset'])."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bootstrap', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('theme', 'Septenary')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('admin_theme', 'Venus')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('default_search', 'all')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_left', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_upper', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_lower', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_aupper', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_blower', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_right', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('shortdate', '".$locale['setup_3700']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('longdate', '".$locale['setup_3701']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forumdate', '".$locale['setup_3702']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('newsdate', '".$locale['setup_3703']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('subheaderdate', '".$locale['setup_3704']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('timeoffset', 'Europe/London')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('serveroffset', 'Europe/London')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('numofthreads', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_ips', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachmax', '150000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachmax_count', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachtypes', '.gif,.jpg,.png,.zip,.rar,.tar,.7z')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thread_notify', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_ranks', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_edit_lock', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_edit_timelimit', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_editpost_to_lastpost', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_last_posts_reply', '10')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_last_post_avatar', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_registration', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('email_verification', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('admin_activation', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('display_validation', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_deactivation', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_period', '365')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_response', '14')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_terms', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('license_agreement', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('license_lastupdate', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_w', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_h', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_w', '400')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_h', '300')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_w', '1800')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_h', '1600')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_b', '512000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_compression', 'gd2')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumbs_per_row', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumbs_per_page', '12')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_image', 'images/watermark.png')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color1', 'FF6600')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color2', 'FFFF00')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color3', 'FFFFFF')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_save', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('tinymce_enabled', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_host', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_port', '25')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_username', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_password', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_words_enabled', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_words', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_word_replace', '****')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('login_method', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('guestposts', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_enabled', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('ratings_enabled', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('hide_userprofiles', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('userthemes', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('newsperpage', '12')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('blogperpage', '12')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('flood_interval', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('counter', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('version', '9.00.00')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance_message', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_max_b', '512000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_types', '.pdf,.gif,.jpg,.png,.zip,.rar,.tar,.bz2,.7z')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('articles_per_page', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('downloads_per_page', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('links_per_page', '15')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_per_page', '10')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('posts_per_page', '20')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('threads_per_page', '20')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_sorting', 'ASC')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_avatar', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_width', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_height', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_filesize', '50000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_ratio', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('cronjob_day', '".time()."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('cronjob_hour', '".time()."')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('flood_autoban', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('visitorcounter_enabled', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('rendertime_enabled', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('popular_threads_timeframe', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance_level', '102')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_w', '400')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_h', '300')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_frontpage', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_readmore', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_action', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('captcha', 'securimage2')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('password_algorithm', 'sha256')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('default_timezone', 'Europe/London')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('userNameChange', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_b', '150000')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_w', '1024')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_h', '768')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_public', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_private', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_theme', 'red')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screenshot', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_thumb_max_w', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_thumb_max_h', '100')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('multiple_logins', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_auth', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('mime_check', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('normalize_seo', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('debug_seo', '0')");
	$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('privacy_policy', '')");
	if (isset($_POST['enabled_languages']) && !empty($_POST['enabled_languages'])) {
		$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enabled_languages', '".stripinput($enabled_languages)."')");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enabled_languages', '".stripinput($_POST['localeset'])."')");
	}
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('AR', '".$locale['setup_3200']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('BL', '".$locale['setup_3213']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('CP', '".$locale['setup_3201']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('DL', '".$locale['setup_3202']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('ES', '".$locale['setup_3214']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('FQ', '".$locale['setup_3203']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('FO', '".$locale['setup_3204']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('FR', '".$locale['setup_3212']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('NS', '".$locale['setup_3205']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('PG', '".$locale['setup_3206']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('PO', '".$locale['setup_3207']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('ET', '".$locale['setup_3208']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('WL', '".$locale['setup_3209']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('SL', '".$locale['setup_3210']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."mlt_tables (mlt_rights, mlt_title, mlt_status) VALUES ('PN', '".$locale['setup_3211']."', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['setup_3000']."', 'administrators.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('APWR', 'admin_pass.gif', '".$locale['setup_3047']."', 'admin_reset.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SB', 'banners.gif', '".$locale['setup_3003']."', 'banners.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BB', 'bbcodes.gif', '".$locale['setup_3004']."', 'bbcodes.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B', 'blacklist.gif', '".$locale['setup_3005']."', 'blacklist.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C', '', '".$locale['setup_3006']."', 'reserved', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['setup_3007']."', 'custom_pages.php', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['setup_3008']."', 'db_backup.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('ERRO', 'errors.gif', '".$locale['setup_3048']."', 'errors.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['setup_3013']."', 'images.php', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I', 'infusions.gif', '".$locale['setup_3014']."', 'infusions.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['setup_3015']."', 'reserved', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M', 'members.gif', '".$locale['setup_3016']."', 'members.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P', 'panels.gif', '".$locale['setup_3019']."', 'panels.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PL', 'permalink.gif', '".$locale['setup_3052']."', 'permalink.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['setup_3021']."', 'phpinfo.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['setup_3023']."', 'site_links.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SM', 'smileys.gif', '".$locale['setup_3024']."', 'smileys.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SU', 'submissions.gif', '".$locale['setup_3025']."', 'submissions.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U', 'upgrade.gif', '".$locale['setup_3026']."', 'upgrade.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('TS', 'rocket.gif', '".$locale['setup_3056']."', 'theme.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['setup_3027']."', 'user_groups.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['setup_3030']."', 'settings_main.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['setup_3031']."', 'settings_time.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['setup_3033']."', 'settings_registration.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['setup_3035']."', 'settings_misc.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['setup_3036']."', 'settings_messages.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S9', 'settings_users.gif', '".$locale['setup_3041']."', 'settings_users.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S10', 'settings_ipp.gif', '".$locale['setup_3043']."', 'settings_ipp.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S12', 'security.gif', '".$locale['setup_3044']."', 'settings_security.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UF', 'user_fields.gif', '".$locale['setup_3037']."', 'user_fields.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UL', 'user_log.gif', '".$locale['setup_3049']."', 'user_log.php', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('ROB', 'robots.gif', '".$locale['setup_3050']."', 'robots.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('MAIL', 'email.gif', '".$locale['setup_3800']."', 'email.php', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('LANG', 'languages.gif', '".$locale['setup_3051']."', 'settings_languages.php', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('0', '0', '1', '20', '20', '20')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('smiley', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('b', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('i', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('u', '4')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('url', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('mail', '6')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('img', '7')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('center', '8')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('small', '9')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('code', '10')");
	$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('quote', '11')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':)', 'smile.gif', '".$locale['setup_3620']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (';)', 'wink.gif', '".$locale['setup_3621']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':(', 'sad.gif', '".$locale['setup_3622']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':|', 'frown.gif', '".$locale['setup_3623']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':o', 'shock.gif', '".$locale['setup_3624']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':P', 'pfft.gif', '".$locale['setup_3625']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES ('B)', 'cool.gif', '".$locale['setup_3626']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':D', 'grin.gif', '".$locale['setup_3627']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':@', 'angry.gif', '".$locale['setup_3628']."')");
	$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['setup_3400']."', 'css_navigation_panel', '', '1', '1', 'file', '0', '0', '1', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('RSS Feeds', 'rss_feeds_panel', '', '1', '2', 'file', '0', '0', '1', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['setup_3401']."', 'online_users_panel', '', '1', '3', 'file', '0', '0', '1', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['setup_3404']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1', '')");
	$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['setup_3406']."', 'user_info_panel', '', '4', 1, 'file', '0', '0', '1', '')");
	// UF 1.02
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (1, '".$locale['setup_3640']."', 0, '', '', 'entypo user', 1)");
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (2, '".$locale['setup_3641']."',1, '', '', 'entypo user', 1)");
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (3, '".$locale['setup_3642']."',1, '', '', 'entypo user', 2)");
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (4, '".$locale['setup_3643']."',1, '', '', 'entypo user', 3)");
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (5, '".$locale['setup_3644']."',1, '', '', 'entypo user', 4)");
	$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_parent, field_cat_db, field_cat_index, field_cat_class, field_cat_order) VALUES (6, '".$locale['setup_3645']."',1,  '', '', 'entypo shareable', 5)");
	// Install UF Modules
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_location', '3', 'file', '0', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_birthdate', '3', 'file', '0', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_skype', '2', 'file', '0', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_aim', '2', 'file', '0', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_icq', '2', 'file', '0', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_yahoo', '2', 'file', '0', '5')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_web', '2', 'file', '0', '6')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_timezone', '4', 'file', '0', '1')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_theme', '4', 'file', '0', '2')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_sig', '4', 'file', '0', '3')");
	$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_type, field_required, field_order) VALUES ('user_blacklist', '5', 'file', '0', '1')");

	$lang = explode('.', $enabled_languages);
	if (!function_exists('install_multiple_lang')) {
		function install_multiple_lang($db_prefix, $_lang, $username, $email) {
			include LOCALE.$_lang.'/setup.php';
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('".$locale['setup_3300']."', 'index.php', '0', '2', '0', '1', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('".$locale['setup_3305']."', 'contact.php', '0', '1', '0', '8', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('".$locale['setup_3309']."', 'search.php', '0', '1', '0', '10', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('---', '---', '101', '1', '0', '11', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."email_templates (template_id, template_key, template_format, template_active, template_name, template_subject, template_content, template_sender_name, template_sender_email, template_language) VALUES ('', 'PM', 'html', '0', '".$locale['setup_3801']."', '".$locale['setup_3802']."', '".$locale['setup_3803']."', '".$username."', '".$email."', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."email_templates (template_id, template_key, template_format, template_active, template_name, template_subject, template_content, template_sender_name, template_sender_email, template_language) VALUES ('', 'POST', 'html', '0', '".$locale['setup_3804']."', '".$locale['setup_3805']."', '".$locale['setup_3806']."', '".$username."', '".$email."', '".$_lang."')");
			$result = dbquery("INSERT INTO ".$db_prefix."email_templates (template_id, template_key, template_format, template_active, template_name, template_subject, template_content, template_sender_name, template_sender_email, template_language) VALUES ('', 'CONTACT', 'html', '0', '".$locale['setup_3807']."', '".$locale['setup_3808']."', '".$locale['setup_3809']."', '".$username."', '".$email."', '".$_lang."')");
		}
	}

	foreach($lang as $_lang) {
		install_multiple_lang($db_prefix, $_lang, $username, $email);
	}
}

?>
