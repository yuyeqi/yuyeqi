<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GoodsValidator extends FormRequest implements ValidatesWhenResolved
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
        $id = $this->route('id');
        return [
            'username' => 'required|max:32|unique:hp_admin,username,'.$id,
            'account' => 'required|max:20|unique:hp_admin,account,'.$id,
            'phone' => 'required|size:11|unique:hp_admin,phone,'.$id,
            'email' => 'required|email',
            'sex' => 'required',
            'password' => 'required|between:6,18'
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
            'username.required' => '用户名不能为空',
            'username.max'  => '用户名限制长度32',
            'username.unique' => '用户名已存在',
            'account.required' => '账户不能为空',
            'account.max'  => '账户限制长度20',
            'account.unique' => '账户已存在',
            'phone.required' => '电话不能为空',
            'phone.max'  => '电话号码格式错误',
            'phone.unique' => '电话号码已存在',
            'password.required' => '密码不能为空',
            'password.between' => '密码长度必须在6-12'
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
                'username' ,       //复用 rules() 下 name 规则
                'account',
                'phone',
                'email',
                'sex',
                'password'
            ],
            //edit场景
            'edit' => [
                'username' ,       //复用 rules() 下 name 规则
                'account',
                'phone',
                'email',
                'sex',
            ],
        ];
    }

}
