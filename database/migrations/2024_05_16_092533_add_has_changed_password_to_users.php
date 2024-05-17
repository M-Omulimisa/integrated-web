<?php

use App\Models\User;
use App\Models\Utils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasChangedPasswordToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            Utils::create_column(
                (new User())->getTable(),
                [
                    [
                        'name' => 'has_changed_password',
                        'type' => 'String',
                        'default' => 'No',
                    ],
                    [
                        'name' => 'raw_password',
                        'type' => 'String',
                    ],
                    [
                        'name' => 'reset_password_token',
                        'type' => 'String',
                        'default' => 'No',
                    ],
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
