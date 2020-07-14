@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="user_name" class="layui-form-label">
                       用户名称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->user_name or ''}}" name="user_name" disabled class="layui-input">
                    </div>
                    <label for="phone" class="layui-form-label">
                        用户电话:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" value="{{ $detail->phone or ''}}" name="phone" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="order_no" class="layui-form-label">
                        订单号:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->order_no or ''}}" name="order_no" disabled class="layui-input">
                    </div>
                    <label for="goods_name" class="layui-form-label">
                        商品名称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->goods_name or ''}}" name="goods_name" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="total_price" class="layui-form-label">
                        订单金额:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->total_price or ''}}" name="total_price" disabled class="layui-input">
                    </div>
                    <label for="goods_price" class="layui-form-label">
                        商品价格:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->goods_price or ''}}" name="goods_price" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="pay_price" class="layui-form-label">
                        支付价格:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->pay_price or ''}}" name="pay_price" disabled class="layui-input">
                    </div>
                    <label for="update_price" class="layui-form-label">
                        更新价格:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->update_price or ''}}" name="update_price" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="score" class="layui-form-label">
                        赠送积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->score  or ''}}" name="score" disabled class="layui-input">
                    </div>
                    <label for="pay_status" class="layui-form-label">
                        订单状态:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->pay_status['status_name'] or ''}}" name="pay_status" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="transaction_id" class="layui-form-label">
                        支付交易号:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->transaction_id or ''}}" name="transaction_id" disabled class="layui-input">
                    </div>
                    <label for="pay_time" class="layui-form-label">
                        支付时间:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->pay_time or ''}}" name="pay_time" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="transaction_id" class="layui-form-label">
                        是否评价:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->is_comment or ''}}" name="is_comment" disabled class="layui-input">
                    </div>
                    <label for="pay_time" class="layui-form-label">
                        更新时间:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->update_time or ''}}" name="pay_time" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="update_user_name" class="layui-form-label">
                        更新人:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->update_user_name or ''}}" name="is_comment" disabled class="layui-input">
                    </div>
                    <label for="pay_time" class="layui-form-label">
                        创建时间:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->create_time or ''}}" name="pay_time" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        买家留言：
                    </label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" disabled>{{$detail->buyer_remark or ''}}</textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
