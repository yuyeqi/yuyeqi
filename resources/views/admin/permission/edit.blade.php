@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" action="javascript:return false">
                <div class="layui-form-item">
                    <label for="cate_name" class="layui-form-label">
                        <span class="x-red">*</span>父级菜单
                    </label>
                    <div class="layui-input-inline"  style="width: 300px">
                        <select name="pid" lay-filter="aihao">
                            <option value="0">顶级分类</option>
                            @isset($permission)
                                @foreach($permission as $item)
                                    <option value="{{ $item->id }}" @if($item->id == $detail->pid) selected @endif>{{ $item->name }}</option>
                                    @isset($item->first)
                                        @foreach($item->first as $v)
                                            <option value="{{ $v->id }}" @if($v->id == $detail->pid) selected @endif>&ensp;&ensp;&ensp;&ensp;{{ $v->name }}</option>
                                        @endforeach
                                    @endisset
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>类型
                    </label>
                    <div class="layui-input-inline"  style="width: 300px">
                        <select name="type" lay-filter="aihao">
                            <option value="1" @if(1 == $detail->type) selected @endif>目录</option>
                            <option value="2"@if(2 == $detail->type) selected @endif>菜单</option>
                            <option value="3" @if(3 == $detail->type) selected @endif>按钮</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>菜单名称
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 300px" type="text" name="name"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{$detail->name or ''}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>权限值
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 300px" type="text" name="permission_value"  required="" lay-verify="required"
                               autocomplete="off" value="{{$detail->permission_value or ''}}" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>路径
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 300px" type="text" name="uri"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{$detail->uri or ''}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>目录图标
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 300px" type="text" name="icon"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{$detail->icon or ''}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input style="width: 300px" type="text" name="sort"  required="" lay-verify="required"
                               autocomplete="off" class="layui-input" value="{{$detail->sort or ''}}">
                    </div>
                </div>
                <input  type="hidden" name="id" id="hiddenId"  value="{{$detail->id or 0}}">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label">
                        </label>
                        <button  class="layui-btn" lay-filter="add" lay-submit="">
                            增加
                        </button>
                    </div>
                </form>
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
                var form = layui.form
                //监听提交
                form.on('submit(add)', function(data) {
                    var fields = data.field;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{route('permission_edit')}}',
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
            });
    </script>
@endsection
