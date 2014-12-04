<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("requirement.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"question_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";
?>

<table id="single_battle_list" class="table table_bordered">
	<tr><td colspan="3" style="text-align:center;">対戦相手募集中<td></tr>
	<tr><td>募集している人</td><td>賭け金</td><td>対戦申し込み </td></tr>
<?php
foreach ($single_battle_requirement as $single_battle_requirements){

	echo Form::open('singlebattle/single_battle_apply');
	echo '<tr>';
	echo "<td>".$single_battle_requirements['first_user_name'].$single_battle_requirements['second_user_name']."さん</td>";
	echo "<td>".$single_battle_requirements['bet_money']."円</td>";
	if($single_post_flg==0){
		echo Form::hidden("single_battle_id",$single_battle_requirements['id']);
		echo Form::hidden("apply_flg",0);
		echo "<td>".Form::submit("submit","申し込む")."</td>";
		echo Form::close('singlebattle/single_battle_apply');
	}else{
		echo "<td>現在は申し込みできません</td>";	
	}
	echo '</tr>';
	
}
echo '</table>';
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";
?>
</body>
</html>
