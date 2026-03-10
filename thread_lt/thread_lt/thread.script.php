<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div class="thread_img_full">
    <button type="button" class="thread_img_close"><i class="fa-solid fa-circle-xmark"></i></button>
    <div class="thread_img_wrap"></div>
</div>
<script>
$(document).on('click', '.thread_btn button', function(e){
    $.ajax({
        url: '<?=$board_skin_url; ?>/write.skin.php',
        type: 'POST',
        data: {
            wr_id: '',
            bo_table : '<?=$bo_table;?>'
        },
        success: function(data) {
            $('.thread_comment').html('');
            $('#thread_write').html(data);
            if ($('.thread_btn').hasClass('on')) {
                $('.thread_btn').removeClass('on');
                $('.thread_write').stop().slideUp();
            } else {
                $('.thread_btn').addClass('on');
                $('.thread_write').stop().slideDown();
            }
            
        }
    });    
    
});

// 스포일러 확인
function spoilerThreadShow(wr_id){
    $('.thread_main'+wr_id).removeClass('spoiler');
    $('.spoiler_sec'+wr_id).remove();
}

// 보호글 확인
function showThreadContent(form){
    var formData = new FormData(form);
    var wr_id = formData.get('wr_id');
    $.ajax({
        url: '<?=$board_skin_url?>/list.skin.con.php',
        data: formData,
        processData: false,
		contentType: false,
		type: 'POST',
        success: function(data) {
            $('#thread'+wr_id).html(data);
        }
    });
    return false;
}

// 타래 작성
function writeThreadComment(wr_id,ca_name=''){
    $.ajax({
        url: '<?=$board_skin_url; ?>/write.skin.php',
        type: 'POST',
        data: {
            wr_id: wr_id,
            bo_table : '<?=$bo_table;?>',
            is_comment : 'y',
            is_view : '<?=$is_view?>',
            pa_ca_name : ca_name
        },
        success: function(data) {            
            $('.thread_btn').removeClass('on');
            $('.thread_write').hide();
            $('.thread_write').html('');
            $('.thread_comment').html('');
            $('#thread_comment'+wr_id).html(data);
        }
    });
}

// 이미지 확대
$(document).on('click', '.thread_img', function(e){
    var img_tag = $(this).prop('outerHTML'); 
    if ($(this).parents('.thread_main').hasClass('spoiler')){

    } else {
        $('.thread_img_full').css('display','flex');
        $('.thread_img_full .thread_img_wrap').html(img_tag);
    }
});

$(document).on('click', '.thread_img_close', function(e){
    $('.thread_img_full').css('display','none');
    $('.thread_img_full .thread_img_wrap').html('');
});

$(document).mouseup(function (e){
	var LayerPopup = $(".thread_img_wrap");
	if(LayerPopup.has(e.target).length === 0){
		$('.thread_img_full').css('display','none');
        $('.thread_img_full .thread_img_wrap').html('');
	}
});

$(document).on('click', 'a.blur-txt', function(){ 
    $(this).toggleClass('none-blur');
});

// 게시글 view 주소 복사
$(document).on('click', '.view_link', function(){
    var view_url = $(this).attr('data-url');
    $('#view_copy').val(view_url);
    $('#view_copy').select();

    var banner_url = document.execCommand('copy');
    alert('링크가 복사되었습니다.');
});

<?
if ($_like_config == 'all') {
    $like_id = $member['mb_id'] ? $member['mb_id'] : $_SERVER['REMOTE_ADDR'];
} else {
    $like_id = $member['mb_id'];
}
?>

// 추천, 비추천
$(document).on('click', '.bo_v_good', function(){
    var wr_id = $(this).attr('data-wr_id')
    excute_good(wr_id, '<?=$like_id; ?>');
    return false;
});

function excute_good(wr_id, mb_id)
{
    $.ajax({
        url: '<?=$board_skin_url; ?>/good.php',
        type: 'POST',
        data: {
            wr_id: wr_id,
            bo_table : '<?=$bo_table;?>',
            mb_id : mb_id
        },
        success: function(data) {
            if (data == 'y') {
                alert('좋아요 하셨습니다.');
                $('.bo_v_good[data-wr_id="'+wr_id+'"]').parents('.wr_good').addClass('is_good');
                var good_cnt = $('.bo_v_good[data-wr_id="'+wr_id+'"] + .wr_good_cnt').text();
                    good_cnt = parseInt(good_cnt);
                    if (!good_cnt) { good_cnt = 0; }
                $('.bo_v_good[data-wr_id="'+wr_id+'"] + .wr_good_cnt').text(good_cnt+1);
            } else {
                alert(data);
            }            
        }
    });
}
</script>