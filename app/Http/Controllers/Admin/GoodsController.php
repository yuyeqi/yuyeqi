<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\GoodsValidator;
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
        
    }

    /**
     * 修改商品
     * @param GoodsValidator $validator
     */
    public function updateGoods(GoodsValidator $validator){
        if ($validator->isMethod('get')){
            return view('admin.goods.edit');
        }else{
            //修改数据
        }
    }

    /**
     * 删除商品
     * @param $ids
     */
    public function delete($ids){

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
