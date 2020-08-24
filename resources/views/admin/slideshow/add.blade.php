@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="slideshow_name" class="layui-form-label">
                        <span class="x-red">*</span>名称
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 400px" type="text" id="slideshow_name" name="slideshow_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        产品链接
                    </label>
                    <div class="layui-input-inline">
                        <select name="product_url" lay-filter="轮播链接">
                            <option value=""></option>
                            <option value="/pages/newsDetail/index?id=">商品</option>
                            <option value="/pages/caseDetail/index?id=" >案例</option>
                            <option value="/pages/newsDetail/index?id=" >新闻</option>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" id="product_id" name="product_id" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="slideshow_url" class="layui-form-label">
                        <span class="x-red">*</span>轮播图
                    </label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                            <div class="layui-upload-list">
                                <div id="" class="file-iteme">
                                    <div class="handle" id="handle"></div>
                                    <img style="width: 100px;height: 100px;" alt="" id="uploadPic">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 400px" type="number" id="sort" name="sort"  required="" lay-verify="sort"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">简介</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" name="description" class="layui-textarea"></textarea>
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
                    var productUrl = data.field.product_url+data.field.product_id;
                    var coverPic = $("#uploadPic").attr('src');
                    var data = {slideshow_name:fields.slideshow_name,description:fields.description, sort:fields.sort,
                        slideshow_url:coverPic,product_url:productUrl};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('slideshow_add')}}',
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
                    ,method:'post'
                    ,accept:'images'
                    ,exts: 'jpg|png|gif|bmp|jpeg'
                    ,size: 4*1024*1024
                    ,before: function(obj){
                        console.log(obj)
                        layer.msg('图片上传中...', {
                            icon: 16,
                            shade: 0.01,
                            time: 0
                        })
                    }
                    ,done: function(res){
                        console.log(res,1111)
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
