<?php

require_once 'twitteroauth/twitteroauth.php';

class Controller_Rule extends Controller
{

	public function action_index()
	{
		return Response::forge(View::forge('rule/rule'));

	}
}
