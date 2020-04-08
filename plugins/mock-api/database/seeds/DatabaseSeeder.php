<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('credential_verify_request')->insert([
            'id' => 1,
            'callbackURL' => 'http://localhost/',
            'credentialTypes' => '{ "type": "https://schema.org/PostalAddress",  "issuer": "some-id-of-the-BRP" }',
        ]);
    }
}
