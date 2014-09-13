<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('AddAdminToUserTable');
    }

}

class AddAdminToUserTable extends Seeder {
    public function run()
    {
        $user = new User;
        $user->mail = $_ENV['ADMIN_MAIL'];
        $user->password = Hash::make($_ENV['ADMIN_PASSWORD']);
        $user->name = 'Administrator';
        $user->username = 'admin';
        $user->title_id = 1;
        $user->section_id = 27; // we all love Bergamo, don't we?
        $user->category_id = 1;
        $user->admin = true;
        $user->save();
    }
}
