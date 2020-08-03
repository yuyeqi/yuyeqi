@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        微信昵称:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="userename" value="{{ $detail->nick_name or ''}}" name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                       推荐人:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{ !empty($detail->parent_name) ? $detail->parent_name : '平台'}}" name="parent_name" disabled class="layui-input">
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
                        <input type="text"  value=
                        @switch ($detail->sex)
                        @case(1) '男' @break
                        @case(2) '女' @break
                        @default '未知'
                        @endswitch()
                        name="username" disabled class="layui-input">
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
                        <input type="text"  value=
                        @switch ($detail->user_type)
                            @case(1) '设计师' @break
                            @case(2) '异业' @break
                            @case(3) '用户' @break
                            @case(4) '员工' @break
                            @default '设计师'
                        @endswitch()
                        name="username" disabled class="layui-input">
                    </div>
                    <label for="account" class="layui-form-label">
                        用户状态:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  value= "{{ $detail->status == 10 ? '正常' : '禁用' }}"
                        name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        审核状态:
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" value=
                        @switch ($detail->audit_status)
                        @case(1) '审核中' @break
                        @case(2) '审核通过' @break
                        @case(3) '拒绝' @break
                        @default '未注册'
                        @endswitch()
                        name="username" disabled class="layui-input">
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
