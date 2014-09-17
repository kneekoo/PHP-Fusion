<?php
/*-------------------------------------------------------+
    | PHP-Fusion Content Management System
    | Copyright (C) PHP-Fusion
    | http://www.php-fusion.co.uk/
    +--------------------------------------------------------+
    | Project File: Form API - Hidden Input Based
    | Filename: form_hidden.php
    | Author: PHP-Fusion 8 Development Team
    | Coded by : Frederick MC Chan (Hien)
    | Version : 8.1.3 (please update every commit)
    +--------------------------------------------------------+
    | This program is released as free software under the
    | Affero GPL license. You can redistribute it and/or
    | modify it under the terms of this license which you
    | can read by viewing the included agpl.txt or online
    | at www.gnu.org/licenses/agpl.html. Removal of this
    | copyright header is strictly prohibited without
    | written permission from the original author(s).
    +--------------------------------------------------------*/
function form_hidden($title, $input_name, $input_id, $input_value, $array = FALSE) {
	$title2 = (isset($title) && (!empty($title))) ? stripinput($title) : ucfirst(strtolower(str_replace("_", " ", $input_name)));
	if (!$array) {
		$show_title = 0;
		$width = "style='width:250px'";
		$inline = 0;
		$required = 0;
		$class = '';
	} else {
		$show_title = (array_key_exists('title', $array) && $array['title'] == 1) ? 1 : 0;
		$width = (array_key_exists('width', $array) && $array['width']) ? "style='width: ".$array['width']."'" : "style='width:250px'";
		$inline = (array_key_exists("inline", $array)) ? 1 : 0;
		$class = (array_key_exists('class', $array)) ? $array['class'] : "";
		$required = (array_key_exists('required', $array) && ($array['required'] == 1)) ? '1' : '0';
	}
	// add this to transform hidden input into any JS plugin selector.
	// note: select2 can be appended to a hidden field to display json/ajax output.
	$html = '';
	if ($show_title) {
		$html .= "<div id='$input_id-field' class='form-group m-b-0 $class'>\n";
		$html .= ($title) ? "<label class='control-label ".($inline ? "col-xs-12 col-sm-3 col-md-3 col-lg-3" : '')."' for='$input_id'>$title ".($required == 1 ? "<span class='required'>*</span>" : '')."</label>\n" : '';
		$html .= ($inline) ? "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>\n" : "";
	}
	$html .= "<input type='hidden' name='$input_name' id='$input_id' value='$input_value' ".$width." ".($show_title ? "" : "readonly")." />\n";
	if ($show_title) {
		$html .= "<div id='$input_id-help' class='display-inline-block'></div>";
		$html .= ($inline) ? "</div>\n" : "";
		$html .= "</div>\n";
	}
	return $html;
}

?>