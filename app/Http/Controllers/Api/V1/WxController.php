<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\User;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


/**
 * 微信相关控制器
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class WxController extends Controller
{

    private $app;   //小程序实例
    private $userService;  //用户服务层

    public function __construct()
    {
        $config = config('wechat.mini_program.default');
        $this->app = isset($this->app) ?: Factory::miniProgram($config);
        $this->userService = isset($this->userService) ?: new UserService();
    }


    /**
     * 微信授权登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request)
    {
        //1.验证小程序code
        $code = $request->input('code', '');
        if (empty($code)) {
            Log::error('【微信授权登录】----------缺少必要参数code');
            return Render::error('缺少微信code');
        }
        //2.获取微信的openid
        try {
            $wechat = $this->app->auth->session($code);
        } catch (InvalidConfigException $e) {
            Log::error('【微信授权登录】----获取openid和session_key失败');
            return Render::error('登录失败');
        }
        if (isset($wechat['errcode'])) {
            Log::error('【微信授权登录】----授权登录失败:errorInfo='.json_encode($$wechat));
            switch ($wechat['errcode']) {
                case 40029:
                    return Render::error("code无效");
                    break;

                default:
                    return Render::error("请求频繁");
                    break;
            }
        }
        $openid = $wechat['openid'];    //用户的openid
        //3.判断用户是否授权过
        $userInfo = $this->user->getUserInfoByOpenid($openid);
        $token = '111235895522202'; //登录成功后的token
        if (!$userInfo){
            //如果用户没有登录过，则新增
            $userData = [
                'openid' => $openid,
                'session_key' => $wechat['session_key'],
                'token' => $token
            ];
        }
        if (!User::create($userData)){
            Log::error('【微信授权登录】----保存用户信息失败');
            return Render::error("登录失败");
        }
        Log::info('【微信授权登录】----保存用户信息，user='.json_encode($userData));
        //4.返回token
        return Render::success('获取token成功',compact('token'));
    }

    /**
     * 微信登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxLogin(Request $request){
        $data = $request->only(['iv','encryptedData']);
        //1.验证数据
        $validator = Validator::make($data,[
            'iv' => 'required',
            'encryptedData' => 'required'
        ]);
        if ($validator->fails()){
            return Render::error($validator->errors()->first());
        }
        //2.获取用户sessionkey
       $sessionKey = $request->user['session_key'];
        Log::info('【微信登录】-----用户信息：sessionKey='.$sessionKey);
        if (!$sessionKey){
            Log::error("【微信登录】-----用户sessionKey不存在");
            return Render::error("session_key不存在，请重试");
        }
        //3.获取用户信息
        try {
            $wechatData = $this->app->encryptor->decryptData($sessionKey, $data['iv'], $data['encryptedData']);
        } catch (DecryptException $e) {
            Log::error("【微信登录】----sessionKey无效");
            return Render::error("session_key失效");
        }
        if (isset($wechatData['openid'])){
            Log::error('【微信登录】-----获取openid错误');
            return Render::error('获取openid错误');
        }
        Log::info('【微信登录】-----用户的微信信息：wechatData='.json_encode($wechatData));
        //4.根据用户openid更新用户信息
        $userInfo = $this->userService->getUserInfoByOpenid($wechatData['openid']);
        $userInfo->nick_name = '1111';
        if (!$userInfo->save()){
            Log::error('【微信登录】-----更新用户信息失败');
            return Render::error('更新用户信息失败');
        }
        //5.返回用户数据
        $token = $userInfo->token;
        return Render::success('登录成功',compact('userInfo','token'));
    }
}
