<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: submit.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";
include_once INCLUDES."bbcode_include.php";
include LOCALE.LOCALESET."submit.php";
if (!iMEMBER) {
	redirect("index.php");
}
$stype = filter_input(INPUT_GET, 'stype') ? : '';
$submit_info = array();
$modules = array(
	'n' => db_exists(DB_NEWS),
	'p' => db_exists(DB_PHOTO_ALBUMS),
	'a' => db_exists(DB_ARTICLES),
	'd' => db_exists(DB_DOWNLOADS),
	'l' => db_exists(DB_WEBLINKS),
	'b' => db_exists(DB_BLOG));
$sum = array_sum($modules);
if (!$sum or empty($modules[$stype])) {
	redirect("index.php");

} elseif ($stype === "l") {
	if (isset($_POST['submit_link'])) {
		$submit_info['link_category'] = form_sanitizer($_POST['link_category'], '', 'link_category');
		$submit_info['link_name'] = form_sanitizer($_POST['link_name'], '', 'link_name');
		$submit_info['link_url'] = form_sanitizer($_POST['link_url'], '', 'link_url');
		$submit_info['link_description'] = form_sanitizer($_POST['link_description'], '', 'link_description');
		if (!defined("FUSION_NULL")) {
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('l', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			add_to_title($locale['global_200'].$locale['400']);
			opentable($locale['400']);
			echo "<div style='text-align:center'><br />\n".$locale['410']."<br /><br />\n";
			echo "<a href='submit.php?stype=l'>".$locale['411']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
			closetable();
		}
	}
	add_to_title($locale['global_200'].$locale['400']);
	opentable($locale['400']);
	$result = dbquery("SELECT weblink_cat_id, weblink_cat_name FROM ".DB_WEBLINK_CATS." ".(multilang_table("WL") ? "WHERE weblink_cat_language='".LANGUAGE."'" : "")." ORDER BY weblink_cat_name");
	if (dbrows($result) > 0) {
		$opts = array();
		while ($data = dbarray($result)) {
			$opts[$data['weblink_cat_id']] = $data['weblink_cat_name'];
		}
		echo "<div class='panel panel-default tbl-border'>\n<div class='panel-body'>\n";
		echo "<div class='alert alert-info m-b-20 submission-guidelines'>".$locale['420']."</div>\n";
		echo openform('submit_form', 'post', ($settings['site_seo'] ? FUSION_ROOT : '').BASEDIR."submit.php?stype=l", array('max_tokens' => 1));
		echo form_select('link_category', $locale['421'], isset($_POST['link_category']) ? $_POST['link_category'] : '', array("options" => $opts,
			"required" => TRUE));
		echo form_text('link_name', $locale['422'], isset($_POST['link_name']) ? $_POST['link_name'] : '', array("required" => TRUE));
		echo form_text('link_url', $locale['423'], isset($_POST['link_url']) ? $_POST['link_url'] : '', array("required" => TRUE,
			'placeholder' => 'http://'));
		echo form_text('link_description', $locale['424'], isset($_POST['link_description']) ? $_POST['link_description'] : '', array("required" => TRUE,
			'max_length' => '200'));
		echo form_button('submit_link', $locale['425'], $locale['425'], array('class' => 'btn-primary'));
		echo closeform();
		echo "</div>\n</div>\n";
	} else {
		echo "<div class='well' style='text-align:center'><br />\n".$locale['551']."<br /><br />\n</div>\n";
	}
	closetable();
} elseif ($stype === "n") {
	include INFUSIONS."news/news_submit.php";
} elseif ($stype === "b") {
	include INFUSIONS."blog/blog_submit.php";
} elseif ($stype === "a") {
	include INFUSIONS."articles/article_submit.php";

} elseif ($stype === "p") {
	if (isset($_POST['submit_photo'])) {
		require_once INCLUDES."photo_functions_include.php";
		$error = "";
		$submit_info['photo_title'] = form_sanitizer($_POST['photo_title'], '', 'photo_title');
		$submit_info['photo_description'] = form_sanitizer($_POST['photo_description'], '', 'photo_description');
		$submit_info['album_id'] = isnum($_POST['album_id']) ? $_POST['album_id'] : "0";
		$submit_info['album_photo_file'] = form_sanitizer($_FILES['album_photo_file'], '', 'album_photo_file');
		add_to_title($locale['global_200'].$locale['570']);
		opentable($locale['570']);
		if (!defined('FUSION_NULL')) {
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('p', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			echo "<div style='text-align:center'><br />\n".$locale['580']."<br /><br />\n";
			echo "<a href='submit.php?stype=p'>".$locale['581']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['600']."<br /><br />\n";
			echo "<br /><br />\n<a href='submit.php?stype=p'>".$locale['581']."</a><br /><br />\n</div>\n";
		}
		closetable();
	}
	$opts = "";
	add_to_title($locale['global_200'].$locale['570']);
	opentable($locale['570']);
	$result = dbquery("SELECT album_id, album_title FROM ".DB_PHOTO_ALBUMS." ".(multilang_table("PG") ? "WHERE album_language='".LANGUAGE."' AND" : "WHERE")." ".groupaccess("album_access")." ORDER BY album_title");
	if (dbrows($result)) {
		$opts = array();
		while ($data = dbarray($result)) {
			$opts[$data['album_id']] = $data['album_title'];
		}
		echo openform('submit_form', 'post', ($settings['site_seo'] ? FUSION_ROOT : '').BASEDIR."submit.php?stype=p", array('enc_type' => 1,
			'max_tokens' => 1));
		echo "<div class='panel panel-default tbl-border'>\n<div class='panel-body'>\n";
		echo "<div class='alert alert-info m-b-20 submission-guidelines'>".$locale['620']."</div>\n";
		echo form_select('album_id', $locale['625'], '', array("options" => $opts));
		echo form_text('photo_title', $locale['621'], '', array('required' => 1));
		echo form_textarea('photo_description', $locale['622'], '');
		echo sprintf($locale['624'], parsebytesize($settings['photo_max_b']), $settings['photo_max_w'], $settings['photo_max_h'])."<br/>\n";
		echo form_fileinput('photo_pic_file', $locale['623'], '', array("upload_path" => PHOTOS."submissions/",
			"type" => "image",
			"required" => TRUE));
		echo "</div>\n</div>\n";
		echo form_button('submit_photo', $locale['626'], $locale['626'], array('class' => 'btn-primary'));
		echo closeform();
	} else {
		echo "<div class='well' style='text-align:center'><br />\n".$locale['552']."<br /><br />\n</div>\n";
	}
	closetable();
} elseif ($stype === "d") {
	add_to_title($locale['global_200'].$locale['650']);
	if (isset($_POST['submit_download'])) {
		$error = 0;
		$submit_info = array('download_title' => form_sanitizer($_POST['download_title'], '', 'download_title'),
			'download_description' => form_sanitizer($_POST['download_description'], '', 'download_description'),
			'download_description_short' => form_sanitizer($_POST['download_description_short'], '', 'download_description_short'),
			'download_cat' => form_sanitizer($_POST['download_cat'], '0', 'download_cat'),
			'download_homepage' => form_sanitizer($_POST['download_homepage'], '', 'download_homepage'),
			'download_license' => form_sanitizer($_POST['download_license'], '', 'download_license'),
			'download_copyright' => form_sanitizer($_POST['download_copyright'], '', 'download_copyright'),
			'download_os' => form_sanitizer($_POST['download_os'], '', 'download_os'),
			'download_version' => form_sanitizer($_POST['download_version'], '', 'download_version'),
			'download_file' => '',
			'download_url' => '',);
		/**
		 * Download File Section
		 */
		if (isset($_FILES['download_file'])) {
			$upload = form_sanitizer($_FILES['download_file'], '', 'download_file');
			if ($upload) {
				$submit_info['download_file'] = $upload['target_file'];
				$submit_info['download_filesize'] = parsebytesize($_FILES['download_file']['size']);
			}
			unset($upload);
		} elseif (isset($_POST['download_url']) && $_POST['download_url'] != "") {
			$submit_info['download_url'] = form_sanitizer($_POST['download_url'], '', 'download_url');
		}
		if (isset($_FILES['download_image'])) {
			$upload = form_sanitizer($_FILES['download_image'], '', 'download_image');
			if ($upload) {
				$submit_info['download_image'] = $upload['image_name'];
				$submit_info['download_image_thumb'] = $upload['thumb1_name'];
				unset($upload);
			}
		}
		// Break form and return errors
		if (!$submit_info['download_file'] && !$submit_info['download_url']) {
			$defender->stop();
			$defender->addNotice($locale['675']);
		}
		if (!defined("FUSION_NULL")) {
			opentable($locale['650']);
			// this is what goes into DB_SUBMISSIONS
			$data = array('submit_type' => 'd',
				'submit_user' => $userdata['user_id'],
				'submit_datestamp' => time(),
				'submit_criteria' => serialize($submit_info),);
			$result = dbquery_insert(DB_SUBMISSIONS, $data, 'save');
			if ($result) {
				echo "<div class='well'>\n";
				echo "<p>".$locale['660']."</p>";
				echo "<a href='submit.php?stype=d'>".$locale['661']."</a><br />";
				echo "<a href='index.php'>".$locale['412']."</a>\n<br/>";
				echo "<a href='submit.php?stype=d'>".$locale['661']."</a>\n";
				echo "</div>\n";
			}
			closetable();
		}
	}
	add_to_title($locale['global_200'].$locale['650']);
	opentable($locale['650']);
	$result = dbquery("SELECT download_cat_id, download_cat_name FROM ".DB_DOWNLOAD_CATS." ".(multilang_table("DL") ? "WHERE download_cat_language='".LANGUAGE."'" : "")." ORDER BY download_cat_name");
	if (dbrows($result)) {
		$opts = array();
		while ($data = dbarray($result)) {
			$opts[$data['download_cat_id']] = $data['download_cat_name'];
		}
		echo openform('submit_form', 'post', ($settings['site_seo'] ? FUSION_ROOT : '').BASEDIR."submit.php?stype=d", array('enctype' => 1,
			'max_tokens' => 1));
		echo "<div class='panel panel-default tbl-border'>\n<div class='panel-body'>\n";
		echo "<div class='alert alert-info m-b-20 submission-guidelines'>".$locale['680']."</div>\n";
		echo form_text('download_title', $locale['681'], '', array('required' => 1, 'error_text' => $locale['674']));
		echo form_textarea('download_description_short', $locale['682b'], '', array('bbcode' => 1,
			'required' => 1,
			'error_text' => $locale['676'],
			'form_name' => 'submit_form'));
		echo form_textarea('download_description', $locale['682'], '', array('bbcode' => 1,
			'form_name' => 'submit_form'));
		echo form_text('download_url', $locale['683'], '', array('error_text' => $locale['675']));
		echo "<div class='pull-right'>\n<small>\n";
		echo sprintf($locale['694'], parsebytesize($settings['download_max_b']), str_replace(',', ' ', $settings['download_types']))."<br />\n";
		echo "</small>\n</div>\n";
		$file_options = array("upload_path" => DOWNLOADS."submissions/",
			"max_bytes" => fusion_get_settings("download_max_b"),
			'valid_ext' => fusion_get_settings("download_types"),
			'error_text' => $locale['675'],);
		echo form_fileinput('download_file', $locale['684'], '', $file_options);
		echo "<div class='pull-right'>\n<small>\n";
		echo sprintf($locale['694b'], parsebytesize($settings['download_screen_max_b']), str_replace(',', ' ', ".jpg,.gif,.png"), $settings['download_screen_max_w'], $settings['download_screen_max_h'])."<br />\n";
		echo "</small>\n</div>\n";
		$file_options = array("upload_path" => DOWNLOADS."submissions/images/",
			"max_width" => fusion_get_settings("download_screen_max_w"),
			"max_height" => fusion_get_settings("download_screen_max_w"),
			"max_byte" => fusion_get_settings("download_screen_max_b"),
			"type" => "image",
			"delete_original" => FALSE,
			"thumbnail_folder" => "",
			"thumbnail" => TRUE,
			"thumbnail_suffix" => "_thumb",
			"thumbnail_w" => fusion_get_settings("download_thumb_max_w"),
			"thumbnail_h" => fusion_get_settings("download_thumb_max_h"),
			"thumbnail2" => 0);
		echo form_fileinput('download_image', $locale['686'], '', $file_options);
		echo form_select('download_cat', $locale['687'], '', array("options" => $opts));
		echo form_text('download_license', $locale['688'], '');
		echo form_text('download_os', $locale['689'], '');
		echo form_text('download_version', $locale['690'], '');
		echo form_text('download_homepage', $locale['691'], '');
		echo form_text('download_copyright', $locale['692'], '');
		echo form_hidden('calc_upload', '', '1');
		echo "</div>\n</div>\n";
		echo form_button('submit_download', $locale['695'], $locale['695'], array('class' => 'btn-primary'));
		echo closeform();
	} else {
		echo "<div class='well' style='text-align:center'><br />\n".$locale['551']."<br /><br />\n</div>\n";
	}
	closetable();
}
require_once THEMES."templates/footer.php";
