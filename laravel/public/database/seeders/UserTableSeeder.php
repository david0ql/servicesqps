<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Felix pinto';
        $user->email = 'felixpinto@qps1.com';
        $user->password = bcrypt('password');
        $user->save();

        $user = new User();
        $user->id = 2;
        $user->name = 'Jose Febles';
        $user->email = 'josefebles@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 3;
        $user->name = 'Kike';
        $user->email = 'kike@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 4;
        $user->name = 'Mike';
        $user->email = 'mike@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 5;
        $user->name = 'Juan Santos';
        $user->email = 'juansantos@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 6;
        $user->name = 'Jessy';
        $user->email = 'jessy@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 7;
        $user->name = 'John Zapata';
        $user->email = 'johnzapata@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 8;
        $user->name = 'Nestor';
        $user->email = 'nestor@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 9;
        $user->name = 'Jhonny';
        $user->email = 'jhonny@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        $user = new User();
        $user->id = 10;
        $user->name = 'Ricardo';
        $user->email = 'rocardo@qps1.com';
        $user->password = bcrypt('12345678');
        $user->save();

        //Cleanners

        $user = new User();
        $user->id = 11;
        $user->name = 'Elmida';
        $user->email = 'elmida@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 12;
        $user->name = 'Milvia';
        $user->email = 'milvia@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 13;
        $user->name = 'Giovanna';
        $user->email = 'giovanna@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 14;
        $user->name = 'Thays';
        $user->email = 'thays@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 15;
        $user->name = 'Felix';
        $user->email = 'felix@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 16;
        $user->name = 'Yanelis';
        $user->email = 'yanelis@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 17;
        $user->name = 'Hugo';
        $user->email = 'hugo@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 18;
        $user->name = 'Rebeca';
        $user->email = 'rebeca@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 19;
        $user->name = 'Merlyn';
        $user->email = 'merlyn@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();

        $user = new User();
        $user->id = 20;
        $user->name = 'Ana Roraima';
        $user->email = 'anaroraima@qps1.com';
        $user->password = bcrypt('987654321');
        $user->save();
    }
}
