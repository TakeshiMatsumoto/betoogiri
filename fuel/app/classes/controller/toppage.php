<?php

class Controller_Toppage extends Controller_Admin
{
	public function action_index()
	{
		
//Cookie::delete('user_name');
		$user_name=Cookie::get('user_name');
		//$user_name="bakutaro02";
		//$user_name="1";
		$data['user_name']=$user_name;
		$single_battle_id = Session::get('single_battle_id');
		$data['former_single_battle_id']=  Session::get('former_single_battle_id');
		$former_single_battle_id=  Session::get('former_single_battle_id');
		
		
		//一斉投稿の締め切りまでの時間を求める
		$now_date=date('H:i:s');
		//今が22時以降なら明日の22時までの差を求め、そうでないなら今日の22時
		if($now_date>"22:00:00"){
			$due_date= date("Y-m-d 22:00:00", strtotime("+1 day"));
		}else{
			$due_date= "22:00:00";	
		}

		$datetime1=new Datetime($now_date);
		$datetime2=new Datetime($due_date);
		$due_time=$datetime2->diff($datetime1);
		$data['due_time']=$due_time->format('%h時間 %i分 %s秒');

		//画面の状態の切り分けフラグとお題番号をテーブルからとってくる　1->出題　2->採点
		$group_info = GetGroupQuestionInfo::getgroupinfo();
		$question_state_flg = $group_info['question_state_flg'];
		$question_num = $group_info['current_quetion_num'];

		
		//画面に表示するエラーメッセージなど。
		$data['message']=Session::get_flash('message');
		//既に投票しているかどうか
		$data['group_vote_flg']=Session::get('group_vote_flg');
		
		
		$former_question_num=$question_num-1;
		//結果を取得する
		if($former_question_num!=0){
			$group_answer_result = DB::select()->from('groupquestionresult');
			$group_answer_result -> where('question_num','=',$former_question_num);
			$group_answer_result -> order_by('date','desc');
			$group_answer_result -> order_by('rank','asc');		
			$group_answer_list = $group_answer_result -> execute()->as_array();
			$data['group_answer_result_list'] = $group_answer_list;
		}
		$group_answer_query = DB::select()->from('questionlist');
		$group_answer_query -> where('used_flg','=',1);
		$group_answer_query -> order_by('date','desc');
		$group_answer_query -> limit(4);
		$past_group_answer = $group_answer_query-> execute()->as_array();
		$data['past_group_answer'] = $past_group_answer;
		
		//ランキング取得
		$money_rank_query = DB::select()->from('userinfo');
		$money_rank_query -> order_by('money','desc');
		$money_rank_query -> limit(5);
		$data['money_rank'] = $money_rank_query -> execute()->as_array();
		


		//投票期間中のバトルを取得
		$single_battle_top_query = DB::select()->from('singlebattlemaster');
		$single_battle_top_query->where('battle_state','=',2);
		$single_battle_top_query -> limit(3);
		$single_battle_top_list	= $single_battle_top_query -> execute() -> as_array();
		$data['single_battle_top_list']=$single_battle_top_list;
		
		//前回のタイマンバトルの結果を取得
		$former_single_battle_query = DB::select()->from('singlebattlemaster');
		$former_single_battle_query->where('id','=',$former_single_battle_id);
		$former_single_battle= $former_single_battle_query -> execute() -> as_array();
		$data['former_single_battle']=$former_single_battle;

		//タイマンバトル募集中の表示を出す
		$sigle_requirement_query = DB::select()->from('singlebattlemaster');
		$sigle_requirement_query-> where('battle_state','=',0);
		$sigle_requirement_query-> where('battle_apply_flg','=',0);
		$sigle_requirement_query-> limit(5);
		$sigle_requirement_result  = 	$sigle_requirement_query ->execute()->as_array();
		$data['single_battle_requirement']=$sigle_requirement_result;
		//$single_battle_apply_
		$single_battle_apply_query = DB::select()->from('singlebattlemaster');
		$single_battle_apply_query -> where('battle_apply_flg',"=",1);
		$single_battle_apply_query -> where('apply_user_name',"=",$user_name);
		$data['single_battle_apply_list']=$single_battle_apply_query->execute()->as_array();
		
		//自分の投稿期間中のタイマンお題を取得
		$my_single_battle_query = DB::select()->from('singlebattlemaster');
		$my_single_battle_query -> where('id','=',$single_battle_id);		
		$my_single_battle_result  = $my_single_battle_query ->execute()->as_array();
		$data['my_single_battle']=$my_single_battle_result;

		$data['money']=Session::get('money');
		$data['single_post_flg']=Session::get('single_post_flg');
		$data['single_battle_id']=$single_battle_id;
		//もし１ならお題を返す
		if($question_state_flg == 1){
			$question_query = DB::select()->from('questionlist');
			$question_query -> where('used_flg','=',0);
			$question_query -> limit(1);
			$question_search_result = $question_query -> execute() -> as_array();
	
			$data['group_question']=$question_search_result;
			return Response::forge(View::forge('toppage/index',$data));
		}else{
			$question_query = DB::select()->from('questionlist');
			$question_query -> where('id','=',$question_num);
			$question_query -> limit(1);
			$question_search_result = $question_query -> execute() -> as_array();
			$data['current_question']=$question_search_result;
			return Response::forge(View::forge('toppage/index',$data));	
		}
		

		
		
							
		
		
	}
}
