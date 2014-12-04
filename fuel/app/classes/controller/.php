<?php

class Controller_Toppage extends Controller
{


	public function action_index()
	{
		return Response::forge(View::forge('toppage/index'));
	}

}
