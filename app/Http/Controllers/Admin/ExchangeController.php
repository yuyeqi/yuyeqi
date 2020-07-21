<?php


namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GoodsValidator;
use App\Http\Service\ExchangeCateService;
use App\Http\Service\ExchangeService;
use App\Http\Service\GoodsCateService;
use App\Http\Service\GoodsService;
use App\Library\Render;
use Illuminate\Http\Request;

/**
 * 兑换商品控制器
 * Class GoodsController
 * @package App\Http\Controllers\Admin
 */
class ExchangeController extends BaseController
{
    //兑换商品service
    private $exchangeSerivce;

    //兑换商品分类
    private $exchangeCateService;

    /**
     * ExchangeController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        $this->exchangeSerivce = isset($this->exchangeSerivce) ?: new ExchangeService();
        $this->exchangeCateService = isset($this->exchangeCateService) ?: new ExchangeCateService();
    }

    /**
     * 兑换商品列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        return view('admin.exchange.index');
    }

    /**
     * 商品列表
     * @param Request $request
     */
    public function getLists(Request $request){
        //接收参数
        $keyword = trim($request->get('keywords',''));
        $limit = intval($request->get('limit','10'));
        //获取数据
        $lists = $this->exchangeSerivce->getLists($keyword,$limit);
        return Render::table($lists->items(),$lists->total());
    }

    /**
     * 商品详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id){
        $detail = $this->exchangeCateService->getGoodsDetailById($id);
        return view('admin.exchange.show',['detail'=>$detail]);
    }

    /**
     * 添加页面展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addShow(){
        //商品类别列表
        $lists = $this->exchangeCateService->getCateList();
        return view('admin.exchange.add',compact('lists'));
    }
    /**
     * 添加商品
     * @param GoodsValidator $validator
     */
    public function add(Request $validator){
        //接收数据
        $data = $validator->only(['goods_no','goods_name','goods_cover','cate_id','goods_desc','content','sales_score'
        ,'line_score','sales_num','stock_num','sort','status','mulPic']);
        //添加数据
        try {
            $res = $this->exchangeSerivce->addGoods($data, $this->loginInfo);
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
        return view("admin.exchange.edit",compact('detail','lists'));
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
                dd($this->goodsSerivce->getErrorMsg());
                return Render::error("修改失败");
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
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
