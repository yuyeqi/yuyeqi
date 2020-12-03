<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GoodsValidator;
use App\Http\Service\GoodsCateService;
use App\Http\Service\GoodsService;
use App\Library\Render;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 商品控制器
 * Class GoodsController
 * @package App\Http\Controllers\Admin
 */
class GoodsController extends BaseController
{
    //商品service
    private $goodsSerivce;

    //商品分类
    private $goodsCateService;

    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->goodsSerivce = isset($this->goodsSerivce) ?: new GoodsService();
        $this->goodsCateService = isset($this->goodsCateService) ?: new GoodsCateService();
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
        //商品类别列表
        $lists = $this->goodsCateService->getCateList();
        return view('admin.goods.add',compact('lists'));
    }
    /**
     * 添加商品
     * @param GoodsValidator $validator
     */
    public function add(Request $validator){
        //接收数据
        $data = $validator->only(['goods_no','goods_name','good_price','book_price','score','sales_initial','sort'
        ,'is_new','goods_status','is_hot','is_recommend','goods_cover','mulPic','cate_id','goods_desc','goods_content']);
        //添加数据
        try {
            $res = $this->goodsSerivce->addGoods($data, $this->loginInfo);
            if ($res) {
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
        //商品类别列表
        $lists = $this->goodsCateService->getCateList();
        return view("admin.goods.edit",compact('detail','lists'));
    }
    /**
     * 修改商品
     * @param GoodsValidator $validator
     */
    public function updateGoods(Request $validator){
        //接收数据
        $data = $validator->only(['id','goods_no','goods_name','good_price','book_price','score','sales_initial','sort'
            ,'is_new','goods_status','is_hot','is_recommend','goods_cover','mulPic','cate_id','goods_desc','goods_content']);
        //修改数据
        try {
            $res = $this->goodsSerivce->updateGoods($data, $this->loginInfo);
            if ($res) {
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
     * 修改排序
     */
    public function updateSort(){

    }

    /**
     * 评论列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function comment(){
        return view('admin.goods.comment');
    }
    /**
     * 评论列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentLists(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $keywords = $request->input('keywords','');
        $lists = $this->goodsSerivce->getCommentLists($keywords,$page,$limit);
        return  Render::table($lists->items(),$lists->total());
    }

    /**
     * 更新状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request){
        $data = $request->only('id','status');
        //数据验证
        $validator =Validator::make($data,[
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        //更新状态
        if ($this->goodsSerivce->updateStatus($data,$this->loginInfo)){
            return Render::success('设置成功');
        }
        return  Render::error('设置失败');
    }

    /**
     * 置顶
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTop(Request $request){
        $data = $request->only('id','is_top');
        //数据验证
        $validator =Validator::make($data,[
            'id' => 'required|integer',
            'is_top' => 'required|integer',
        ]);
        //更新状态
        if ($this->goodsSerivce->updateStatus($data,$this->loginInfo)){
            return Render::success('设置成功');
        }
        return  Render::error('设置失败');
    }

    /**
     * 删除商品
     * @param $request
     */
    public function delBatchCommnet(Request $request){
        $ids = $request->input("ids");
        if (empty($ids)){
            return Render::error("参数错误");
        }
        if ($this->goodsSerivce->delBatchCommnet($ids,$this->loginInfo)){
            return Render::success("删除成功");
        }
        return Render::error("删除失败");
    }
}
