@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="cate_name" class="layui-form-label">
                        <span class="x-red">*</span>名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="cate_name" name="cate_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sort" name="sort"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                        </label>
                        <button  class="layui-btn" lay-filter="add" lay-submit="">
                            增加
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
                    layedit = layui.layedit,
                    upload = layui.upload,
                    $ = layui.$;
                //

                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var data = {cate_name:fields.cate_name,sort:fields.sort};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('exchangeCate_add')}}',
                        data: data,
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
