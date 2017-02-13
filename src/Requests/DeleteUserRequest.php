<?php
namespace G3n1us\Pub\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
use User;

class DeleteUserRequest extends FormRequest
{
	
	
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    if(User::count() == 1) return false;
	    
	    $groups = $this->user()->groups->pluck('group', 'group');
	    return $groups->has('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [];
    }
}
