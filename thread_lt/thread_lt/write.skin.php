<?php
include_once('./_common.php');
include_once ($board_skin_path.'/setting/user.config.php');
include_once ($board_skin_path.'/color.config.php');
$action_url = https_url(G5_BBS_DIR)."/write_update.php";
$is_notice = true;
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/txtggu/textggu.css">', 0);

$prefiles = array();
if ($w == 'u') {
    if (is_array($file) && $file['count'] > 0) {
        for ($i = 0; $i < $file['count']; $i++) {
            $prefiles[] = array(
                'name' => $file[$i]['source'],
                'url'  => $file[$i]['path'].'/'.$file[$i]['file'],
                'idx'  => $i
            );
        }
    }
}
$wr_num = $write['wr_num'];
if ($is_comment && $w != 'u') {
    $write = array();
}

$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $ca_name = "";
    if (isset($write['ca_name']))
        $ca_name = $write['ca_name'];
    $category_option = get_category_option($bo_table, $ca_name);
    $is_category = true;
}

$pa_ca_name = $write['ca_name'] ? $write['ca_name'] : $pa_ca_name;
?>
<script type="text/javascript" src="<?=$board_skin_url;?>/js/paste.js"></script>

<?//=print_r2($file['count']);?>
<section id="bo_w" <?if($board['bo_table_width']>0){?>style="max-width:<?=$board['bo_table_width']?><?=$board['bo_table_width']>100 ? "px":"%"?>;margin:0 auto;"<?}?>>
	<!-- 게시물 작성/수정 시작 { -->
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
    <? if ($is_comment) { ?>
        <input type="hidden" name="pa_wr_id" value="<?php echo $write['wr_parent'] ? $write['wr_parent'] : $wr_id ?>">
        <input type="hidden" name="pa_wr_num" value="<?=$wr_num;?>">
        <input type="hidden" name="wr_is_comment" value="1">
        <input type="hidden" name="ca_name" value="<?=$pa_ca_name?>">
    <? } ?>
    <? if ($is_view) { ?>
        <input type="hidden" name="is_view" value="1">
    <? } ?>
	<?php
	$option = '';
	$option_hidden = '';
	if ($is_notice || $is_html || $is_secret || $is_mail) {
		$option = '';
		if ($is_notice && !$is_comment) {
			$option .= "\n".'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'."\n".'<label for="notice">공지</label>';
		}

		if ($is_secret) {
			if ($is_admin || $is_secret==1) {
				if($secret_checked)$sec_select="selected";
				$sec .='<option value="secret" '.$sec_select.'>비밀글</option>';
			} else {
				$option_hidden .= '<input type="hidden" name="secret" value="secret">';
			}
		}

	}

	echo $option_hidden;
		if($write['wr_secret']=='1') $mem_select="selected";
		if($write['wr_protect']!='') $pro_select="selected";
		if($is_member) {$sec .='<option value="protect" '.$pro_select.'>보호글</option>';
		$sec .='<option value="member" '.$mem_select.'>멤버공개</option>';}
	?>

	<div class="board-write theme-box">
	<?php if ($is_category && !$is_comment) { ?>
	<dl>
		<dt>분류</dt>
		<dd><nav id="write_category">
			<select name="ca_name" id="ca_name" required class="required" >
				<option value="">선택하세요</option>
				<?php echo $category_option ?>
			</select> 
		</nav>
		</dd>
	</dl>
	<?}?>
	<dl>
		<dt>옵션</dt>
		<dd>
		<?if($is_secret!=2||$is_admin){?>
		<select name="set_secret" id="set_secret">
			<option value="">전체공개</option>
			<?=$sec?>
		</select>
        &nbsp;
		<?}?>        
		<?php echo $option ?>
        <? if ($option) { ?>&nbsp;<?} ?>
        <input type="checkbox" id="wr_1" name="wr_1" value="1"<?=($write['wr_1']) ? ' checked' : '';?>> <label for="wr_1">스포일러</label>
        &nbsp;
        <a href="javascript:void(0)" onclick="window.open('<?php echo $board_skin_url ?>/txtggu/index.php','txtggu','width=500, height=800')" class="ui-btn new_win"><i class="fa-solid fa-code"></i></a>
        <a href="javascript:void(0)" onclick="window.open('<?php echo $board_skin_url ?>/emoticon_list.php','txtggu','width=500, height=800')" class="ui-btn new_win">이모티콘</a>  
    </dd>
	</dl>
	<dl id="set_protect" style="display:<?=$w=='u' && $pro_select ? 'block':'none'?>;">
		<dt><label for="wr_protect">보호글 암호</label></dt>
		<dd><input type="text" name="wr_protect" id="wr_protect" value="<?=$write['wr_protect']?>" maxlength="20"></dd>
	</dl>
    <? if ($_list_style == 'list' && !$is_comment) { ?>
    <dl>
        <dt>썸네일</dt>
        <dd><input type="text" name="wr_10" id="wr_10" value="<?=$write['wr_10']?>" class="frm_input full" size="50" maxlength="255" placeholder="외부링크 삽입"></dd>
    </dl>
    <? } ?>
	<dl>
		<dt>제목</dt>
		<dd>
            <? $subject = ($subject == 'noSubject') ? '' : $subject; ?>
            <input type="hidden" name="wr_subject" value="noSubject">
            <input type="text" name="wr_subject2" value="<?php echo $subject ?>" id="wr_subject" class="frm_input full" size="50" maxlength="255" placeholder="필수 입력 X">
        </dd>
	</dl>
		<? if($board['bo_1']) { ?>
		<div class="write-notice">
			<?=$board['bo_1']?>
		</div>
		<? } ?>
	<dl>
		<dt>
            내용<br>
            <label id="upload-btn" class="txt-point"><i class="fa-regular fa-image"></i></label>
        </dt>
		<dd id="dropzone">
            <div class="wr_content">
                <textarea id="wr_content" name="wr_content" style="width:100%;min-height:70px" oninput="autoResize(this)"><?=$write['wr_content']?></textarea>                
            </div>
            <div id="preview">                
            </div>
            <span class="frm_info"><i class="fa-solid fa-circle-exclamation"></i> 이미지 업로드는 복사+붙여넣기 및 드래그 앤 드롭 가능. 내용 작성 시 [파일1], [파일2] 등으로 작성하면 위치 지정 가능.</span>
            <span class="frm_info"><i class="fa-solid fa-circle-exclamation"></i> 토글[토글제목]<br>[토글내용] 으로 작성 시 토글 영역으로 만들 수 있습니다.</span>
            <input type="file" id="image-input" name="bf_file[]" multiple accept="image/*" style="display: none;">
        </dd>
	</dl>
    <dl>
		<dt>외부링크</dt>
		<dd>
            <input type="text" name="wr_2" id="wr_2" value="<?=$write['wr_2'];?>" class="frm_input">
            <input type="text" name="wr_3" id="wr_3" value="<?=$write['wr_3'];?>" class="frm_input">
            <input type="text" name="wr_4" id="wr_4" value="<?=$write['wr_4'];?>" class="frm_input">
            <input type="text" name="wr_5" id="wr_5" value="<?=$write['wr_5'];?>" class="frm_input">
            <div>
                <span class="frm_info"><i class="fa-solid fa-circle-exclamation"></i> 첨부파일과 혼용이 가능합니다. [이미지1], [이미지2] 등으로 사용 가능</span>
            </div>
        </dd>
	</dl>
<?if(!$is_member){?>
	<dl>
		<dt></dt>
		<dd class="txt-right">
        <label for="wr_name">이름<strong class="sound_only">필수</strong></label>
        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" >
		&nbsp;&nbsp;
        <label for="wr_password">비밀번호<strong class="sound_only">필수</strong></label>
        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" >
	</dd>
	</dl>
<?}?>
    <br>
        <div class="btn_confirm txt-center">
            <input type="submit" value="작성" id="btn_submit" accesskey="s" class="btn_submit ui-btn point">
            <? if ($w == 'u') { ?>
            <a href="./board.php?bo_table=<?php echo $bo_table ?>&wr_id=<?=$write['wr_parent']?>" class="btn_cancel ui-btn">취소</a>
            <? } ?>
        </div>
	</div>
	</form>
</section>
<!-- } 게시물 작성/수정 끝 -->

<? if ($w == 'u') { ?>
<? include_once($board_skin_path.'/write.script.php'); // 글 작성시 이미지 관련 ?>
<? } ?>