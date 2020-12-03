@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户姓名:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->user_name or ''}}"  disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        微信昵称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->nick_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户余额（元）:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->amount or '0.00'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        用户积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->score or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        提现金额（元）:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->withdraw_amount or '0.00'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        提现积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->withdraw_score or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        冻结金额（元）:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->frozen_amount or '0.00'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        冻结积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->frozen_score or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        现金兑现积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->cush_score or '0'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        礼物兑现积分:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->present_score or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        订单数量（单）:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->order_num  or '0'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        兑换次数（礼物）:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->exchage_num or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        预约次数:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->book_num or '0'}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        推广用户数量:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->children_num or '0'}}" name="username" disabled class="layui-input">
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
