<?php
namespace G3n1us\Pub\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;

class SaveUserRequest extends FormRequest
{
    public function __construct(Factory $factory)
    {
        $factory->extend('valid_group', function ($attribute, $value, $parameters)
            {
                return array_key_exists($value, config('groups'));
            },
            'One or more selected groups is not valid'
        );
    }
	
	
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
        return [
            'name'   => 'required',
            'email'  => 'required|email',
            'groups' => 'array',
            'groups.*' => 'valid_group',
        ];
    }
}
