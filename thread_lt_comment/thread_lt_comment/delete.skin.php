<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


// 원글 삭제 시 코멘트에 첨부된 파일 함께 삭제
$sql_co = " select * from {$write_table} where wr_parent = '{$write['wr_id']}' ";
$result_co = sql_query($sql_co);

$wr_count = sql_fetch(" select count(*) as cnt from {$write_table} where wr_id = '{$write['wr_id']}' and wr_parent = '{$write['wr_id']}'");
$_count_write = $wr_count['cnt'];
$co_count = sql_fetch(" select count(*) as cnt from {$write_table} where wr_id = '{$write['wr_id']}' and wr_parent != '{$write['wr_id']}'");
$_count_comment = $co_count['cnt'];

while ($row_co = sql_fetch_array($result_co)) {

    $sql_co2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row_co['wr_id']}' ";
    $result_co2 = sql_query($sql_co2);

    while ($row_co2 = sql_fetch_array($result_co2)) {
        @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.str_replace('../', '', $row_co2['bf_file']));
        // 썸네일삭제
        if(preg_match("/\.({$config['cf_image_extension']})$/i", $row_co2['bf_file'])) {
            delete_board_thumbnail($bo_table, $row_co2['bf_file']);
        }
    }

    // 파일테이블 행 삭제
    sql_query(" delete from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$row_co['wr_id']}' ");

    // 좋아요 기록 삭제
    sql_query(" delete from {$g5['board_good_table']} where bo_table = '{$bo_table}' and wr_id = '{$row_co['wr_id']}' ");
}


// 해당 글 파일만 삭제
$sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$write['wr_id']}' ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.str_replace('../', '', $row['bf_file']));
    // 썸네일삭제
    if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
        delete_board_thumbnail($bo_table, $row['bf_file']);
    }

}

sql_query(" delete from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$write['wr_id']}' ");
sql_query(" delete from {$g5['board_new_table']} where wr_id = '{$write['wr_id']}' ");
sql_query(" delete from {$g5['board_new_table']} where wr_parent = '{$write['wr_id']}' ");
sql_query(" delete from $write_table where wr_parent = '{$write['wr_id']}' AND wr_reply != ''");
sql_query(" delete from $write_table where wr_id = '{$write['wr_id']}' ");

if ($_count_write > 0 || $_count_comment > 0)
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '$_count_write', bo_count_comment = bo_count_comment - '$_count_comment' where bo_table = '$bo_table' ");

?>