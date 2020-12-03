@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="update_price" class="layui-form-label">
                        更新价格
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="update_price" value="{{ $detail->update_price or '' }}" name="update_price" required="" lay-verify="required"
                                                                             autocomplete="off" class="layui-input">
                        <input type="hidden" value="{{ $detail->id or '' }}" id="hiddenId">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="user_name" class="layui-form-label">
                        赠送积分
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="score" value="{{ $detail->score or '' }}" name="score" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                        <input type="hidden" value="{{ $detail->id or '' }}" id="hiddenId">
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
                    var data = {'id':id,update_price:fields.update_price,score:fields.score};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "{{route('order_edit')}}",
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
