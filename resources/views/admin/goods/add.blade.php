@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="account" class="layui-form-label">
                        <span class="x-red">*</span>商品编码
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="goods_no" name="goods_no" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>用户名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="goods_name" name="goods_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>商品主图
                    </label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" id="demo1"  width="100px" height="100px">
                                <p id="demoText"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>轮播图
                    </label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="test2">多图片上传</button>
                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                预览图：
                                <div class="layui-upload-list" id="demo2"></div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品价格
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="good_price" name="good_price" required="" lay-verify="good_price"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品订金
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="book_price" name="book_price" required="" lay-verify="book_price"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="score" name="score"  required="" lay-verify="score"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>初始销量
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="sales_initial" name="sales_initial"  required="" lay-verify="sales_initial"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="sort" name="sort"  required="" lay-verify="sort"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-block">
                        <input type="radio" name="goods_status" value="10" title="上架" checked="">
                        <input type="radio" name="goods_status" value="20" title="下架">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新品</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_new" value="0" title="正常" checked="">
                        <input type="radio" name="is_new" value="1" title="新品">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">热门</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_hot" value="0" title="正常" checked="">
                        <input type="radio" name="is_hot" value="1" title="热门">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">推荐</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_recommend" value="0" title="正常" checked="">
                        <input type="radio" name="is_recommend" value="1" title="推荐">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品简介</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">编辑器</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea layui-hide" name="goods_content" lay-verify="content" id="LAY_demo_editor"></textarea>
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
        var mulPic = [];
        layui.use(['form', 'layer','table','layedit','upload'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    layedit = layui.layedit,
                    upload = layui.upload,
                    $ = layui.$;
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var coverPic = $("#demo1").attr('src');
                    var data = {goods_no:fields.goods_no,goods_name:fields.goods_name,good_price:fields.good_price,book_price:fields.book_price,
                        score:fields.score,sales_initial:fields.sales_initial,sort:fields.sort,is_new:fields.is_new, goods_status:fields.goods_status,
                        is_hot:fields.is_hot,is_recommend:fields.is_recommend,goods_cover:coverPic,mulPic:mulPic};
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: '{{route('goods_add')}}',
                            data: data,
                            dataType: 'json',
                            success: function (data) {
                                if (data.code == 1){
                                    //发异步，把数据提交给php
                                    layer.alert(data.msg, {icon: 6});
                                    return false
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
                //创建一个编辑器
                var editIndex = layedit.build('LAY_demo_editor');
                //普通图片上传
                var uploadInst = upload.render({
                    elem: '#test1',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    ,url: "{{ route('upload') }}" //改成您自己的上传接口
                    ,accept:'file'
                    ,before: function(obj){
                        //预读本地文件示例，不支持ie8
                        obj.preview(function(index, file, result){
                            $('#demo1').attr('src', result); //图片链接（base64）
                        });
                    }
                    ,done: function(res){
                        //如果上传失败
                        if(res.code > 0){
                            return layer.msg('上传失败');
                        }
                        //上传成功
                        $('#demo1').attr('src', res.data); //图片链接（base64）
                        return layer.msg('上传成功');
                    }
                    ,error: function(){
                        //演示失败状态，并实现重传
                        var demoText = $('#demoText');
                        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        demoText.find('.demo-reload').on('click', function(){
                            uploadInst.upload();
                        });
                    }
                });
                //多图片上传
                upload.render({
                    elem: '#test2'
                    ,headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    ,url: "{{ route('upload') }}" //改成您自己的上传接口
                    ,multiple: true
                    ,accept:'file'
                    ,before: function(obj){
                        //预读本地文件示例，不支持ie8
                        obj.preview(function(index, file, result){
                            $('#demo2').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img" width="100px" height="100px">')
                        });
                    }
                    ,done: function(res){
                        //上传完毕
                        if(res.code > 0){
                            return layer.msg('上传失败');
                        }
                        //上传成功
                        mulPic.push(res.data);
                        return layer.msg('上传成功');
                    }
                });
            });
    </script>
@endsection
