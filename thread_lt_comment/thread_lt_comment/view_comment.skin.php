<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<div class="board-comment-list board-comment-list<?=$list[$i]['wr_id'];?>">
	<?
	$cmt_amt = count($comment_list);
	for ($c=0; $c<$cmt_amt; $c++) {
		$comment_id = $comment_list[$c]['wr_id'];
		$comment = $comment_list[$c]['content'];
		$comment = markup_content($comment); 
        $comment = textggu_change($comment); 
		$comment = emote_ev($comment);
	?>
	<div class="cmt-item" id="c_<? echo $comment_id ?>">
        <div class="cmt-profile">
            <img src="https://i.pinimg.com/736x/ea/17/33/ea17336df0e8423cef735c0c708ef2fd.jpg" alt="프로필">
        </div>
        
        <div class="cmt-content-wrap">
            <div class="cmt-header">
                <span class="cmt-name"><?=$comment_list[$c]['wr_name']; ?></span>
                <span class="cmt-actions">
                    <span style="color:#888; margin-right:5px;"><? echo date('m.d H:i', strtotime($comment_list[$c]['wr_datetime'])) ?></span>
                    <? if($comment_list[$c]['is_edit']) { ?><a href="javascript:void(0)" onclick="comment_box('<? echo $comment_id ?>', 'ru','<?=$list[$i]['wr_id'];?>'); return false;">수정</a><? } ?>
                    <? if($comment_list[$c]['is_del'])  { ?><a href="<? echo $comment_list[$c]['del_link'];  ?>" onclick="return comment_delete();">삭제</a><? } ?>
                </span>
            </div>
            <div class="cmt-text">
                <? if (strstr($comment_list[$c]['wr_option'], "secret")) { ?><span class="secret" style="color:var(--kitsch-pink);">[비밀글]</span> <? } ?>
                <? echo $comment ?>
            </div>
            
            <span id="edit_<? echo $comment_id ?>"></span><span id="reply_<? echo $comment_id ?>"></span><textarea id="save_comment_<? echo $comment_id ?>_<?=$list[$i]['wr_id'];?>" style="display:none"><? echo get_text($comment_list[$c]['content1'], 0) ?></textarea>
        </div>
	</div>
	<? } ?>
</div>

<? if($c == 0) { ?>
<script> $('.board-comment-list<?=$list[$i]['wr_id']?>').remove(); </script>
<? } ?>

<? if ($is_comment_write) { 
	if($w == '') $w = 'r';
?>
<div id="bo_vc_w_<?=$comment_id;?>" class="board-comment-write bo_vc_w_<?=$list[$i]['wr_id'];?>">
	<form name="fviewcomment" action="<?=$board_skin_url; ?>/write_reply_update.php" onsubmit="return fviewreply_submit(this);" method="post" autocomplete="off">
		<input type="hidden" name="w" value="<? echo $w ?>" id="w_<?=$list[$i]['wr_id'];?>">
		<input type="hidden" name="bo_table" value="<? echo $bo_table ?>">
		<input type="hidden" name="wr_id" value="<? echo $list[$i]['wr_id'] ?>">
		<input type="hidden" name="comment_id" value="<? echo $c_id ?>" id="comment_id_<?=$list[$i]['wr_id'];?>">
		
		<div class="board-comment-form cmt-write">
			<textarea id="wr_content_<?=$list[$i]['wr_id'];?>" name="wr_content" required class="required" placeholder="댓글을 남겨보세요..."><? echo $c_wr_content;  ?></textarea>
			<div class="btn_confirm">
				<button type="submit" id="btn_submit_<?=$list[$i]['wr_id'];?>" class="btn-cmt pixel-txt">등록</button>
			</div>
		</div>
	</form>
</div>
<script> var save_html<?=$list[$i]['wr_id'];?> = document.getElementById('bo_vc_w_<?=$comment_id;?>').innerHTML; </script>
<? } ?>