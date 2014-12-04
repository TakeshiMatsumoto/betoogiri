<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Pastquestionlist extends Controller
{

	public function action_index()
	{

		$question_result_query=DB::select()->from('questionlist');
		$question_result_query->where('used_flg','=',1);
		$question_result_query -> order_by('id','desc');		
		$data['past_question']=$question_result_query->execute()->as_array();
		return Response::forge(View::forge('result/pastquestionlist',$data));

	}
}
