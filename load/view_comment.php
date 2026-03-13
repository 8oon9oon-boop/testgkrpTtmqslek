<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


$comment = array();

$is_comment_write = false;
if ($member['mb_level'] >= $board['bo_comment_level'])
	$is_comment_write = true;

// 코멘트 출력
//$sql = " select * from {$write_table} where wr_parent = '{$wr_id}' and wr_is_comment = 1 order by wr_comment desc, wr_comment_reply ";
$sql = " select * from $write_table where wr_parent = '{$list_item['wr_id']}' and wr_content != '' order by wr_is_comment, wr_comment, wr_comment_reply ";
$result = sql_query($sql);
for ($c_i=0; $c_row=sql_fetch_array($result); $c_i++)
{ 
	$comment[$c_i] = $c_row;
	$comment[$c_i]['c_view'] = false;
	//$comment[$c_i]['name'] = get_sideview($c_row['mb_id'], cut_str($c_row['wr_name'], 20, ''), $c_row['wr_email'], $c_row['wr_homepage']);

	$tmp_name = get_text(cut_str($c_row['wr_name'], $config['cf_cut_name'])); // 설정된 자리수 만큼만 이름 출력
	if ($board['bo_use_sideview'])
		$comment[$c_i]['name'] = get_sideview($c_row['mb_id'], $tmp_name, $c_row['wr_email'], $c_row['wr_homepage']);
	else
		$comment[$c_i]['name'] = '<span class="'.($c_row['mb_id']?'member':'guest').'">'.$tmp_name.'</span>';


	$html = 0;
	if (strstr($comment[$c_i]['wr_option'], 'html1'))
	$html = 1;
	else if (strstr($comment[$c_i]['wr_option'], 'html2'))
	$html = 2;  
	// 공백없이 연속 입력한 문자 자르기 (way 보드 참고. way.co.kr)
	//$comment[$c_i]['content'] = eregi_replace("[^ \n<>]{130}", "\\0\n", $c_row['wr_content']);

	$comment[$c_i]['content'] = $comment[$c_i]['content1']= '비밀글 입니다.';

	//@230715
	if (!strstr($c_row['wr_option'], 'secret' ) && !$c_row['wr_2'] ||
		$is_admin ||
		($write['mb_id']==$member['mb_id'] && $member['mb_id']) ||
		($c_row['mb_id']==$member['mb_id'] && $member['mb_id'])) {
		$comment[$c_i]['c_view'] = true;
		$comment[$c_i]['content1'] = $c_row['wr_content'];
		$comment[$c_i]['content'] = conv_content($c_row['wr_content'], $html, 'wr_content');
		$comment[$c_i]['content'] = search_font($stx, $comment[$c_i]['content']);
	} else {
		$p_ss_name=array();//@230715
		if($comment[$c_i]['wr_comment_reply']) { 
			$len=strlen($comment[$c_i]['wr_comment_reply']);
			$parent_list=sql_query("select wr_id from {$write_table} where wr_comment='{$comment[$c_i]['wr_comment']}' and wr_parent='{$comment[$c_i]['wr_parent']}' and wr_comment_reply!='{$comment[$c_i]['wr_comment_reply']}' and length(wr_comment_reply)<{$len}");
			for($t=0;$p_row=sql_fetch_array($parent_list);$t++){
				$p_ss_name[$t] = 'ss_secret_comment_'.$bo_table.'_'.$p_row['wr_id'];
			}
		}
		$ss_name = 'ss_secret_comment_'.$bo_table.'_'.$comment[$c_i]['wr_id'];

		if(!get_session($ss_name)){
			$comment[$c_i]['c_view'] = false;
			if($comment[$c_i]['mb_id']!='') { //@250403
				if(strstr($c_row['wr_option'], 'secret' ))
				$comment[$c_i]['content'] = '비공개 댓글입니다.';
				else if ($comment[$c_i]['wr_2']){
					if(!$is_member) $comment[$c_i]['content'] = '멤버공개 댓글입니다.';
					else{ 
						$comment[$c_i]['c_view'] = true;
						$comment[$c_i]['content'] = conv_content($c_row['wr_content'], $html, 'wr_content');
						$comment[$c_i]['content'] = search_font($stx, $comment[$c_i]['content']);
					}
				}
			}
			else 
				$comment[$c_i]['content'] = '<a href="./password.php?w=sc&amp;bo_table='.$bo_table.'&amp;wr_id='.$comment[$c_i]['wr_id'].$qstr.'" class="s_cmt">댓글내용 확인</a>';
				
			$s_count=false;
			if($is_member){  //@230715
				$p_com=sql_fetch("select wr_id from {$write_table} where wr_comment='{$comment[$c_i]['wr_comment']}' and wr_parent='{$comment[$c_i]['wr_parent']}' and wr_comment_reply!='{$comment[$c_i]['wr_comment_reply']}' and length(wr_comment_reply)<{$len} and mb_id='{$member['mb_id']}'");
				if($p_com['wr_id']) $s_count=true;
			} else if(count($p_ss_name)>0){
				for($p_c=0;$p_c<count($p_ss_name);$p_c++){
					if(get_session($p_ss_name[$p_c]))
					$s_count=true;
				}
			}
			if($s_count){
				$comment[$c_i]['c_view'] = true;
				$comment[$c_i]['content'] = conv_content($c_row['wr_content'], $html, 'wr_content');
				$comment[$c_i]['content'] = search_font($stx, $comment[$c_i]['content']);
			}
		}
		else {
			if($comment[$c_i]['wr_2']){ //@230715 
				if($is_member) $comment[$c_i]['c_view'] = true;
				else {
					$comment[$c_i]['c_view'] = false;
					$comment[$c_i]['content'] = '멤버공개 댓글입니다.';
				}
			}else{
			$comment[$c_i]['c_view'] = true;
			$comment[$c_i]['content'] = conv_content($c_row['wr_content'], $html, 'wr_content');
			$comment[$c_i]['content'] = search_font($stx, $comment[$c_i]['content']);
			}
		}
	}

	$comment[$c_i]['datetime'] = substr($c_row['wr_datetime'],2,14);

	// 관리자가 아니라면 중간 IP 주소를 감춘후 보여줍니다.
	$comment[$c_i]['ip'] = $c_row['wr_ip'];
	if (!$is_admin)
		$comment[$c_i]['ip'] = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $c_row['wr_ip']);

	$comment[$c_i]['is_reply'] = false;
	$comment[$c_i]['is_edit'] = false;
	$comment[$c_i]['is_del']  = false;
	if ($is_comment_write || $is_admin)
	{
		$token = '';

		if ($member['mb_id'])
		{
			if ($c_row['mb_id'] == $member['mb_id'] || $is_admin)
			{
				set_session('ss_delete_comment_'.$c_row['wr_id'].'_token', $token = uniqid(time()));
				$comment[$c_i]['del_link']  = './delete_comment.php?bo_table='.$bo_table.'&amp;comment_id='.$c_row['wr_id'].'&amp;token='.$token.'&amp;page='.$page.$qstr;
				$comment[$c_i]['is_edit']   = true;
				$comment[$c_i]['is_del']    = true;
			}
		}
		else
		{
			if (!$c_row['mb_id']) {
				$comment[$c_i]['del_link'] = './password.php?w=x&amp;bo_table='.$bo_table.'&amp;comment_id='.$c_row['wr_id'].'&amp;page='.$page.$qstr;
				$comment[$c_i]['is_del']   = true;
			}
		}

		if (strlen($c_row['wr_comment_reply']) < 5)
			$comment[$c_i]['is_reply'] = true;
	}

	// 05.05.22
	// 답변있는 코멘트는 수정, 삭제 불가
	if ($i > 0 && !$is_admin)
	{
		if ($c_row['wr_comment_reply'])
		{
			$tmp_comment_reply = substr($c_row['wr_comment_reply'], 0, strlen($c_row['wr_comment_reply']) - 1);
			if ($tmp_comment_reply == $comment[$c_i-1]['wr_comment_reply'])
			{
				$comment[$c_i-1]['is_edit'] = false;
				$comment[$c_i-1]['is_del'] = false;
			}
		}
	}
}

//  코멘트수 제한 설정값
if ($is_admin)
{
	$comment_min = $comment_max = 0;
}
else
{
	$comment_min = (int)$board['bo_comment_min'];
	$comment_max = (int)$board['bo_comment_max'];
}

//array_unshift($comment, $list_item);
include($board_skin_path.'/view_comment.skin.php');

?>