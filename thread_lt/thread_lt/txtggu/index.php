<?
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once ('../color.config.php');
add_stylesheet('<link rel="stylesheet" href="textggu.css">', 1);
?>
<link rel="stylesheet" href="style.css" type="text/css">

<div id="main_body">
<div class="theme-box"> 
<ul class="txt_list">
	<div class="txt-info"> 

	<div class="list-1">
	<span class="txt-ti">◇치환자</span> 
	<span class="pre">*치환자 옆에 띄어쓰기 없이!</span>
	<div class="list-2">
	• <span class="italictext">기울임</span> : *<span class="pre">기울임</span>*<br>
	• <span style="font-weight: bold;">굵게</span> : **<span class="pre">굵게</span>**<br>
	• <span class="italictext" style="font-weight: bold;">내용</span> : ***<span class="pre">기울임+굵게</span>***<br>
	• <span class="txt-box">박스</span> : ``<span class="pre">내용</span>``<br>
	• <a class="blur-txt">블러</a> : ||<span class="pre">내용</span>||
	</div></div>

	<div class="list-1">
	<span class="txt-ti">◇ 텍스트 서식: 제목</span>
	<span class="pre">*내용에 쓰고 싶은 말 적기</span>
	<div class="list-2">
	• 제목1[<span class="pre">내용</span>] : <span class="textggu--title1">제목1</span><br>
	• 제목2[<span class="pre">내용</span>] : <span class="textggu--title2">제목2</span><br>
	• 제목3[<span class="pre">내용</span>] : <span class="textggu--title3">제목3</span><br>
	• 제목4[<span class="pre">내용</span>] : <span class="textggu--title4">제목4</span><br>
	• 제목5[<span class="pre">내용</span>] : <span class="textggu--title5">제목5</span><br>
	• 제목6[<span class="pre">제목</span>]-<span class="pre">부제</span>- : <span class="textggu--title6" data-text="부제는 이렇게">제목6</span><br>
	• 제목7[<span class="pre">내용</span>] : <span class="textggu--title7">제목7</span>
	</div></div>

	<div class="list-1">
	<span class="txt-ti">◇ 텍스트 서식: 소제</span>
	<div class="list-2">
	• 소제1[<span class="pre">내용</span>] : <span class="textggu--sub1">소제1</span><br>
	• 소제2[<span class="pre">내용</span>] : <span class="textggu--sub2">소제2</span><br>
	• 소제3[<span class="pre">내용</span>] : <span class="textggu--sub3">소제3</span><br>
	• 소제4[<span class="pre">내용</span>] : <span class="textggu--sub4">소제4</span><br>
	• 소제5[<span class="pre">내용</span>] : <span class="textggu--sub5">소제5</span>
	</div></div>

	<div class="list-1-last">
	<span class="txt-ti">◇ 텍스트 서식: 기타</span>
	<div class="list-2">
	• 기타1[<span class="pre">내용</span>] : <span class="textggu--etc1">기타1</span><br>
	• 기타2[<span class="pre">내용</span>] : <span class="textggu--etc2">기타2</span><br>
	• 기타3[<span class="pre">내용</span>] : <span class="textggu--etc3">기타3</span><br>
	• 기타4[<span class="pre">내용</span>] : <span class="textggu--etc4">기타4</span><br>
	• 기타5[<span class="pre">내용</span>] : <span class="textggu--etc5">기타5</span><br>
	• 기타6[<span class="pre">내용</span>] : <span class="textggu--etc6">기타6</span>
	</div></div>


	</div>
	</ul>
</div>
</div>
<script>
$('a.blur-txt').on('click',function(){ 
    $(this).toggleClass('none-blur');
});
</script>

<?
include_once(G5_PATH.'/tail.sub.php');
?>