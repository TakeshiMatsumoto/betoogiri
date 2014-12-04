<?php


class Controller_Checksingledue extends Controller
{

	public function action_index()
	{
		$date=date("y-m-d");	
		$due_check_query=DB::select()->from('singlebattlemaster');
		$due_check_query ->where('due_date','<',$date);
		$due_check_query ->and_where_open();
		$due_check_query ->where('first_user_answer','=',null);
		$due_check_query -> or_where('second_user_answer','=',null);
		$due_check_query ->and_where_close();
		$due_check=$due_check_query -> execute()->as_array();

		foreach ($due_check as $due_checks) {
			if($due_checks['first_user_answer']==null&&$due_checks['second_user_answer']!=null){
				$user_change1=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change1->where('user_name','=',$due_checks['first_user_name']);
				$user_change1->execute();

				$user_change2=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money + ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change2->where('user_name','=',$due_checks['second_user_name']);
				$user_change2->execute();
				
				$single_change=DB::update('singlebattlemaster')->set(array('battle_state'=>4));
				$single_change->where("id","=",$due_checks['id']);
				$single_change->execute();
			}
			
			if($due_checks['second_user_answer']==null&&$due_checks['first_user_answer']!=null){
				$user_change1=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money + ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change1->where('user_name','=',$due_checks['first_user_name']);
				$user_change1->execute();

				$user_change2=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change2->where('user_name','=',$due_checks['second_user_name']);
				$user_change2->execute();
				
				$single_change=DB::update('singlebattlemaster')->set(array('battle_state'=>4));
				$single_change->where("id","=",$due_checks['id']);
				$single_change->execute();
				
			}
			if($due_checks['second_user_answer']==null&&$due_checks['first_user_answer']==null){
				$user_change1=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change1->where('user_name','=',$due_checks['first_user_name']);
				$user_change1->execute();

				$user_change2=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>0));
				$user_change2->where('user_name','=',$due_checks['second_user_name']);
				$user_change2->execute();
				
				$single_change=DB::update('singlebattlemaster')->set(array('battle_state'=>4));
				$single_change->where("id","=",$due_checks['id']);
				$single_change->execute();
				
			}				
		}

	}
}
