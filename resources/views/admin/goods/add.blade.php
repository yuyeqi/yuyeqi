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
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>商品名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="goods_name" name="goods_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品价格
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="good_price" name="good_price" required="" lay-verify="good_price"
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品订金
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="book_price" name="book_price" required="" lay-verify="book_price"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="score" name="score"  required="" lay-verify="score"
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>初始销量
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sales_initial" name="sales_initial"  required="" lay-verify="sales_initial"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品分类
                    </label>
                    <div class="layui-input-inline">
                        <select name="cate_id" id="cate_id"  required>
                            <option value="0">请选择</option>
                            @isset($lists)
                                @foreach($lists as $item)
                                <option value="{{$item->id}}">{{$item->cate_name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sort" name="sort"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="goods_status" value="10" title="上架" checked="">
                        <input type="radio" name="goods_status" value="20" title="下架">
                    </div>
                    <label class="layui-form-label">新品</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="is_new" value="0" title="正常" checked="">
                        <input type="radio" name="is_new" value="1" title="新品">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">热门</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="is_hot" value="0" title="正常" checked="">
                        <input type="radio" name="is_hot" value="1" title="热门">
                    </div>
                    <label class="layui-form-label">推荐</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="is_recommend" value="0" title="正常" checked="">
                        <input type="radio" name="is_recommend" value="1" title="推荐">
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
                        <span class="x-red">*</span>轮播图
                    </label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                                <button type="button" class="layui-btn" id="test2">多图片上传</button>
                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;width: 88%">
                                    预览图：
                                    <div class="layui-upload-list uploader-list" style="overflow: auto;" id="uploader-list">

                                        </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品简介</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" name="goods_desc" class="layui-textarea"></textarea>
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
                layedit.set({
                    uploadImage: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('uploadEdit') }}" //接口url
                        ,type: '' //默认post
                    }
                });
                //创建一个编辑器
                var editIndex = layedit.build('LAY_demo_editor');
                //监听提交
                form.on('submit(add)', function(data) {
                    data.field.content = layedit.getContent(editIndex);//获取编辑器内容并赋值给保存对象内
                    var fields = data.field;
                    var coverPic = $("#uploadPic").attr('src');
                    if(mulPic == '') {
                        //发异步，把数据提交给php
                        layer.msg('请选择轮播图',{icon:6,time:1000});
                        return  false;
                    }
                    var data = {goods_no:fields.goods_no,goods_name:fields.goods_name,good_price:fields.good_price,book_price:fields.book_price,
                        score:fields.score,sales_initial:fields.sales_initial,sort:fields.sort,is_new:fields.is_new, goods_status:fields.goods_status,
                        is_hot:fields.is_hot,is_recommend:fields.is_recommend,goods_cover:coverPic,mulPic:mulPic,goods_desc:fields.goods_desc,
                        goods_content:fields.content,cate_id:fields.cate_id};
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
                //多图片上传
                upload.render({
                    elem: '#test2'
                    ,headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    ,url: "{{ route('upload') }}" //改成您自己的上传接口
                    ,multiple: true
                    ,accept:'file'
                    ,bindAction:"#btn"
                    ,before: function(obj){
                        layer.msg('图片上传中...', {
                            icon: 16,
                            shade: 0.01,
                            time: 0
                        })
                    }
                    ,done: function(res){
                        //上传完毕
                        if(res.code > 0){
                            return layer.msg(res.msg);
                        }
                        //上传成功
                        mulPic.push(res.data);
                        //上传完毕
                        $('#uploader-list').append(
                            '<div id="" class="file-iteme">' +
                            '<div class="handle pic"><i class="layui-icon layui-icon-delete"></i></div>' +
                            '<img style="width: 100px;height: 100px;" src='+ res.data +'>' +
                            '<div class="info">' + res.data + '</div>' +
                            '</div>'
                        );
                        return layer.msg('上传成功');
                    }
                });
            });
    </script>
@endsection
