<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once ($board_skin_path.'/setting/user.config.php');
include_once ($board_skin_path.'/color.config.php');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/txtggu/textggu.css">', 1);

if ($board['bo_upload_count'] < 4) {
    sql_query("UPDATE {$g5['board_table']} SET bo_upload_count = '4' WHERE bo_table = '$bo_table'");
}
if ($board['bo_use_list_file'] != '1') {
    sql_query("UPDATE {$g5['board_table']} SET bo_use_list_file = '1' WHERE bo_table = '$bo_table'");
}
?>
<div <?if($board['bo_table_width']>0){?>style="max-width:<?=$board['bo_table_width']?><?=$board['bo_table_width']>100 ? "px":"%"?>;margin:0 auto;"<?}?>>

<? if($board['bo_content_head']) { ?>
    <div class="board-notice">
        <?=stripslashes($board['bo_content_head']);?>
    </div>
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
    <div class="thread_btn">
        <button type="button" class="ui-btn pixel-txt">새 글 작성</button>
    </div>    
    <div class="thread_write" id="thread_write" style="display:none;">
        <div class="thread-write-box">
            <div class="write-header pixel-txt">새 글 작성</div>
            <? include($board_skin_path.'/write.skin.php'); ?>
        </div>
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
    <? } ?>
    
    <div class="thread_wrap type_<?=$_list_style; ?>">
        <? for ($i=0; $i<count($list); $i++) { ?>
            <? if ($_list_style == 'thread') { ?>
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
                            <a href="<?=$list[$i]['href']?>" class="pixel-txt" style="color:var(--line); margin-bottom:10px; display:inline-block;">전체 보기</a>
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
                        <button type="button" onclick="writeThreadComment(<?=$list[$i]['wr_parent'];?>,'<?=$list[$i]['ca_name']?>')">댓글 달기</button>
                    </div>
                    <div id="thread_comment<?=$list[$i]['wr_parent'];?>" class="thread_comment" style="display:none;"></div>
                    <? } ?>
                </div>
            <? } elseif ($_list_style == 'list') { 
                $_file_src = $list[$i]['file'][0]['file'] ? $list[$i]['file'][0]['path'].'/'.$list[$i]['file'][0]['file'] : '';
                $_img_src = $list[$i]['wr_2'] ? $list[$i]['wr_2'] : $_file_src;
                $_img_src = $list[$i]['wr_10'] ? $list[$i]['wr_10'] : $_img_src; 
            ?>
                <div class="list-box">
                    <?php if ($is_checkbox) { ?>
                    <span class="td_chk">
                        <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                        <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                    </span>
                    <?php } ?>
                    <a href="<?=$list[$i]['href']?>">
                    <div class="list_thumb<?=(!$_img_src) ? ' no_img' : ''; ?>">
                        <? if ($_img_src) { ?>
                            <img src="<?=$_img_src; ?>" alt="">
                        <? } else { ?>
                            <i class="fa-solid fa-list"></i>
                        <? } ?>
                    </div>
                    <? if ($list[$i]['wr_subject']) { ?><div class="list_title"><span><?=$list[$i]['wr_subject']; ?></span></div><? } ?>          
                    </a>
                </div>
            <? } ?>		
        <? } ?>
        <? if (count($list) == 0) { echo '<div class="no-data" style="text-align:center; padding:50px 0; background:#fff; border:1px solid #000;">게시물이 없습니다.</div>'; } ?>
    </div>
    
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
</div>
</div>
<? include_once($board_skin_path.'/thread.script.php'); ?>
<? include_once($board_skin_path.'/write.script.php'); ?>