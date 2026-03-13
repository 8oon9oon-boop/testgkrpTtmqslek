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
            <span class="author" style="color:#f39c12;"><?=$log_comment['name']?></span>
            <span class="text"><?=$content?></span>
            <span class="time">(<?=date('m-d H:i', strtotime($log_comment['wr_datetime']))?>)</span>
            <span class="actions">
                <? if ($log_comment['is_edit']) { ?><a href="#">수정</a><? } ?>
                <? if ($log_comment['is_del'])  { ?><a href="<?=$log_comment['del_link']?>" onclick="return comment_delete();">삭제</a><? } ?>
            </span>
        </div>
    </div>
</div>
<? } ?>