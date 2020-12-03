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
                <div class="layui-card-body ">
                    <div class="layui-input-inline layui-show-xs-block">
                        <input class="layui-input" placeholder="开始日" name="start" id="start"></div>
                    <div class="layui-input-inline layui-show-xs-block">
                        <input class="layui-input" placeholder="截止日" name="end" id="end"></div>
                    <div class="layui-input-inline layui-show-xs-block">
                        <select id="cateId" class="layui-select" style="width: 150px">
                            <option value="">全部</option>
                            @isset($cateLists)
                                @foreach($cateLists as $item)
                                    <option value="{{ $item->id }}">{{ $item->cate_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="layui-input-inline layui-show-xs-block">
                        <input type="text" name="keywords" placeholder="请输入姓名/电话/公司" autocomplete="off" class="layui-input"></div>
                    <button class="layui-btn" data-type="reload">搜索</button>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
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
    @{{# if(d.is_audit == 10){ }}
    <a class="layui-btn layui-btn-xs audit" lay-event="audit">审核</a>
    @{{# } }}
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
            ,url: "{{ route('person_lists') }}"
            ,cols: [[
                {type: 'checkbox',field: 'left'}
                ,{field:'id', width:80, title: 'ID', sort: true,align: "center"}
                ,{field:'person_name', width:120, title: '姓名', align:"center"}
                ,{field:'phone', width:120, title: '电话',align: "center"}
                ,{field:'company', width:200, title: '公司',align: "center"}
                ,{field:'ocupation', width:120, title: '职业',align: "center"}
                ,{field:'person_price', width:150, title: '定制预算',align: "center"}
                ,{field:'sales_price', width:150, title: '销售额',align: "center"}
                ,{field:'is_audit', title: '状态',align: "center", width:120,templet:function (d) {
                        if(d.is_audit == 10){
                            return '审核中';
                        }else if(d.is_audit == 20){
                            return  '审核通过';
                        }else if(d.is_audit == 30) {
                            return '拒绝'
                        }else {
                            return  '';
                        }
                    } }
                ,{field:'person_remark', width:150, title: '备注',align: "center"}
                ,{field:'update_time', title: '更新时间',align: "center",width:200}
                ,{field:'create_time', title: '创建时间',align: "center",width:200}
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
                xadmin.open('编辑',"/hp/person/editShow/"+data.id,600,650);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            } else if(obj.event === 'audit'){
                xadmin.open('审核',"/hp/person/auditShow/"+data.id,500,300);
            }
        });
        //执行重载
        var $ = layui.$,active = {
            reload: function (){
                var keywords = $("input[name='keywords']").val();
                var startTime = $("input[name='start']").val();
                var endTime = $("input[name='end']").val();
                var cateId = $("#cateId").val();
                //执行重载
                table.reload('tableId',{
                    page: {
                        curr: 1
                    }
                    , where: {
                        keywords:keywords,
                        startTime:startTime,
                        endTime:endTime,
                        cateId:cateId
                    }
                })
            }
        }
        //点击搜索
        $('.layui-card-body .layui-btn').on('click',function (){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        })
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
    /*用户-停用*/
    function member_stop(id,status){
        var status = status == 10 ? 20 : 10;
        var msg = status == 20 ? '确认要停用吗？' : '确认要启用吗？';
        layer.confirm(msg,function(index){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: "{{ route('cases_update_status') }}",
                dataType: 'json',
                data: {id:id,status:status},
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
        });
    }
    /*用户-删除*/
    function member_del(id){
        var data = [id];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: {ids:data},
            url: "{{ route('person_del') }}",
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
                url: '{{route("person_del")}}',
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
