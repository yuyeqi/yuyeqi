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
                        <input type="text" id="phone" name="phone" required="" lay-verify="phone"
                               autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>轮播图
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="email" required="" lay-verify="email"
                               autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
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
                        <input type="radio" name="sex" value="10" title="上架" checked="">
                        <input type="radio" name="sex" value="20" title="下架">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新品</label>
                    <div class="layui-input-block">
                        <input type="radio" name="sex" value="0" title="正常" checked="">
                        <input type="radio" name="sex" value="1" title="新品">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">热门</label>
                    <div class="layui-input-block">
                        <input type="radio" name="sex" value="0" title="正常" checked="">
                        <input type="radio" name="sex" value="1" title="热门">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">推荐</label>
                    <div class="layui-input-block">
                        <input type="radio" name="sex" value="0" title="正常" checked="">
                        <input type="radio" name="sex" value="1" title="推荐">
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
                        <textarea class="layui-textarea layui-hide" name="content" lay-verify="content" id="LAY_demo_editor"></textarea>
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
        layui.use(['form', 'layer','table','layedit'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    layedit = layui.layedit,
                    $ = layui.$;
                //自定义验证规则
                form.verify({
                    nikename: function(value) {
                        if (value.length < 5) {
                            return '昵称至少得5个字符啊';
                        }
                    },
                    pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    repass: function(value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });

                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var data = {username:fields.username,account:fields.account,phone:fields.phone,password:fields.password,
                        email:fields.email,sex:fields.sex,remark:fields.remark,};
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: '{{route('admin_add')}}',
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
            });
    </script>
@endsection
