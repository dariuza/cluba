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

		$this->call('RolTableSeeder');
		$this->call('UserTableSeeder');		
		$this->call('UserProfileTableSeeder');
		$this->call('AppTableSeeder');
		$this->call('AppXUserTableSeeder');
		$this->call('ModuleTableSeeder');
		$this->call('OptionTableSeeder');
		$this->call('PermitTableSeeder');	
		$this->call('CluStateTableSeeder');
		$this->call('CluSuscriptionTableSeeder');
		$this->call('CluLicenseTableSeeder');
		$this->call('EntityTableSeeder');
		$this->call('SubEntityTableSeeder');
		$this->call('SpecialtyTableSeeder');		
		$this->call('DepartmentTableSeeder');
		$this->call('CityTableSeeder');
		$this->call('SpecialistTableSeeder');
		$this->call('SpecialistXSpecialtyTableSeeder');		
		$this->call('AvailableTableSeeder');			
		$this->call('AvailableXSpecialtyTableSeeder');	
		$this->call('BeneficiaryTableSeeder');	
		$this->call('ServiceTableSeeder');			

	}

}
