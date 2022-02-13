<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Database\Seeder;

class InitProject extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setGroups = ['admin', 'manager', 'normal'];

        foreach ($setGroups as $group) {
            Group::create(['group_name' => $group]);
        }

        User::create([
            'name'      => 'admin',
            'email'     => 'admin@wasateam.com',
            'password'  => bcrypt(12345678)
        ]);

        UserGroup::create([
            'group_id' => 1,
            'user_id'  => 1
        ]);
    }
}
