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
<div id="rule">
<p>賭け大喜利は、ゲーム内のお金を奪い合う<span style="line-height: 24px;">大喜利ゲームです。</span></p>
<p><span style="font-size: 200%;"><strong>・ゲームの始め方</strong></span></p>
<p>TOPページのログインボタンを押すだけで会員登録ができます。</p>
<p>twitter認証を用いているので、twitterでログインをしてください。</p>
<p>当サイトは、ユーザーの許可なくツイート、フォローをしたりすることはありません。</p>
<p><span style="font-size: 200%;"><strong>・一斉<span style="line-height: 24px;">大喜利</span></strong></span></p>
<p>当サイト全員が同時に参加できる<span style="line-height: 24px;">大喜利です。全員がお題に対してボケを投稿、投票できます。</p>
<p>上位に入賞しますと一定の額が手に入り、逆に下位になってしまうとお金がなくなってしまいます。</span></p>
<p><span style="font-size: 200%;"><strong>・タイマン<span style="line-height: 24px;">大喜利</span></strong></span></p>
<p>1対１でお金を賭けて戦う<span style="line-height: 24px;">大喜利バトルです。</span></p>
<p>タイマン<span style="line-height: 24px;">大喜利をするには</span></p>
<p><strong>「対戦相手を指定して対戦を申し込む」</strong></p>
<p><strong>「申し込まれた対戦を受ける」</strong></p>
<p><strong>「自分で対戦相手を募集する」</strong></p>
<p><strong>「募集されてる対戦に参加する」</strong></p>
<p>の4通りのパターンがあります。</p>
<p><span style="font-size: 200%;"><strong>・お金を得るには</strong></span></p>
<p>「一斉<span style="line-height: 24px;">大喜利</span>」で勝つ</p>
<p>「タイマン」で勝つ</p>
<p>の他に</p>
<p style="font-size: 150%;"><strong>「投票をする」</strong></p>
<p>ことでもお金を得ることができます。</p>
<p>お金が足りなくなったらぜひ投票してみてください。</p>
</div>
</body>
</html>
