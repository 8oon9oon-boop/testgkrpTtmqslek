<?php if (!defined('_GNUBOARD_')) exit; ?>
<form name="fwrite" id="fwrite" action="<?=$action_url?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="uid" value="<?=get_uniqid()?>">
    <input type="hidden" name="w" value="<?=$w?>">
    <input type="hidden" name="bo_table" value="<?=$bo_table?>">
    <input type="hidden" name="wr_subject" value="<?=$member['mb_nick'] ? $member['mb_nick'] : 'GUEST'?>" /> 
    
    <div style="display:none;">
        <select name="wr_type"><option value="TEXT" selected>TEXT</option><option value="UPLOAD">UPLOAD</option></select>
        <input type="file" id="wr_file" name="bf_file[]" />
        <input type="checkbox" id="secret_chk" name="secret" value="secret">
        <input type="checkbox" id="html_chk" name="html" value="html1">
        <button type="submit" id="btn_submit">등록</button>
    </div>

    <footer class="messenger-footer">
        <div class="input-toolbar">
            <div class="toolbar-left">
                <span onclick="$('select[name=wr_type]').val('UPLOAD'); $('#wr_file').click();">이미지</span>
                <span><label for="secret_chk" style="cursor:pointer;">비밀글</label></span>
                <span><label for="html_chk" style="cursor:pointer;">HTML</label></span>
            </div>
            <div class="toolbar-right">
                <span onclick="$('#btn_submit').click();" style="color:#000;">등록</span>
                <span onclick="$('#wr_text').val('');" style="color:#999;">취소</span>
            </div>
        </div>
        <div class="input-area">
            <textarea name="wr_text" id="wr_text" placeholder="내용을 입력해 주세요"></textarea>
        </div>
    </footer>
</form>

<script>
function fwrite_submit(f) {
    if (f.wr_text.value == "" && f.wr_type.value == "TEXT") {
        alert("내용을 입력해 주세요.");
        f.wr_text.focus();
        return false;
    }
    document.getElementById("btn_submit").disabled = "disabled";
    return true;
}
</script>