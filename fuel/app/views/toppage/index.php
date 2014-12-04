<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("Top_page.css");
?>
</head>
<body>

<?php
echo '<p id="top_message">'.$message.'</p>';
echo Asset::img("Top_title.gif",array("id"=>"top_title","width"=>"750px"))."</br>";	

?>
<div id="side_bar">
		<?php echo Html::anchor('/rule',"ルール説明",array("id"=>"rule")); ?>
	<div id="user_info">
		<h2>ユーザー情報</h2>
		<?php
		 if(empty($user_name)){
		 	echo "会員登録　or ログインをしてください。";
		 	
		 }else{
		 	echo $user_name."さん　ようこそ</br>";
		 	echo "所持金:".$money."円</br>";
		 }
	 ?>
	</div>
	<div id="user_rank">
		<h2>お金持ちランキング</h2>
		<table>
		<?php
		$rank=1;
		foreach ($money_rank as $money_ranks) {
					
			echo '<tr><td>'.$rank.'位　'.$money_ranks['user_name']."</td><td>".$money_ranks['money'].'円</td></tr>';
			$rank++;
		}

		?>
		</table>
	</div>
	<div id="new_result">
		<h2>結果発表</h2>
<?php
	if(empty($group_answer_result_list)){
	
	}else{
			echo Html::anchor('groupanswerresult?target_question_num='.$group_answer_result_list[0]['question_num'], $group_answer_result_list[0]['question'])."<br>";	
			echo "1位".$group_answer_result_list[0]['username']."<br>";
			echo $group_answer_result_list[0]['answer']."<br>";
		if($group_answer_result_list[1]['username']!=""){
			echo "2位".$group_answer_result_list[1]['username']."<br>";
			echo $group_answer_result_list[1]['answer']."<br>";
		}
		if(isset($group_answer_result_list[2]['username'])){
			echo "3位".$group_answer_result_list[2]['username']."<br>";
			echo $group_answer_result_list[2]['answer']."<br>";
		}
		
	}
?>
	</div>
	<div id="past_result">
		<h2>過去のお題</h2>
<?php
	foreach ($past_group_answer as $past_group_answers) {
		echo $past_group_answers['id']."回".Html::anchor('groupanswerresult?target_question_num='.$past_group_answers['id'], $past_group_answers['question'])."<br>";	
	}
	echo Html::anchor('/pastquestionlist',"過去の結果一覧",array("id"=>"single_vote_anchor"));
?>
	</div>
	<div id="past_single_battle">
		<h2>過去のタイマンバトル</h2>
		<?php
		echo Html::anchor('/pastsinglebattle',"過去のタイマンバトル",array("id"=>"past_single_battke_anchor"));
		?>
	</div>
</div>
<div id="login_box">
<?php
	echo Form::open('confirmlogin ');
	echo "10秒で会員登録!→".Form::submit("login","login",array("class"=>"btn btn-primary"))."</br>";
	echo Form::close('confirmlogin');
?>
</div>
<?php

?>
<div id="contents">

<div>
<div class="question_title">
	<?php
echo Asset::img("group_question.gif",array("id"=>"group_question","width"=>"388px"));	

	if(isset($group_question)){
		foreach ($group_question as $group_questions) {
			echo Form::open('receiveanswer');
		?>
		<?php	echo '<p class="group_question_post_time">'.$group_questions['id']."回目 お題:".$group_questions['question'].'</p>'; ?>
	</div>
		<?php
		if(empty($user_name)){
			echo "投稿するにはログインする必要があります。";
		}else{
			echo Form::textarea('users_answer', '', array('rows' => 12, 'cols' => 53))."</br>";
			echo Form::hidden("question_num",$group_questions['id']);
			echo Form::submit("submit","投稿",array('id'=>'group_answer_submit',"class"=>"btn btn-primary"));
			echo Form::close('submit_answer');		
			
		}
		}
	}else{?>
	<p id="group_vote_time">投票期間中！<p></br>
	<?php

		echo '<p class="group_question">「お題:'.$current_question[0]['id'].'回目  '.$current_question[0]['question'].'」</p>';
	if ($group_vote_flg==0) {
		if(empty($user_name)){
			echo '<p id="vote_login_ann">投票するにはログインする必要があります。</>';
		}else{
			echo '<p id="group_question_vote_page">投票してお金をGET→'.Html::anchor('vote', '投票ページへ').'</p><br>';		
		}
	}		
	}?>
	<?php
	echo '<p class="due_time">残り時間（※毎日22時締め切り）:';
	echo $due_time;
	echo "</p>";
	?>
<div>
<?php
echo Asset::img("single_battle.gif",array("id"=>"single_battle","width"=>"388px"));	
if($former_single_battle_id!=0){
	
	if($former_single_battle[0]['first_total_point']>$former_single_battle[0]['second_total_point']){
		$win=$former_single_battle[0]['first_user_name'];
	}else{
		$win=$former_single_battle[0]['second_user_name'];
	}
	

	echo '<p id="former_single_battle">最新の対戦結果</p>';
	echo '<p id="single_former_battle_card">'.$former_single_battle[0]['first_user_name']."  VS  ".$former_single_battle[0]['second_user_name'];;	
	if($user_name == $win){
		echo'<p id="win_lose">あなたの勝利! '.$former_single_battle[0]['bet_money']."円獲得</p>";	
	}else{
		echo'<p id="win_lose">あなたの負け　'.$former_single_battle[0]['bet_money']."円を失った</p>";	
	}	
	
	echo '<p id="former_single_battle_link">'.Html::anchor('/pastsinglebattle',"詳しい対戦結果はこちら",array("id"=>"former_single_battle_anchor"))."<p><br>";
	
}

if(empty($user_name)){
	echo '<p id="register_battle">対戦相手を募集</br></p>';
	echo "ログインをすると対戦を募集したり、特定の人の対戦を申し込んだりできます</br>";
}else{
	if($single_post_flg==0){
		echo '<p id="register_battle">対戦相手を募集</br></p>';
		echo Form::open('singlebattle/register');
		echo "募集金額：".Form::input('bet_money', '', array('size'=>20))."　円　";
		echo Form::submit("submit","募集する",array('id'=>'single_apply',"class"=>"btn btn-primary"));
		echo Form::close('singlebattle/register');
	}elseif($my_single_battle[0]['battle_state']==0){
		echo '<p id="register_battle">対戦相手を募集</br></p>';
		if($my_single_battle[0]['battle_apply_flg']==1){
			echo '<p id="apply_money">'.$my_single_battle[0]['bet_money']."円で".$my_single_battle[0]['apply_user_name']."に対戦申し込み中</p>";			
		}else{
			echo '<p id="apply_money">'.$my_single_battle[0]['bet_money']."円で対戦相手募集中</p>";
		}
		echo Form::open('singlebattle/deletebattle');
		echo Form::hidden("single_battle_id",$single_battle_id);
		echo Form::submit("submit","募集をキャンセルする",array('id'=>'cansel_submit',"class"=>"btn btn-danger"))."</br>";
		echo Form::open('singlebattle/deletebattle');
	}elseif($my_single_battle[0]['battle_state']==1){
		echo '<p id="register_battle">投稿期間中</br></p>';
		echo Form::open('singlebattle/receiveanswer');
		echo '<p id="battle_card">カード:'.$my_single_battle[0]['first_user_name'].' VS '.$my_single_battle[0]['second_user_name'].'<br>';
		echo '<p id="single_question">お題:'.$my_single_battle[0]['question'].'</p><br>';
		echo Form::textarea('users_answer', '', array('rows' => 12, 'cols' => 53,'id'=>'single_answer_text'))."</br>";
		echo Form::hidden("single_battle_id",$single_battle_id);
		echo Form::submit("submit","投稿",array('id'=>'single_answer_submit',"class"=>"btn btn-primary"));
		echo Form::close('singlebattle/receiveanswer');
		echo '<p class="due_time_single">投稿締め切り:'.$my_single_battle[0]['due_date']."の23:59:59秒まで</p>";
	}elseif($my_single_battle[0]['battle_state']==2){
			echo '<p id="single_vote_time">現在投票期間中！'.'</br><p>';
			echo '<p id="battle_card">カード:'.$my_single_battle[0]['first_user_name']."  VS  ".$my_single_battle[0]['second_user_name']."</p><br>";
	}
}

echo Html::anchor("/applysinglebattle", '対戦相手を探しに行く',array("class"=>"applysinglebattle"))."<br>";	
?>
<p id="apply_battle_for_you">あなたへの対戦申し込み</p>
<table>
<?php
if(empty($single_battle_apply_list)){
	echo '<p id="no_single_battle">現在対戦申し込みはありません</p>';	
}else{
	foreach ($single_battle_apply_list as $single_battle_apply_lists) {
		echo Form::open('singlebattle/single_battle_apply');
		echo "<tr><td>".$single_battle_apply_lists['from_user_name']."</td>";
		echo "<td>".$single_battle_apply_lists['bet_money']."円</td>";
		if($single_post_flg==0){
			echo Form::hidden("from_user_name",$single_battle_apply_lists['from_user_name']);
			echo Form::hidden("single_battle_id",$single_battle_apply_lists['id']);
			echo Form::hidden("apply_flg",1);
			echo "<td>".Form::submit("submit","対戦を受ける",array('id'=>'accept_single_battle',"class"=>"btn btn-primary"))."</td>";
			echo Form::close('singlebattle/single_battle_apply');	
		}else{
			echo "<td>現在は対戦を受けられません</td>";	
		}
	}
}
?>
</table>
</div>

<table class="table table-bordered" id="single_battle_list">
	<tr><td colspan="3" style="text-align:center;">対戦相手募集中</td></tr>
	<tr><td>募集している人</td><td>賭け金</td><td>対戦申し込み </td></tr>
<?php
foreach ($single_battle_requirement as $single_battle_requirements){

	echo Form::open('singlebattle/single_battle_apply');
	echo '<tr>';
	echo "<td>".$single_battle_requirements['first_user_name'].$single_battle_requirements['second_user_name']."さん</td>";
	echo "<td>".$single_battle_requirements['bet_money']."円</td>";
	if($single_post_flg==0 && isset($user_name)){
		echo Form::hidden("single_battle_id",$single_battle_requirements['id']);
		echo Form::hidden("apply_flg",0);
		echo "<td>".Form::submit("submit","申し込む",array("class"=>"btn btn-primary"))."</td>";
		echo Form::close('singlebattle/single_battle_apply');
	}else{
		echo "<td>現在は申し込みできません</td>";	
	}
	echo '</tr>';
	
}
echo '</table>';

if (count($single_battle_requirement)<5) {
	echo Html::anchor('/singlebattlerequirement',"対戦募集をもっと見る",array("id"=>"requirment_anchor"));
}

if($single_post_flg==1){
	echo "<p>申し込みができない場合は現在対戦中のタイマンバトルを終えるか、募集中のバトルをキャンセルしてください。<p/>";
}
?>
<table class="table table-bordered" id="single_vote_table">
	<tr><td colspan="3" style="text-align:center;">投票期間中</td></tr>
<?php
foreach ($single_battle_top_list as $single_battle_top_lists){

	echo '<tr colspan="2">';
	echo "<td>".$single_battle_top_lists['first_user_name']."さん VS ".$single_battle_top_lists['second_user_name']."さん  </td>";
	echo '</tr>';
	
}
echo '</table>';
echo '<p class="vote_anchor" >投票をしてお金をGET→'.Html::anchor('singlebattle/single_vote_display',"タイマンバトル投票受付中",array("id"=>"single_vote_anchor")).'</p>';
?>
</div>
</div>
</body>

</html>
