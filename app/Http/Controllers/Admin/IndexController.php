<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Book;
use App\Models\ExchangeRecord;
use App\Models\Goods;
use App\Models\News;
use App\Models\Order;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    /**
     * 构造方法
     * AdminController constructor.
     * @param Admin $admin
     */
    public function __construct(){
        parent:: __construct();
    }

    //后台首页
    public function index(Request $request){
        $username = $this->loginInfo['username'];
        return view('admin.index.index',compact('username'));
    }

    //后台欢迎页
    public function welcome(){
        $username = $this->loginInfo['username'];
        $account = $this->getAccountData();
        $price = $this->getAmountData();
        return view('admin.index.welcome',compact('username','account','price'));
    }

    /**
     * 小程序统计
     * @return mixed
     */
    private function getAccountData(){
        //用户统计
        $data['userAccount'] = User::count('id');
        //订单数
        $data['orderAccount'] = Order::count('id');
        //预约数
        $data['bookAccount'] = Book::count('id');
        //私人定制
        $data['personAccount'] = Person::count('id');
        //商品数
        $data['goodsAccount'] = Goods::count('id');
        //兑换次数
        $data['exchangeAccount'] = ExchangeRecord::count('id');
        return $data;
    }

    /**
     * 金额统计
     * @return mixed
     */
    private function getAmountData(){
        //今年
        $year = date('Y');
        //当月
        $month = date('m');
        //当日
        $day = date('Y-m-d');
        //总计支付金额
        $data['allPrice'] = Order::getAmountData();
        //本年支付金额
        $data['yearPrice'] = Order::getAmountData('y',$year);
        //本月支付金额
        $data['monthPrice'] = Order::getAmountData('m',$month);
        //当天支付金额
        $data['dayPrice'] = Order::getAmountData('d',$day);
        return $data;
    }
}
