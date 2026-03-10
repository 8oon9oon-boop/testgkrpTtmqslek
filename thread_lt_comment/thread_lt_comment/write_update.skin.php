<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
$temp=sql_fetch("select * from {$write_table}");
if(!isset($temp['wr_protect'])){
	sql_query(" ALTER TABLE `{$write_table}` ADD `wr_protect` varchar(255) NOT NULL DEFAULT '' AFTER `wr_url` ");
	} 
unset($temp);

$add_sql = '';

if ($wr_subject2) {
    $add_sql .= ", wr_subject = '{$wr_subject2}'";
} else {
    $add_sql .= ", wr_subject = ''";
}

if ($wr_is_comment && $w !='u') {
    $co_count = sql_fetch("SELECT wr_comment FROM {$write_table} WHERE wr_parent = '{$pa_wr_id}' and wr_is_comment = '1' ORDER BY wr_comment DESC");
    $co_cnt = $co_count['wr_comment'] + 1;
    $add_sql .= ", wr_num = '{$pa_wr_num}'";
    $add_sql .= ", wr_parent = '{$pa_wr_id}'";
    $add_sql .= ", wr_is_comment = '1'";
    $add_sql .= ", wr_comment = '{$co_cnt}'";
}

if($w!='c' && $w!='cu'){

$temp_wr_id = $wr_id;

$sec=""; 
$mem=0;
$protect="";
if($set_secret) {

	if($set_secret=='secret'){
		$sec="secret";
	} 
	else if ($set_secret=='member'){
		$mem=1;
	}
	else if($set_secret == 'protect' && $wr_protect!=''){
		$protect=$wr_protect;
	}
}
	sql_query("update {$write_table} set wr_option='$html,$sec', wr_secret='{$mem}', wr_protect= '{$wr_protect}' {$add_sql} where wr_id='{$wr_id}'");
}

// 게시글 카운트 조정
if ($wr_is_comment && $w != 'u') {
    sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write - 1, bo_count_comment = bo_count_comment + 1 where bo_table = '{$bo_table}'");
}

$new_wr_id = $pa_wr_id ? $pa_wr_id : $wr_id;

// 최신글 조정
sql_query("UPDATE {$g5['board_new_table']} SET wr_parent = '{$new_wr_id}' WHERE wr_id = '{$wr_id}' AND bo_table = '{$bo_table}'");

$go_wr_id = $pa_wr_id ? $pa_wr_id : $_REQUEST['wr_id'];

if ($_REQUEST['is_view']) {
    goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$go_wr_id.'&amp;bottom=true'.$qstr);
} else {
    goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.$qstr);
}

?>
