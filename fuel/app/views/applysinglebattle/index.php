<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("apply_single_battle.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"result_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
?><?php
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";	
?>
<p id="rule">対戦をする場合は賭け金を設定して、申し込みボタンを押してください。相手が対戦を受託すると対戦が開始されます。</p>
<table class="table table_bordered" id="apply_table">
	<tr><td>ユーザー名</td><td>所持金</td><td>賭け金</td><td>申し込みボタン</td></tr>
	<?php
	foreach ($user_list as $user_lists) {
		if($user_name!=$user_lists['user_name']){
			echo Form::open('applysinglebattle/register');
			echo "<tr><td>".$user_lists['user_name']."</td><td>";
			echo $user_lists['money']."</td><td>".Form::input('bet_money', '', array('size'=>20))."</td><td>".Form::submit("submit","申し込む")."</td>";
			echo Form::hidden("user_name",$user_lists['user_name']);
			echo Form::hidden("for_money",$user_lists['money']);
			echo Form::hidden("apply_flg",1);
			echo Form::close('singlebattle/single_battle_apply');
		}
	}
echo '</table>';

echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";	


?>

</body>
</html>
