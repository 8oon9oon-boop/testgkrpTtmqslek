<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$update_href = $delete_href = '';

// 권한 체크 및 링크 설정 (기존 로직 유지)
if (($member['mb_id'] && ($member['mb_id'] == $list_item['mb_id'])) || $is_admin) {
	$update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
	if(!$list_item['wr_log'] || $is_admin) { 
		set_session('ss_delete_token', $token = uniqid(time()));
		$delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
	}
} else if (!$list_item['mb_id']) { 
	$update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
	$delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
}

$image_url = '';
if($list_item['wr_type'] == 'UPLOAD') { 
	$thumb = get_mmb_image($bo_table, $list_item['wr_id']);
	$image_url = '<img src="'.$thumb['src'].'" onclick="window.open(this.src)" style="max-width:100%; border-radius:10px;" />';
} else if($list_item['wr_type'] == 'URL' || $list_item['wr_type'] == 'VIDEO') {
	$image_url = $list_item['wr_video'] ? $list_item['wr_video'] : '<img src="'.$list_item['wr_url'].'" onclick="window.open(this.src)" style="max-width:100%; border-radius:10px;" />';
}

$is_viewer = true; // 비밀글 등 권한 체크 생략 (기존 로직 유지됨)
?>

<div class="message-group" id="log_<?=$list_item['wr_id']?>">
    <div class="avatar"></div>
    
    <div class="message-content">
        <div class="message-row">
            <span class="author"><?= $list_item['wr_name'] ?></span>
            <span class="time">(<?= date('m-d H:i', strtotime($list_item['wr_datetime'])) ?>)</span>
            <span class="actions">
                <? if ($update_href) { ?><a href="<?php echo $update_href ?>" style="color:#999; text-decoration:none;">수정</a><? } ?>
                <? if ($delete_href) { ?><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;" style="color:#999; text-decoration:none;">삭제</a><? } ?>
            </span>
        </div>

        <? if($list_item['wr_type'] == 'TEXT') { ?>
        <div class="text" style="background:#fff; padding:10px 15px; border-radius:10px; display:inline-block; border:1px solid #eaeaea; margin-bottom:5px;">
            <?= conv_content($list_item['wr_text'],0) ?>
        </div>
        <? } ?>

        <? if($image_url) { ?>
        <div class="image-box" style="background:transparent; border:none; height:auto; text-align:left; margin-left:0;">
            <?= $image_url ?>
        </div>
        <? } ?>

        <div class="ui-comment" style="margin-top:10px;"> 
            <div class="item-comment-box">
                <? include($board_skin_path."/view_comment.php");?>
            </div>
            <div class="item-comment-form-box">
                <? include($board_skin_path."/write_comment.php");?>
            </div> 
        </div>
    </div>
</div>