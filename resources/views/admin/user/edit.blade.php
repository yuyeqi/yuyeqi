@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="user_name" class="layui-form-label">
                        用户姓名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="user_name" value="{{ $detail->user_name or '' }}" name="user_name" required="" lay-verify="required"
                                                                             autocomplete="off" class="layui-input">
                        <input type="hidden" value="{{ $detail->id or '' }}" id="hiddenId">
                    </div>
                    <label for="phone" class="layui-form-label">
                        用户电话
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" value="{{ $detail->phone or '' }}" name="phone" required="" lay-verify="username" lay-verify="phone"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        用户类型
                    </label>
                    <div class="layui-input-inline">
                        <select name="user_type">
                            <option value="1"  @if($detail->user_type['status'] == 1 )selected=""@endif>设计师</option>
                            <option value="2"  @if($detail->user_type['status'] == 2 )selected=""@endif>异业</option>
                            <option value="3"  @if($detail->user_type['status'] == 3 )selected=""@endif>用户</option>
                            <option value="4"  @if($detail->user_type['status'] == 4 )selected=""@endif>员工</option>
                            <option value="5"  @if($detail->user_type['status'] == 5 )selected=""@endif>其他</option>
                        </select>
                    </div>
                    <label class="layui-form-label">用户性别</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="sex" value="1" title="男"  @if($detail->sex['status'] == 1 )checked=""@endif>
                        <input type="radio" name="sex" value="2" title="女"  @if($detail->sex['status'] == 2 )checked=""@endif>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="position_name" class="layui-form-label">
                        用户职称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="position_name" value="{{ $detail->position_name or '' }}" name="position_name" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="org_name" class="layui-form-label">
                        单位名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="org_name" value="{{ $detail->org_name or '' }}" name="org_name" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="birthday" class="layui-form-label">
                        用户生日
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="birthday" value="{{ $detail->birthday or '' }}" name="birthday" required="" lay-verify="date"
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="province" class="layui-form-label">
                        省
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="province" value="{{ $detail->province or '' }}" name="province" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="city" class="layui-form-label">
                        市
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="city" value="{{ $detail->city or '' }}" name="city" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="area" class="layui-form-label">
                        区
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="area" value="{{ $detail->area or '' }}" name="area" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="user_brand" class="layui-form-label">
                        导购品牌
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="user_brand" value="{{ $detail->user_brand or '' }}" name="user_brand" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="address" class="layui-form-label">
                        详细地址
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="address" value="{{ $detail->address or '' }}" name="address" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                        </label>
                        <button  class="layui-btn" lay-filter="add" lay-submit="">
                            保存
                        </button>
                    </div>
                </form>

        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'layer','table','laydate'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    $ = layui.$
                    ,laydate = layui.laydate;

                //日期
                laydate.render({
                    elem: '#birthday'
                });
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var id = $('#hiddenId').val();
                    var data = {'id':id,user_name:fields.user_name,phone:fields.phone,sex:fields.sex,
                        position_name:fields.position_name,org_name:fields.org_name,birthday:fields.birthday,
                        province:fields.province,city:fields.city,area:fields.area,address:fields.address,
                        user_type:fields.user_type,user_brand:fields.user_brand};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "{{route('user_edit')}}",
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            if (data.code == 1){
                                //发异步，把数据提交给php
                                layer.msg(data.msg,{icon:5,time:1000});
                            }else {
                                //发异步，把数据提交给php
                                layer.alert(data.msg, {icon: 1},function () {
                                    // 获得frame索引
                                    var index = parent.layer.getFrameIndex(window.name);
                                    //关闭当前frame
                                    parent.layer.close(index);
                                    //刷新页面
                                    //window.parent.location.reload();
                                });
                            }
                        },
                        error: function (xhr,type) {
                        }
                    })
                    return false;
                });

            });
    </script>
@endsection
