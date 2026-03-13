<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$is_secret=$board['bo_use_secret']; 
$is_error = false;
$option = '';
$option_hidden = '';

if(!$is_error) { 
	$category_option = '';
	if ($board['bo_use_category']) {
		$ca_name = isset($write['ca_name']) ? $write['ca_name'] : "";
		$category_option = get_category_option($bo_table, $ca_name);
		$is_category = true;
	}

	$image_url = $board_skin_url."/img/no_image.png";
	$img_data = "";
	if($w == 'u') { 
		if($write['wr_type'] == 'URL') {
			$image_url = $write['wr_url'];
			$img_data = "width : ".$write['wr_width']."px / height : ".$write['wr_height']."px";
		} else if($file[0]['file']) { 
			$image_url = $file[0]['path']."/".$file[0]['file'];
			$img_data = "width : ".$file[0]['wr_width']."px / height : ".$file[0]['wr_height']."px";
		} 
	}
	$write['wr_subject'] = $member['mb_nick'] ? $member['mb_nick'] : 'GUEST';
?>

<div id="load_log_board">
	<section id="bo_w" class="mmb-board<?if($board['bo_use_chick']){echo " chick";}?>">
		<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
		<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
		<input type="hidden" name="w" value="<?php echo $w ?>">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
		<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
		<input type="hidden" name="sca" value="<?php echo $sca ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="spt" value="<?php echo $spt ?>">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="wr_subject" value="<?=$write['wr_subject']?>" /> 
		<input type="hidden" name="wr_width" id="wr_width" value="<?php if($w=='u') echo $write['wr_width']; ?>">
		<input type="hidden" name="wr_height" id="wr_height" value="<?php if($w=='u') echo $write['wr_height']; ?>"> 

		<footer class="messenger-footer">
			<div class="input-toolbar">
				<div class="toolbar-left">
					<span onclick="$('#wr_file').click();">이미지</span>
					<span><label for="secret_chk" style="cursor:pointer;">비밀글</label></span>
					<?php if ($is_html && !$board['bo_use_chick']) { ?>
						<span><label for="html" style="cursor:pointer;">HTML</label></span>
					<?php } ?>
					<?if(!$board['bo_use_chick']||$w=='u'){?>
						<span onclick="window.open('<?php echo $board_skin_url ?>/emoticon_list.php', 'emoticon', 'width=400, height=600');">이모티콘</span>
					<?}?>
				</div>
				<div class="toolbar-right">
					<span onclick="$('#btn_submit').click();">등록</span>
					<span onclick="location.href='./board.php?bo_table=<?php echo $bo_table ?>';">취소</span>
				</div>
			</div>

			<div style="display:none;">
				<select name="wr_type" onchange="fn_log_type(this.value);">
					<option value="UPLOAD" selected>UPLOAD</option>
				</select>
				<input type="file" id="wr_file" name="bf_file[]" title="로그등록" />
				<input type="checkbox" id="secret_chk" name="secret" value="secret" <?if(strstr($write['wr_option'],'secret')) echo "checked";?>>
				<input type="checkbox" id="html" name="html" value="html1">
				<button type="submit" id="btn_submit">등록</button>
			</div>

			<div class="input-area">
				<div style="width:100%;">
					<?php echo $editor_html; ?>
				</div>
			</div>
		</footer>
		</form>
	</section>
</div>

<script>
	// (기존 스크립트 그대로 유지, 생략 없이 복사)
	function fwrite_submit(f) {
		<?php echo $editor_js; ?>
		var content = f.wr_content.value;
		if (content == "") { alert("내용을 입력해주세요."); return false; }
		document.getElementById("btn_submit").disabled = "disabled";
		return true;
	}
</script>
<? } ?>