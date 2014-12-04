<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Confirmlogin extends Controller
{
	public function action_index(){
		$base_url= Uri::base();
		define('CONSUMER_KEY', 'wixFFix8Hy4uCrLRr79I6sOP7');
		define('CONSUMER_SECRET', '2lfG4O6QXt1q0JHa2zC5CCfNR51hoTI6ptgIsrBPFGiBlBekAI');
		define('CALLBACK_URL', $base_url.'confirmlogin/registerUser');
		// request token取得
		$tw = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$token = $tw->getRequestToken(CALLBACK_URL);
		if(! isset($token['oauth_token'])){
		    echo "error: getRequestToken\n";
		    exit;
		}
		 
		// callback.php で使うので session に突っ込む
	
		Session::set('oauth_token', $token['oauth_token']);
		Session::set('oauth_token_secret', $token['oauth_token_secret']);	
		// 認証用URL取得してredirect
		$authURL = $tw->getAuthorizeURL($token['oauth_token']);
		Response::redirect($authURL);
	}
	
	public function action_registerUser(){

		define('CONSUMER_KEY', 'wixFFix8Hy4uCrLRr79I6sOP7');
		define('CONSUMER_SECRET', '2lfG4O6QXt1q0JHa2zC5CCfNR51hoTI6ptgIsrBPFGiBlBekAI');
		$oauth_token = Session::get('oauth_token');
		$oauth_token_secret = Session::get('oauth_token_secret');
	

		// getToken.php でセットした oauth_token と一致するかチェック
		if ($oauth_token !== $_REQUEST['oauth_token']) {
		    //unset($_SESSION);
		    echo '<a href="getToken.php">token不一致。最初からどうぞ</a>';
		    exit;
		}
		 
		// access token 取得
		$tw = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,
		$oauth_token, $oauth_token_secret);
		$access_token = $tw->getAccessToken($_REQUEST['oauth_verifier']);
				
		$user_id     = $access_token['user_id'];
		$screen_name = $access_token['screen_name'];
		
		//oauth_tokenとoauth_token_secret所得
		$oauth_token=$access_token['oauth_token'];
		$oauth_token_secret=$access_token['oauth_token_secret'];
		
		
		Cookie::set('user_name', $screen_name, 60 * 60 * 24);
		Cookie::set('oauth_token', $oauth_token, 60 * 60 * 24);
		Cookie::set('oauth_token_secret', $oauth_token_secret, 60 * 60 * 24);
		Cookie::set('oauth_verifier', $_REQUEST['oauth_verifier'], 60 * 60 * 24);
		//すでにDBに登録済みかどうかの判定
		$user_register = Model_userinfo::find_by_pk($screen_name);

		if(is_null($user_register)){
			$user_register = Model_userinfo::forge();		
			$user_register -> set(array('user_name'=>$screen_name));
			$user_register -> save();
		}
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);	

	}



}
