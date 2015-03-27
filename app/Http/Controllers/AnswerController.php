<?php namespace App\Http\Controllers;

use App\Answer;
use App\Client;
use App\Http\Requests;
use Illuminate\Cookie\CookieJar;
use App\Http\Controllers\Controller;

class AnswerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	public function store()
	{
		$user_id = \Request::cookie('crowd_id');

		if ($this->check_not_user($user_id))
			return \Response::json(['status' => 'failure'], 403);

		$task_id = \Request::input('task_id');

		if (isset($task_id))
			return $this->handle_task_answer($task_id, \Request::input('data'), \Request::input('time_taken'), $user_id);
		else
			return $this->handle_domain_answer(\Request::input('domain_id'), \Request::input('rank'), $user_id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	protected function handle_task_answer($task_id, $data, $time_taken, $user_id)
	{
		$answer = new Answer;
		$answer->data = $data;
		$answer->task_id = $task_id;
		$answer->time_taken = $time_taken;
		$answer->user_id = $user_id;

		if ($answer->save())
			$response_array = array('status' => 'success');
		else
			$response_array = array('status' => 'fail');

		return \Response::json($response_array, 200);
	}

	protected function check_not_user($user_id)
	{
		$user = Client::where('id', '=', (string) $user_id)->first();
		if ($user == null)
			return true;
		else
			return false;
	}

	protected function handle_domain_answer($domain_id, $rank, $user_id)
	{
		$task_buffer = TaskBuffer::where('user_id', $user_id)->orderBy('id', 'desc')->first();
		
		if ($task_buffer->task_id_list == [] && $task_buffer->post_confidence_value == null)
		{
			$task_buffer->post_confidence_value = $rank;
			if($task_buffer->save())
				return \Response::json(['status' => 'success'], 200);
			else
				return \Response::json(['status' => 'failure'], 200);
		}
		else if (count($task_buffer->task_id_list) == count($task_buffer->domain()->first()->tasks()))
		{
			$task_buffer->pre_confidence_value = $rank;
			if($task_buffer->save())
				return \Response::json(['status' => 'success'], 200);
			else
				return \Response::json(['status' => 'failure'], 200);
		}
		else
			return \Response::json(['status' => 'failure'], 200);
	}
}
