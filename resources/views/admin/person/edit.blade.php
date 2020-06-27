@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="person_name" class="layui-form-label">
                        <span class="x-red">*</span>姓名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="person_name" name="person_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->person_name or '' }}">
                        <input type="hidden" value="{{ $detail->id or '' }}" id="hiddenId">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>电话
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" name="phone" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->phone or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="company" class="layui-form-label">
                        <span class="x-red">*</span>公司
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="company" name="company" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->company or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="ocupation" class="layui-form-label">
                        <span class="x-red">*</span>职业
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="ocupation" name="ocupation" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->ocupation or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="person_price" class="layui-form-label">
                        <span class="x-red">*</span>定制预算
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="person_price" name="person_price" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->person_price or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="sales_price" class="layui-form-label">
                        <span class="x-red">*</span>销售额
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="sales_price" name="sales_price" required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{ $detail->sales_price or '' }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" name="person_remark" class="layui-textarea">{{ $detail->person_remark or '' }}</textarea>
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
        layui.use(['form', 'layer','table','layedit'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    layedit = layui.layedit,
                    $ = layui.$;
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var id = $('#hiddenId').val();
                    var data = {id:id,person_name:fields.person_name,phone:fields.phone, company:fields.company,
                        ocupation:fields.ocupation,person_remark:fields.person_remark, person_price:fields.person_price,
                        sales_price:fields.sales_price};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('person_edit')}}',
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
            });
    </script>
@endsection
