@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="news_title" class="layui-form-label">
                        <span class="x-red">*</span>配置编号
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" disabled id="config_no" name="config_no" required="" lay-verify="required"
                               autocomplete="off" class="layui-input"  style="width: 800px" value="{{ $detail->config_no or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="news_title" class="layui-form-label">
                        <span class="x-red">*</span>配置名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="config_name" name="config_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input"  style="width: 800px" value="{{ $detail->config_name or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="news_title" class="layui-form-label">
                        <span class="x-red">*</span>配置值
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="config_value" name="config_value"
                               autocomplete="off" class="layui-input"  style="width: 800px" value="{{ $detail->config_value or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="news_cover" class="layui-form-label">
                        <span class="x-red">*</span>配置图
                    </label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                            <div class="layui-upload-list">
                                <div id="" class="file-iteme">
                                    <div class="handle" id="handle"></div>
                                    <img src="{{ $detail->background or '' }}" style="width: 100px;height: 100px;" alt="" id="uploadPic">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">配置内容</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" name="content" class="layui-textarea">{{ $detail->content or '' }}</textarea>
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
                    layedit = layui.layedit,
                    upload = layui.upload,
                    $ = layui.$;
                //
                $(document).on("mouseenter mouseleave", ".file-iteme", function(event){
                    if(event.type === "mouseenter"){
                        //鼠标悬浮
                        $(this).children(".info").fadeIn("fast");
                        $(this).children(".handle").fadeIn("fast");
                    }else if(event.type === "mouseleave") {
                        //鼠标离开
                        $(this).children(".info").hide();
                        $(this).children(".handle").hide();
                    }
                });
                // 删除图片
                $(document).on("click", ".file-iteme .pic", function(event){
                    $(this).parent().remove();
                });
                // 删除单图图片
                $(document).on("click", "#handle", function(event){
                    $('#uploadPic').attr('src',null);//图片链接（base64）
                });
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var coverPic = $("#uploadPic").attr('src');
                    var data = {config_no:fields.config_no,config_name:fields.config_name,config_value:fields.config_value, background:coverPic,content:fields.content};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('config_edit')}}',
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
                //普通图片上传
                var uploadInst = upload.render({
                    elem: '#test1',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    ,url: "{{ route('upload') }}" //改成您自己的上传接口
                    ,accept:'images'
                    ,exts: 'jpg|png|gif|bmp|jpeg'
                    ,size: 4*1024*1024
                    ,before: function(obj){
                        layer.msg('图片上传中...', {
                            icon: 16,
                            shade: 0.01,
                            time: 0
                        })
                    }
                    ,done: function(res){
                        //如果上传失败
                        if(res.code > 0){
                            return layer.msg('上传失败');
                        }
                        //上传成功
                        $('#uploadPic').attr('src', res.data); //图片链接（base64）
                        return layer.msg('上传成功');
                    }
                });
            });
    </script>
@endsection
