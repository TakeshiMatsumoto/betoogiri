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
echo Asset::img("vote_title.gif",array("id"=>"vote_title","width"=>"750px"));	
?>	

<table id="vote_table" border="1">
<?php

echo Form::open('vote/confirmvote');

foreach ($answer_list as $answer_lists) {

	if($answer_lists['user_name']!=$user_name){
	echo "</tr>";
	echo '<td width="300px">';
	echo $answer_lists['answer'];
	echo "</td>";
	echo "<td>";
	echo Form::radio($answer_lists['id'],1, true)."1";
	echo "</td>";
	echo "<td>";
	echo Form::radio($answer_lists['id'],3,  true)."3";
	echo "</td>";
	echo "<td>";
	echo Form::radio($answer_lists['id'],5,  true)."5</br>";
	echo "</td>";
	echo "<td>";
	echo Form::radio($answer_lists['id'],7,  true)."7</br>";
	echo "</td>";
	echo "</tr>";
}}
?>
</table>
<?php
echo Form::submit("submit","確認",array("class"=>"btn btn-primary","id"=>"vote_confirm_button"));
echo Form::close('vote/confirmvote');

?>

</body>
</html>
