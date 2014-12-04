<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("past_question.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"question_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";
?>
<table id="question_table" class="table table-bordered">
<?php
if(isset($past_question)){
	foreach($past_question as $past_questions){
		echo "<tr><td>".$past_questions['id']."回目</td><td>"
		.Html::anchor($base_url."groupanswerresult?target_question_num=".$past_questions['id'], $past_questions['question'],array())."</td></tr>";	
	}
}

?>
<table>

</body>
</html>
