<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		DB::statement("SET foreign_key_checks = 0");
		$this->call('DomainTableSeeder');

		$this->call('TaskTableSeeder');
		DB::statement("SET foreign_key_checks = 1");	
		//		$this->call('UserTableSeeder');
//		$this->call('TaskBufferTableSeeder');
//		$this->call('AnswerTableSeeder');
		
	}

}
