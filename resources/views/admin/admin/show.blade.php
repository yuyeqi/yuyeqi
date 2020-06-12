@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                       账户
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="userename" value="{{ $detail->account or ''}}" name="username" disabled class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                       用户名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="userename"  disabled name="username" value="{{ $detail->username or '' }}" required=""  class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                       手机
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" disabled name="phone" value="{{ $detail->phone or '' }}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        邮箱
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" disabled name="email" value="{{ $detail->email or '' }}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                        性别
                    </label>
                    <div class="layui-input-inline">
                        @if(isset($detail->sex) && $detail->sex === 1)
                            <input type="text" id="L_pass" name="password" disabled  value="男" class="layui-input">
                        @else
                            <input type="text" id="L_pass" name="password" disabled value="女" class="layui-input">
                        @endif
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                       状态
                    </label>
                    <div class="layui-input-inline">
                        @if(isset($detail->status) && $detail->status === 0)
                        <input type="text" id="L_pass" name="password" disabled  value="正常" class="layui-input">
                        @else
                        <input type="text" id="L_pass" name="password" disabled value="禁用" class="layui-input">
                        @endif
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        登录时间
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_repass" disabled name="repass" value="{{ $detail->login_time or '' }}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        修改人
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_repass" disabled name="repass" value="{{ $detail->update_user_name or '' }}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        创建人
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_repass" disabled name="repass" value="{{ $detail->create_user_name or '' }}" class="layui-input">
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-block">
                            <input type="text" name="remark" disabled value="{{ $detail->remark or '' }}" class="layui-input">
                        </div>
                    </div>
                </form>

        </div>
    </div>
@endsection
