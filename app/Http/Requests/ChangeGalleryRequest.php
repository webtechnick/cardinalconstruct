<?php

namespace App\Http\Requests;

use App\Gallery;
use App\Http\Requests\Request;

class ChangeGalleryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gallery::where([
            'slug' => $this->slug,
            'user_id' => $this->user()->id
        ])->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'required|mimes:jpg,jpeg,png,gif'
        ];
    }
}
