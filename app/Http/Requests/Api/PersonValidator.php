<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\SceneValidator;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonValidator extends FormRequest implements ValidatesWhenResolved
{
    use SceneValidator;

    /**
     * 重写验证方法
     * @param Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $error= $validator->errors()->all();
        throw new HttpResponseException(response()->json(['code'=>1,'msg'=>$error[0],'data'=>''], 200));
    }

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

        ];
    }
    /**
     * 获取被定义验证规则的错误消息
     *
     * @return array
     * @translator laravelacademy.org
     */
    public function messages(){
        return [

        ];
    }

    /**
     * 场景规则
     * @return array
     */
    public function scene(){
        return [
            //add 场景
            'add' => [

            ],
            //edit场景
            'edit' => [

            ],
        ];
    }

}
