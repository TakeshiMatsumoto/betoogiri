<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Groupanswerresult extends Controller
{

	public function action_index()
	{
		$target_question_num=Input::get('target_question_num');
		
		$question_result_query=DB::select()->from('groupquestionresult');
		$question_result_query->where('question_num','=',$target_question_num);
		$data['question_result']=$question_result_list = $question_result_query->execute()->as_array();
		return Response::forge(View::forge('result/index',$data));

	}
}
