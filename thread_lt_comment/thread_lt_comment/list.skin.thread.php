<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if ($row['wr_is_comment']) {
    $list[$i] = $row;
}

$update_href = $delete_href = '';
if (($member['mb_id'] && ($member['mb_id'] === $list[$i]['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    set_session('ss_delete_token', $token = uniqid(time()));
    $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
    if ($list[$i]['wr_is_comment']) $update_href .= '&amp;is_comment=y';
    if ($is_view) $update_href .= '&amp;is_view=1';
} else if (!$list[$i]['mb_id']) {
    $update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    if ($list[$i]['wr_is_comment']) $update_href .= '&amp;is_comment=y';
    if ($is_view) $update_href .= '&amp;is_view=1';
}
if (!$list[$i]['file']) {
    $list[$i]['file'] = get_file($board['bo_table'], $list[$i]['wr_id']);
}
?>

<div class="thread_admin">
<? if (!$list[$i]['wr_is_comment']) { ?>
    <a href="javascript:void(0)" class="view_link" data-url="<?=$list[$i]['href'];?>" title="주소 복사">링크</a>
    <input type="text" id="view_copy" style="position:absolute;top:-9999em;">    
<? } ?>
<? if ($write_href) { ?>
    <? if ($update_href) { ?><a href="<? echo $update_href ?>">수정</a><? } ?>
    <? if ($delete_href) { ?><a href="<? echo $delete_href ?>" onclick="del(this.href); return false;">삭제</a><? } ?>
<? } ?>
</div>

<? if ($list[$i]['is_notice']) { ?>
    <span class="thread_notice" style="background:#000; color:#fff; padding:2px 5px; font-size:11px; margin-bottom:5px; display:inline-block;">공지</span>
<? } ?>

<div class="thread_info">
    <span class="name">
        <?=$list[$i]['wr_name']; ?>
        <? if ($list[$i]['wr_protect'] || $list[$i]['wr_secret']) { ?><i class="fa-solid fa-lock"></i><? } ?>
    </span>
    <span class="date">
        <?=date('Y.m.d H:i', strtotime($list[$i]['wr_datetime'])) ?>
    </span>
</div>

<? if($list[$i]['wr_subject']) { ?>                
    <div class="thread_title"><strong><?=$list[$i]['wr_subject']; ?></strong></div>
<? } ?>

<div id="thread<?=$list[$i]['wr_id']; ?>" class="thread_wr">
<? if ($list[$i]['wr_protect'] && !$is_admin) { ?>
    <div class="thread_main">
        보호글입니다.
        <div class="pass-form">
            <form name="fboardpassword" onsubmit="return showThreadContent(this);" method="post">
            <input type="hidden" name="w" value="p">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $list[$i]['wr_id'] ?>">
            <fieldset class="box-pw" style="margin-top:5px;">
                <input type="password" name="wr_password" id="password_wr_password" required class="frm_input required" size="15" maxlength="20" placeholder="비밀번호">
                <input type="submit" value="확인" class="btn_submit ui-btn">
            </fieldset>
            </form>
        </div>
    </div>
<? } elseif ($list[$i]['wr_secret'] && !$is_member) { ?>
    <div class="thread_main">멤버 공개 게시글입니다.</div>
<? } elseif (($member['mb_level'] < $board['bo_read_level'])) { ?>
    <div class="thread_main">글읽기 권한이 없습니다.</div>
<? } else { ?>
    <? include($board_skin_path.'/list.skin.con.php'); ?>
<? } ?>
</div>