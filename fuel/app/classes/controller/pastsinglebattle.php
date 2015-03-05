<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Pastsinglebattle extends Controller_Admin
{
	public function action_index(){
		$total_page=DB::select()->from('singlebattlemaster')->where("battle_state","=", 3)->execute()->count();
		$config = array(
		    'pagination_url' => '/Pastsinglebattle/',
		    'total_items'    => $total_page,
		    'per_page'       => 30,
		    'uri_segment'    => 2,
		);
		
		$pagination = Pagination::forge('mypagination', $config);
		$data['past_single_battle'] = DB::select()
                            ->from('singlebattlemaster')
			　　->where("battle_state","=", 3)
                            ->limit($pagination->per_page)
                            ->offset($pagination->offset)
			-> order_by('id','desc')
                            ->execute()
                            ->as_array();
		$data['pagination'] = $pagination;
		return Response::forge(View::forge('result/singlebattle',$data));
	}
}
