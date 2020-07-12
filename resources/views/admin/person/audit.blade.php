@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" value="{{ $id }}" id="hiddenId">
                <div class="layui-form-item">
                    <label class="layui-form-label">审核状态</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_audit" value="10" title="审核中" checked="">
                        <input type="radio" name="is_audit" value="20" title="通过">
                        <input type="radio" name="is_audit" value="30" title="拒绝">
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-block">
                            <input type="text" name="audit_remark"  autocomplete="off" placeholder="请输入备注" class="layui-input">
                        </div>
                    </div>
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
        layui.use(['form', 'layer','table'],
            function() {
                $ = layui.jquery;
                table = layui.table;
                var form = layui.form,
                    layer = layui.layer,
                    $ = layui.$;

                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    var id = $('#hiddenId').val();
                    var data = {'id':id,'is_audit':fields.is_audit,'audit_remark':fields.audit_remark};
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "{{ route('person_update_status') }}",
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
