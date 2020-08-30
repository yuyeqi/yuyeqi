@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form action="" method="post" class="layui-form layui-form-pane">
                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>角色名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        拥有权限
                    </label>
                    <table  class="layui-table layui-input-block">
                        <tbody>
                        @isset($permission)
                            @foreach($permission as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" lay-skin="primary" lay-filter="father" title="{{ $item->name }}">
                                    </td>
                                    <td>
                                        <div class="layui-input-block">
                                            @isset($item->first)
                                                @foreach($item->first as $value)
                                                    <input name="ids[]" lay-skin="primary" type="checkbox" value="{{ $value->id }}" title="{{ $value->name }}">
                                                    @isset($value->second)
                                                        @foreach($value->second as $v)
                                                            <input name="ids[]" lay-skin="primary" type="checkbox" value="{{ $v->id }}" title="{{ $v->name }}">
                                                        @endforeach
                                                    @endisset
                                                @endforeach
                                            @endisset
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="description" class="layui-form-label">
                        描述
                    </label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="desc" name="description" class="layui-textarea"></textarea>
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
                ,layer = layui.layer;
            //监听提交
            form.on('submit(add)', function(data){
                var fields = data.field;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{route('role_add')}}',
                    data: fields,
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
            form.on('checkbox(father)', function(data){

                if(data.elem.checked){
                    $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                    form.render();
                }else{
                    $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                    form.render();
                }
            });


        });
    </script>
@endsection
