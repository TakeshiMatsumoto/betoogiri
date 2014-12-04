<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_applysinglebattle extends Controller_Admin
{
	public function action_index(){
		$user_name = Cookie::get('user_name');
		if(isset($user_name)){
			$user_query = DB::select()->from('userinfo');
			$user_query ->where('single_post_flg','=',0);
			$user_list=$user_query ->execute()->as_array();
			$data['user_list']=$user_list;
			$data['user_name']=Session::get('user_name');
			return Response::forge(View::forge('applysinglebattle/index',$data));
		}else{
			$base_url= Uri::base();
			Response::redirect($base_url."confirmlogin");				
		}
	}
	
	public function action_register(){
		$bet_money=Input::post('bet_money');
		$user_money1=Session::get('money');
		$user_money2=Input::post('for_money');
		//応募した人のユーザー名
		$user_name1=Session::get('user_name');
		//応募された人のユーザー名
		$user_name2=Input::post('user_name');
		
		$bet_money = str_replace("円", "", $bet_money);

		if($bet_money>$user_money1 or $bet_money>$user_money2){
			$message="自分/相手の所持金以上のお金は賭けられません";
		}elseif(!(preg_match("/^[0-9]+$/", $bet_money))){
			$message="半角数字のみを入力してください";			
		}elseif($bet_money==""){
			$message="賭け金が空です";				
		}
		else{
			$rand=mt_rand ( 1 , 2);
			$single_battle_register=DB::insert('singlebattlemaster');
			if($rand==1){
				$single_battle_register->set(array('first_user_name'=>$user_name1,
													'bet_money'=>$bet_money,
													'battle_apply_flg'=>1,
													'from_user_name'=>$user_name1,
													'apply_user_name'=>$user_name2));
			}else{
				$single_battle_register->set(array('second_user_name'=>$user_name1,
													'bet_money'=>$bet_money,
													'battle_apply_flg'=>1,
													'from_user_name'=>$user_name1,
													'apply_user_name'=>$user_name2));
			}
			$single_battle_register->execute();
			
			$battle_id_query = DB::select('id')->from('singlebattlemaster');
			$battle_id_query ->and_where('battle_state','=',0);
			$battle_id_query -> where('first_user_name','=',$user_name1);
			$battle_id_query -> or_where('second_user_name','=',$user_name1);
			
			$battle_id = $battle_id_query ->execute()->as_array();
			$single_battle_flg_change=DB::update('userinfo')->set(array('single_post_flg'=>1,'single_battle_id' => $battle_id[0]['id']));
			$single_battle_flg_change -> where('user_name','=',$user_name1);
			$single_battle_flg_change->execute();
			$message="登録完了";		
		}
			Session::set_flash('message', $message);
			//TOPページに飛ばす
			$base_url= Uri::base();
			Response::redirect($base_url);	
		
	}
}
