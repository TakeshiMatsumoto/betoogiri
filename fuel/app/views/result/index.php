<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("group_result.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"result_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";

echo '<p id="question">'.$question_result[0]['question'].'</p>';
if(isset($question_result)){
	$cut = 1;
	foreach ($question_result as $question_results) {
		echo '<table id="result_table" class="table table-bordered" width="500px">';
		echo '<tr id="rank"><td>'.$question_results['rank'].'位:'.$question_results['username'].'           '.$question_results['totalpoint']."点</td></tr>";
		echo '<tr><td>'.$question_results['answer'].'</td></tr>';
		if($question_results['7point']!=""){
			$question_results['7point'] = substr( $question_results['7point'] , 0 , strlen($question_results['7point'])-$cut );
			echo "<tr><td>7点: ".$question_results['7point'].'</td></tr>';
		}
		if($question_results['5point']!=""){
			$question_results['5point'] = substr( $question_results['5point'] , 0 , strlen($question_results['5point'])-$cut );
			echo "<tr><td>5点: ".$question_results['5point'].'</td></tr>';
		}
		if($question_results['3point']!=""){
			$question_results['3point'] = substr( $question_results['3point'] , 0 , strlen($question_results['3point'])-$cut );
			echo "<tr><td>3点: ".$question_results['3point'].'</td></tr>';
		}
		if($question_results['1point']!=""){
			$question_results['1point'] = substr( $question_results['1point'] , 0 , strlen($question_results['1point'])-$cut );
			echo "<tr><td>1点: ".$question_results['1point']."</td></tr></br>";
		}
		echo "</table>";
	}
	echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";	
}

?>

</body>
</html>
