<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: blog_submit.php
| Author: Frederick MC Chan (Hien)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$blog_settings = get_settings("blog");
include INFUSIONS."blog/locale/".LOCALESET."blog_admin.php";
opentable("<i class='fa fa-commenting-o fa-lg m-r-10'></i>".$locale['blog_0600']);
if (iMEMBER && $blog_settings['blog_allow_submission']) {
	$criteriaArray = array(
		"blog_subject" => "",
		"blog_cat" => 0,
		"blog_snippet" => "",
		"blog_body" => "",
		"blog_language" => LANGUAGE,
		"blog_keywords" => "",
		"blog_ialign" => "",
	);
	if (isset($_POST['submit_blog'])) {
		$blog_blog = "";
		if ($_POST['blog_blog']) {
			$blog_blog = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, parseubb(stripslashes($_POST['blog_blog'])));
			$blog_blog = html_entity_decode($blog_blog);
		}
		$blog_extended = "";
		if ($_POST['blog_body']) {
			$blog_extended = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, parseubb(stripslashes($_POST['blog_body'])));
			$blog_extended = html_entity_decode($blog_extended);
		}
		$criteriaArray = array(
			"blog_subject" => form_sanitizer($_POST['blog_subject'], "", "blog_subject"),
			"blog_cat" => form_sanitizer($_POST['blog_cat'], "", "blog_cat"),
			"blog_snippet" => form_sanitizer($blog_blog, "", "blog_blog"),
			"blog_body" => form_sanitizer($blog_extended, "", "blog_body"),
			"blog_language" => form_sanitizer($_POST['blog_language'], "", "blog_language"),
			"blog_keywords" => form_sanitizer($_POST['blog_keywords'], "", "blog_keywords"),
		);
		if ($blog_settings['blog_allow_submission_files']) {
			if (isset($_FILES['blog_image'])) {
				$upload = form_sanitizer($_FILES['blog_image'], '', 'blog_image');
				if (!empty($upload)) {
					$criteriaArray['blog_image'] = $upload['image_name'];
					$criteriaArray['blog_image_t1'] = $upload['thumb1_name'];
					$criteriaArray['blog_image_t2'] = $upload['thumb2_name'];
					$criteriaArray['blog_ialign'] = (isset($_POST['blog_ialign']) ? form_sanitizer($_POST['blog_ialign'], "pull-left", "blog_ialign") : "pull-left");
				} else {
					$criteriaArray['blog_image'] = (isset($_POST['blog_image']) ? $_POST['blog_image'] : "");
					$criteriaArray['blog_image_t1'] = (isset($_POST['blog_image_t1']) ? $_POST['blog_image_t1'] : "");
					$criteriaArray['blog_image_t2'] = (isset($_POST['blog_image_t2']) ? $_POST['blog_image_t2'] : "");
					$criteriaArray['blog_ialign'] = (isset($_POST['blog_ialign']) ? form_sanitizer($_POST['blog_ialign'], "pull-left", "blog_ialign") : "pull-left");
				}
			}
		}
		if (defender::safe()) {
			$inputArray = array(
				"submit_type" => "b",
				"submit_user" => $userdata['user_id'],
				"submit_datestamp" => time(),
				"submit_criteria" => addslashes(serialize($criteriaArray))
			);
			dbquery_insert(DB_SUBMISSIONS, $inputArray, "save");
			addNotice("success", $locale['blog_0701']);
			redirect(clean_request("submitted=b", array("stype"), TRUE));
		}
	}
	if (isset($_GET['submitted']) && $_GET['submitted'] == "b") {
		add_to_title($locale['global_200'].$locale['blog_0600']);
		echo "<div class='well text-center'><p><strong>".$locale['blog_0701']."</strong></p>";
		echo "<p><a href='submit.php?stype=b'>".$locale['blog_0702']."</a></p>";
		echo "<p><a href='index.php'>".$locale['blog_0704']."</a></p>\n";
		echo "</div>\n";
	} else {
		// Preview
		if (isset($_POST['preview_blog'])) {
			/* lost data after preview */
			$blog_blog = "";
			if ($_POST['blog_blog']) {
				$blog_blog = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, parseubb(stripslashes($_POST['blog_blog'])));
				$blog_blog = html_entity_decode($blog_blog);
			}
			$blog_body = "";
			if ($_POST['blog_body']) {
				$blog_body = str_replace("src='".str_replace("../", "", IMAGES_B), "src='".IMAGES_B, parseubb(stripslashes($_POST['blog_body'])));
				$blog_body = html_entity_decode($blog_body);
			}
			$criteriaArray = array(
				"blog_subject" => form_sanitizer($_POST['blog_subject'], "", "blog_subject"),
				"blog_cat" => form_sanitizer($_POST['blog_cat'], 0, "blog_cat"),
				"blog_keywords" => form_sanitizer($_POST['blog_keywords'], "", "blog_keywords"),
				"blog_snippet" => form_sanitizer($blog_blog, "", "blog_snippet"),
				"blog_body" => form_sanitizer($blog_body, "", "blog_body"),
				"blog_image" => isset($_POST['blog_image']) ? $_POST['blog_image'] : '',
				"blog_image_t1" => isset($_POST['blog_image_t1']) ? $_POST['blog_image_t1'] : "",
				"blog_image_t2" => isset($_POST['blog_image_t2']) ? $_POST['blog_image_t2'] : "",
				"blog_ialign" => (isset($_POST['blog_ialign']) ? $_POST['blog_ialign'] : "pull-left"),
				"blog_language" => form_sanitizer($_POST['blog_language'], "", "blog_language"),
			);
			if (defender::safe()) {
				opentable($locale['blog_0141']);
				echo "<h4>".$criteriaArray['blog_subject']."</h4>\n";
				echo "<p class='text-bigger'>".html_entity_decode(stripslashes($criteriaArray['blog_snippet']))."</p>\n";
				if (!empty($criteriaArray['blog_body'])) {
					echo html_entity_decode(stripslashes($criteriaArray['blog_body']));
				}
				closetable();
			}
		}
		add_to_title($locale['global_200'].$locale['blog_0600']);
		echo "<div class='panel panel-default tbl-border'>\n<div class='panel-body'>\n";
		echo "<div class='m-b-20 submission-guidelines'>".$locale['blog_0703']."</div>\n";
		echo openform('submit_form', 'post', (fusion_get_settings("site_seo") ? FUSION_ROOT : '').BASEDIR."submit.php?stype=b", array("enctype" => $blog_settings['blog_allow_submission_files'] ? TRUE : FALSE));
		echo form_text('blog_subject', $locale['blog_0422'], $criteriaArray['blog_subject'], array(
			"required" => TRUE,
			"inline" => TRUE
		));
		if (multilang_table("BL")) {
			echo form_select('blog_language', $locale['global_ML100'], $criteriaArray['blog_language'], array(
				"options" => fusion_get_enabled_languages(),
				"placeholder" => $locale['choose'],
				"width" => "250px",
				"inline" => TRUE,
			));
		} else {
			echo form_hidden('blog_language', '', $criteriaArray['blog_language']);
		}
		echo form_select('blog_keywords', $locale['blog_0443'], $criteriaArray['blog_keywords'], array(
			"max_length" => 320,
			"inline" => TRUE,
			"placeholder" => $locale['blog_0444'],
			"width" => "100%",
			"error_text" => $locale['blog_0457'],
			"tags" => TRUE,
			"multiple" => TRUE
		));
		echo form_select_tree("blog_cat", $locale['blog_0423'], $criteriaArray['blog_cat'], array(
			"width" => "250px",
			"inline" => TRUE,
			"parent_value" => $locale['blog_0424'],
			"query" => (multilang_table("BL") ? "WHERE blog_cat_language='".LANGUAGE."'" : "")
		), DB_BLOG_CATS, "blog_cat_name", "blog_cat_id", "blog_cat_parent");
		if ($blog_settings['blog_allow_submission_files']) {
			$file_input_options = array(
				'upload_path' => IMAGES_B,
				'max_width' => $blog_settings['blog_photo_max_w'],
				'max_height' => $blog_settings['blog_photo_max_h'],
				'max_byte' => $blog_settings['blog_photo_max_b'],
				// set thumbnail
				'thumbnail' => 1,
				'thumbnail_w' => $blog_settings['blog_thumb_w'],
				'thumbnail_h' => $blog_settings['blog_thumb_h'],
				'thumbnail_folder' => 'thumbs',
				'delete_original' => 0,
				// set thumbnail 2 settings
				'thumbnail2' => 1,
				'thumbnail2_w' => $blog_settings['blog_photo_w'],
				'thumbnail2_h' => $blog_settings['blog_photo_h'],
				'type' => 'image',
				"inline" => TRUE,
			);
			echo form_fileinput("blog_image", $locale['blog_0439'], "", $file_input_options);
			echo "<div class='small col-sm-offset-3 m-b-10'><span class='p-l-15'>".sprintf($locale['blog_0440'], parsebytesize($blog_settings['blog_photo_max_b']))."</span></div>\n";
			$alignOptions = array(
				'pull-left' => $locale['left'],
				'news-img-center' => $locale['center'],
				'pull-right' => $locale['right']
			);
			echo form_select('blog_ialign', $locale['blog_0442'], $criteriaArray['blog_ialign'], array(
				"options" => $alignOptions,
				"inline" => TRUE
			));
		}
		echo form_textarea('blog_blog', $locale['blog_0425'], $criteriaArray['blog_snippet'], array(
			"required" => TRUE,
			"html" => TRUE,
			"form_name" => "submit_form",
			"autosize" => fusion_get_settings("tinymce_enabled") ? FALSE : TRUE
		));
		echo form_textarea('blog_body', $locale['blog_0426'], $criteriaArray['blog_body'], array(
			"required" => $blog_settings['blog_extended_required'] ? TRUE : FALSE,
			"html" => TRUE,
			"form_name" => "submit_form",
			"autosize" => fusion_get_settings("tinymce_enabled") ? FALSE : TRUE
		));
		echo fusion_get_settings("site_seo") ? "" : form_button('preview_blog', $locale['blog_0436'], $locale['blog_0436'], array('class' => 'btn-primary m-r-10'));
		echo form_button('submit_blog', $locale['blog_0700'], $locale['blog_0700'], array('class' => 'btn-success'));
		echo closeform();
		echo "</div>\n</div>\n";
	}
} else {
	echo "<div class='well text-center'>".$locale['blog_0138']."</div>\n";
}
closetable();

/**
 * if (isset($_POST['submit_blog'])) {
 * if (!defined('FUSION_NULL')) {
 * $result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES('b', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
 * add_to_title($locale['global_200'].$locale['450b']);
 * opentable($locale['450b']);
 * echo "<div style='text-align:center'><br />\n".$locale['460b']."<br /><br />\n";
 * echo "<a href='submit.php?stype=b'>".$locale['461b']."</a><br /><br />\n";
 * echo "<a href='index.php'>".$locale['412b']."</a><br /><br />\n</div>\n";
 * closetable();
 * }
 * }
 * if (isset($_POST['preview_blog'])) {
 * $blog_subject = stripinput($_POST['blog_subject']);
 * $blog_cat = isnum($_POST['blog_cat']) ? $_POST['blog_cat'] : "0";
 * $blog_snippet = stripinput($_POST['blog_snippet']);
 * $blog_body = stripinput($_POST['blog_body']);
 * opentable($blog_subject);
 * echo $locale['478b']." ".nl2br(parseubb($blog_snippet))."<br /><br />";
 * echo $locale['472b']." ".nl2br(parseubb($blog_body));
 * closetable();
 * } else {
 * $blog_subject = "";
 * $blog_cat = "0";
 * $blog_snippet = "";
 * $blog_body = "";
 * }
 * $result2 = dbquery("SELECT blog_cat_id, blog_cat_name, blog_cat_language FROM ".DB_BLOG_CATS." ".(multilang_table("BL") ? "WHERE blog_cat_language='".LANGUAGE."'" : "")." ORDER BY blog_cat_name");
 * if (dbrows($result2)) {
 * $cat_list = array();
 * while ($data2 = dbarray($result2)) {
 * $cat_list[$data2['blog_cat_id']] = $data2['blog_cat_name'];
 * }
 * }
 * add_to_title($locale['global_200'].$locale['450b']);
 * opentable($locale['450b']);
 * echo "<div class='panel panel-default tbl-border'>\n<div class='panel-body'>\n";
 * echo "<div class='alert alert-info m-b-20 submission-guidelines'>".$locale['470b']."</div>\n";
 * echo openform('submit_form', 'post', (fusion_get_settings("site_seo") ? FUSION_ROOT : '').BASEDIR."submit.php?stype=b", array('max_tokens' => 1));
 * echo form_text('blog_subject', $locale['471b'], $blog_subject, array("required" => 1));
 * echo form_select('blog_cat', $locale['476b'], $blog_cat, array("options" => $cat_list, "required" => 1));
 * echo form_textarea('blog_snippet', $locale['478b'], $blog_snippet, array('bbcode' => 1,
 * 'form_name' => 'submit_form'));
 * echo form_textarea('blog_body', $locale['472b'], $blog_body, array("required" => 1,
 * 'bbcode' => 1,
 * 'form_name' => 'submit_form'));
 * echo fusion_get_settings("site_seo") ? "" : form_button('preview_blog', $locale['474b'], $locale['474b'], array('class' => 'btn-primary m-r-10'));
 * echo form_button('submit_blog', $locale['475b'], $locale['475b'], array('class' => 'btn-primary'));
 * echo closeform();
 * echo "</div>\n</div>\n";
 * closetable();
 */