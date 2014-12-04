<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Receiveanswer extends Controller
{


	public function action_index()
	{
		$users_answer=Input::post('users_answer');
		$question_num=Input::post('question_num');
		//画面の状態の切り分けフラグをテーブルからとってくる　1->出題　->採点
		$question_num_result = Model_questionstate::find_one_by('id',1);

		$current_question_num=$question_num_result['current_quetion_num'];
		$user_name = Cookie::get('user_name');
		//$user_name = "1";
		//$user_name = "bakuraro02";
		if ($current_question_num == $question_num){
			$answer_register = Model_groupanswerlist::forge();		
			
			//既に回答を登録済みかどうかを調べる
			$answer_check_query=DB::select()->from('groupanswerlist');
			$answer_check_query->where('user_name','=',$user_name);
			$answer_check_query->where('question_num','=',$question_num);
			$answer_check_result = $answer_check_query->execute()->as_array();

			//既に登録済みならupdate、そうでないならinsert
			if (empty($answer_check_result)){
				$answer_register -> set(array('user_name'=>$user_name,'answer'=>$users_answer,'question_num' =>$question_num));
				$answer_register -> save();
			}else{
				$answer_register_query = DB::update('groupanswerlist');
				$answer_register_query ->value('answer',$users_answer);
				$answer_register_query ->where('user_name','=',$user_name);
				$answer_register_query ->where('question_num','=',$question_num);
				$answer_register_query ->execute();
			}
			Session::set_flash('message', "回答を送信しました");
		}else{
			
			
			
		}
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);		
	}
}
