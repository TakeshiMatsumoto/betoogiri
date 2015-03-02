<?php

class Controller_Admin extends Controller
{
	public function before(){
		$user_name = Cookie::get('user_name');
		$base_url= Uri::base();
		if(isset($user_name)){
			$user_list_query=DB::select()->from('userinfo');
			$user_list_query->where('user_name','=',$user_name);
			$user_list_result = $user_list_query->execute()->as_array();
			Session::set('money', $user_list_result[0]['money']);
			Session::set('single_post_flg', $user_list_result[0]['single_post_flg']);
			Session::set('single_battle_id', $user_list_result[0]['single_battle_id']);
			Session::set('former_single_battle_id', $user_list_result[0]['former_single_battle_id']);
			Session::set('user_name', $user_list_result[0]['user_name']);
			Session::set('group_vote_flg', $user_list_result[0]['group_vote_flg']);
	}
		
}
