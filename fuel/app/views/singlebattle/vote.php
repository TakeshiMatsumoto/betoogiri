<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("single_vote.css");
?>
</head>
<body>
<?php

echo '<p id="top_message">'.$message.'</p>';
echo Asset::img("Top_title.gif",array("id"=>"single_vote_img","width"=>"750px"))."</br>";	
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";	
foreach ($single_battle_top_list as $single_battle_top_lists) {
	
	echo '<table id="vote_table" class="table  table-bordered">';
	if($user_name!=$single_battle_top_lists['first_user_name']&&$user_name!=$single_battle_top_lists['second_user_name']){
		echo Form::open('singlebattle/single_vote_register');
		echo '<tr class="single_battle_card" colspan="4"><td colspan="4">';
		echo $single_battle_top_lists['first_user_name']."VS".$single_battle_top_lists['second_user_name'];
		echo '	    只今	'.$vote_number[$single_battle_top_lists['id']].'/5人投票</td></tr><tr><td>';
		echo $single_battle_top_lists['first_user_answer'];
		echo '</td>';
		echo '<td>';
		echo Form::radio("first_user".$single_battle_top_lists['id'],1, true)."1";
		echo '</td>';
		echo '<td>';
		echo Form::radio("first_user".$single_battle_top_lists['id'],3,  true)."3";
		echo '</td>';
		echo '<td>';
		echo Form::radio("first_user".$single_battle_top_lists['id'],5,  true)."5</br>";
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>';
		echo $single_battle_top_lists['second_user_answer'];
		echo '</td>';
		echo '<td>';
		echo Form::radio("second_user".$single_battle_top_lists['id'],1, true)."1";
		echo '</td><td>';
		echo Form::radio("second_user".$single_battle_top_lists['id'],3,  true)."3";
		echo '</td><td>';
		echo Form::radio("second_user".$single_battle_top_lists['id'],5,  true)."5</br>";
		echo Form::hidden("single_battle_id",$single_battle_top_lists['id']);
		echo '</tr>';
	}
	echo "<table>";
	if($user_name!=$single_battle_top_lists['first_user_name']&&$user_name!=$single_battle_top_lists['second_user_name']){
		if(empty($user_name)){
			echo "投票するにはログインする必要があります";
		}else{
			
			echo Form::submit("submit","投票",array("class"=>"submit_button"))."<br>";
			echo Form::close('singlebattle/single_vote_register');
		}
	}
}
if(count($single_battle_top_list)==0){
	echo '<p id="novote">現在投票はありません<p>';
	
}
?>

</body>
</html>
