<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Singlebattle extends Controller_Admin
{
	public function action_register(){
		$bet_money=Input::post('bet_money');
		$user_money=Session::get('money');
		$user_name=Session::get('user_name');
		$bet_money = str_replace("円", "", $bet_money);

		if($bet_money>$user_money){
			$message="自分の所持金以上のお金は賭けられません";
		}elseif(!(preg_match("/^[0-9]+$/", $bet_money))){
			$message="半角数字のみを入力してください";			
		}elseif($bet_money==""){
			$message="賭け金を入力してください";
		}else{
			$rand=mt_rand ( 1 , 2);
			$single_battle_register=DB::insert('singlebattlemaster');
			if($rand==1){
				$single_battle_register->set(array('first_user_name'=>$user_name,'bet_money'=>$bet_money));
			}else{
				$single_battle_register->set(array('second_user_name'=>$user_name,'bet_money'=>$bet_money));			
			}
			$single_battle_register->execute();
			
			$battle_id_query = DB::select('id')->from('singlebattlemaster');
			$battle_id_query ->and_where('battle_state','=',0);
			$battle_id_query -> where('first_user_name','=',$user_name);
			$battle_id_query -> or_where('second_user_name','=',$user_name);
			
			$battle_id = $battle_id_query ->execute()->as_array();
			$single_battle_flg_change=DB::update('userinfo')->set(array('single_post_flg'=>1,'single_battle_id' => $battle_id[0]['id']));
			$single_battle_flg_change -> where('user_name','=',$user_name);
			$single_battle_flg_change->execute();
			
			$single_battle_flg_change=DB::update('userinfo')->set(array('single_post_flg'=>1,'single_battle_id' => $battle_id[0]['id']));
			$single_battle_flg_change -> where('user_name','=',$user_name);
			$single_battle_flg_change->execute();
			
			
			$message="登録完了";		
		}
		Session::set_flash('message', $message);
		
		return Response::forge(View::forge('singlebattle/complete'));
		
	}
	
	public function action_single_battle_apply(){
		
		Input::post('single_battle_id');
		$user_name=Session::get('user_name');
		$single_post_flg=Session::get('single_post_flg');
		$single_battle_id1=Input::post('single_battle_id');
		$single_battle_id2=Session::get('single_battle_id');
		$apply_flg=input::post('apply_flg');
		
		//お題をとってくる
		if($single_post_flg==0){
			$question_query = DB::select()->from('questionlist');
			$question_query -> limit(1);
			$question_query -> order_by(\DB::expr('RAND()'));
			$question_search_result = $question_query -> execute() -> as_array();
			
			$due_date = date("y/n/j", strtotime("+2 day"));

			//タイマンバトルを登録して、お題も設定する
			$single_apply_register=DB::update('singlebattlemaster');
			
			//最初に登録した人がfirstuserに登録されてるなら、seconduserに、seconduserに登録されてるならfirstuserに登録する。
			$user_check_query=DB::select('first_user_name')->from('singlebattlemaster');
			$user_check_query -> where('id','=',Input::post('single_battle_id'));
			$user_check=$user_check_query -> execute()->as_array();
			if(empty($user_check[0]['first_user_name'])){
				$single_apply_register->set(array('first_user_name'=>$user_name,'question'=>$question_search_result[0]['question'],'battle_state'=>1,'due_date'=>$due_date));
			}else{
				$single_apply_register->set(array('second_user_name'=>$user_name,'question'=>$question_search_result[0]['question'],'battle_state'=>1,'due_date'=>$due_date));			
			}
			
			$single_apply_register -> where('id','=',Input::post('single_battle_id'));
			
		
			$single_battle_flg_change=DB::update('userinfo')->set(array('single_post_flg'=>1,'single_battle_id' => Input::post('single_battle_id')));
			
			//対戦申し込みからの登録なら
			if($apply_flg==1){
				$single_apply_register->value('battle_apply_flg', 2);
			}
			
			$single_apply_register -> execute();
			
			$single_battle_flg_change -> where('user_name','=',$user_name);
			$single_battle_flg_change->execute();
			
				if($apply_flg==1){			
					$from_user_query = DB::select('from_user_name')->from('singlebattlemaster');
					$from_user_query->where('battle_state','=','0');
					$from_user_query ->where('apply_user_name','=',$user_name);
					$from_user_list=$from_user_query ->execute()->as_array();
					
					$change_flg = DB::update('userinfo')->set(array('single_post_flg'=>0));
					$change_flg ->where('user_name', 'in', $from_user_list);
					$change_flg ->execute();
					
					//$change_flg = DB::update('userinfo')->set(array('single_post_flg'=>0));
					//$change_flg ->where('user_name', 'in', DB::expr('select from_user_name from single_battle_maseter where battle_state=0 and apply_user_name='.$user_name));
					//$change_flg ->execute();					
					$delete_query = DB::delete('singlebattlemaster')->where('battle_state','=','0');
					$delete_query ->where('apply_user_name','=',$user_name);
					$delete_query ->execute();

				}

			Session::set_flash('message', "申し込み完了");
			return Response::forge(View::forge('singlebattle/complete'));
		}else{
			Session::set_flash('message', "２戦以上タイマンバトルはできません");
		}		
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);	
	}
	public function action_receiveanswer(){
		
		$sigle_battle_id=Input::post('single_battle_id');
		$users_answer=Input::post('users_answer');
		$user_name=Session::get('user_name');
		//firstuserを取得する
		$first_user_name_query = DB::select('first_user_name','first_user_answer','second_user_answer')->from('singlebattlemaster');
		$first_user_name_query->where('id','=',$sigle_battle_id);
		$first_user_name_result = $first_user_name_query-> execute() -> as_array();
		
		//日付を取得
		$date=date("Y/m/d  H:i:s");
		
		//投票したものがfirstuserならfirstuseranswerに挿入、secondならsecondに
		if($first_user_name_result[0]['first_user_name']==$user_name){
			$single_answer_register=DB::update('singlebattlemaster');
			$single_answer_register->set(array('first_user_answer'=>$users_answer));
		//既に対戦相手がネタを投稿しているならフラグを変える	
			if (strlen($first_user_name_result[0]['second_user_answer'])!=0) {
				$single_state_change=DB::update('singlebattlemaster');
				$single_state_change->set(array('battle_state'=>2,'vote_start_time'=>$date));
				$single_state_change->execute();
			}
		}else{
			$single_answer_register=DB::update('singlebattlemaster');
			$single_answer_register->set(array('second_user_answer'=>$users_answer));
		//既に対戦相手がネタを投稿しているならフラグを変える	
			if (strlen($first_user_name_result[0]['first_user_answer'])!=0) {
				$single_state_change=DB::update('singlebattlemaster');
				$single_state_change->set(array('battle_state'=>2,'vote_start_time'=>$date));
				$single_state_change->execute();
			}
		}

		$single_answer_register->where('id','=',$sigle_battle_id);
		$single_answer_register->execute();
		
		Session::set_flash('message', "投稿完了");
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);	
		
	}
	public function action_single_vote_display(){
		$data['message']=Session::get_flash('message');
		$data['user_name']=Session::get('user_name');
		$user_name = Cookie::get('user_name');
		if(isset($user_name)){
			//投票期間中のバトルを取得
			$single_battle_query = DB::select()->from('singlebattlemaster');	
			$single_battle_query->where('battle_state','=',2);
			$single_battle_random_list	= $single_battle_query -> execute() -> as_array();
			
			shuffle($single_battle_random_list);
			
			$data['single_battle_top_list']=$single_battle_random_list;
			
			
			$vote_number_array="";
			foreach ($single_battle_random_list as $single_battle_random_lists) {
				//今の投票人数を取得する
				$single_count=DB::select('id')->from('single_battle_vote');
				$single_count->where('single_battle_id','=',$single_battle_random_lists['id']);
				$single_count_list=$single_count-> execute() -> as_array();
				$single_count_num=count($single_count_list);
				
				$vote_number_array[$single_battle_random_lists['id']]=$single_count_num;
			}
			
			$data['vote_number']=$vote_number_array;
			
			return Response::forge(View::forge('singlebattle/vote',$data));
		}else{
			$base_url= Uri::base();
			Response::redirect($base_url."confirmlogin");	
		}
	}
	public function action_single_vote_register(){

		//得点を取得
		$first_user_key="first_user".input::post('single_battle_id');
		$second_user_key="second_user".input::post('single_battle_id');
		
		$first_user_point=input::post($first_user_key);
		$second_user_point=input::post($second_user_key);
		
		$user_name=Session::get('user_name');
		$single_count=DB::select('id')->from('single_battle_vote');
		$single_count->where('single_battle_id','=',input::post('single_battle_id'));
		$single_count_list=$single_count-> execute() -> as_array();
		$single_count_num=count($single_count_list);

		$single_search_register=DB::select('id')->from('single_battle_vote');
		$single_search_register->where('user_name','=',$user_name);
		$single_search_register->where('single_battle_id','=',input::post('single_battle_id'));
		$single_search=$single_search_register-> execute() -> as_array();

		if (empty($single_search)) {
			$single_vote_register=DB::insert('single_battle_vote')
			->set(array('first_user_point'=>$first_user_point,
			'second_user_point'=>$second_user_point,
			'user_name'=>$user_name,'single_battle_id'=>input::post('single_battle_id')));
			$single_vote_register->execute();
			
			//ユーザーの投票フラグを１にする。
			$user_info_query=DB::update('userinfo')->set(array('money'=>DB::expr('money + ' . 150)));
			$user_info_query->where('user_name','=',$user_name);
			$user_info_query->execute();
			
			Session::set_flash('message', "投票完了しました");

			//既に5人投票済みなら採点する
			if($single_count_num==5){
			
				$single_battle_result_query = DB::select()->from('singlebattlemaster');
				$single_battle_result_query ->where('id','=',input::post('single_battle_id'));
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
														'first_five_point'=>$first_five_point_person,
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
			
		}else{
			Session::set_flash('message', "あなたは既に投票済みです");
			
		}
		
		//投票ページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url."singlebattle/single_vote_display");

	}
	
	public function action_deletebattle(){
		$single_battle_id=input::post('single_battle_id');
		$user_name=Session::get('user_name');
		
		$user_list_query=DB::select()->from('userinfo');
		$user_list_query->where('user_name','=',$user_name);
		$user_list_result = $user_list_query->execute()->as_array();
		
		if($user_list_result[0]['single_battle_id']==$single_battle_id){
			$delete_query = DB::delete('singlebattlemaster')->where('id','=',$single_battle_id);
			$delete_query ->execute();
			$user_change=DB::update('userinfo')->set(array('single_post_flg'=>0));
			$user_change->where('user_name','=',$user_name);
			$user_change->execute();
			Session::set_flash('message', "削除完了しました");
		}else{
			Session::set_flash('message', "不正な値です");
		}
		//TOPページに飛ばす
		$base_url= Uri::base();
		Response::redirect($base_url);

	}

	public function action_tweetsinglebattle(){
		define('CONSUMER_KEY', 'wixFFix8Hy4uCrLRr79I6sOP7');
		define('CONSUMER_SECRET', '2lfG4O6QXt1q0JHa2zC5CCfNR51hoTI6ptgIsrBPFGiBlBekAI');
		$oauth_token = Cookie::get('oauth_token');
		$oauth_token_secret =Cookie::get('oauth_token_secret');
		$oauth_verifier =Cookie::get('oauth_verifier');
		$user_name=Session::get('user_name');
		$base_url= Uri::base();
		
		$user_list_query=DB::select()->from('userinfo');
		$user_list_query->where('user_name','=',$user_name);
		$user_list_result = $user_list_query->execute()->as_array();

		$single_battle_id = $user_list_result[0]['single_battle_id'];
		$single_query = DB::select()->from('singlebattlemaster')->where('id','=',$single_battle_id);
		$single_info=$single_query ->execute();
			
		$bet_money = $single_info[0]['bet_money'];
		if($single_info[0]['battle_state']==0){
			$message = "賭け金".$bet_money."円でタイマンバトル募集中！　".$base_url."singlebattlerequirement";
		}else{
			$message = "@".$single_info[0]['first_user_name']." VS　"."@".$single_info[0]['second_user_name']." バトル開始".$base_url."singlebattlerequirement";
		}
		// access token 取得
		$tw = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,
		$oauth_token, $oauth_token_secret);
		
		//投稿
		$tw->post('statuses/update', array('status' => $message));
		
		Session::set_flash('message', "tweetしました");

		//TOPページに飛ばす
		Response::redirect($base_url);

	}


}
