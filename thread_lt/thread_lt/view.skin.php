<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once ($board_skin_path.'/setting/user.config.php');
include_once ($board_skin_path.'/color.config.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/txtggu/textggu.css">', 1);
$p_url="";
if ($view['wr_protect']!=''){
	if( get_session("ss_secret_{$bo_table}_{$view['wr_num']}") ||  $view['mb_id'] && $view['mb_id']==$member['mb_id'] || $is_admin )
		$is_viewer = true;
	else {
	$is_viewer = false; 
	$p_url="./password.php?w=p&amp;bo_table=".$bo_table."&amp;wr_id=".$view['wr_id'].$qstr;
	}
}else if($view['wr_secret'] == '1') {
	if($board['bo_read_level'] < $member['mb_level'] && $is_member)
		$is_viewer = true; 
	else {
	$is_viewer = false; 
	$p_url="./login.php";
	}
}
if(!$is_viewer && $p_url!=''){
	if($p_url=="./login.php") alert("멤버공개 글입니다. 로그인 후 이용해주세요.",$p_url);
	else goto_url($p_url);
}

$list[$i] = $view;
$is_view = true;
?>
<div class="board-skin-basic">
<div class="thread_wrap">
    <div class="thread-box theme-box<? if ($list[$i]['is_notice']) echo " bo_notice"; ?>">

        <? include ($board_skin_path.'/list.skin.thread.php'); ?>

        <!-- 타래 코멘트 리스트 -->
        <?
            $thread_co_cnt = sql_fetch("SELECT count(wr_id) AS cnt FROM {$write_table} WHERE wr_parent = '{$list[$i]['wr_id']}' AND wr_is_comment = '1'");
            $thread_co_sql = "SELECT * FROM {$write_table} WHERE wr_parent = '{$list[$i]['wr_id']}' AND wr_is_comment = '1' ORDER BY wr_comment ASC ";
            $thread_result = sql_query($thread_co_sql);
        ?>
        <? if ($thread_co_cnt['cnt'] > 0) { ?>
        <div class="thread_co_wrap">
            <? for ($k=0; $row=sql_fetch_array($thread_result); $k++) { ?>
                <div class="thread_co">
                <? include ($board_skin_path.'/list.skin.thread.php'); ?>
                </div>
            <? } ?>
        </div>
        <? } ?>

        <? if ($write_href) { ?>
        <div class="thread_bottom">
            <button type="button" class="txt-point" onclick="writeThreadComment(<?=$list[$i]['wr_parent'];?>,'<?=$list[$i]['ca_name'];?>')"><i class="fa-solid fa-comment"></i></button>
        </div>
        <div id="thread_comment<?=$list[$i]['wr_parent'];?>" class="thread_comment"></div>
        <? } ?>
    </div>
</div>
<!-- } 게시글 읽기 끝 -->
<!-- 링크 버튼 시작 { -->
<?
// 수정, 삭제 링크
$update_href = $delete_href = '';
// 로그인중이고 자신의 글이라면 또는 관리자라면 비밀번호를 묻지 않고 바로 수정, 삭제 가능
if (($member['mb_id'] && ($member['mb_id'] === $view['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$view['wr_id'].'&amp;page='.$page.$qstr;
    set_session('ss_delete_token', $token = uniqid(time()));
    $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$view['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
}
else if (!$view['mb_id']) { // 회원이 쓴 글이 아니라면
    $update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$view['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$view['wr_id'].'&amp;page='.$page.$qstr;
}
?>
<div id="bo_v_bot">
    <?
    ob_start();
        ?>
    <? if ($prev_href || $next_href) { ?>
    <div class="bo_v_nb">
        <? if ($prev_href) { ?><a href="<? echo $prev_href ?>" class="ui-btn">이전타래</a><? } ?>
        <? if ($next_href) { ?><a href="<? echo $next_href ?>" class="ui-btn">다음타래</a><? } ?>
    </div>
    <? } ?>

    <div class="bo_v_com">
        <? if ($update_href) { ?><a href="<? echo $update_href ?>" class="ui-btn">수정</a><? } ?>
        <? if ($delete_href) { ?><a href="<? echo $delete_href ?>" class="ui-btn admin" onclick="del(this.href); return false;">삭제</a><? } ?>
        <a href="<? echo $list_href ?>" class="ui-btn">목록</a>
    </div>
    <?
    $link_buttons = ob_get_contents();
    ob_end_flush();
        ?>
</div>
<!-- } 링크 버튼 끝 -->
</div>

<? if ($_REQUEST['bottom']) { ?>
<script>
$(function(e){
    $('html, body').scrollTop( $(document).height() );
});
</script>
<? } ?>
<? include_once($board_skin_path.'/thread.script.php'); // 타래 공통 스크립트 ?>
<? include_once($board_skin_path.'/write.script.php'); // 글 작성시 이미지 관련 ?>