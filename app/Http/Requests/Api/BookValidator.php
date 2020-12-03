<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\SceneValidator;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookValidator extends FormRequest implements ValidatesWhenResolved
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
        $curr_date = date('Y-m-d');
        return [
            'client_name' => 'required',
            'client_phone' => 'regex:/^1[345789][0-9]{9}$/',     //正则验证
            'province' => 'required',     //正则验证
            'city' => 'required',
            'district' => 'required',
            'community' => 'required',
            'house_name' => 'required',
            'arrive_time' => "date|after:{$curr_date}",
            'sex' => 'required'
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
            'client_name.required' => '请输入收货人姓名',
            'client_phone.regex' => '请输入正确的电话号',     //正则验证
            'province.required' => '请选择省份',     //正则验证
            'city.required' => '请选择城市',
            'district.required' => '请选择区域',
            'community.required' => '请输入小区',
            'house_name.required' => '请输入楼号',
            'sex.required' => '请选择性别',
            'arrive_time.date' => '到店时间错误',
            'arrive_time.after' => '到店时间要大于当时间'
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
