<?php
namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Carbon\Carbon;

/**
 * Class FormatPermission.
 */
trait FormatPermission
{
    use Uuid;
    /**
     * @param $name
     *
     * @return bool | mixed
     */
    protected function webPermissions($name, $type, $group, $description)
    {
        return [
            'id'            => $this->generateUuid(),
            'name'          => $name, 
            'type_id'       => $type, 
            'group_name'    => $group, 
            'guard_name'    => 'web',
            'description'   => removeStr($description, '_'), 
            'created_at'    => Carbon::now(), 
            'updated_at'    => Carbon::now()
        ];
    }

    /**
     * @param array $tables
     */
    protected function apiFormat(array $tables)
    {
    
    }
}
