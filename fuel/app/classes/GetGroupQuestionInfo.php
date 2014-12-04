<?php

class GetGroupQuestionInfo extends Controller {
	public static function getgroupinfo() {
		$question_state_query = DB::select()->from('questionstate');
		$question_state_result = $question_state_query->execute()->as_array();
		
		return $question_state_result[0];
	}
}