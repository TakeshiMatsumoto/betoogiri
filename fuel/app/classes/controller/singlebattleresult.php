<?php


class Controller_Singlebattleresult extends Controller_admin
{

	public function action_index()
	{
				//１日前の日付を設定
		$date=date("Y-m-d H:i:s",strtotime("-1 day"));
		
		//結果発表の時間が過ぎていて、かつ、投票期間中のデータを持ってくる
		$single_battle_result_query = DB::select()->from('singlebattlemaster');
		$single_battle_result_query ->where('battle_state','=',2);
		//$single_battle_result_query ->where('vote_start_time','>',$date);
		$single_battle_result=$single_battle_result_query->execute()->as_array();

		foreach($single_battle_result as $single_battle_results){
			//合計ポイントを出す
			$single_search_register=DB::select(DB::expr('SUM(first_user_point) as first_user_total_point'),DB::expr('SUM(second_user_point) as second_user_total_point','bet_money'))
			->from('single_battle_vote');
			$single_search_register->group_by('single_battle_id');
			$single_search_register->where('single_battle_id','=',$single_battle_results['id']);
			$total_point=$single_search_register->execute()->as_array();
			

			//誰が何ポイント入れたかを取得するためのもの
			$point_search_register=DB::select()->from('single_battle_vote');
			$point_search_register->where('single_battle_id','=',$single_battle_results['id']);
			$point_list=$point_search_register->execute()->as_array();	
			
			//firstにそれぞれの点数をいれた人の名前
			$first_one_point_person="";
			$first_three_point_person="";
			$first_five_point_person="";
			
			//secondにそれぞれの点数をいれた人の名前
			$second_one_point_person="";
			$second_three_point_person="";
			$second_five_point_person="";			

			//first,secondそれぞれに誰が何点いれたかをいれていく
			foreach ($point_list as $point_lists) {

				switch ($point_lists['first_user_point']) {
				    case "1":
				        $first_one_point_person= $first_one_point_person.$point_lists['user_name']."/";
				        break;
				    case "3":
				        $first_three_point_person=$first_three_point_person.$point_lists['user_name']."/";
				        break;
				    case "5":
				        $first_five_point_person=$first_five_point_person.$point_lists['user_name']."/";
				        break;
				}
				switch ($point_lists['second_user_point']) {
				    case "1":
				        $second_one_point_person=$second_one_point_person.$point_lists['user_name']."/";
				        break;
				    case "3":
				        $second_three_point_person=$second_three_point_person.$point_lists['user_name']."/";
				        break;
				    case "5":
				        $second_five_point_person=$second_five_point_person.$point_lists['user_name']."/";
				        break;
				}
				
			}
			

			$single_point_register=DB::update('singlebattlemaster');
			$single_point_register->set(array('first_total_point'=>$total_point[0]['first_user_total_point'],
												'first_one_point'=>$first_one_point_person,
												'first_three_point'=>$first_three_point_person,
												'first_five_point'=>$first_one_point_person,
												'second_total_point'=>$total_point[0]['second_user_total_point'],												
												'second_one_point'=>$second_one_point_person,
												'second_three_point'=>$second_three_point_person,
												'second_five_point'=>$second_five_point_person,	
												'battle_state'=>3,												
												));
			$single_point_register->where('id','=',$single_battle_results['id']);
			$single_point_register->execute();

			$first_user_name=$single_battle_results['first_user_name'];
			$second_user_name=$single_battle_results['second_user_name'];
			

			if($total_point[0]['first_user_total_point']>$total_point[0]['second_user_total_point']){				
				$win_user=$first_user_name;
			}else{
				$win_user=$second_user_name;
			}
			$bet_money = $single_battle_results['bet_money'];

			//投稿者のタイマンバトルフラグを０にする（タイマンバトルをしていない状態にする）
			if($win_user==$first_user_name){
				$first_user_change=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money + ' . $bet_money),'former_single_battle_id' =>$single_battle_results['id']));
			}else{
				$first_user_change=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $bet_money),'former_single_battle_id' =>$single_battle_results['id']));					
			}
			$first_user_change->where('user_name', '=',$first_user_name);
			$first_user_change->execute();
			
			//投稿者のタイマンバトルフラグを０にする（タイマンバトルをしていない状態にする）
			if($win_user==$second_user_name){
				$second_user_change=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money + ' . $bet_money),'former_single_battle_id' =>$single_battle_results['id']));
			}else{
				$second_user_change=DB::update('userinfo')->set(array('single_post_flg'=>0,'money'=>DB::expr('money - ' . $bet_money),'former_single_battle_id' =>$single_battle_results['id']));					
			}
			$second_user_change->where('user_name', '=',$second_user_name);
			$second_user_change->execute();	
		}
	
	}
}
