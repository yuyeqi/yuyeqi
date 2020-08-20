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
                        <input type="text" value="{{ $detail->cate_name or ''}}" id="cate_name" name="cate_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="register_account" class="layui-form-label">
                        <span class="x-red">*</span>注册赠送金额
                    </label>
                    <div class="layui-input-inline">
                        <input type="number"  value="{{ $detail->register_account or ''}}"  id="sort" name="register_account"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="tg_account" class="layui-form-label">
                        <span class="x-red">*</span>推广赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="tg_account"  value="{{ $detail->tg_account or ''}}" name="tg_account"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="book_score" class="layui-form-label">
                        <span class="x-red">*</span>预约赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number"  value="{{ $detail->book_score or ''}}" id="book_score" name="book_score"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="store_score" class="layui-form-label">
                        <span class="x-red">*</span>到店赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number"  value="{{ $detail->store_score or ''}}" id="store_score" name="store_score"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="order_score" class="layui-form-label">
                        <span class="x-red">*</span>下单赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="number"  value="{{ $detail->order_score or ''}}" id="order_score" name="order_score"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                        </label>
                        <input type="hidden" id="hiddenId" value="{{ $detail->id or '' }}">
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

                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var id = $('#hiddenId').val();
                    var data = {id:id,cate_name:fields.cate_name,sort:fields.sort,register_account:fields.register_account,tg_account:fields.tg_account,
                        book_score:fields.book_score,store_score:fields.store_score,order_score:fields.order_score};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('userCate_edit')}}',
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
