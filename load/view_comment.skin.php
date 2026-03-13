<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

for ($index=0; $index<count($comment); $index++) {
	$log_comment = $comment[$index];
	$comment_id = $log_comment['wr_id'];
    $content = $log_comment['content'];
    
    // 권한 및 삭제 링크 설정 로직 (기존 로직 유지)
    $c_reply_href = $c_edit_href = '';
    if($log_comment['is_edit']) {
        $c_edit_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=cu#bo_vc_w_'.$list_item['wr_id'];
    }
?>

<div class="reply-wrapper" id="c_<?php echo $comment_id ?>">
    <span class="reply-icon" style="color:#f39c12; font-weight:bold;">ㄴ</span>
    <div class="reply-avatar"></div>
    
    <div style="flex:1;">
        <div class="message-row">
            <span class="reply-author"><?= $log_comment['name'] ?></span>
            <span class="time">(<?= date('m-d H:i', strtotime($log_comment['wr_datetime'])) ?>)</span>
            <span class="actions">
                <?php if ($log_comment['is_edit']) { ?><a href="<?php echo $c_edit_href; ?>" onclick="comment_box('<?=$list_item['wr_id']?>','<?php echo $comment_id ?>', 'cu'); return false;" style="color:#999; text-decoration:none;">수정</a><?php } ?>
                <?php if ($log_comment['is_del'])  { ?><a href="<?php echo $log_comment['del_link']; ?>" onclick="return comment_delete();" style="color:#999; text-decoration:none;">삭제</a><?php } ?>
            </span>
        </div>
        
        <div class="text" style="background:#f1f1f1; padding:8px 12px; border-radius:10px; display:inline-block; font-size:14px;">
            <?= $content ?>
        </div>
        
        <div id="edit_<? echo $comment_id ?>" class="bo_vc_w"></div>
    </div>
</div>
<? } ?>