<?php

namespace App\Http\Requests;

use App\Gallery;
use App\Http\Requests\Request;

class AddPhotoRequest extends Request
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

        $isAdmin = $this->user()->isAdmin();

        $isWorker = $this->user()->isWorker();

        return ($isAdmin || $isWorker || $userowned);
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
