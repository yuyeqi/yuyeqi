@extends('admin.layouts.app')
@section('title','管理员列表')
@section('nav')
    <!-- 导航开始 -->
    <div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">首页</a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </span>
    </div>
    <!-- 导航结束 -->
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                         <button class="layui-btn" onclick="xadmin.open('添加角色','{{ route('role_add_show') }}',1000,850,true)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body layui-table-body layui-table-main">
                        <table class="layui-hide" id="table" lay-filter="tableTool"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        var table;
        layui.use(['laydate','form','table'], function(){
            var laydate = layui.laydate;
            var  form = layui.form,
                table = layui.table,
                $ = layui.$;
            //表格展示
            table.render({
                elem: '#table'
                ,url: "{{ route('role_lists') }}"
                ,cols: [[
                    {type: 'checkbox',field: 'left'}
                    ,{field:'id', width:80, title: 'ID', sort: true,align: "center"}
                    ,{field:'name', width:150, title: '角色名称', align:"center"}
                    ,{field:'description', width:300, title: '角色描述',align: "center"}
                    ,{field:'create_time', title: '创建时间',align: "center",width:200}
                    ,{field:'update_user_name',align: "center", width:100, title: '更新人'}
                    ,{field:'update_time', title: '更新时间',align: "center",width:200}
                    ,{fixed: 'right', align: 'center', title:'操作', toolbar: '#barDemo', width:200}
                ]]
                ,page: true
                ,id: 'tableId'
            })
            //监听工具条
            //监听工具条
            table.on('tool(tableTool)', function(obj){
                var data = obj.data;
                if(obj.event === 'edit'){
                    xadmin.open('编辑',"/hp/role/editShow/"+data.id,1000,850,true);
                } else if(obj.event === 'del'){
                    layer.confirm('确认要删除吗？',function (){
                        member_del(data.id);
                    })
                }
            });
            // 监听全选
            form.on('checkbox(checkall)', function(data){
                if(data.elem.checked){
                    $('tbody input').prop('checked',true);
                }else{
                    $('tbody input').prop('checked',false);
                }
                form.render('checkbox');
            });
            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });
            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });
        /*用户-删除*/
        function member_del(id){
            var data = [id];
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                data: {ids:data},
                url: "{{ route('cases_del') }}",
                dataType: 'json',
                success: function (data) {
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:1000});
                    }else{
                        layer.msg(data.msg,{icon:5,time:1000});
                    }
                    //刷新页面
                    location.reload();
                },
                error: function (xhr,type) {

                }
            })
        }
        function delAll (argument) {
            layui.use(['table'],function () {
                var table = layui.table;
                var ids = [];
                var checkStatus = table.checkStatus('tableId').data
                $.each(checkStatus,function (index,val) {
                    ids.push(val['id'])
                })
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{route("cases_del")}}',
                    dataType: 'json',
                    data: {ids: ids},
                    success: function (data) {
                        if(data.code == 0){
                            layer.msg(data.msg,{icon:1,time:1000});
                        }else{
                            layer.msg(data.msg,{icon:5,time:1000});
                        }
                        //刷新页面
                        location.reload();
                    },
                    error: function (xhr,type) {

                    }
                })
            })
        }
    </script>
@endsection
