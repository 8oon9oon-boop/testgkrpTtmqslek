<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// CSS 설정 가져오기
$css_sql = sql_query("select * from {$g5['css_table']}");
$css = array();
for($i=0; $cs = sql_fetch_array($css_sql); $i++) {
	$css[$cs['cs_name']][0] = $cs['cs_value'];
	$css[$cs['cs_name']][1] = $cs['cs_etc_1'];
	$css[$cs['cs_name']][2] = $cs['cs_etc_2'];
	$css[$cs['cs_name']][3] = $cs['cs_etc_3'];
	$css[$cs['cs_name']][4] = $cs['cs_etc_4'];
	$css[$cs['cs_name']][5] = $cs['cs_etc_5'];
	$css[$cs['cs_name']][6] = $cs['cs_etc_6'];
	$css[$cs['cs_name']][7] = $cs['cs_etc_7'];
	$css[$cs['cs_name']][8] = $cs['cs_etc_8'];
	$css[$cs['cs_name']][9] = $cs['cs_etc_9'];
	$css[$cs['cs_name']][10] = $cs['cs_etc_10'];
}

$_point_color = $_point_color ? $_point_color : $css['color_point'][0];

add_stylesheet('<style>:root{--lt-color-point:'.$_point_color.';--lt-color-default:'.$css['color_default'][0].';--lt-font-color:'.$css['box_style'][1].'}</style>', 0);

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<style>
<? if ($_box_background) { ?>
    .theme-box {background:<?=$_box_background;?>}
<? } ?>
<? if ($_box_line) { ?>
    .theme-box {border-color:<?=$_box_line;?>}
<? } ?>
<? if ($_box_font) { ?>
    .theme-box {color:<?=$_box_font;?>}
<? } ?>
</style>