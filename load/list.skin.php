<?php
if (!defined('_GNUBOARD_')) exit; 
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0); 
?>

<div id="load_log_board">
    <header class="messenger-header">
        <div class="left-menu"><a href="<?php echo G5_URL ?>">◀ 이전</a></div>
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
            <a href="?bo_table=<?=$bo_table?>" style="margin-left:10px;">≡ 새로고침</a>
        </div>
    </header>

    <main id="log_list" class="chat-area">
        <div class="system-notice"><span><?php echo date('Y년 m월 d일'); ?> <?php echo get_yoil(date('Y-m-d')); ?>요일</span></div>
        <div class="enter-notice">/<?php echo $board['bo_subject'] ?>/에 입장하셨습니다.</div>

        <?php
        for ($i=0; $i<count($list); $i++) {
            $list_item = $list[$i];
            include($board_skin_path."/list.log.skin.php");
        }
        if (count($list) == 0) { echo "<div class=\"system-notice\"><span>등록된 대화가 없습니다.</span></div>"; } 
        ?>
    </main>

    <?php if ($write_href && $board['bo_use_chick']) { 
        $action_url = G5_BBS_URL."/write_update.php";
        include($board_skin_path.'/write.skin.php');
    } ?>
</div>

<script>
var avo_mb_id = "<?=$member['mb_id']?>";
var avo_board_skin_path = "<?=$board_skin_path?>";
var avo_board_skin_url = "<?=$board_skin_url?>";
function comment_box(wr_id,co_id, work) { /* 폼 토글 로직 생략 없이 에러 안 나게 더미 유지 */ }
function comment_delete() { return confirm("이 댓글을 삭제하시겠습니까?"); }
</script>
<script src="<?=$board_skin_url?>/load.board.js"></script>