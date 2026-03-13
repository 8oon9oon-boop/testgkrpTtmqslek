<?php
if (!defined('_GNUBOARD_')) exit; 
for ($index=0; $index<count($comment); $index++) {
    $log_comment = $comment[$index];
    $comment_id = $log_comment['wr_id'];
    $content = strip_tags($log_comment['content']); // 깔끔한 텍스트 출력
?>

<div class="reply-wrapper" id="c_<?=$comment_id?>">
    <span class="reply-icon">ㄴ</span>
    <div>
        <div class="message-row">
    <span class="author" style="color:#f39c12;"><?php echo $log_comment['name'] ?></span>
    <span class="text"><?php echo $content ?></span>
    <span class="time">(<?php echo date('m-d H:i', strtotime($log_comment['wr_datetime'])) ?>)</span>
    <span class="actions">
        <a href="#" onclick="comment_box('<?php echo $list_item['wr_id'] ?>', '<?php echo $comment_id ?>', 'c'); return false;">답글</a>
        <?php if ($log_comment['is_edit']) { ?>
            <a href="#" onclick="comment_box('<?php echo $list_item['wr_id'] ?>', '<?php echo $comment_id ?>', 'cu'); return false;">수정</a>
        <?php } ?>
        <?php if ($log_comment['is_del']) { ?>
            <a href="<?php echo $log_comment['del_link']; ?>" onclick="return comment_delete();">삭제</a>
        <?php } ?>
    </span>
</div>
<div id="edit_<?php echo $comment_id ?>"></div>
    </div>
</div>
<? } ?>