<?php


namespace App\Http\Service;

use App\Models\Goods;
use App\Models\Picture;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * 商品service
 * Class GoodsService
 * @package App\Http\Service
 */
class GoodsService extends BaseSerivce
{
    //商品模型
    protected $goods = null;

    //图片模型
    protected $picture = null;
    /**
     * GoodsService constructor.
     */
    public function __construct()
    {
        $this->goods = isset($this->goods) ?: new Goods();
        $this->picture = isset($this->picture) ?: new Picture();
    }

    /**
     * 商品列表
     * @param $keywords
     * @param $limit
     * @return mixed
     */
    public function getGoodsLists($keywords,$limit){
        return $this->goods->getGoodsLists($keywords,$limit);
    }

    /**
     * 商品详情
     * @param $id
     * @return mixed
     */
    public function getGoodsDetailById($id){
        return $this->goods->getGoodsDetailById($id);
    }

    /**
     * 添加商品
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function addGoods($data,$loginInfo){
        //商品数据
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        //保存商品数据
        //开启事务
        DB::beginTransaction();
        try {
            $res = $this->goods->addGoods($data);//添加商品
            $img = [];
            if (isset($data['mulPic']) && is_array($data['mulPic']) && !empty($data['mulPic'])) {
                foreach ($data['mulPic'] as $key => $item) {
                    $img[$key]['pic_id'] = $res->id;
                    $img[$key]['pic_type'] = Config::get('constants.PIC_GOODS_TYPE');
                    $img[$key]['pic_url'] = $item;
                    $img[$key]['create_time'] = time();
                }
                $this->picture->addPicture($img);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            return false;
        }
    }

    /**
     * 修改商品
     * @param $data
     * @param $loginInfo
     * @return bool
     */
    public function updateGoods($data,$loginInfo){
        //商品数据
        $data['create_user_id'] = $loginInfo['id'];
        $data['create_user_name'] = $loginInfo['username'];
        $data['update_user_id'] = $loginInfo['id'];
        $data['update_user_name'] = $loginInfo['username'];
        $mulPic = $data['mulPic'];
        unset($data['mulPic']);
        //保存商品数据
        //开启事务
        DB::beginTransaction();
        try {
            //添加新的轮播
            $res = $this->goods->updateGoods($data);
            //删除原来的图片
            $this->picture->deletePic($data["id"]);
            $img = [];
            if (isset($mulPic) && is_array($mulPic) && !empty($mulPic)) {
                foreach ($mulPic as $key => $item) {
                    $img[$key]['pic_id'] = $data['id'];
                    $img[$key]['pic_type'] = Config::get('constants.PIC_GOODS_TYPE');
                    $img[$key]['pic_url'] = $item;
                    $img[$key]['create_time'] = time();
                }
                $this->picture->addPicture($img);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(0);
            $this->setErrorMsg($e);
            return false;
        }
    }
    /**
     * 删除商品
     * @param $ids
     * @param $loginInfo
     * @return bool|null
     * @throws \Exception
     */
    public function delBatch($ids,$loginInfo){
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        $data['is_delete'] = 1;
        return $this->goods->delBatch($ids,$data);
    }
/*----------------------------------------小程序------------------------------------------------------*/
    /**
     * 小程序首页新品推荐
     * @return mixed
     */
    public function getNesGoods()
    {
        return $this->goods->getNewsGoods();
    }
}
