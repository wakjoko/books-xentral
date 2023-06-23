<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookProgressRequest extends FormRequest
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
            'id' => 'required',
            'last_page' => 'required',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        /**
         * always add user_id to scope db query by specific user
         */
        $add['user_id'] = $this->user()->id;

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
