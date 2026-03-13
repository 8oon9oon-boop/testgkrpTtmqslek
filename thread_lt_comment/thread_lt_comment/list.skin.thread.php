<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if ($row['wr_is_comment']) {
    $list[$i] = $row;
}

// 수정, 삭제 링크
$update_href = $delete_href = '';
if (($member['mb_id'] && ($member['mb_id'] === $list[$i]['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    set_session('ss_delete_token', $token = uniqid(time()));
    $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
} else if (!$list[$i]['mb_id']) { 
    $update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
}
if (!$list[$i]['file']) {
    $list[$i]['file'] = get_file($board['bo_table'], $list[$i]['wr_id']);
}
?>

<div class="thread_admin">
<? if ($write_href) { ?>
    <? if ($update_href) { ?><a href="<? echo $update_href ?>">수정</a><? } ?>
    <? if ($delete_href) { ?><a href="<? echo $delete_href ?>" onclick="del(this.href); return false;">삭제</a><? } ?>
<? } ?>
</div>

<div class="item-header">
    <div class="profile-img">
        <img src="https://i.pinimg.com/736x/ea/17/33/ea17336df0e8423cef735c0c708ef2fd.jpg" alt="프로필">
    </div>
    <div class="author-info">
        <div class="name"><?=$list[$i]['wr_name']; ?> <? if ($list[$i]['wr_protect'] || $list[$i]['wr_secret']) { ?><i class="fa-solid fa-lock"></i><? } ?></div>
        <div class="date pixel-txt"><?=date('Y.m.d H:i', strtotime($list[$i]['wr_datetime'])) ?></div>
    </div>
</div>

<div id="thread<?=$list[$i]['wr_id']; ?>" class="item-body">
<? if ($list[$i]['wr_protect'] && !$is_admin) { ?>
    보호글입니다.
    <div class="pass-form">
        <form name="fboardpassword" onsubmit="return showThreadContent(this);" method="post">
        <input type="hidden" name="w" value="p">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $list[$i]['wr_id'] ?>">
        <fieldset class="box-pw">
            <input type="password" name="wr_password" id="password_wr_password" required class="frm_input required" size="15" maxlength="20" placeholder="비밀번호">
            <input type="submit" value="확인" class="btn_submit ui-btn">
        </fieldset>
        </form>
    </div>
<? } elseif ($list[$i]['wr_secret'] && !$is_member) { ?>
    멤버 공개 게시글입니다.
<? } elseif (($member['mb_level'] < $board['bo_read_level'])) { ?>
    글읽기 권한이 없습니다.
<? } else { ?>
    <? include($board_skin_path.'/list.skin.con.php'); ?>
<? } ?>
</div>