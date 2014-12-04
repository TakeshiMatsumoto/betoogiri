<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

/**
 * Robot example task
 *
 * Ruthlessly stolen from the beareded Canadian sexy symbol:
 *
 *		Derek Allard: http://derekallard.com/
 *
 * @package		Fuel
 * @version		1.0
 * @author		Phil Sturgeon
 */

class Robots
{

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots
	 *
	 * or
	 *
	 * php oil r robots "Kill all Mice"
	 *
	 * @return string
	 */
	public static function run($speech = null)
	{
		

		//現在のお題の状態を取得
		$question_state= \Model_questionstate::find_one_by('id',1);
			//お題番号の取得
		$target_question_num=$question_state['current_quetion_num'];

				//次のお題になるものをとってくる
		$answer_query = \DB::select()->from('groupanswerlist');
		$answer_query -> where('question_num','=',$target_question_num);
		$answer_search_result = $answer_query -> execute() -> as_array();
		
		var_dump($answer_search_result);
			var_dump($target_question_num);
		if(count($answer_search_result)>0){
			if($question_state['question_state_flg']==1){
				//現在のお題の状態を取得
				$question_state= \Model_questionstate::find_one_by('id',1);
				$question_state->set(array('question_state_flg'=>2));
				$question_state->save();
			}else{
				//お題番号の取得
				$target_question_num=$question_state['current_quetion_num'];
	
		
				//お題を使用済みに変える
				$question_flg_change_query=\DB::update('questionlist');
				$question_flg_change_query->set(array('used_flg'=>1,'date'=>date("Y/m/d(D)")));
				$question_flg_change_query->where('id','=',$target_question_num);
				$question_flg_change_query->execute();
				
		
				//次のお題になるものをとってくる
				$question_query = \DB::select()->from('questionlist');
				$question_query -> where('used_flg','=',0);
				$question_query -> limit(1);
				$question_search_result = $question_query -> execute() -> as_array();
		
		
		
				//お題の状態フラグと現在のお題を変える
				$question_state->set(array('question_state_flg'=>1,'current_quetion_num'=>$question_search_result[0]['id']));
				$question_state->save();
				
				
				
				//投票情報を集計して取得
				$answer_list_query2=\DB::select(\DB::expr('SUM(point) as total_point'),'votelist.answer_num','votelist.point','groupanswerlist.user_name','groupanswerlist.answer','questionlist.question')->from('votelist');
				$answer_list_query2->join('groupanswerlist','inner');
				$answer_list_query2->on('votelist.answer_num','=','groupanswerlist.id');
				$answer_list_query2->join('questionlist','inner');
				$answer_list_query2->on('questionlist.id','=','groupanswerlist.question_num');
				$answer_list_query2->group_by('answer_num');
				$answer_list_query2->where('votelist.question_num','=',$target_question_num);
				$answer_list_query2->order_by('total_point','desc');
				$answer_list_result = $answer_list_query2->execute()->as_array();
				
				$top_bonus=ceil(count($answer_list_result)*0.1);
				$worst_bonus=ceil(count($answer_list_result)*0.9);
						
				$user_info_query=\DB::update('userinfo')->set(array('group_vote_flg'=>0));
				$user_info_query->execute();
		
				$rank=1;
				$temp_rank;
				$temp_point="";
		
				//集計された情報を順位付けして\DBにいれていく。
				foreach ($answer_list_result as $answer_list_results) {
				$get_lost_money=0;		
				//もし一個前のねたと同じ得点ならランクから１を引いて同じ順位にすｒ
					if($temp_point==$answer_list_results['total_point']){
						$rank = $rank-1;
					}
					
					if($rank>=$top_bonus){
						$get_lost_money=5000;
					}elseif($worst_bonus>=$rank){
						$get_lost_money=-2000;
					}
					
					switch ($rank) {
						case 1:
							$get_lost_money=$get_lost_money+7000;
							break;
						case 2:
							$get_lost_money=$get_lost_money+4000;
							break;
						case 3:
							$get_lost_money=$get_lost_money+3000;					
							break;
					}
					
					$vote_person_query=\DB::select()->from('votelist');
					$vote_person_query->where('answer_num','=',$answer_list_results['answer_num']);
					$vote_person_list = $vote_person_query->execute()->as_array();
					$one_point_person="";
					$three_point_person="";
					$five_point_person="";
					$seven_point_person="";
					//対象のねたに誰が何点いれているかを集計していれる。
					foreach ($vote_person_list as $vote_person_lists) {
						switch ($vote_person_lists['point']) {
						    case "1":
						        $one_point_person=$one_point_person.$vote_person_lists['user_name']."/";
						        break;
						    case "3":
						        $three_point_person=$three_point_person.$vote_person_lists['user_name']."/";
						        break;
						    case "5":
						        $five_point_person=$five_point_person.$vote_person_lists['user_name']."/";
						        break;
							case "7":
						        $seven_point_person=$seven_point_person.$vote_person_lists['user_name']."/";
						        break;
						}
						
					}
					
					$vote_result_register=\DB::insert('groupquestionresult')
					->set(array('rank'=>$rank,'username'=>$answer_list_results['user_name'],
					'answer'=>$answer_list_results['answer'],'question'=>$answer_list_results['question'],
					'answer_num' =>$answer_list_results['answer_num'],'question_num' =>$target_question_num,'totalpoint'=>$answer_list_results['total_point'],
					'1point' => $one_point_person,'3point' => $three_point_person,'5point' => $five_point_person,'7point' => $seven_point_person,'date'=>date("Y/m/d(D)")));
					
					//ユーザーの情報変更（フラグ＆金額）
					$user_info_query=\DB::update('userinfo')->set(array('group_vote_flg'=>0,'money'=>\DB::expr('money + ' . $get_lost_money)));
					$user_info_query->where('user_name','=',$answer_list_results['user_name']);
					$user_info_query->execute();
					
					$vote_result_register-> execute();
					$rank++;
					$temp_point = $answer_list_results['total_point'];
					$one_point_person="";
					$three_point_person="";
					$five_point_person="";
					$five_point_person="";			
					
				}
				}
				}
	}

	/**
	 * An example method that is here just to show the various uses of tasks.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots:protect
	 *
	 * @return string
	 */
	public static function checksingle()
	{
		$date=date("y-m-d");	
		$due_check_query=\DB::select()->from('singlebattlemaster');
		$due_check_query ->where('due_date','<',$date);
		$due_check_query ->and_where('first_user_answer','=',null);
		$due_check_query -> or_where('second_user_answer','=',null);
		$due_check=$due_check_query -> execute()->as_array();
		var_dump($due_check);
		
		if($due_check!=""){
			foreach ($due_check as $due_checks) {
				if($due_checks['first_user_answer']==null&&$due_checks['second_user_answer']!=null){
					$user_change1=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change1->where('user_name','=',$due_checks['first_user_name']);
					$user_change1->execute();
	
					$user_change2=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money + ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change2->where('user_name','=',$due_checks['second_user_name']);
					$user_change2->execute();
					
					$single_change=\DB::update('singlebattlemaster')->set(array('battle_state'=>4));
					$single_change->where("id","=",$due_checks['id']);
					$single_change->execute();
				}
				
				if($due_checks['second_user_answer']==null&&$due_checks['first_user_answer']!=null){
					$user_change1=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money + ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change1->where('user_name','=',$due_checks['first_user_name']);
					$user_change1->execute();
	
					$user_change2=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change2->where('user_name','=',$due_checks['second_user_name']);
					$user_change2->execute();
					
					$single_change=\DB::update('singlebattlemaster')->set(array('battle_state'=>4));
					$single_change->where("id","=",$due_checks['id']);
					$single_change->execute();
					
				}
				if($due_checks['second_user_answer']==null&&$due_checks['first_user_answer']==null){
					$user_change1=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change1->where('user_name','=',$due_checks['first_user_name']);
					$user_change1->execute();
	
					$user_change2=\DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>\DB::expr('money - ' . $due_checks['bet_money']),'former_single_battle_id' =>""));
					$user_change2->where('user_name','=',$due_checks['second_user_name']);
					$user_change2->execute();
					
					$single_change=\DB::update('singlebattlemaster')->set(array('battle_state'=>4));
					$single_change->where("id","=",$due_checks['id']);
					$single_change->execute();
					
				}				
		}
		}

	}
}

/* End of file tasks/robots.php */
