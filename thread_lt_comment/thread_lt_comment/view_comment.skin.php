<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<script>
// 글자수 제한
var char_min = parseInt(<? echo $comment_min ?>); // 최소
var char_max = parseInt(<? echo $comment_max ?>); // 최대
</script>

<div class="board-comment-list board-comment-list<?=$list[$i]['wr_id'];?>">
	<?
	$cmt_amt = count($comment_list);
	for ($c=0; $c<$cmt_amt; $c++) {
		$comment_id = $comment_list[$c]['wr_id'];
		$cmt_depth = ""; // 댓글단계
		$cmt_depth = strlen($comment_list[$c]['wr_comment_reply']) * 10;
		$comment = $comment_list[$c]['content'];

		$comment = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $comment);
		$cmt_sv = $cmt_amt - $c + 1; 
		$comment = markup_content($comment); 
        $comment = textggu_change($comment); 
		$comment = emote_ev($comment);
	?>
	<div class="item <?=($cmt_depth ? "reply" : "")?>" id="c_<? echo $comment_id ?>">
        
        <div class="co-profile">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="profile" class="profile-img">
        </div>

        <div class="co-main">
            <div class="co_head_wrap">
                <div class="co-name txt-point">
                    <?=$comment_list[$c]['wr_name']; ?>
                </div>
                <div class="co-info">
                    <span><? echo date('m.d H:i', strtotime($comment_list[$c]['wr_datetime'])) ?></span>
                    <? if($comment_list[$c]['is_reply'] || $comment_list[$c]['is_edit'] || $comment_list[$c]['is_del']) {
                        $query_string = clean_query_string($_SERVER['QUERY_STRING']);

                        if($w == 'cu') {
                            $sql = " select wr_id, wr_content, mb_id from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
                            $cmt = sql_fetch($sql);
                            if (!($is_admin || ($member['mb_id'] == $cmt['mb_id'] && $cmt['mb_id'])))
                                $cmt['wr_content'] = '';
                            $c_wr_content = $cmt['wr_content'];
                        }
                    ?>
                    <? if ($comment_list[$c]['is_edit']) { ?><span><a href="javascript:void(0)" onclick="comment_box('<? echo $comment_id ?>', 'ru','<?=$list[$i]['wr_id'];?>'); return false;">수정</a></span><? } ?>
                    <? if ($comment_list[$c]['is_del'])  { ?><span><a href="<? echo $comment_list[$c]['del_link'];  ?>" onclick="return comment_delete();">삭제</a></span><? } ?>
                    <? } ?>
                </div>
            </div>
            <div class="co-content">
                <? if ($comment_list[$c]['wr_1'] == 'hide') { ?>
                    <button type="button" class="ui-btn point" onclick="toggleComment('<?=$comment_id?>');">펼치기</button>
                <? } ?>
                <div class="co-inner co-inner<?=$comment_id?>"<?=($comment_list[$c]['wr_1'] == 'hide') ? ' style="display:none;"' : '';?>>
                    <? if (strstr($comment_list[$c]['wr_option'], "secret")) { ?><span class="secret">[ 비밀글 ]</span><? } ?>
                    <? echo $comment ?>
                </div>
                
                <span id="edit_<? echo $comment_id ?>"></span><span id="reply_<? echo $comment_id ?>"></span><input type="hidden" value="<? echo strstr($comment_list[$c]['wr_option'],"secret") ?>" id="secret_comment_<? echo $comment_id ?>_<?=$list[$i]['wr_id'];?>">
                <input type="hidden" value="<? echo $comment_list[$c]['wr_1']; ?>" id="wr_1_comment_<? echo $comment_id ?>_<?=$list[$i]['wr_id'];?>">
                <textarea id="save_comment_<? echo $comment_id ?>_<?=$list[$i]['wr_id'];?>" style="display:none"><? echo get_text($comment_list[$c]['content1'], 0) ?></textarea>
            </div>
        </div>
	</div>
	<? } ?>
</div>

<? if($c == 0) { ?>
<script>
	$('.board-comment-list<?=$list[$i]['wr_id']?>').remove();
</script>
<? } ?>

<? if ($is_comment_write) { 
	if($w == '')
		$w = 'r';
?>
<div id="bo_vc_w_<?=$comment_id;?>" class="board-comment-write bo_vc_w_<?=$list[$i]['wr_id'];?>">
	<form name="fviewcomment" action="<?=$board_skin_url; ?>/write_reply_update.php" onsubmit="return fviewreply_submit(this);" method="post" autocomplete="off">
		<input type="hidden" name="w" value="<? echo $w ?>" id="w_<?=$list[$i]['wr_id'];?>">
		<input type="hidden" name="bo_table" value="<? echo $bo_table ?>">
		<input type="hidden" name="wr_id" value="<? echo $list[$i]['wr_id'] ?>">
		<input type="hidden" name="comment_id" value="<? echo $c_id ?>" id="comment_id_<?=$list[$i]['wr_id'];?>">
		<input type="hidden" name="sca" value="<? echo $sca ?>">
		<input type="hidden" name="sfl" value="<? echo $sfl ?>">
		<input type="hidden" name="stx" value="<? echo $stx ?>">
		<input type="hidden" name="spt" value="<? echo $spt ?>">
		<input type="hidden" name="page" value="<? echo $page ?>">
		<? if ($is_view) { ?>
		<input type="hidden" name="is_view" value="true">
		<? } ?>
		
		<div class="board-comment-form">
			<textarea id="wr_content_<?=$list[$i]['wr_id'];?>" name="wr_content" maxlength="10000" required class="required" title="내용"
			<? if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<? } ?>><? echo $c_wr_content;  ?></textarea>
			<? if ($comment_min || $comment_max) { ?><script> check_byte('wr_content', 'char_count'); </script><? } ?>
			<script>
			$(document).on( "keyup change", "textarea#wr_content_<?=$list[$i]['wr_id'];?>[maxlength]", function(){
				var str = $(this).val()
				var mx = parseInt($(this).attr("maxlength"))
				if (str.length > mx) {
					$(this).val(str.substr(0, mx));
					return false;
				}
			});
			</script>
			
			<div style="width: 100%; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
			    <p style="margin:0; display:flex; gap:10px; align-items:center;">
			    <?php if ($is_guest) { ?>
                <label for="wr_name_<?=$list[$i]['wr_id'];?>" class="sound_only">이름<strong> 필수</strong></label>			
                <input type="text" name="wr_name" value="<?php echo get_cookie("ck_sns_name"); ?>" id="wr_name_<?=$list[$i]['wr_id'];?>" required class="frm_input required" size="15" placeholder="이름">

                <label for="wr_password_<?=$list[$i]['wr_id'];?>" class="sound_only">비밀번호<strong> 필수</strong></label>
                <input type="password" name="wr_password" id="wr_password_<?=$list[$i]['wr_id'];?>" required class="frm_input required" size="15"  placeholder="비밀번호">
                <?php } ?>

			    <input type="checkbox" name="secret" value="secret" id="co_wr_secret_<?=$list[$i]['wr_id'];?>"> <label for="co_wr_secret_<?=$list[$i]['wr_id'];?>">비밀글</label>
			    <input type="checkbox" name="wr_1" value="hide" id="co_wr_1_<?=$list[$i]['wr_id'];?>"> <label for="co_wr_1_<?=$list[$i]['wr_id'];?>">접기</label>
			    </p>

			    <div class="btn_confirm">
				    <button type="submit" id="btn_submit_<?=$list[$i]['wr_id'];?>" class="ui-btn point">댓글등록</button>
			    </div>
            </div>
		</div>
	</form>
</div>

<script>
var save_html<?=$list[$i]['wr_id'];?> = document.getElementById('bo_vc_w_<?=$comment_id;?>').innerHTML;
</script>
<? } ?>