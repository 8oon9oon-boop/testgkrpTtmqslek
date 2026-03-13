<?php
if (!defined("_GNUBOARD_")) exit; 
$update_href = $delete_href = '';
if (($member['mb_id'] && ($member['mb_id'] == $list_item['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list_item['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
}
// 이미지 처리
$image_url = '';
if($list_item['wr_type'] == 'UPLOAD') { 
    $thumb = get_mmb_image($bo_table, $list_item['wr_id']);
    $image_url = '<img src="'.$thumb['src'].'" onclick="window.open(this.src)" />';
} else if($list_item['wr_type'] == 'URL') {
    $image_url = '<img src="'.$list_item['wr_url'].'" onclick="window.open(this.src)" />';
}
?>

<div class="message-group" id="log_<?=$list_item['wr_id']?>">
    <div class="avatar"></div>
    <div class="message-content">
        <div class="message-row">
            <span class="author"><?=$list_item['wr_name']?></span>
            <span class="text"><?=strip_tags(conv_content($list_item['wr_text'], 0))?></span>
            <span class="time">(<?=date('m-d H:i', strtotime($list_item['wr_datetime']))?>)</span>
            <span class="actions">
                <a href="#" onclick="$('#bo_vc_w_<?=$list_item['wr_id']?>').toggle(); return false;">답글</a>
                <? if ($update_href) { ?><a href="<?=$update_href?>">수정</a><? } ?>
                <? if ($delete_href) { ?><a href="<?=$delete_href?>" onclick="del(this.href); return false;">삭제</a><? } ?>
            </span>
        </div>

        <? if($image_url) { ?>
        <div class="image-box"><?=$image_url?></div>
        <? } ?>

        <div class="ui-comment"> 
            <div class="item-comment-box">
                <? include($board_skin_path."/view_comment.php");?>
            </div>
            <div class="item-comment-form-box" id="bo_vc_w_<?=$list_item['wr_id']?>" style="display:none; margin-top:10px;">
                <? include($board_skin_path."/write_comment.php");?>
            </div> 
        </div>
    </div>
</div>