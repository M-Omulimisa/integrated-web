<?php

namespace App\Http\Requests\Organisations;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Users\Role;
use Auth;


class StoreOrganisationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    protected function prepareForValidation(){

        $this->merge([
            'roles' => Role::ROLE_ORG_ADMIN,
            'status' => 'Active',
            'created_by' => Auth::user()->id,
            
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'name' => 'required',
            'organisation' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'services' => 'required',
            'roles' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
            'status' => 'required',
            'dialing_code' => 'required',
            'phone' => 'required',
            'created_by' => 'required',
            'status' => 'required',
            'file' => 'nullable|mimes:png,jpg,jpeg|max:5120',
        ];
    }
}
