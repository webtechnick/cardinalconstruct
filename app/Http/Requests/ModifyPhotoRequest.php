<?php

namespace App\Http\Requests;

use App\Gallery;
use App\Http\Requests\Request;

class ModifyPhotoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If we aren't a user, return false
        if (! $this->user()) {
            return false;
        }

        // If we own this gallery
        $userowned = Gallery::where([
            'slug' => $this->slug,
            'user_id' => $this->user()->id
        ])->exists();

        $result = ($this->user()->isAdmin() || $userowned);

        dd($result);

        return $result;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
