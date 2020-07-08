<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GoodsValidator;
use App\Http\Service\GoodsService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 商品控制器
 * Class GoodsController
 * @package App\Http\Controllers\Admin
 */
class GoodsController extends BaseController
{
    //商品service
    private $goodsSerivce = null;

    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->goodsSerivce = isset($this->goodsSerivce) ?: new GoodsService();
    }

    /**
     * 商品列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        return view('admin.goods.index');
    }

    /**
     * 商品列表
     * @param Request $request
     */
    public function getGoodsLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        //获取数据
        $lists = $this->goodsSerivce->getGoodsLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 商品详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id){
        $detail = $this->goodsSerivce->getGoodsDetailById($id);
        return view('admin.goods.show',['detail'=>$detail]);
    }

    /**
     * 添加页面展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addShow(){
        return view('admin.goods.add');
    }
    /**
     * 添加商品
     * @param GoodsValidator $validator
     */
    public function add(Request $validator){
        //接收数据
        $data = $validator->only(['goods_no','goods_name','good_price','book_price','score','sales_initial','sort'
        ,'is_new','goods_status','is_hot','is_recommend','goods_cover','mulPic']);
        //添加数据
        try {
            $res = $this->goodsSerivce->addGoods($data, $this->loginInfo);
            if ($res > 0) {
                return Render::success('添加成功');
            } else {
                return Render::error("添加失败");
            }
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 编辑数据
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $detail = $this->goodsSerivce->getGoodsDetailById($id);
        return view("admin.goods.edit",['detail'=>$detail]);
    }
    /**
     * 修改商品
     * @param GoodsValidator $validator
     */
    public function updateGoods(GoodsValidator $validator){
        //接收数据
        $data = $validator->only(['id','goods_no','goods_name','good_price','book_price','score','sales_initial','sort'
            ,'is_new','goods_status','is_hot','is_recommend','goods_cover','mulPic']);
        //修改数据
        try {
            $res = $this->goodsSerivce->updateGoods($data, $this->loginInfo);
            if ($res > 0) {
                return Render::success('修改成功');
            } else {
                return Render::error("修改失败");
            }
        } catch (\Exception $e) {
            return Render::error("系统异常，请稍后再试！");
        }
    }

    /**
     * 删除商品
     * @param $request
     */
    public function delBatch(Request $request){
        $ids = $request->input("ids");
        if (empty($ids)){
            return Render::error("参数错误");
        }
        if ($this->goodsSerivce->delBatch($ids,$this->loginInfo)){
            return Render::success("删除成功");
        }
        return Render::error("删除失败");
    }

    /**
     * 修改商品状态
     */
    public function updateStatus(){

    }

    /**
     * 修改排序
     */
    public function updateSort(){

    }
}
