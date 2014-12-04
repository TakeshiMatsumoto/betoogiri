<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("past_single_battle.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"result_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";	
foreach ($past_single_battle as $past_single_battle_list){
	$first_user_win="";
	$second_user_win="";
	$cut = 1;
	$past_single_battle_list['first_one_point'] = substr( $past_single_battle_list['first_one_point'] , 0 , strlen($past_single_battle_list['first_one_point'])-$cut );
	$past_single_battle_list['first_three_point'] = substr( $past_single_battle_list['first_three_point'] , 0 , strlen($past_single_battle_list['first_three_point'])-$cut );
	$past_single_battle_list['first_five_point'] = substr( $past_single_battle_list['first_five_point'] , 0 , strlen($past_single_battle_list['first_five_point'])-$cut );
	$past_single_battle_list['second_one_point'] = substr( $past_single_battle_list['second_one_point'] , 0 , strlen($past_single_battle_list['second_one_point'])-$cut );
	$past_single_battle_list['second_three_point'] = substr( $past_single_battle_list['second_three_point'] , 0 , strlen($past_single_battle_list['second_three_point'])-$cut );
	$past_single_battle_list['second_five_point'] = substr( $past_single_battle_list['second_five_point'] , 0 , strlen($past_single_battle_list['second_five_point'])-$cut );
	
	if ($past_single_battle_list['first_total_point']>$past_single_battle_list['second_total_point']) {
		$first_user_win='<span class="win">win! </span>';
	} else {
		$second_user_win='<span class="win">win! </span>';
	}

	echo '<table class="result_table">';
	echo '<tr><td>'.
	$first_user_win.$past_single_battle_list['first_user_name'].'    VS    '.$second_user_win.$past_single_battle_list['second_user_name'].
	"</td></tr>";
	echo '<tr><td>';
	echo "賭け金：　".$past_single_battle_list['bet_money']."円";
	"</td></tr>";
	echo "</table>";
	
	echo "<table class='table table-bordered point_table'>";
	echo "<tr><td>".$past_single_battle_list['first_user_name']."   TOTAL  ".$past_single_battle_list['first_total_point']."点".
	'</td><td>'.$past_single_battle_list['second_user_name']."      TOTAL  ".$past_single_battle_list['second_total_point']."点";
	echo "</td></tr>";
	echo "<tr><td>".$past_single_battle_list['first_user_answer'].'</td>';
	echo '<td>'.$past_single_battle_list['second_user_answer'];
	echo "</td></tr>";
	echo '<tr><td><span class="point">5点:  </span>'.$past_single_battle_list['first_five_point'].'</td><td>'.$past_single_battle_list['second_five_point'];
	echo "</td></tr>";
	echo '<tr><td><span class="point">3点:  </span>'.$past_single_battle_list['first_three_point'].'</td><td>'.$past_single_battle_list['second_three_point'];
	echo "</td></tr>";
	echo '<tr><td><span class="point">1点:  </span>'.$past_single_battle_list['first_one_point'].'</td><td>'.$past_single_battle_list['second_one_point'];
	echo "</td></tr>";
	echo '</table>';
}

echo html_entity_decode($pagination);
?>

</body>
</html>
