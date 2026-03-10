<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function markup_content($str)
{

	$source[] = "/(?<!\*)\|\|(?![\s*])(.*?)(?<![\s*])\|\|(?!\*)/s";
	$target[] = "<a class='blur-txt' title='스포일러' alt='스포일러'>$1</a>";
	$source[] = "/(?<!\*)\*\*(?![\s*])(.*?)(?<![\s*])\*\*(?!\*)/s";
	$target[] = "<strong>$1</strong>";
	$source[] = "/(?<!\*)\*(?![\s*])(.*?)(?<![\s*])\*(?!\*)/s";
	$target[] = "<span class='italictext'>$1</span>";
	$source[] = "/(?<!\*)\*\*\*(?![\s*])(.*?)(?<![\s*])\*\*\*(?!\*)/s";
	$target[] = "<span class='italictext'><strong>$1</strong></span>";
	$source[] = "/(?<!\*)\`\`(?![\s*])(.*?)(?<![\s*])\`\`(?!\*)/s";
	$target[] = "<span class='txt-box'>$1</span>";

return preg_replace($source, $target, $str);
}

function textggu_change($str)
{
	$str = preg_replace('`제목1\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title1">$1</span>', $str);
    $str = preg_replace('`제목2\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title2">$1</span>', $str);
    $str = preg_replace('`제목3\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title3">$1</span>', $str);
    $str = preg_replace('`제목4\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title4">$1</span>', $str);
    $str = preg_replace('`제목5\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title5">$1</span>', $str);
    $str = preg_replace('`제목6\[(?![\s*])(.*?)(?<![\s*])\]-(?![\s*])(.*?)(?<![\s*])-`', '<span class="textggu--title6" data-text="$2">$1</span>', $str);
    $str = preg_replace('`제목7\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--title7">$1</span>', $str);

    $str = preg_replace('`소제1\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--sub1">$1</span>', $str);
    $str = preg_replace('`소제2\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--sub2">$1</span>', $str);
    $str = preg_replace('`소제3\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--sub3">$1</span>', $str);
    $str = preg_replace('`소제4\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--sub4">$1</span>', $str);
    $str = preg_replace('`소제5\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--sub5">$1</span>', $str);

    $str = preg_replace('`기타1\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc1">$1</span>', $str);
    $str = preg_replace('`기타2\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc2">$1</span>', $str);
    $str = preg_replace('`기타3\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc3">$1</span>', $str);
    $str = preg_replace('`기타4\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc4">$1</span>', $str);
    $str = preg_replace('`기타5\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc5">$1</span>', $str);
    $str = preg_replace('`기타6\[(?![\s*])(.*?)(?<![\s*])\]`', '<span class="textggu--etc6">$1</span>', $str);

	return $str;
}

function convertYouTubeLinks($content) {
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/i';
    $replacement = '<div style="position:relative; padding-top:56.25%;"><iframe style="position:absolute;top:0;left:0;width:100%;height:100%;" src="https://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div>';
    return preg_replace($pattern, $replacement, $content);
}

function url_auto_link2($str)
{
	global $g5;
	global $config;

	$str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
	$str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"{$config['cf_link_target']}\">\\2</A>", $str);
	$str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"{$config['cf_link_target']}\">\\2</A>", $str);
	$str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\">\\0</a>", $str);
	$str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

	return $str;
}
?>