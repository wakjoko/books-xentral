<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class SaveBookRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'author' => 'required|string|min:5|max:255',
            'genre' => 'required|string|min:5|max:255',
            'total_pages' => 'required|integer|gte:5',
            'id' => 'sometimes',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        /**
         * always add user_id to scope db query by specific user
         */
        $add['user_id'] = $this->user()->id;

        $doesntContainId = ! Arr::exists($validated, 'id');

        /**
         * set default status_id only when storing a new book
         */
        if ($doesntContainId) {
            $add['status_id'] = array_key_first(Book::STATUSES);
        }

        return array_merge($validated, $add);
    }

    protected function prepareForValidation(): void
    {
        /**
         * convert {id} from route parameter to standard http form
         * only when updating existing book
         */
        if ($this->id) {
            $this->mergeIfMissing(['id' => (int) $this->id]);
        }
    }
}
