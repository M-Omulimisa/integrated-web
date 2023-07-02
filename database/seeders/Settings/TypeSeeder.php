<?php

namespace Database\Seeders\Settings;

use App\Models\Settings\Type;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class TypeSeeder extends Seeder
{
    use DisableForeignKeys, Uuid;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();        
        Type::query()->truncate();
        $now = \Carbon\Carbon::now();

        Type::query()->insert([
            [
                "id" => $this->generateUuid(),
                "name" => "App",
                "alias" => "app", 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                "id" => $this->generateUuid(),
                "name" => "Brand",
                "alias" => "brand", 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $this->enableForeignKeys();
    }
}