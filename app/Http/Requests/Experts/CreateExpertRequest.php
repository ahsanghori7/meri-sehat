<?php   namespace App\Http\Requests\Experts;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest as BaseRequest;

class CreateExpertRequest extends BaseRequest
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
            'email' => 'required|email|unique:experts',
            'name' => 'required|string|max:100',
            'tagline' => 'required|string|max:100',
            'about' => 'required|string|max:100',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|size:1024',
            'is_featured' => 'required',
            'speciality' => ['required','array'],
            'speciality.*' => ['required', 'exists:specialities,id']
        ];
    }
}
