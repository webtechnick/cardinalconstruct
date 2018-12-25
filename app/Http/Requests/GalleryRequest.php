<?php

namespace App\Http\Requests;

use App\Gallery;
use App\Http\Requests\Request;

class GalleryRequest extends Request
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

        return ($this->user()->isAdmin() || $userowned);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Don't need validation if we're trying to delete
        if ($this->method == 'DELETE') {
            return [];
        }
        return [
            'title' => 'required',
            'body' => 'required'
        ];
    }
}
