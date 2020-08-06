<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Service\UserService;
use App\Library\Render;
use App\Models\User;
use App\Models\UserStatistic;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


/**
 * 微信相关控制器
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class WxController extends BaseController
{

    private $app;   //小程序实例
    private $appOfficialAccount; //公众号
    private $userService;  //用户服务层

    public function __construct()
    {
        parent:: __construct();
        $config = config('wechat.mini_program.default');
        $this->app = isset($this->app) ?: Factory::miniProgram($config);
        $this->appOfficialAccount = Factory::officialAccount($config);
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
            Log::error('【微信授权登录】----授权登录失败:errorInfo='.json_encode($wechat));
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
        $userInfo = User::getUserInfoByOpenid($openid);
        $token = str_random(64); //登录成功后的token
        if (!$userInfo){
            //如果用户没有登录过，则新增
            $userData = [
                'openid' => $openid,
                'session_key' => $wechat['session_key'],
                'token' => $token
            ];
                $user = User::create($userData);
                if (!$user) {
                    Log::error('【微信授权登录】----保存用户信息失败');
                    return Render::error("登录失败");
                }//新增用户统计
                $userStatistic = ['user_id' => $user->id];
                UserStatistic::create($userStatistic);
        }else{
            //更新token
            $userInfo->token = $token;
            $userInfo->session_key = $wechat['session_key'];
            if(!$userInfo->save()){
                Log::error('【微信授权登录】----更新用户信息失败');
                return Render::error("登录失败");
            }
        }
        //获取用户信息
        $userDetail = User::getUserInfoByOpenid($openid);
        Log::info('【微信授权登录】----保存用户信息，user='.json_encode($userDetail));
        //4.返回token
        return Render::success('获取token成功',compact('userDetail'));
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
       $sessionKey = $this->userInfo['session_key'];
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
        if (!isset($wechatData['openId'])){
            Log::error('【微信登录】-----获取openid错误');
            return Render::error('获取openid错误');
        }
        Log::info('【微信登录】-----用户的微信信息：wechatData='.json_encode($wechatData));
        //4.根据用户openid更新用户信息
        $userInfo = User::getUserInfoByOpenid($wechatData['openId']);
        $userInfo->nick_name = $wechatData['nickName'];
        $userInfo->avatar_url = $wechatData['avatarUrl'];
        if (!$userInfo->save()){
            Log::error('【微信登录】-----更新用户信息失败');
            return Render::error('更新用户信息失败');
        }
        //5.返回用户数据
        $token = $userInfo->token;
        return Render::success('登录成功',compact('userInfo','token'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function getQrCode(Request $request){
        //获取页面路径
        $page = 'pages/index/index';
        $appCode = $this->app->app_code->getUnlimit('scene-value', [
            'page'  => $page,
            'width' => 600,
        ]);
        // 保存小程序码到文件
        $filename = '';
        if ($appCode instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $appCode->save('qrcode/','qrcode.jpg');
        }
        //文件名非空时返回图片路径
        $baseUrl = url()->previous().'/qrcode/'.$filename;
        return Render::success("获取成功",$baseUrl);
    }

    /**
     * 获取微信jssdk
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getJsConfig(){
       $data = $this->appOfficialAccount->jssdk->buildConfig(array('onMenuShareAppMessage',
            'onMenuShareTimeline',
            'updateAppMessageShareData',
            'updateTimelineShareData'), false);
       return Render::success("获取成功",$data);
    }
}
