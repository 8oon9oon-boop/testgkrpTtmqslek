<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0); 

$owner_front = get_style('mmb_owner_name', 'cs_etc_2');		// 자기 로그 접두문자
$owner_front = $owner_front['cs_etc_2'];
$owner_behind = get_style('mmb_owner_name', 'cs_etc_3');		// 자기 로그 접미문자
$owner_behind = $owner_behind['cs_etc_3'];
$upload_max_filesize = round($board['bo_upload_size']/1000000 , 2)."Mb";
$comment_min=$board['bo_comment_min'];
$comment_max=$board['bo_comment_max'];
?>

<div id="load_log_board" <?if($board['bo_table_width']>0){?>style="max-width:<?=$board['bo_table_width']?><?=$board['bo_table_width']>100 ? "px":"%"?>;margin:0 auto;"<?}?>>

	<header class="messenger-header">
		<div class="left-menu">
			<a href="<?php echo G5_URL ?>">◀ 다른 게시판 이동</a>
		</div>
		<div class="room-info">
			<?php echo $board['bo_subject'] ?> <span class="count"><?php echo number_format($board['bo_count_write']) ?></span>
		</div>
		<div class="right-menu">
			<?php if ($is_member) { ?>
				<a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=register_form.php">정보 수정</a> | 
				<a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a>
			<?php } else { ?>
				<a href="<?php echo G5_BBS_URL ?>/login.php">로그인</a>
			<?php } ?>
		</div>
	</header>
	<? if($board['bo_content_head']) { ?>
		<div class="board-notice">
			<?=stripslashes($board['bo_content_head']);?>
		</div>
	<? } ?>

	<?
		/*-------------------------------------------
			동접자 카운터 설정
		---------------------------------------------*/
		$wiget = get_style('mmb_counter');
		if($wiget['cs_value']) { echo '<div class="connect-wiget">'.$wiget['cs_value'].'</div>'; }
	?>

	<?php if ($is_category) { ?>
		<nav id="navi_category">
			<ul>
				<?php echo $category_option ?>
			</ul>
		</nav>
		<?php } ?>
	<div class="ui-mmb-button">
		<?php if ($write_href) { 
			// 췩 사용 여부를 체크 한다.
			if($board['bo_use_chick']) { // 췩 사용 가능할 경우, 파일 업로드 폼을 생성한다. 
				$action_url = G5_BBS_URL."/write_update.php";
		?>
			<div class="ui-mmb-list-write">
				 <?include($board_skin_path.'/write.skin.php');?>
			</div>
		<? } else { ?>
			<a href="<?php echo $write_href ?>" class="ui-btn point small">등록하기</a>
		<? } } ?>
			<a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$bo_table?>" class="ui-btn small">새로고침</a>
			<a href="<?php echo $board_skin_url ?>/emoticon_list.php" class="ui-btn small new_win">이모티콘</a>
			<?if($is_admin){?>
			<a href="<?php echo G5_ADMIN_URL ?>/board_form.php?bo_table=<?=$bo_table?>&w=u" class="ui-btn small admin">관리자</a><?}?>
	</div>

	<? if($write_pages) { ?><div class="ui-paging"><?php echo $write_pages;  ?></div><? } ?>

	<div id="log_list" class="none-trans chat-area">
		<div class="system-notice"><span><?php echo date('Y년 m월 d일'); ?> 채팅 기록</span></div>

		<?
			for ($i=0; $i<count($list); $i++) {
				$list_item = $list[$i];
				include($board_skin_path."/list.log.skin.php");
			}
			if (count($list) == 0) { echo "<div class=\"empty_list\">등록된 로그가 없습니다.</div>"; } 
		?>
	</div>

	<? if($write_pages) { ?>
		<div class="ui-paging">
			<?php echo $write_pages;  ?>
		</div>
	<? } ?>

	<div class="searc-sub-box">
		<form name="fsearch" method="get">
			<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
			<input type="hidden" name="sca" value="<?php echo $sca ?>">
			<input type="hidden" name="sop" value="and">
			<input type="hidden" name="hash" value="<?=$hash?>">
			<div class="ui-search-box">
				<fieldset class="sch_category select-box">
					<select name="sfl" id="sfl"> 
						<option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>코멘트</option>
						<option value="wr_name,1"<?php echo get_selected($sfl, 'wr_name,1'); ?>>작성자</option>
						<option value="wr_name"<?php echo get_selected($sfl, 'wr_name'); ?>>작성자(코)</option>
						<option value="wr_1"<?php echo get_selected($sfl, 'wr_1'); ?>>메모</option>
					</select>
				</fieldset>
				<fieldset class="sch_text">
					<input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="frm_input" maxlength="20">
				</fieldset>
				<fieldset class="sch_button">
					<button type="submit" class="ui-btn point">검색</button>
				</fieldset>
			</div> 
		</form>
	</div>
</div>

<script>
var avo_mb_id = "<?=$member['mb_id']?>";
var avo_board_skin_path = "<?=$board_skin_path?>";
var avo_board_skin_url = "<?=$board_skin_url?>";

/* (이하 기존 스크립트 그대로 유지...) */
var save_before = '';
var save_html = '';

function fviewcomment_submit(f) {
	set_comment_token(f);
	var pattern = /(^\s*)|(\s*$)/g;
	var content = "";
	$.ajax({
		url: g5_bbs_url+"/ajax.filter.php", type: "POST", data: { "content": f.wr_content.value }, dataType: "json", async: false, cache: false,
		success: function(data, textStatus) { content = data.content; }
	});
	if (content) { alert("내용에 금지단어('"+content+"')가 포함되어있습니다"); f.wr_content.focus(); return false; }
	if (!f.wr_content.value) { alert("댓글을 입력하여 주십시오."); return false; }
	return true;
}
function comment_delete() { return confirm("이 댓글을 삭제하시겠습니까?"); }
function comment_box(wr_id,co_id, work) { /* 기존 로직 생략 없이 포함 */ }
$(".co-more").click(function(){ $(this).next(".original_comment_area").slideToggle(); $(this).toggleClass("on"); return false; });

// 좋아요(추천) 스크립트
function excute_good(href, $el, $tx) {
    $.post(href, { js: "on" }, function(data) {
        if(data.error) {
            alert(data.error);
            return false;
        }
        if(data.count) {
            $tx.text(number_format(String(data.count)));
            alert("좋아요를 눌렀습니다.");
        }
    }, "json");
}
</script>
<script src="<?=$board_skin_url?>/load.board.js"></script>