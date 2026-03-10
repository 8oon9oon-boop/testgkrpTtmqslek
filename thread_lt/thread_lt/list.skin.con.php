<?php
include_once('./_common.php');

include_once($board_skin_path.'/list.skin.script.php');

if ($w == 'p') {
    //보호글일 경우
    $qstr = 'bo_table='.$bo_table.'&amp;sfl='.$sfl.'&amp;stx='.$stx.'&amp;sop='.$sop.'&amp;wr_id='.$wr_id.'&amp;page='.$page;

    $wr = get_write($write_table, $wr_id);
	
    if ($wr_password!=$wr['wr_protect'])
        alert('비밀번호가 틀립니다.');

    // 세션에 아래 정보를 저장. 하위번호는 비밀번호없이 보아야 하기 때문임.
    $ss_name = 'ss_secret_'.$bo_table.'_'.$wr['wr_num'];
    set_session($ss_name, TRUE);

    $list[$i] = sql_fetch("SELECT * FROM {$write_table} WHERE wr_id = '{$wr_id}'");
    $list[$i]['file'] = get_file($bo_table, $wr_id);

}

$file_count = $list[$i]['file']['count'];

$img_count = 0;
for ($ii=0; $ii<4; $ii++){
    if ($list[$i]['wr_'.($ii+2)]) {
        $img_count++;
    }
}
$file_count = $list[$i]['file']['count'] + $img_count;

// 내용 콘텐츠 [파일*] 및 [이미지*] 변환
$list_content = $list[$i]['wr_content'];

$file_pattern = '/\[파일\d+\]/';
$img_pattern = '/\[이미지\d+\]/';

// 첨부파일 변환
if (preg_match($file_pattern, $list[$i]['wr_content'])) {
    for($j=0; $j<$list[$i]['file']['count']; $j++) {
        $file_pattern2 = '/\[파일'.($j+1).'\]/';
        $_file_src = $list[$i]['file'][$j]['file'] ? $list[$i]['file'][$j]['path'].'/'.$list[$i]['file'][$j]['file'] : '';
        $_img_tag = '<img src="'.$_file_src.'" class="thread_img" alt="">';
        $list_content = $_file_src ? preg_replace($file_pattern2, $_img_tag, $list_content) : $list_content;
    }

    $file_count = 0;
}

// 외부링크 이미지 변환
if (preg_match($img_pattern, $list[$i]['wr_content'])) {
    for($j=0; $j<$img_count; $j++) {
        $img_pattern2 = '/\[이미지'.($j+1).'\]/';
        $_img_src = $list[$i]['wr_'.($j+2)] ? $list[$i]['wr_'.($j+2)] : '';
        $_img_tag = '<img src="'.$_img_src.'" class="thread_img" alt="">';
        $list_content = $_img_src ? preg_replace($img_pattern2, $_img_tag, $list_content) : $list_content;
    }
    $file_count = 0;
}

$list_content = convertYouTubeLinks($list_content);
$list_content = url_auto_link2($list_content);
$list_content = markup_content($list_content); // 텍스트 서식
$list_content = textggu_change($list_content); // 텍스트 꾸미기
$list_content = emote_ev($list_content); // 이모티콘 관련
$toggle_pattern = '/토글\[(.*?)\]\s*\n\s*\[(.*?)\]/s';
$toggle_replacement = '<details><summary>$1</summary>$2</details>';
$list_content= preg_replace($toggle_pattern, $toggle_replacement, $list_content);



// 좋아요 관련
$good_href = $board_skin_url.'/good.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;good=good';
$good_id = $member['mb_id']? $member['mb_id'] : $_SERVER['REMOTE_ADDR'];
$good_sql = sql_fetch("SELECT count(*) AS cnt FROM {$g5['board_good_table']} WHERE bo_table ='{$bo_table}' AND wr_id = '{$list[$i]['wr_id']}' AND mb_id = '{$good_id}'");
?>
<!-- ******** 타래 본문 ******** -->
<div class="thread_main thread_main<?=$list[$i]['wr_id'];?><?=($list[$i]['wr_1']) ? ' spoiler' : '';?>">
<? if ($file_count > 0) { ?>
    <div class="img_box_wrap img_box_<?=$file_count;?>">
    <?
        for($j=0; $j<$file_count; $j++) {
            $file_src = $list[$i]['file'][$j]['path'].'/'.$list[$i]['file'][$j]['file'];

            $file_src = ($j == $list[$i]['file']['count']) ? $list[$i]['wr_2'] : $file_src;
            $file_src = ($j == $list[$i]['file']['count']+1) ? $list[$i]['wr_3'] : $file_src;
            $file_src = ($j == $list[$i]['file']['count']+2) ? $list[$i]['wr_4'] : $file_src;
            $file_src = ($j == $list[$i]['file']['count']+3) ? $list[$i]['wr_5'] : $file_src;
    ?>
    <div class="img_box">
        <img src="<?=$file_src; ?>" class="thread_img" alt="">
    </div>
    <? } ?>
    </div>
<?} ?>
<? if($list_content) { ?>
    <div class="thread_content"><?=nl2br($list_content); ?></div>
<? } ?>
<? if($_like_use == 'y') {?>
<div class="wr_good<?=($good_sql['cnt']>0) ? ' is_good' : '';?>">
    <a href="javascript:void(0)" class="bo_v_good" data-wr_id="<?=$list[$i]['wr_id']?>"><i class="fa-solid fa-heart" aria-hidden="true"></i><span class="sound_only">좋아요</span></a>
    <span class="wr_good_cnt"><?=$list[$i]['wr_good'] ? number_format($list[$i]['wr_good']) : ''; ?></span>      
</div>
<? } ?>

</div>
<!-- ******** 타래 본문 ******** -->
<? if ($list[$i]['wr_1']) { ?>
    <div class="spoiler_sec spoiler_sec<?=$list[$i]['wr_id'];?>">
        <p><i class="fa-solid fa-circle-exclamation"></i> 스포일러 주의</p>
        <button type="button" onclick="spoilerThreadShow(<?=$list[$i]['wr_id'];?>);">확인</button>
    </div>
<? } ?>
