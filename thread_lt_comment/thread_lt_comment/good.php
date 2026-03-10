<?php
include_once('./_common.php');

if (!$mb_id)
{
    echo '로그인 후 가능합니다.';
    return false;
}

if (!($bo_table && $wr_id)) {
    echo '값이 제대로 넘어오지 않았습니다.';
    return false;
}

$row = sql_fetch(" select count(*) as cnt from {$g5['write_prefix']}{$bo_table} ", FALSE);
if (!$row['cnt']) {
    echo '존재하는 게시판이 아닙니다.';
    return false;
}

if($write['mb_id'] == $member['mb_id']) {
    echo '자신의 글에는 좋아요 하실 수 없습니다.';
    return false;
}

$sql = " select bg_flag from {$g5['board_good_table']}
            where bo_table = '{$bo_table}'
            and wr_id = '{$wr_id}'
            and mb_id = '{$mb_id}'
            and bg_flag = 'good' ";
$row = sql_fetch($sql);
if ($row['bg_flag'])
{ echo "이미 좋아요 하신 글 입니다."; return false;}
else
{
    // 좋아요(찬성), 비좋아요(반대) 카운트 증가
    sql_query(" update {$g5['write_prefix']}{$bo_table} set wr_good = wr_good + 1 where wr_id = '{$wr_id}' ");
    // 내역 생성
    sql_query(" insert {$g5['board_good_table']} set bo_table = '{$bo_table}', wr_id = '{$wr_id}', mb_id = '{$mb_id}', bg_flag = 'good', bg_datetime = '".G5_TIME_YMDHIS."' ");

    echo "y";
}

?>