<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindBookRequest extends FormRequest
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
         * always convert {id} from route parameter to standard http form
         */
        $this->mergeIfMissing(['id' => (int) $this->id]);
    }
}
