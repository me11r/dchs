<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RightsSeeder::class);
        $this->call(AddAdminSeeder::class);

        $this->call(DictionarySeeder::class);
        $this->call(MudflowProtectionSeeder::class);
    }
}
