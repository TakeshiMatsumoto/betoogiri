<?php
require_once 'twitteroauth/twitteroauth.php';

class Controller_Singlebattlerequirement extends Controller_Admin
{

	public function action_index()
	{
		
		$user_name = Cookie::get('user_name');
		if(isset($user_name)){
			$user_name = Session::get('user_name');
			$single_post_flg = Session::get('single_post_flg');
			$data['user_name'] = $user_name;
			$data['single_post_flg'] = $single_post_flg;
			//タイマンバトル募集中の表示を出す
			$sigle_requirement_query = DB::select()->from('singlebattlemaster');
			$sigle_requirement_query-> where('battle_state','=',0);
			$sigle_requirement_query-> where('battle_apply_flg','=',0);
			$sigle_requirement_result  = 	$sigle_requirement_query ->execute()->as_array();
			$data['single_battle_requirement']=$sigle_requirement_result;
			return Response::forge(View::forge('singlebattle/requirement',$data));
		}else{
			$base_url= Uri::base();
			Response::redirect($base_url."confirmlogin");		
		}

	}
}
