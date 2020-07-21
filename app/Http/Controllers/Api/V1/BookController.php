<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\BookValidator;
use App\Http\Service\BookService;
use App\Library\Render;
use App\Models\Book;
use Illuminate\Http\Request;

/**
 * 预约API控制器
 * Class BookController
 * @package App\Http\Controllers\Api
 */
class BookController extends BaseController
{
    private $bookService;
    /**
     * BookController constructor.
     */
    public function __construct()
    {
        $this->bookService = isset($this->bookService) ?: new BookService();
    }

    /**
     * 预约列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApiBookLists(Request $request){
        $page = $request->input("page",1);
        $limit = $request->input("limit",10);
        $lists = $this->bookService->getApiBookLists($this->userInfo,$page,$limit);
        return Render::success('获取成功', $lists);
    }

    /**
     * 客户预约
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBook(BookValidator $request){
        $data = $request->only(['client_name','client_phone','province','city','district',
        'community','house_name','sex','arrive_time']);
        if ($this->bookService->addBook($data, $this->userInfo)){
            return Render::success('预约成功');
        }else{
            return  Render::error($this->bookService->getErrorMsg() ?: '预约失败');
        }
    }

    /**
     * 客户预约详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookDetail(Request $request){
        $id = $request->input('id',0);
        if ($id <= 0){
            return  Render::error("参数错误,请重试!");
        }
        $detail = Book::getApiBookDetail($id);
        return Render::success("获取成功",$detail);
    }
}
