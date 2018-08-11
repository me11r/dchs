<?php


use Illuminate\Database\Seeder;

class FireDepartmentUsersSeeder extends Seeder
{
    public function run()
    {
        $departments = \App\FireDepartment::all();
        foreach ($departments as $department) {
            $deptuser = new \App\User();
            $deptuser->name = $department->name;
            $deptuser->password = bcrypt('password');
            $deptuser->email = 'fd' . $department->id . '@localhost.local';
            $deptuser->fire_department_id = $department->id;
            $deptuser->save();
            $deptuser->rights()->sync([1, 2, 8]);
        }
    }
}