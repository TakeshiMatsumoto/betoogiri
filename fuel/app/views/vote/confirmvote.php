<html lang="ja">
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("group_vote.css");
echo Asset::CSS("bootstrap.css");	
?>
</head>
<body>
<?php
echo Asset::img("vote_title.gif",array("id"=>"vote_title","width"=>"750px"))."</br>";	
?>	
<p id="confirm-sentence">以上の内容でよろしいでしょうか？</p>
<table id="vote_confirm_table" border="1">

<?php

echo Form::open('vote/registervote');
foreach ($answer_list as $answer_lists) {
	if($answer_lists['user_name']!=$user_name){
		echo "<tr>";
		echo "<td>";	
		echo $answer_lists['answer'];
		echo "</td>";
		echo "<td>";
		echo $point_list[$answer_lists['id']];
		echo "</td>";
		echo Form::hidden($answer_lists['id'],$point_list[$answer_lists['id']])."</br>";
		echo "</tr>";
}
}
?>
</table>
<?php
echo Form::submit("submit","投票",array("class"=>"btn btn-primary","id"=>"vote_button"));
echo Form::close('vote/registervote');

?>

</body>
</html>
