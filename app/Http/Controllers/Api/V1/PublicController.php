<?php


namespace App\Http\Controllers\Api\V1;


use App\Library\Render;
use http\Env\Request;
use Illuminate\Support\Facades\Validator;

;

class PublicController
{
    /**
     * 获取小程序的openid
     * @param $code
     * @return int
     */
    public function getWxInfo($code){
        return 1;
    }
    public function login(Request $request){
        //小程序登陆
        $post = $request->only(['encryptedData', 'iv', 'code']);
        $validator = Validator::make($post, [
            'encryptedData' => 'required',
            'iv'            => 'required',
            'code'          => 'required'
        ]);
        if ($validator->fails()){
            return Render::json(1002,'非法请求');
        }
        //解密微信电话号码
        $decryption = (new BdDataDecrypt())->decrypt($post['encryptedData'],$post['iv'],$post['code']);
        $user = 1;
        if ($user){
            //设置登陆时间
            $user->save();
        }else{
            //用户不存在新增用户
            $user = [];
        }
    }
}
