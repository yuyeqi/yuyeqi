@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="case_name" class="layui-form-label">
                        <span class="x-red">*</span>客户姓名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="client_name" name="client_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->client_name or '' }}">
                        <input type="hidden" name="id" value="{{ $detail->id or '' }}" id="hiddenId">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="case_name" class="layui-form-label">
                        <span class="x-red">*</span>客户电话
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="client_phone" name="client_phone" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->client_phone or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="case_name" class="layui-form-label">
                        <span class="x-red">*</span>省市区
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="province" name="province" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->province or '' }}">
                        <input type="text" id="city" name="city" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->city or '' }}">
                        <input type="text" id="district" name="district" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->district or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="case_name" class="layui-form-label">
                        <span class="x-red">*</span>小区名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="community" name="community" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->community or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="case_name" class="layui-form-label">
                        <span class="x-red">*</span>楼层地址
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="house_name" name="house_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->house_name or '' }}">
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
        //创建一个编辑器
        layui.use(['form', 'layer','table','layedit','upload'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    $ = layui.$;
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('book_edit')}}',
                        data: fields,
                        dataType: 'json',
                        success: function (data) {
                            if (data.code == 1){
                                //发异步，把数据提交给php
                                layer.msg(data.msg,{icon:5,time:1000});
                            }else {
                                //发异步，把数据提交给php
                                layer.alert(data.msg, {icon: 6},function () {
                                    // 获得frame索引
                                    var index = parent.layer.getFrameIndex(window.name);
                                    //关闭当前frame
                                    parent.layer.close(index);
                                    //刷新页面
                                    window.parent.location.reload();
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
