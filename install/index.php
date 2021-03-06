<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------*
| Filename: install/index.php
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
require_once 'setup_includes.php';

define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("IN_FUSION", TRUE);
define("BASEDIR", '../');
define("INCLUDES", BASEDIR."includes/");
define("LOCALE", BASEDIR."locale/");
define("IMAGES", BASEDIR."images/");
define("THEMES", BASEDIR."themes/");
define("USER_IP", $_SERVER['REMOTE_ADDR']);
if (!defined('DYNAMICS')) { define('DYNAMICS', INCLUDES."dynamics/"); }

//$siteurl = rtrim(dirname(getCurrentURL()), '/').'/';
//$url = parse_url($siteurl);
//var_dump($url);

ob_start();

if (isset($_GET['localeset']) && file_exists(LOCALE.$_GET['localeset']) && is_dir(LOCALE.$_GET['localeset'])) {
	include LOCALE.$_GET['localeset']."/setup.php";
	define("LOCALESET", $_GET['localeset']."/");
} else {
	$_GET['localeset'] = "English";
	define("LOCALESET", "English/");
	include LOCALE.LOCALESET."setup.php";
}

define('SETUP', true);
$settings = array('description'=>'', 'keywords'=>'');
include "../includes/defender.inc.php";
include "../includes/output_handling_include.php";
$defender = new defender();


if (isset($_POST['step']) && $_POST['step'] == "8") {
	if (file_exists(BASEDIR.'config_temp.php')) {
		@rename(BASEDIR.'config_temp.php', BASEDIR.'config.php');
		@chmod(BASEDIR.'config.php', 0644);
	}
	redirect(BASEDIR.'index.php');
}

//determine the chosen database functions
$pdo_enabled = filter_input(INPUT_POST, 'pdo_enabled', FILTER_VALIDATE_BOOLEAN);
$db_host = 'localhost';
$db_user = '';
$db_pass = '';
$db_name = '';
$db_prefix = '';
if (file_exists(BASEDIR.'config.php')) { include BASEDIR.'config.php'; }
elseif (file_exists(BASEDIR.'config_temp.php')) { include BASEDIR.'config_temp.php'; }
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
	$pdo_enabled = (bool) intval($pdo_enabled);
	$db_host = stripinput(trim(strval(filter_input(INPUT_POST, 'db_host')))) ? : $db_host;
	$db_user = stripinput(trim(strval(filter_input(INPUT_POST, 'db_user')))) ? : $db_user;
	$db_pass = stripinput(trim(strval(filter_input(INPUT_POST, 'db_pass')))) ? : $db_pass;
	$db_name = stripinput(trim(strval(filter_input(INPUT_POST, 'db_name')))) ? : $db_name;
	$db_prefix = stripinput(trim(strval(filter_input(INPUT_POST, 'db_prefix')))) ? : $db_prefix;
}


$locale_files = makefilelist("../locale/", ".svn|.|..", TRUE, "folders");
$settings['description'] = $locale['setup_0000'];
$settings['keywords'] = "";
$settings['siteemail'] = '';
$settings['sitename'] = '';
$settings['siteusername'] = $locale['setup_0002'];
$settings['siteurl'] = FUSION_SELF;
require_once LOCALE.LOCALESET.'global.php';
require_once INCLUDES."output_handling_include.php";
include_once INCLUDES."dynamics/dynamics.inc.php";
require_once INCLUDES."sqlhandler.inc.php";
require_once INCLUDES."db_handlers/".($pdo_enabled ? 'pdo' : 'mysql')."_functions_include.php";
$dynamics = new dynamics();
$dynamics->boot();
$system_apps = array(
	// dbname to locale application title
	'articles' => $locale['articles']['title'],
	'blog' => $locale['blog']['title'],
	'downloads' => $locale['downloads']['title'],
	'eshop' => $locale['eshop']['title'],
	'faqs' => $locale['faqs']['title'],
	'forums' => $locale['forums']['title'],
	'news' => $locale['news']['title'],
	'photos' => $locale['photos']['title'],
	'polls' => $locale['polls']['title'],
	'weblinks' => $locale['weblinks']['title']
);

opensetup();

switch (filter_input(INPUT_POST, 'step', FILTER_VALIDATE_INT) ? : 1) {
	// Introduction
	case 1: default:
		$settings = array();
		// create htaccess file.
		if (isset($_POST['htaccess']) && isset($db_prefix) && !empty($settings)) {
			write_htaccess();
			redirect(FUSION_SELF."?localeset=".$_GET['localeset']);
		}

		// ALWAYS reset config to config_temp.php
		if (file_exists(BASEDIR.'config.php')) {
			@rename(BASEDIR.'config.php', BASEDIR.'config_temp.php');
			@chmod(BASEDIR.'config_temp.php', 0755);
		}

		// Must always include a temp file.
		/* 1. To enter Recovery. CONFIG TEMP file must have dbprefix and have value in dbprefix. */
		if (isset($db_prefix) && $db_prefix) {
			dbconnect($db_host, $db_user, $db_pass, $db_name, FALSE);
			if (isset($_POST['uninstall'])) {
				include_once 'includes/core.setup.php'; // why does it still produce flash of error message?
				@unlink(BASEDIR.'config_temp.php');
				@unlink(BASEDIR.'config.php');
				redirect(BASEDIR."install/index.php", 1); // temp fix.
			} else {
				$result = dbquery("SELECT * FROM ".$db_prefix."settings");
				if (dbrows($result)) {
					while ($data = dbarray($result)) {
						$settings[$data['settings_name']] = $data['settings_value'];
					}
				}
			}
			echo "<h4 class='strong'>".$locale['setup_1002']."</h4>\n";
			echo "<span class='display-block m-t-20 m-b-10'>".$locale['setup_1003']."</span>\n";

			echo "<div class='well'>\n";
			echo "<span class='strong display-inline-block m-b-10'>".$locale['setup_1017']."</span><br/><p>".$locale['setup_1018']."</p>";
			echo form_button($locale['setup_1019'], 'step', 'step', '8', array('class' => 'btn-success btn-sm m-t-10'));
			echo "</div>\n";

			echo "<div class='well'>\n";
			echo "<span class='strong display-inline-block m-b-10'>".$locale['setup_1004']."</span><br/><p>".$locale['setup_1005']." <span class='strong'>".$locale['setup_1006']."</span></span></p>";
			echo form_button($locale['setup_1007'], 'uninstall', 'uninstall', 'uninstall', array('class' => 'btn-danger btn-sm m-t-10'));
			echo "</div>\n";
			echo "<div class='well'>\n";
			echo "<span class='strong display-inline-block m-b-10'>".$locale['setup_1008']."</span>\n<br/><p>".$locale['setup_1009']."</p>";
			echo form_button($locale['setup_1010'], 'step', 'step', '5', array('class' => 'btn-primary btn-sm m-r-10'));
			echo "</div>\n";
			echo "<div class='well'>\n";
			echo "<span class='strong display-inline-block m-b-10'>".$locale['setup_1011']."</span>\n<br/><p>".$locale['setup_1012']."</p>";
			echo form_button($locale['setup_1013'], 'step', 'step', '6', array('class' => 'btn-primary btn-sm m-r-10'));
			echo "</div>\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_GET['localeset'])."' />\n";
			if (isset($db_prefix)) {
				echo "<div class='well'>\n";
				echo "<span class='strong display-inline-block m-b-10'>".$locale['setup_1014']."</span>\n<br/><p>".$locale['setup_1015']."</p>";
				echo form_button($locale['setup_1016'], 'htaccess', 'htaccess', 'htaccess', array('class' => 'btn-primary btn-sm m-r-10'));
				echo "</div>\n";
			}

		}
		/* Without click uninstall this is the opening page of installer - just for safety. if not, an else suffices */
		elseif (!isset($_POST['uninstall'])) {
			// no db_prefix
			$locale_list = makefileopts($locale_files, $_GET['localeset']);
			echo "<h4 class='strong'>".$locale['setup_0002']."</h4>\n";
			if (isset($_GET['error']) && $_GET['error'] == 'license') {
				echo "<div class='alert alert-danger'>".$locale['setup_5000']."</div>\n";
			} else {
				echo "<span>".$locale['setup_0003']."</span>\n";
			}
			echo "<span class='display-block m-t-20 m-b-10 strong'>".$locale['setup_1000']."</span>\n";
			echo form_select('', 'localeset', 'localeset', array_combine($locale_files, $locale_files), $_GET['localeset'], array('placeholder' => $locale['choose']));
			echo "<script>\n";
			echo "$('#localeset').bind('change', function() {
				var value = $(this).val();
				document.location.href='".FUSION_SELF."?localeset='+value;
			});";
			echo "</script>\n";
			echo "<div>".$locale['setup_1001']."</div>\n";
			echo "<hr>\n";
			echo form_checkbox($locale['setup_0005'], 'license', 'license', '');
			echo "<hr>\n";
			echo "<input type='hidden' name='step' value='2' />\n";
			renderButton();
		}
	break;
	// Step 2 - File and Folder Permissions
	case 2:
		if (!isset($_POST['license'])) redirect(FUSION_SELF."?error=license&localeset=".$_GET['localeset']);

		// Create a blank config temp by now if not exist.
		if (!file_exists(BASEDIR."config_temp.php")) {
			if (file_exists(BASEDIR."_config.php") && function_exists("rename")) {
				@rename(BASEDIR."_config.php", BASEDIR."config_temp.php");
			} else {
				touch(BASEDIR."config_temp.php");
			}
		}
		$check_arr = array(
			"administration/db_backups" => FALSE,
			"forum/attachments" => FALSE,
			"downloads" => FALSE,
			"downloads/images" => FALSE,
			"downloads/submissions/" => FALSE,
			"downloads/submissions/images" => FALSE,
			"ftp_upload" => FALSE,
			"images" => FALSE,
			"images/imagelist.js" => FALSE,
			"images/articles" => FALSE,
			"images/avatars" => FALSE,
			"images/news" => FALSE,
			"images/news/thumbs" => FALSE,
			"images/news_cats" => FALSE,
			"images/news" => FALSE,
			"images/blog/thumbs" => FALSE,
			"images/blog_cats" => FALSE,
			"images/photoalbum" => FALSE,
			"images/photoalbum/submissions" => FALSE,
			"config_temp.php" => FALSE,
			"robots.txt" => FALSE);
		$write_check = TRUE;
		$check_display = "";
		foreach ($check_arr as $key => $value) {
			$check_arr[$key] = (file_exists(BASEDIR.$key) && is_writable(BASEDIR.$key))
							or (file_exists(BASEDIR.$key) && function_exists("chmod") 
								&& @chmod(BASEDIR.$key, 0777) && is_writable(BASEDIR.$key));
			if (!$check_arr[$key]) {
				$write_check = FALSE;
			}
			
			$check_display .= "<tr>\n<td class='tbl1'>".$key."</td>\n";
			$check_display .= "<td class='tbl1' style='text-align:right'>".($check_arr[$key] == TRUE ? "<label class='label label-success'>".$locale['setup_1100']."</label>" : "<label class='label label-warning'>".$locale['setup_1101']."</label>")."</td>\n</tr>\n";
		}
		echo "<div class='m-b-20'><h4>".$locale['setup_1106']."</h4> ".$locale['setup_1102']."</div>\n";
		echo "<table class='table table-responsive'>\n".$check_display."\n</table><br /><br />\n";
		// can proceed
		if ($write_check) {
			echo "<p><strong>".$locale['setup_1103']."</strong></p>\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			echo "<input type='hidden' name='step' value='3' />\n";
			renderButton();
		} else {
			echo "<p><strong>".$locale['022']."</strong></p>\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			echo "<input type='hidden' name='step' value='2' />\n";
			echo "<br/><button type='submit' name='next' value='".$locale['setup_1105']."' class='btn btn-md btn-primary'><i class='entypo cw'></i> ".$locale['setup_1105']."</button>\n";
		}
	break;
	// Step 3 - Database Settings
	case 3:
		if (!$db_prefix) {
			$db_prefix = "fusion".createRandomPrefix()."_";
		}
		$cookie_prefix = "fusion".createRandomPrefix()."_";
		$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
		$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
		$db_error = (isset($_POST['db_error']) && isnum($_POST['db_error']) ? $_POST['db_error'] : "0");
		$field_class = array("", "", "", "", "");
		if ($db_error > "0") {
			$field_class[2] = " tbl-error";
			if ($db_error == 1) {
				$field_class[1] = " tbl-error";
				$field_class[2] = " tbl-error";
			} elseif ($db_error == 2) {
				$field_class[3] = " tbl-error";
			} elseif ($db_error == 3) {
				$field_class[4] = " tbl-error";
			} elseif ($db_error == 7) {
				if ($db_host == "") {
					$field_class[0] = " tbl-error";
				}
				if ($db_user == "") {
					$field_class[1] = " tbl-error";
				}
				if ($db_name == "") {
					$field_class[3] = " tbl-error";
				}
				if ($db_prefix == "") {
					$field_class[4] = " tbl-error";
				}
			}
		}

		echo "<div class='m-b-20'><h4>".$locale['setup_1200']."</h4> ".$locale['setup_1201']."</div>\n";

		echo "<table class='table table-responsive'>\n<tr>\n";
		echo "<td class='tbl1' style='text-align:left'>".$locale['setup_1202']."</td>\n";
		echo "<td class='tbl1'><input type='text' value='".$db_host."' name='db_host' class='form-control input-sm textbox".$field_class[0]."' style='width:200px' /></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1' style='text-align:left'>".$locale['setup_1203']."</td>\n";
		echo "<td class='tbl1'><input type='text' value='".$db_user."' name='db_user' class='form-control input-sm textbox".$field_class[1]."' style='width:200px' /></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1' style='text-align:left'>".$locale['setup_1204']."</td>\n";
		echo "<td class='tbl1'><input type='password' value='' name='db_pass' class='form-control input-sm textbox".$field_class[2]."' style='width:200px' /></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1205']."</td>\n";
		echo "<td class='tbl1'><input type='text' value='".$db_name."' name='db_name' class='form-control input-sm textbox".$field_class[3]."' style='width:200px' /></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1' style='text-align:left'>".$locale['setup_1208']."</td>\n";
		// enable PDO
		echo "<td class='tbl1'>\n";
		if (!defined('PDO::ATTR_DRIVER_NAME')) {
			echo $locale['setup_1209'];
		} else {
			echo "<select name='pdo_enabled' class='form-control input-sm textbox' style='width:200px'>\n";
			echo "<option value='0' selected='selected'>".$locale['setup_1210']."</option>\n";
			echo "<option value='1'>".$locale['setup_1211']."</option>\n";
			echo "</select>\n";
		}
		echo "</td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1213']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='text' name='username' value='".$username."' placeholder='Admin' maxlength='255' class='form-control input-sm textbox' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1509']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='text' name='email' value='".$email."' maxlength='100' class='form-control input-sm textbox' style='width:200px' /></td></tr>\n";
		echo "<tr><td class='tbl1'>".$locale['setup_1212']."</td>\n";
		echo "<td class='tbl1'>\n";
		for ($i = 0; $i < sizeof($locale_files); $i++) {
			if (file_exists(BASEDIR.'locale/'.$locale_files[$i].'/setup.php')) {
				echo "<input type='checkbox' value='".$locale_files[$i]."' name='enabled_languages[]' class='m-r-10 textbox' ".($locale_files[$i] == $_POST['localeset'] ? "checked='checked'" : "")."> ".$locale_files[$i]."<br />\n";
			}
		}
		echo "</td></tr>\n";
		echo "<tr>\n<td class='tbl1' style='text-align:left'>".$locale['setup_1206']."</td>\n";
		echo "<td class='tbl1'><input type='text' value='".$db_prefix."' name='db_prefix' class='form-control input-sm textbox".$field_class[4]."' style='width:200px' /></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl1' style='text-align:left'>".$locale['setup_1207']."</td>\n";
		echo "<td class='tbl1'><input type='text' value='".$cookie_prefix."' name='cookie_prefix' class='form-control input-sm textbox' style='width:200px' /></td>\n</tr>\n";
		echo "</table>\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='step' value='4' />\n";
		renderButton();
	break;
	// Step 4 - Config / Database Setup
	case 4:
		// Generate All Core Tables - this includes settings and all its injections
		$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : "fusion_");
		$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
		$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
		$enabled_languages = '';

		if (!empty($_POST['enabled_languages'])) {
			for ($i = 0; $i < sizeof($_POST['enabled_languages']); $i++) {
				$enabled_languages .= $_POST['enabled_languages'][$i].".";
			}
			$enabled_languages = substr($enabled_languages, 0, (strlen($enabled_languages)-1));
		} else {
			$enabled_languages = stripinput($_POST['localeset']);
		}
		if ($db_prefix != "") {
			$db_prefix_last = $db_prefix[strlen($db_prefix)-1];
			if ($db_prefix_last != "_") {
				$db_prefix = $db_prefix."_";
			}
		}
		if ($cookie_prefix != "") {
			$cookie_prefix_last = $cookie_prefix[strlen($cookie_prefix)-1];
			if ($cookie_prefix_last != "_") {
				$cookie_prefix = $cookie_prefix."_";
			}
		}
		$selected_langs = '';
		$secret_key = createRandomPrefix(32);
		$secret_key_salt = createRandomPrefix(32);
		if ($db_host != "" && $db_user != "" && $db_name != "" && $db_prefix != "") {
			$connection_info = dbconnect($db_host, $db_user, $db_pass, $db_name, FALSE);
			$db_connect = $connection_info['connection_success'];
			$db_select = $connection_info['dbselection_success'];
			if ($db_connect) {
				if ($db_select) {
					if (dbrows(dbquery("SHOW TABLES LIKE '".str_replace("_", "\_", $db_prefix)."%'")) == "0") {
						$table_name = uniqid($db_prefix, FALSE);
						$can_write = TRUE;
						$result = dbquery("CREATE TABLE ".$table_name." (test_field VARCHAR(10) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci");
						if (!$result) {
							$can_write = FALSE;
						}
						$result = dbquery("DROP TABLE ".$table_name);
						if (!$result) {
							$can_write = FALSE;
						}
						if ($can_write) {
							// Write a Temporary Config File.
							$config = "<?php\n";
							$config .= "// database settings\n";
							$config .= "\$db_host = '".$db_host."';\n";
							$config .= "\$db_user = '".$db_user."';\n";
							$config .= "\$db_pass = '".$db_pass."';\n";
							$config .= "\$db_name = '".$db_name."';\n";
							$config .= "\$db_prefix = '".$db_prefix."';\n";
							$config .= "\$pdo_enabled = ".intval($pdo_enabled).";\n";
							$config .= "define(\"DB_PREFIX\", \"".$db_prefix."\");\n";
							$config .= "define(\"COOKIE_PREFIX\", \"".$cookie_prefix."\");\n";
							$config .= "define(\"SECRET_KEY\", \"".$secret_key."\");\n";
							$config .= "define(\"SECRET_KEY_SALT\", \"".$secret_key_salt."\");\n";
							$config .= "?>";
							if (fusion_file_put_contents(BASEDIR.'config_temp.php', $config)) {
								$fail = FALSE;
								if (!$result) {
									$fail = TRUE;
								}
								// install core tables fully injected.
								include 'includes/core.setup.php';
								if (!$fail) {
									echo "<i class='entypo check'></i> ".$locale['setup_1300']."<br /><br />\n<i class='entypo check'></i> ";
									echo $locale['setup_1301']."<br /><br />\n<i class='entypo check'></i> ";
									echo $locale['setup_1302']."<br /><br />\n";
									$success = TRUE;
									$db_error = 6;
									// get settings for htaccess.
									$result = dbquery("SELECT * FROM ".$db_prefix."settings");
									if (dbrows($result)) {
										while ($data = dbarray($result)) {
											$settings[$data['settings_name']] = $data['settings_value'];
										}
									}
								} else {
									echo "<br />\n<i class='entypo check'></i> ".$locale['setup_1300']."<br /><br />\n<i class='entypo check'></i> ";
									echo $locale['setup_1301']."<br /><br />\n<i class='entypo icancel'></i> ";
									echo "<strong>".$locale['setup_1303']."</strong> ".$locale['setup_1308']."<br /><br />\n";
									$success = FALSE;
									$db_error = 0;
								}
							} else {
								echo "<br />\n".$locale['setup_1300']."<br /><br />\n";
								echo "<strong>".$locale['setup_1303']."</strong> ".$locale['setup_1306']."<br />\n";
								echo "<span class='small'>".$locale['setup_1307']."</span><br /><br />\n";
								$success = FALSE;
								$db_error = 5;
							}

							write_htaccess();

						} else {
							echo "<div class='alert alert-danger'>\n";
							echo $locale['setup_1300']."<br /><br />\n";
							echo "<strong>".$locale['setup_1303']."</strong> ".$locale['setup_1314']."<br />\n";
							echo "<span class='small'>".$locale['setup_1315']."</span><br /><br />\n";
							echo "</div>\n";
							$success = FALSE;
							$db_error = 4;
						}
					} else {
						echo "<div class='alert alert-danger'>\n";
						echo "<strong>".$locale['setup_1303']."<strong> ".$locale['setup_1312']."<br />\n";
						echo "<span class='small'>".$locale['setup_1313']."</span><br /><br />\n";
						echo "</div>\n";
						$success = FALSE;
						$db_error = 3;
					}
				} else {
					echo "<div class='alert alert-danger'>\n";
					echo "<br />\n<strong>".$locale['setup_1303']."<strong> ".$locale['setup_1310']."<br />\n";
					echo "<span class='small'>".$locale['setup_1311']."</span><br /><br />\n";
					echo "</div>\n";
					$success = FALSE;
					$db_error = 2;
				}
			} else {
				echo "<div class='alert alert-danger'>\n";
				echo "<strong>".$locale['setup_1303']."<strong> ".$locale['setup_1304']."<br />\n";
				echo "<span class='small'>".$locale['setup_1305']."</span><br /><br />\n";
				echo "</div>\n";
				$success = FALSE;
				$db_error = 1;
			}
		} else {
			echo "<div class='alert alert-danger'>\n";
			echo "<strong>".$locale['setup_1303']."<strong> ".$locale['setup_1316']."<br />\n";
			echo "".$locale['setup_1317']."<br /><br />\n";
			echo "</div>\n";
			$success = FALSE;
			$db_error = 7;
		}

		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='enabled_languages' value='".$selected_langs."' />\n";
		if ($success) {
			echo "<input type='hidden' name='step' value='5' />\n";
			renderButton();
		} else {
			echo "<input type='hidden' name='step' value='3' />\n";
			echo "<input type='hidden' name='db_host' value='".$db_host."' />\n";
			echo "<input type='hidden' name='db_user' value='".$db_user."' />\n";
			echo "<input type='hidden' name='db_name' value='".$db_name."' />\n";
			echo "<input type='hidden' name='db_prefix' value='".$db_prefix."' />\n";
			echo "<input type='hidden' name='db_error' value='".$db_error."' />\n";
			echo "<button type='submit' name='next' value='".$locale['setup_0122']."' class='btn btn-md btn-warning'><i class='entypo cw'></i> ".$locale['setup_0122']."</button>\n";
		}
	break;
	// Step 5 - Configure Core System - $settings accessible - Requires Config_temp.php (Shut down site when upgrading).
	case 5:
		if (!isset($_POST['done'])) {
			// Load Config and SQL handler.
			if (file_exists(BASEDIR.'config_temp.php')) {
				$connection_info = dbconnect($db_host, $db_user, $db_pass, $db_name, FALSE);
				$db_connect = $connection_info['connection_success'];
				$db_select = $connection_info['dbselection_success'];
				$settings = array();
				$result = dbquery("SELECT * FROM ".$db_prefix."settings");
				if (dbrows($result)>0) {
					while ($data = dbarray($result)) {
						$settings[$data['settings_name']] = $data['settings_value'];
					}
				} else {
					redirect(FUSION_SELF);
				}
			} else {
				redirect(FUSION_SELF); // start all over again if you tampered config_temp here.
			}
			$fail = FALSE;
			$message = '';

			// Do installation
			if (isset($_POST['install'])) {
				$_apps = stripinput($_POST['install']);
				if (file_exists('includes/'.$_apps.'_setup.php')) {
					include 'includes/'.$_apps.'_setup.php';
					$message = "<div class='alert alert-success strong'><i class='entypo check'></i>".sprintf($locale['setup_1406'], $system_apps[$_apps])."</div>";
					if ($fail) {
						$message = "<div class='alert alert-danger strong'><i class='entypo icancel'></i>".sprintf($locale['setup_1407'], $system_apps[$_apps])."</div>";
					}
				}
			}

			// Do uninstallation
			if (isset($_POST['uninstall'])) {
				$_apps = stripinput($_POST['uninstall']);
				if (file_exists('includes/'.$_apps.'_setup.php')) {
					include 'includes/'.$_apps.'_setup.php';
					$message = "<div class='alert alert-warning'><i class='entypo check'></i>".sprintf($locale['setup_1408'], $system_apps[$_apps])."</div>";
					if ($fail) {
						$message = "<div class='alert alert-danger'><i class='entypo icancel'></i>".sprintf($locale['setup_1409'], $system_apps[$_apps])."</div>";
					}
				}
			}
			foreach ($system_apps as $_apps_key => $_apps) {
				if (file_exists('includes/'.$_apps_key.'_setup.php')) {
					$installed = db_exists($db_prefix.$_apps_key);
					$apps_data = array('title' => $locale[$_apps_key]['title'], 'description' => $locale[$_apps_key]['description'], 'key' => $_apps_key);
					if ($installed) {
						$apps['1'][] = $apps_data;
					} else {
						$apps['0'][] = $apps_data;
					}
				}
			}
			echo "<div class='m-b-20'><h4>".$locale['setup_1400']."</h4> ".$locale['setup_1401']."</div>\n";
			echo $message;
			if (!empty($apps[1])) {
				foreach ($apps[1] as $k => $v) {
					echo "<hr class='m-t-5 m-b-5'/>\n";
					echo "<div class='row'>\n";
					echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n".ucwords($v['title']);
					echo "<div class='pull-right'>\n";
					echo form_button($locale['setup_1405'], 'uninstall', 'uninstall', $v['key'], array('class' => 'btn-xs btn-default',
						'icon' => 'entypo trash'));
					echo "</div>\n";
					echo "</div>\n<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>".$v['description']."";
					echo "</div>\n</div>\n";
				}
			}
			if (!empty($apps[0])) {
				foreach ($apps[0] as $k => $v) {
					echo "<hr class='m-t-5 m-b-5'/>\n";
					echo "<div class='row'>\n";
					echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n".ucwords($v['title']);
					echo "<div class='pull-right'>\n";
					echo form_button($locale['setup_1404'], 'install', 'install', $v['key'], array('class' => 'btn-xs btn-default', 'icon' => 'entypo publish'));
					echo "</div>\n";
					echo "</div>\n<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>".$v['description']."";
					echo "</div>\n</div>\n";
				}
			}
		} elseif (isset($_POST['done'])) {
			// system ready
			echo "<div class='m-b-20'><h4>".$locale['setup_1402']."</h4> ".$locale['setup_1403']."</div>\n";
		}

		if (isset($_POST['done'])) {
			echo "<div class='m-t-10'>\n";
			echo "<input type='hidden' name='step' value='6' />\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			renderButton();
			echo "</div>\n";
		} else {
			echo "<div class='m-t-10'>\n";
			echo "<input type='hidden' name='step' value='5' />\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			renderButton(2);
			echo "</div>\n";
		}
	break;
	// Step 6 - Primary Admin Details
	case 6:
		$iOWNER = 0;
		$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
		$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
		$error_pass = (isset($_POST['error_pass']) && isnum($_POST['error_pass']) ? $_POST['error_pass'] : "0");
		$error_name = (isset($_POST['error_name']) && isnum($_POST['error_name']) ? $_POST['error_name'] : "0");
		$error_mail = (isset($_POST['error_mail']) && isnum($_POST['error_mail']) ? $_POST['error_mail'] : "0");
		$field_class = array("", "", "", "", "", "");
		if ($error_pass == "1" || $error_name == "1" || $error_mail == "1") {
			$field_class = array("", " tbl-error", " tbl-error", " tbl-error", " tbl-error", "");
			if ($error_name == 1) {
				$field_class[0] = " tbl-error";
			}
			if ($error_mail == 1) {
				$field_class[5] = " tbl-error";
			}
		}
		// to scan whether User Acccount exists.
		if (file_exists(BASEDIR.'config.php') || file_exists(BASEDIR.'config_temp.php')) {
			$connection_info = dbconnect($db_host, $db_user, $db_pass, $db_name, FALSE);
			$db_connect = $connection_info['connection_success'];
			$db_select = $connection_info['dbselection_success'];
			$settings = array();
			$result = dbquery("SELECT * FROM ".$db_prefix."settings");
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					$settings[$data['settings_name']] = $data['settings_value'];
				}
			}

			$iOWNER = dbcount("('user_id')", $db_prefix."users", "user_id='1'");

		} else {
			redirect(FUSION_SELF);
		}


		if ($iOWNER) {
			echo "<div class='m-b-20'><h4>".$locale['setup_1502']."</h4> ".$locale['setup_1503']."</div>\n";
			echo "<input type='hidden' name='transfer' value='1'>\n";
			// load authentication during post.
			// in development.
		} else {
			echo "<div class='m-b-20'><h4>".$locale['setup_1500']."</h4> ".$locale['setup_1501']."</div>\n";
		}

		echo "<table class='table table-responsive'>\n<tr>\n";
		echo "<td class='tbl1'>".$locale['setup_1504']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='text' name='username' value='".$username."' maxlength='30' class='form-control input-sm textbox".$field_class[0]."' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1509']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='text' name='email' value='".$email."' maxlength='100' class='form-control input-sm textbox' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1505']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='password' name='password1' maxlength='64' class='form-control input-sm textbox".$field_class[1]."' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1506']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='password' name='password2' maxlength='64' class='form-control input-sm textbox".$field_class[2]."' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1507']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password1' maxlength='64' class='form-control input-sm textbox".$field_class[3]."' style='width:200px' /></td></tr>\n";
		echo "<tr>\n<td class='tbl1'>".$locale['setup_1508']."</td>\n";
		echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password2' maxlength='64' class='form-control input-sm textbox".$field_class[4]."' style='width:200px' /></td></tr>\n";
		echo "</table>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='enabled_languages' value='".$settings['enabled_languages']."' />\n";
		echo "<input type='hidden' name='step' value='7' />\n";
		renderButton();
	break;
	// Step 7 - Final Settings
	case 7:
		if (file_exists(BASEDIR.'config_temp.php')) {
			$connection_info = dbconnect($db_host, $db_user, $db_pass, $db_name, FALSE);
			$db_connect = $connection_info['connection_success'];
			$db_select = $connection_info['dbselection_success'];
			$settings = array();
			$result = dbquery("SELECT * FROM ".$db_prefix."settings");
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					$settings[$data['settings_name']] = $data['settings_value'];
				}
			}
		} else {
			redirect(FUSION_SELF);
		}
		$error = "";
		$error_pass = "0";
		$error_name = "0";
		$error_mail = "0";
		$settings['password_algorithm'] = "sha256";
		$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
		if ($username == "") {
			$error .= $locale['setup_5011']."<br /><br />\n";
			$error_name = "1";
		} elseif (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
			$error .= $locale['setup_5010']."<br /><br />\n";
			$error_name = "1";
		}
		require_once INCLUDES."/classes/PasswordAuth.class.php";
		$userPassword = "";
		$adminPassword = "";
		$userPass = new PasswordAuth();
		$userPass->inputNewPassword = (isset($_POST['password1']) ? stripinput(trim($_POST['password1'])) : "");
		$userPass->inputNewPassword2 = (isset($_POST['password2']) ? stripinput(trim($_POST['password2'])) : "");
		$returnValue = $userPass->isValidNewPassword();
		if ($returnValue == 0) {
			$userPassword = $userPass->getNewHash();
			$userSalt = $userPass->getNewSalt();
		} elseif ($returnValue == 2) {
			$error .= $locale['setup_5012']."<br /><br />\n";
			$error_pass = "1";
		} elseif ($returnValue == 3) {
			$error .= $locale['setup_5013']."<br /><br />\n";
		}
		$adminPass = new PasswordAuth();
		$adminPass->inputNewPassword = (isset($_POST['admin_password1']) ? stripinput(trim($_POST['admin_password1'])) : "");
		$adminPass->inputNewPassword2 = (isset($_POST['admin_password2']) ? stripinput(trim($_POST['admin_password2'])) : "");
		$returnValue = $adminPass->isValidNewPassword();
		if ($returnValue == 0) {
			$adminPassword = $adminPass->getNewHash();
			$adminSalt = $adminPass->getNewSalt();
		} elseif ($returnValue == 2) {
			$error .= $locale['setup_5015']."<br /><br />\n";
			$error_pass = "1";
		} elseif ($returnValue == 3) {
			$error .= $locale['setup_5017']."<br /><br />\n";
		}
		if ($userPass->inputNewPassword == $adminPass->inputNewPassword) {
			$error .= $locale['setup_5016']."<br /><br />\n";
			$error_pass = "1";
		}
		$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
		if ($email == "") {
			$error .= $locale['setup_5020']."<br /><br />\n";
			$error_mail = "1";
		} elseif (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
			$error .= $locale['setup_5019']."<br /><br />\n";
			$error_mail = "1";
		}
		$rows = dbrows(dbquery("SELECT user_id FROM ".$db_prefix."users"));
		if ($error == "") {
			if ($rows == 0) {
				// Create Super Admin with Full Modular Rights - We don't need to update Super Admin later.
				if (isset($_POST['transfer']) && $_POST['transfer'] == 1) {
					$result = dbquery("UPDATE ".$db_prefix."users user_name='".$username."', user_salt='".$userSalt."', user_password='".$userPassword."', user_admin_salt='".$adminSalt."', user_admin_password='".$adminPassword."'
					user_email='".$email."', user_theme='Default', user_timezone='Europe/London' WHERE user_id='1'");
				} else {
					$result = dbquery("INSERT INTO ".$db_prefix."users (
					user_name, user_algo, user_salt, user_password, user_admin_algo, user_admin_salt, user_admin_password, user_email, user_hide_email, user_timezone,
					user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights,
					user_groups, user_level, user_status, user_theme, user_location, user_birthdate, user_aim,
					user_icq, user_yahoo, user_web, user_sig
					) VALUES (
					'".$username."', 'sha256', '".$userSalt."', '".$userPassword."', 'sha256', '".$adminSalt."', '".$adminPassword."',
					'".$email."', '1', 'Europe/London', '',  '0', '0', '".time()."', '0', '0.0.0.0',
					'A.AC.AD.APWR.B.BB.BLOG.BLC.C.CP.DB.DC.D.ERRO.FQ.F.FR.IM.I.IP.M.MAIL.N.NC.P.PH.PI.PL.PO.ROB.SL.S1.S2.S3.S4.S5.S6.S7.S8.S9.S10.S11.S12.S13.SB.SM.SU.UF.UFC.UG.UL.U.TS.W.WC.MAIL.LANG.ESHP',
					'', '103', '0', 'Default', '', '0000-00-00', '', '',  '', '', ''
					)");
				}
			}
			echo "<div class='m-b-20'><h4>".$locale['setup_1600']."</h4> ".$locale['setup_1601']."</div>\n";
			echo "<div class='m-b-10'>".$locale['setup_1602']."</div>\n";
			echo "<div class='m-b-10'>".$locale['setup_1603']."</div>\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			echo "<input type='hidden' name='step' value='8' />\n";
			renderButton(1);

		} elseif ($rows == 0) {
			echo "<br />\n".$locale['setup_5021']."<br /><br />\n".$error;
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			echo "<input type='hidden' name='error_pass' value='".$error_pass."' />\n";
			echo "<input type='hidden' name='error_name' value='".$error_name."' />\n";
			echo "<input type='hidden' name='error_mail' value='".$error_mail."' />\n";
			echo "<input type='hidden' name='username' value='".$username."' />\n";
			echo "<input type='hidden' name='email' value='".$email."' />\n";
			echo "<input type='hidden' name='step' value='6' />\n";
			echo "<button type='submit' name='back' value=".$locale['setup_0122']."' class='btn btn-md btn-warning'><i class='entypo cw'></i> ".$locale['setup_0122']."</button>\n";
		} else {
			echo "<div class='m-b-20'><h4>".$locale['setup_1600']."</h4> ".$locale['setup_1601']."</div>\n";
			echo "<div class='m-b-10'>".$locale['setup_1602']."</div>\n";
			echo "<div class='m-b-10'>".$locale['setup_1603']."</div>\n";
			echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
			echo "<input type='hidden' name='step' value='8' />\n";
			renderButton(1);
		}
	break;
}

// Step 8 - ?
closesetup();

?>