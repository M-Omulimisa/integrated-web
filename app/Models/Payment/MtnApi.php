<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class MtnApi extends Model
{

    protected $table = 'mtn_apis';
    protected $fillable = [
        'url',
        'api',
        'certificate',
        'key',
        'name', 
    	'code',
        'username',
        'password',
        'description',
        'status'
    ]; 

    public function decryptData($param)
    {
        return openssl_decrypt($this->$param, config('mtnpay.cipher'), str_replace(" ", "", $this->code));
    }

}