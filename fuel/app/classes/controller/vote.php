<?php

require_once 'twitteroauth/twitteroauth.php';

Class Controller_Vote extends Controller_Admin
{
	public function action_index(){
		$user_name=Cookie::get('user_name');
		if(empty($user_name)){
			$base_url= Uri::base();
			Response::redirect($base_url."confirmlogin");
		}
		//現在のお題の状態を取得
		$question_state= Model_questionstate::find_one_by('id',1);
		$data['user_name']=$user_name;
		$current_question_num=$question_state['current_quetion_num'];
		//現在のお題の状態が投票モード（２）なら回答をviewに渡す、そうでないならTOPにリダイレクト
		if($question_state['question_state_flg']==2 ){
			$answer_list_query=DB::select()->from('groupanswerlist');
			$answer_list_query->where('question_num','=',$current_question_num);
			$answer_list_result = $answer_list_query->execute()->as_array();
			$data['answer_list'] = $answer_list_result;
			$data['confirm_flg'] = "true";
			return Response::forge(View::forge('vote/index',$data));
		}else{
			$base_url= Uri::base();
			Response::redirect($base_url);				
		}
	}
	//受け取った採点を確認画面に表示する
	public function action_confirmvote(){
		$user_name=Cookie::get('user_name');
		$data['user_name']=$user_name;
		$question_state= Model_questionstate::find_one_by('id',1);
		$current_question_num=$question_state['current_quetion_num'];
		
		$answer_list_query=DB::select()->from('groupanswerlist');
		$answer_list_query->where('question_num','=',$current_question_num);
		$answer_list_result = $answer_list_query->execute()->as_array();
		$data['answer_list'] = $answer_list_result;
		
		//ユーザーから受け取った点数を入れる配列
		$point_array=array();
		
		//受け取った点数をidをキーに配列に入れていく。
		foreach ($answer_list_result as $answer_list_results) {
			$point_array[$answer_list_results['id']]=Input::post($answer_list_results['id']);
		}	
		$data['point_list'] = $point_array;
		return Response::forge(View::forge('vote/confirmvote',$data));

	}

	//採点をDBに入れる
	public function action_registervote(){
		//既に投票しているかどうか
		$group_vote_flg=Session::get('group_vote_flg');
		$group_vote_flg=0;
		if($group_vote_flg==0){
				
			$question_state= Model_questionstate::find_one_by('id',1);
			$current_question_num=$question_state['current_quetion_num'];
			
			$answer_list_query=DB::select()->from('groupanswerlist');
			$answer_list_query->where('question_num','=',$current_question_num);
			$answer_list_result = $answer_list_query->execute()->as_array();
			
			$user_name = Cookie::get('user_name');
			//ユーザーの投票フラグを１にする。
			$user_info_query=DB::update('userinfo')->set(array('group_vote_flg'=>1,'money'=>DB::expr('money + ' . 500)));
			$user_info_query->where('user_name','=',$user_name);
			$user_info_query->execute();
			foreach ($answer_list_result as $answer_list_results) {
				if($user_name!=$answer_list_results['user_name']){
					$vote_register=DB::insert('votelist')->set(array('user_name'=>$user_name,'question_num'=>$current_question_num,'answer_num' =>$answer_list_results['id'],
																	'point'=>Input::post($answer_list_results['id'])));
					$vote_register-> execute();
				}
			}
			Session::set_flash('message', "投票完了しました");
		}else{
			Session::set_flash('message', "あなたは既に投票済みです");
			
		}
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);
			
	}
}
