<?php

namespace G3n1us\Pub\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePageRequest extends FormRequest
{
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
            'url'          => 'required|min:5|alpha_dash',
            'description'  => 'required|min:25',
            'name'         => 'required',
            'tags'         => 'array',
        ];
    }
}
