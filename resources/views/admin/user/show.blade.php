@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                       微信ID:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->open_id or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        微信昵称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="userename" value="{{ $detail->nick_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户电话:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->phone or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        用户姓名:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->user_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户性别:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->sex['status_name'] or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        用户生日:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->birthday or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户职称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->org_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        单位名称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->position_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户类型:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->user_type['status_name'] or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        用户状态:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value="{{ $detail->status['status_name'] or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        审核状态:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->audit_status['status_name']  or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        审核人员:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ $detail->audit_user_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        审核备注:
                    </label>
                    <div class="layui-input-block">
                        <input type="text" value="{{ $detail->audit_remark or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        用户地址:
                    </label>
                    <div class="layui-input-block">
                        <input type="text" value="{{ $detail->address or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>


        </div>
    </div>
@endsection
