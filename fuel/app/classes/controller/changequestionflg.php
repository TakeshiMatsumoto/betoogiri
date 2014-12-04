<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Changequestionflg extends Controller
{
	public function action_index()
	{
		//現在のお題の状態を取得
		$question_state= Model_questionstate::find_one_by('id',1);
		$question_state->set(array('question_state_flg'=>2));
		$question_state->save();
	}
}
