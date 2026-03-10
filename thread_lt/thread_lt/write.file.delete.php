<?php
include_once('./_common.php');

$row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$idx}' ");

@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
// 썸네일삭제
if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
    delete_board_thumbnail($bo_table, $row['bf_file']);
}

sql_query(" delete from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$idx}' ");

$idx1 = $idx+1;
$idx2 = $idx+2;
$idx3 = $idx+3;

sql_query(" update {$g5['board_file_table']} set bf_no = '{$idx}' where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$idx1}' ");
sql_query(" update {$g5['board_file_table']} set bf_no = '{$idx1}' where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$idx2}' ");
sql_query(" update {$g5['board_file_table']} set bf_no = '{$idx2}' where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$idx3}' ");
?>