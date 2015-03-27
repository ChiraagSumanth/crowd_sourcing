<?php

use App\TaskBuffer;

/*
function helper($userId)
{
		$result = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
		//$result = \DB::table('task_buffers')->select('id','domain_id', 'task_id_list')->where('user_id', $userId)->orderBy('id','desc')->first();
		
		
		if(isset($result))
		{
			$task_buffer_id=$result->id;
			$domain_id=$result->domain_id;
			$task=$result->task_id_list;
			$num_task=sizeof($task);
			
			if($num_task>0){
				$index=rand(0,$num_task-1);
				$task_id=$task[$index];
				$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id)->first();
				$taskId=$task_desc->id;
				$taskTitle=$task_desc->title;
				$taskType=$task_desc->type;
				$taskData=$task_desc->data;
				$answerType=$task_desc->answer_type;
				$answerData=$task_desc->answer_data;
				$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
				$response_array=array("status"=>"success","task"=>$task_json);
				//$task=array_splice($task,$index,1);
				//DB::table('task_buffers')->where('id', $task_buffer_id)->update(['task_id_list' => $task]);
			}
			
			else{
				$domains = \DB::table('task_buffers')->where('user_id', $userId)->lists('domain_id');
				$domains_all=\DB::table('domains')->lists('id');
				$diff=array_diff($domains_all,$domains);
				if(sizeof($diff)>0)
				{				
					$size=sizeof($diff);
					$index=rand(0,$size-1);
					$domain_id=$diff[$index];
					$task=\DB::table('tasks')->where('domain_id', $domain_id)->lists('id');
					$size=sizeof($task);
					$index=rand(0,$size-1);
					$task_id=$task[$index];
					$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id)->first();
					$taskId=$task_desc->id;
					$taskTitle=$task_desc->title;
					$taskType=$task_desc->type;
					$taskData=$task_desc->data;
					$answerType=$task_desc->answer_type;
					$answerData=$task_desc->answer_data;
					$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
					$response_array=array("status"=>"success","task"=>$task_json);
					//$task=array_splice($task,$index,1);
					
					//DB::table('task_buffers')->insert(['user_id' => $userId, 'domain_id' => $domain_id, 'task_id_list'=>$task]);
			}

			else{
					$response_array=array("status"=>"done");
				}			
			}
			
		
		}
		else if(!isset($result))
		{
			
				$domains_all=\DB::table('domains')->lists('id');
				$size=sizeof($domains_all);
				$index=rand(0,$size-1);
				$domain_id=$domains_all[$index];
				$task=\DB::table('tasks')->where('domain_id', $domain_id)->lists('id');
				$size=sizeof($task);
				$index=rand(0,$size-1);
				$task_id=$task[$index];
				$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id)->first();
				$taskId=$task_desc->id;
				$taskTitle=$task_desc->title;
				$taskType=$task_desc->type;
				$taskData=$task_desc->data;
				$answerType=$task_desc->answer_type;
				$answerData=$task_desc->answer_data;
				$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
				$response_array=array("status"=>"success","task"=>$task_json);
		}
		
		else
		{
			$response_array = array('status' => 'fail');
		}
		return $response_array;
	}
	
*/


















function helper($userId)
{

		$response=domain_helper($userId);
		
		if($response['status']=='done'){
			$response_array = array('status' => 'done');
		}
		
		else if($response['status']=='fail'){
			$response_array = array('status' => 'fail');
		}
		
		else if($response['status']=='success'){
			$domain=$response['domain'];
			$domain_id=$domain['domain_id'];
			$domain_desc=$domain['domain_description'];
		
		
		$result = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
		
	if(isset($result))
	{
		$task=$result->task_id_list;
		$task_buffer_id=$result->id;
		//$domain_id=$result->domain_id;
		$num_task=count($task);
		
		
		
		if ($result->pre_confidence_value==0){
			
			$response_array = array('status' => 'pre_confidence_value','domain'=>$domain);
		}
		else if(sizeof($task)==0 && $result->post_confidence_value==0){
			$response_array = array('status' => 'post_confidence_value','domain'=>$domain);
		
		}
		else if(sizeof($task)>0 && $result->post_confidence_value==0){
			
			$index=rand(0,$num_task-1);
			$task_id=$task[$index];
			$task_desc=\DB::table('tasks')->select('id','title','type','data','answer_type','answer_data')->where('id', $task_id)->first();
			$taskId=$task_desc->id;
			$taskTitle=$task_desc->title;
			$taskType=$task_desc->type;
			$taskData=$task_desc->data;
			$answerType=$task_desc->answer_type;
			$answerData=$task_desc->answer_data;
			$task_json=(array("taskId"=>$taskId,"taskTitle"=>$taskTitle,"taskType"=>$taskType,"taskData"=>$taskData,"answerType"=>$answerType,"answerData"=>$answerData));
			$response_array=array("status"=>"success","task"=>$task_json);
			
		}
		else{
			$response_array=array("status"=>"fail");
		}
	}
	else{
		$response_array=array("status"=>"fail");
	
	}
}	
	return $response_array;	
		
}









function domain_helper($userId)
{
		$result = TaskBuffer::where('user_id', $userId)->orderBy('id','desc')->first();
	
		if(isset($result))
		{
			$task_buffer_id=$result->id;
			$domain_id=$result->domain_id;
			$task=$result->task_id_list;
			$num_task=sizeof($task);
			
			if($num_task>0){
				$domain_desc=\DB::table('domains')->select('description')->where('id', $domain_id)->first();
				$desc=$domain_desc->description;
				$domain_json=(array("domain_id"=>$domain_id,"domain_description"=>$desc));
				$response_array=array("status"=>"success","domain"=>$domain_json);
				
			}
			
			else{
				$domains = \DB::table('task_buffers')->where('user_id', $userId)->lists('domain_id');
				$domains_all=\DB::table('domains')->lists('id');
				$diff=array_diff($domains_all,$domains);
				if(sizeof($diff)>0)
				{				
					$size=sizeof($diff);
					$index=rand(0,$size-1);
					$domain_id=$diff[$index];
					$domain_desc=\DB::table('domains')->select('description')->where('id', $domain_id)->first();
					$desc=$domain_desc->description;
					$domain_json=(array("domain_id"=>$domain_id,"domain_description"=>$desc));
					$response_array=array("status"=>"success","domain"=>$domain_json);
					$tasks=\DB::table('tasks')->where('domain_id',$domain_id)->lists('id');
				
					$tb=new TaskBuffer;
					$tb->domain_id=$domain_id;
					$tb->user_id=$userId;
					$tb->task_id_list=$tasks;
					$tb->save();					

			}

			else{
					$response_array=array("status"=>"done");
				}			
			}
			
		
		}
		else if(!isset($result))
		{
			
				$domains_all=\DB::table('domains')->lists('id');
				$size=sizeof($domains_all);
				$index=rand(0,$size-1);
				$domain_id=$domains_all[$index];
				$domain_desc=\DB::table('domains')->select('description')->where('id', $domain_id)->first();
				$desc=$domain_desc->description;
				$domain_json=(array("domain_id"=>$domain_id,"domain_description"=>$desc));
				$response_array=array("status"=>"success","domain"=>$domain_json);
				$tasks=\DB::table('tasks')->where('domain_id',$domain_id)->lists('id');
				$tb=new TaskBuffer;
				$tb->domain_id=$domain_id;
				$tb->user_id=$userId;
				$tb->task_id_list=$tasks;
				$tb->save();
		}
		
		else
		{
			$response_array = array('status' => 'fail');
		}
		return $response_array;
	}	
	
	

?>