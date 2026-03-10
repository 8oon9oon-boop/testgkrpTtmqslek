<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<script>
let prefiles = <?=$prefiles ? json_encode($prefiles, JSON_UNESCAPED_UNICODE) : '[]' ?>;

$(document).ready(function() {
    if (prefiles && prefiles.length > 0) {
        handleFiles(prefiles);
    }
});

let imageFiles = [];
const maxImages = 4;
    
$(document).ready(function() {
    // 이미지 추가 버튼 클릭 시 file input 열기
    $(document).on('click', '#upload-btn', function(){
        $('#image-input').click();
    });

    // file input 변경 시 선택한 파일 처리
    $(document).on('change', '#image-input', function(event) {
        let selectedFiles = Array.from(event.target.files);
        if (imageFiles.length + selectedFiles.length > maxImages) {
            alert('최대 4개의 이미지만 업로드할 수 있습니다.');
            event.target.value = "";
            return;
        }
        handleFiles(selectedFiles);
        event.target.value = ""; // 파일 선택 후 초기화
        console.log(selectedFiles);
    });
  
    // Ctrl+V 붙여넣기로 이미지 추가
    $(document).on('paste', function(event) {
        let items = (event.clipboardData || event.originalEvent.clipboardData).items;
        let files = [];
        for (let item of items) {
            if (item.type.indexOf('image') !== -1) {
            files.push(item.getAsFile());
            }
        }
        if (imageFiles.length + files.length > maxImages) {
            alert('최대 4개의 이미지만 업로드할 수 있습니다.');
            return;
        }
        handleFiles(files);
    });
      
    // 드래그 앤 드롭 이벤트 설정
    $(document).on('dragover', '#dropzone', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).addClass('dragover');
    });
    
    $(document).on('dragleave', '#dropzone', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass('dragover');
    });
    
    $(document).on('drop', '#dropzone', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass('dragover');
        let droppedFiles = Array.from(event.originalEvent.dataTransfer.files);
        if (imageFiles.length + droppedFiles.length > maxImages) {
            alert('최대 4개의 이미지만 업로드할 수 있습니다.');
            return;
        }
        handleFiles(droppedFiles);
    });
      
    // 기존 file input 변경
    $(document).on('submit', '#fwrite', function(event) {        
        if (typeof DataTransfer !== 'undefined') {
            let dt = new DataTransfer();
            imageFiles.forEach(file => {
            dt.items.add(file);
            });
            // 새 file input 생성
            let newInput = $('<input type="file" name="bf_file[]" multiple accept="image/*" style="display: none;">');
            newInput[0].files = dt.files;
            // 기존 file input과 교체
            $('#image-input').replaceWith(newInput);
        } else {
            console.warn('DataTransfer가 이 브라우저에서 지원되지 않습니다.');
        }
    });
});
let uploadedFiles = [];
// 파일 미리보기 표시
function handleFiles(files) {
    let fetchPromises = [];

    files.forEach(file => {
        if (typeof file === "object" && file.url) {
            // 기존 업로드된 파일을 Blob으로 변환
            uploadedFiles.push({ name: file.name, url: file.url, idx: file.idx });

            let fetchPromise = fetch(file.url)
                .then(response => response.blob())
                .then(blob => {
                    let newFile = new File([blob], file.name, { type: blob.type });
                    return { file: newFile, url: file.url, idx: file.idx };
                })
                .catch(error => {
                    console.error("파일 불러오기 실패:", error);
                    return null;
                });

            fetchPromises.push(fetchPromise);
        } else {
            // 새로 추가한 파일 (바로 처리)
            fetchPromises.push(Promise.resolve({ file, url: null }));
        }
    });

    // 모든 fetch가 완료된 후 순서대로 처리
    Promise.all(fetchPromises).then(results => {
        results.forEach(result => {
            if (result) {
                let previewUrl = result.url ? result.url : URL.createObjectURL(result.file);
                addFileToPreview(result.file, previewUrl, result.url, result.idx);
            }
        });
    });
}

// 미리보기 추가 함수
function addFileToPreview(file, previewUrl, fileUrl = null, idx=null) {
    if (imageFiles.length >= maxImages) return;

    imageFiles.push(file);

    let imgContainer = $('<div class="preview-container"></div>');
    let img = $(`<img src="${previewUrl}" class="preview-img">`);
    let removeBtn = $('<button class="remove-btn">X</button>');

    removeBtn.click(function() {
        let index = imageFiles.indexOf(file);
        if (index > -1) {
            imageFiles.splice(index, 1);
        }

        if(confirm('삭제하시겠습니까?')) {
            // 기존 업로드된 파일이면 삭제
            if (fileUrl) {
                removeUploadedFile(fileUrl, idx);
            }
            $(this).parent().remove();
        } else {            
            return false;
        }
        
    });

    imgContainer.append(img).append(removeBtn);
    $('#preview').append(imgContainer);
}

// 기존 업로드된 파일 삭제
function removeUploadedFile(fileUrl, idx) {
    $.ajax({
        url: '<?=$board_skin_url; ?>/write.file.delete.php',
        type: 'POST',
        data: {
            fileUrl: fileUrl,
            wr_id: '<?=$write['wr_id'];?>',
            bo_table : '<?=$bo_table;?>',
            idx : idx
        },
        success: function(response) {
            
        }
    });
}


function fwrite_submit(f)
{

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
            ed_wr_content.returnFalse();
        else
            f.wr_content.focus();
        return false;
    }

    if (document.getElementById("char_count")) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(check_byte("wr_content", "char_count"));
            if (char_min > 0 && char_min > cnt) {
                alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            }
            else if (char_max > 0 && char_max < cnt) {
                alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }


    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}	
$(document).on('change', '#set_secret', function() {
    var selection = $(this).val();
    if(selection=='protect') $('#set_protect').css('display','block');
    else {$('#set_protect').css('display','none'); $('#wr_protect').val('');}
}); 

function autoResize(textarea) {
    textarea.style.height = 'auto' // 높이를 자동으로 초기화
    textarea.style.height = textarea.scrollHeight + 'px' // 스크롤 높이에 맞게 높이 설정
}
</script>