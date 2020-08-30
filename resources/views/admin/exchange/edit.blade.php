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
                        <input type="text" id="goods_no" name="goods_no"  value="{{ $detail->goods_no or '' }}"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                        <input type="hidden" value="{{ $detail->id or '' }}" id="hiddenId">
                    </div>
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>商品名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="goods_name" name="goods_name" value="{{ $detail->goods_name or '' }}" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="sales_score" class="layui-form-label">
                        <span class="x-red">*</span>兑换积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sales_score" name="sales_score" value="{{ $detail->sales_score or '' }}" required="" lay-verify="sales_score"
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>划线积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="line_score" name="line_score" value="{{ $detail->line_score or '' }}" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>兑换数量
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sales_num" name="sales_num"  value="{{ $detail->sales_num or '' }}" required=""
                               autocomplete="off" class="layui-input">
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>库存数量
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="stock_num" name="stock_num" value="{{ $detail->stock_num or '' }}"  required=""
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>商品分类
                    </label>
                    <div class="layui-input-inline">
                        <select name="cate_id" id="cate_id">
                            <option value="0">请选择</option>
                            @isset($lists)
                                @foreach($lists as $item)
                                    <option value="{{$item->id}}" @if($detail->cate_id == $item->id) selected @endif>{{$item->cate_name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="sort" name="sort"  value="{{ $detail->sort or '' }}" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="status" value="10" title="上架" @if($detail->status == 10) checked @endif>
                        <input type="radio" name="status" value="20" title="下架" @if($detail->status == 20) checked @endif>
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
                                    <img style="width: 100px;height: 100px;" src="{{ $detail->goods_cover }}" alt="" required="" id="uploadPic">
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
                                    @foreach($detail->picture as $item)
                                        <div id="" class="file-iteme">
                                            <div class="handle pic"><i class="layui-icon layui-icon-delete"></i></div>
                                            <img style="width: 100px;height: 100px;" src='{{ $item->pic_url }}'>
                                            <div class="info"></div>
                                        </div>
                                    @endforeach
                                        </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品简介</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" name="goods_desc"  class="layui-textarea">{{ $detail->goods_desc or '' }}</textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">编辑器</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea layui-hide" name="goods_content" lay-verify="content" id="LAY_demo_editor"> {{ $detail->content     or '' }}</textarea>
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
                    data.field.content = layedit.getContent(editIndex);//获取编辑器内容并赋值给
                    var fields = data.field;
                    var id = $('#hiddenId').val();
                    var coverPic = $("#uploadPic").attr('src');
                    var pics = [];
                    $("#uploader-list img").each(function (index,$val) {
                        pics[index] = $(this).attr('src')
                    })
                    if(pics.length <= 0){
                        layer.alert('请选择轮播图',{icon:5,time:1000});
                    }
                    var data = {id:id,goods_no:fields.goods_no,goods_name:fields.goods_name,cate_id:fields.cate_id,goods_desc:fields.goods_desc,
                        sales_score:fields.sales_score,line_score:fields.line_score,sales_num:fields.sales_num,stock_num:fields.stock_num, sort:fields.sort,
                        is_hot:fields.is_hot,is_recommend:fields.is_recommend,goods_cover:coverPic,mulPic:pics,goods_desc:fields.goods_desc,
                        content:fields.content,status:fields.status};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('exchange_edit')}}',
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
