<?php

use Illuminate\Database\Seeder;

class AppXUserTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>1,
			'user_id'=>1,
			'active'=>1
			)
		);	
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>1,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>1,
			'user_id'=>2,
			'active'=>1
			)
		);
		
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>2,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>3,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>4,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>5,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>6,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>7,
			'active'=>1
			)
		);
		\DB::table('seg_app_x_user')->insert(array(
			'app_id'=>2,
			'user_id'=>8,
			'active'=>1
			)
		);
		
	}
}
