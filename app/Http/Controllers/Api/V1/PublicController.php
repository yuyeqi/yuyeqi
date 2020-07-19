<?php


namespace App\Http\Controllers\Api\V1;


use App\Library\Render;
use App\Models\Config;
use EasyWeChat\Factory;
use http\Env\Request;


/**
 * 公共接口
 * Class PublicController
 * @package App\Http\Controllers\Api\V1
 */
class PublicController
{

    /**
     * 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(Request $request)
    {
        $Path = "/public/upload/";
        if (!empty($_FILES['file'])) {
            //获取扩展名
            $exename = $this->getExeName($_FILES['file']['name']);
            if ($exename != 'png' && $exename != 'jpg' && $exename != 'gif') {
                return  Render::error("图片格式错误");
            }
            $fileName = $_SERVER['DOCUMENT_ROOT'] . $Path . date('Ym');//文件路径
            $upload_name = '/img_' . date("YmdHis") . rand(0, 100) . '.' . $exename;//文件名加后缀
            if (!file_exists($fileName)) {
                //进行文件创建
                mkdir($fileName, 0777, true);
            }
            $imageSavePath = $fileName . $upload_name;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $imageSavePath)) {
                return  Render::success('上传成功',$Path . date('Ym') . $upload_name);
            }
        }else{
            return  Render::error("上传失败");
        }

    }

    /**
     * 获取配置信息
     * @param $configNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfigInfo($configNo){
        Factory::miniProgram();
        $detail = Config::getConfigByNo($configNo);
        return Render::success("获取成功",$detail);
    }
}
