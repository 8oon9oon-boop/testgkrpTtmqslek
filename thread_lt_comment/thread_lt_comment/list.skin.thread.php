<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if ($row['wr_is_comment']) {
    $list[$i] = $row;
}

// 수정, 삭제 링크
$update_href = $delete_href = '';
// 로그인중이고 자신의 글이라면 또는 관리자라면 비밀번호를 묻지 않고 바로 수정, 삭제 가능
if (($member['mb_id'] && ($member['mb_id'] === $list[$i]['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    set_session('ss_delete_token', $token = uniqid(time()));
    $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
    
    if ($list[$i]['wr_is_comment']) {
        $update_href .= '&amp;is_comment=y';
    }

    if ($is_view) {
        $update_href .= '&amp;is_view=1';
    }
}

else if (!$list[$i]['mb_id']) { // 회원이 쓴 글이 아니라면
    $update_href = './password.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href = './password.php?w=d&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;

    if ($list[$i]['wr_is_comment']) {
        $update_href .= '&amp;is_comment=y';
    }

    if ($is_view) {
        $update_href .= '&amp;is_view=1';
    }
}
if (!$list[$i]['file']) {
    $list[$i]['file'] = get_file($board['bo_table'], $list[$i]['wr_id']);
}

?>

<div class="thread_admin">
<? if (!$list[$i]['wr_is_comment']) { ?>
    <? if (!$is_view) { ?>
        <a href="<?=$list[$i]['href'];?>">보기</a>
    <? } ?>
    <a href="javascript:void(0)" class="view_link" data-url="<?=$list[$i]['href'];?>" title="주소 복사">주소복사</a>
    <input type="text" id="view_copy" style="position:absolute;top:-9999em;">    
<? } ?>
<? if ($write_href) { ?>
    <? if ($update_href) { ?><a href="<? echo $update_href ?>">수정</a><? } ?>
    <? if ($delete_href) { ?><a href="<? echo $delete_href ?>" onclick="del(this.href); return false;">삭제</a><? } ?>
<? } ?>
</div>

    
<? if ($list[$i]['is_notice']) { ?>
    <span class="thread_notice">공지</span>
<? } ?>
<? if ($list[$i]['ca_name'] && !$list[$i]['wr_is_comment']) { ?>
    <span class="thread_cate"><?=$list[$i]['ca_name']?></span>
<? } ?>
<? if (!$list[$i]['is_notice']) { ?>
<div class="thread_info">
    <span class="name">
        <?=$list[$i]['wr_name']; ?>
        <? if ($list[$i]['wr_protect'] || $list[$i]['wr_secret']) { ?>
            <span style="font-size:0.8em; color:#ff4b4b; vertical-align:middle;">[잠금]</span>
        <? } ?>
    </span>
    <span class="date">
        <?=date('Y-m-d H:i', strtotime($list[$i]['wr_datetime'])) ?>
    </span>
</div>
<? } ?>

<? if($list[$i]['wr_subject']) { ?>                
    <div class="thread_title"><strong class="txt-point"><?=$list[$i]['wr_subject']; ?></strong></div>
<? } ?>


<div id="thread<?=$list[$i]['wr_id']; ?>" class="thread_wr">
<? if ($list[$i]['wr_protect'] && !$is_admin) { ?>
    <div class="thread_main">
        보호글입니다.
        <div class="pass-form">
            <form name="fboardpassword" onsubmit="return showThreadContent(this);" method="post">
            <input type="hidden" name="w" value="p">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $list[$i]['wr_id'] ?>">
            <fieldset class="box-pw">
                <?if($w=='p'){?>
                <input type="text" name="wr_password" id="password_wr_password" required class="frm_input required" size="15" maxlength="20">
                <?}else {?>
                <input type="password" name="wr_password" id="password_wr_password" required class="frm_input required" size="15" maxlength="20">
                <?}?>
            </fieldset>
            <fieldset class="box-btn">
                <input type="submit" value="확인" class="btn_submit ui-btn">
            </fieldset>
            </form>
        </div>
    </div>
<? } elseif ($list[$i]['wr_secret'] && !$is_member) { ?>
    <div class="thread_main">
        멤버 공개 게시글입니다.
    </div>
<? } elseif (($member['mb_level'] < $board['bo_read_level'])) { ?>
    <div class="thread_main">
        글읽기 권한이 없습니다.
    </div>
<? } else { ?>
    <? include($board_skin_path.'/list.skin.con.php'); // 타래 본문 ?>
<? } ?>
</div>```

---

### 2. `list.skin.php`
상단의 '글쓰기' 아이콘, 목록보기의 '이미지 없음' 아이콘, 답글달기 아이콘, 하단 '검색' 아이콘을 모두 텍스트로 변경했습니다.

```php
<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once ($board_skin_path.'/setting/user.config.php');
include_once ($board_skin_path.'/color.config.php');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/txtggu/textggu.css">', 1);

// 파일업로드 설정
if ($board['bo_upload_count'] < 4) {
    sql_query("UPDATE {$g5['board_table']} SET bo_upload_count = '4' WHERE bo_table = '$bo_table'");
}
if ($board['bo_use_list_file'] != '1') {
    sql_query("UPDATE {$g5['board_table']} SET bo_use_list_file = '1' WHERE bo_table = '$bo_table'");
}

?>
<div <?if($board['bo_table_width']>0){?>style="max-width:<?=$board['bo_table_width']?><?=$board['bo_table_width']>100 ? "px":"%"?>;margin:0 auto;"<?}?>>
<hr class="padding">
<? if($board['bo_content_head']) { ?>
	<div class="board-notice">
		<?=stripslashes($board['bo_content_head']);?>
	</div><hr class="padding" />
<? } ?>

<div class="board-skin-basic">
	<?php if ($is_category) { ?>
	<nav id="navi_category">
		<ul>
			<?php echo $category_option ?>
		</ul>
	</nav>
	<?php } ?>
    <? if ($write_href) { ?>
    <div class="thread_btn"><button class="ui-btn point">글쓰기</button></div>    
    <div class="thread_write" id="thread_write">
        <? include($board_skin_path.'/write.skin.php'); // 게시글 작성 ?>
    </div>
<? } ?>

<? if ($_list_style == 'list') { ?>
    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">
	<?//@201023 게시판 글 다중체크용 폼 필드 추가?>
<? } ?>
    
	<div class="thread_wrap type_<?=$_list_style; ?>">
		<? for ($i=0; $i<count($list); $i++) { ?>

    <? if ($_list_style == 'thread') { // 타래형 ?>
		<div class="thread-box theme-box<? if ($list[$i]['is_notice']) echo " bo_notice"; ?>">
            <? include ($board_skin_path.'/list.skin.thread.php'); ?>
            <?
                $thread_co_cnt = sql_fetch("SELECT count(wr_id) AS cnt FROM {$write_table} WHERE wr_parent = '{$list[$i]['wr_id']}' AND wr_is_comment = '1' AND wr_reply =''");
                $thread_co_sql = "SELECT * FROM (SELECT * FROM {$write_table} WHERE wr_parent = '{$list[$i]['wr_id']}' AND wr_is_comment = '1' AND wr_reply ='' ORDER BY wr_comment DESC limit 2) AS sub ORDER BY wr_comment ASC";
                $thread_result = sql_query($thread_co_sql);
            ?>
            <? if ($thread_co_cnt['cnt'] > 0) { ?>
            <div class="thread_co_wrap">
                <? if ($thread_co_cnt['cnt'] > 2) { ?>
                    <a href="<?=$list[$i]['href']?>">전체 보기</a>
                <? } ?>
                <? for ($k=0; $row=sql_fetch_array($thread_result); $k++) { ?>
                    <div class="thread_co">
                    <? include ($board_skin_path.'/list.skin.thread.php'); ?>
                    </div>
                <? } ?>
            </div>
            <? } ?>
            <? if ($write_href) { ?>
            <div class="thread_bottom">
                <button type="button" class="txt-point" style="font-weight:bold;" onclick="writeThreadComment(<?=$list[$i]['wr_parent'];?>,'<?=$list[$i]['ca_name']?>')">답글달기</button>
            </div>
            <div id="thread_comment<?=$list[$i]['wr_parent'];?>" class="thread_comment"></div>
            <? } ?>
		</div>
    <? } elseif ($_list_style == 'list') { // 목록형
        $_file_src = $list[$i]['file'][0]['file'] ? $list[$i]['file'][0]['path'].'/'.$list[$i]['file'][0]['file'] : '';
        $_img_src = $list[$i]['wr_2'] ? $list[$i]['wr_2'] : $_file_src;
        $_img_src = $list[$i]['wr_10'] ? $list[$i]['wr_10'] : $_img_src; // 썸네일 외부링크 우선
    ?>
        <div class="list-box">
            <?php if ($is_checkbox) { ?>
            <span class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </span>
            <?php } //@201023?>
            <a href="<?=$list[$i]['href']?>">
            <div class="list_thumb<?=(!$_img_src) ? ' no_img' : ''; ?>">
                <? if ($_img_src) { ?>
                    <img src="<?=$_img_src; ?>" alt="">
                <? } else { ?>
                    <span style="font-size:14px; color:#999;">이미지 없음</span>
                <? } ?>
            </div>
            <? if ($list[$i]['wr_subject']) { ?><div class="list_title"><span><?=$list[$i]['wr_subject']; ?></span></div><? } ?>          
            </a>
        </div>
    <? } ?>		
		<? } ?>
		<? if (count($list) == 0) { echo '<div class="no-data ">게시물이 없습니다.</div>'; } ?>
	</div>
	
	<? if ($list_href || $is_checkbox || $write_href) { ?>
	<div class="bo_fx txt-right">
		<? if ($list_href || $write_href) { ?>
            <?php if ($is_checkbox && $_list_style == 'list') { ?> 
            <p class="chk_all">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </p> 
		    <button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="ui-btn admin">선택삭제</button>
            <?php } ?>
		<? if ($list_href) { ?><a href="<? echo $list_href ?>" class="ui-btn">목록</a><? } ?>		
		<? } ?>
		<? if($admin_href){?><a href="<?=$admin_href?>" class="ui-btn admin" target="_blank">관리자</a><?}?>
	</div>
	<? } ?>

    <? if ($_list_style == 'list') { ?>
    </form>
    <script>
    function all_checked(sw) {
        var f = document.fboardlist;

        for (var i=0; i<f.length; i++) {
            if (f.elements[i].name == "chk_wr_id[]")
                f.elements[i].checked = sw;
        }
    }

    function fboardlist_submit(f) {
        var chk_count = 0;

        for (var i=0; i<f.length; i++) {
            if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
                chk_count++;
        }

        if (!chk_count) {
            alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
            return false;
        }
        if(document.pressed == "선택삭제") {
            if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
                return false;

            f.removeAttribute("target");
            f.action = "./board_list_update.php";
        }
        return true;
    }
    </script>
    <? } ?>
	<? echo $write_pages;  ?>

	<fieldset id="bo_sch" class="txt-center">
		<legend>게시물 검색</legend>

		<form name="fsearch" method="get">
		<input type="hidden" name="bo_table" value="<? echo $bo_table ?>">
		<input type="hidden" name="sca" value="<? echo $sca ?>">
		<input type="hidden" name="sop" value="and">
		<select name="sfl" id="sfl">
			<option value="wr_subject"<? echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
			<option value="wr_content"<? echo get_selected($sfl, 'wr_content'); ?>>내용</option>
			<option value="wr_subject||wr_content"<? echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
		</select>
		<input type="text" name="stx" value="<? echo stripslashes($stx) ?>" required id="stx" class="frm_input required" size="15" maxlength="20">
		<button type="submit" class="ui-btn point">검색</button>
		</form>
	</fieldset>
	</div>
</div>
<? include_once($board_skin_path.'/thread.script.php'); // 타래 공통 스크립트 ?>
<? include_once($board_skin_path.'/write.script.php'); // 글 작성시 이미지 관련 ?>