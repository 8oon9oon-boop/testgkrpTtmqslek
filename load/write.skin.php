<?php if (!defined('_GNUBOARD_')) exit; ?>

<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="wr_subject" value="<?php echo $member['mb_nick'] ? $member['mb_nick'] : 'GUEST'; ?>">

    <footer class="messenger-footer">
        <div class="input-toolbar">
            <div class="toolbar-left">
                <span onclick="$('#wr_file').click();">이미지</span>
                <input type="file" id="wr_file" name="bf_file[]" style="display:none;">

                <input type="checkbox" id="secret_chk" name="secret" value="secret" <?php echo strstr($write['wr_option'], 'secret') ? 'checked' : ''; ?> style="display:none;">
                <span id="label_secret" onclick="toggle_option('secret')" style="cursor:pointer;">비밀글</span>

                <input type="checkbox" id="html_chk" name="html" value="html1" style="display:none;">
                <span id="label_html" onclick="toggle_option('html')" style="cursor:pointer;">HTML</span>
            </div>
            <div class="toolbar-right">
                <span onclick="document.getElementById('btn_submit').click();" style="color:#000;">등록</span>
                <span onclick="location.href='?bo_table=<?php echo $bo_table ?>';" style="color:#999;">취소</span>
            </div>
        </div>

        <div class="input-area">
            <textarea name="wr_content" id="wr_content" required placeholder="내용을 입력해 주세요"><?php echo get_text($write['wr_content'], 0) ?></textarea>
        </div>
        <button type="submit" id="btn_submit" style="display:none;">전송</button>
    </footer>
</form>

<script>
function toggle_option(type) {
    var chk = $('#' + type + '_chk');
    var label = $('#label_' + type);
    chk.prop('checked', !chk.prop('checked'));
    label.css('color', chk.prop('checked') ? '#17a2b8' : '#000'); // 체크 시 하늘색으로 변경
}

function fwrite_submit(f) {
    if (f.wr_content.value == "") { alert("내용을 입력해 주세요."); return false; }
    document.getElementById("btn_submit").disabled = "disabled";
    return true;
}
</script>