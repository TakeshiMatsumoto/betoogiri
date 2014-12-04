<html>
<head>
<title>賭け大喜利</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
echo Asset::CSS("bootstrap.css");	
echo Asset::CSS("single_complete.css");
?>
</head>
<body>
<?php
echo Asset::img("result.gif",array("id"=>"question_img","width"=>"750px"))."<br>";
$base_url= Uri::base();
echo Html::anchor($base_url, 'TOPに戻る',array("class"=>"top_anchor"))."<br>";

?>
<p id="explain">投稿完了しました。twitterで通知することができます</p>
<?php
			echo Form::open('singlebattle/tweetsinglebattle');
			echo Form::submit("submit","ツイート",array("class"=>"submit_button btn btn-primary"))."<br>";
			echo Form::close('singlebattle/tweetsinglebattle');
?>



</body>
</html>
