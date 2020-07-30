@extends('admin.layouts.app')
@section('title','管理员列表')
@section('nav')
<!-- 导航开始 -->
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">首页</a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<!-- 导航结束 -->
@endsection
@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <div class="layui-inline">
                        <input class="layui-input" name="keywords" autocomplete="off">
                    </div>
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
    <a class="layui-btn layui-btn-xs audit" lay-event="audit">审核</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="show">查看</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="account">账户</a>
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
        var username = $("input[name='username']").val();
        //表格展示
        table.render({
            elem: '#table'
            ,url: "{{ route('user_lists') }}"
            ,cellMinWidth: 150
            ,cols: [[
                {type: 'checkbox',field: 'left'}
                ,{field:'id', width:80, title: 'ID', sort: true}
                ,{field:'user_name', align: "center",width:100, title: '姓名'}
                ,{field:'nick_name',align: "center", minWidth:150, title: '微信昵称'}
                ,{field:'avatar_url',align: "center", title: '微信图像',templet: function(d){
                        return  "<span id='pic_" + d.id + "'><img src='" + d.avatar_url + "'  style='width: 40px' lay-event='showPic' ></span>";
                    } }
                , {
                    field: 'user_type', align: "center", width: 100, title: '用户类型', templet: function (d) {
                        if (d.user_type == 1) {
                            return '设计师';
                        } else if (d.user_type == 2) {
                            return '异业';
                        } else if (d.user_type == 3) {
                            return '用户';
                        } else if (d.user_type == 4) {
                            return '员工';
                        } else{
                            return '其他';
                        }
                    }
                }
                ,{field:'phone', align: "center",width:100, title: '电话'}
                ,{field:'sex',align: "center", title: '性别', minWidth: 80,templet: function(d){
                    if(d.sex == 1){
                        return  '男';
                    }else if(d.sex ==2){
                        return  '女';
                    }else{
                        return  '未知'
                    }
                    } }
                ,{field:'parent_name', align: "center",width:150, title: '推荐人'}
                ,{field:'position_name', align: "center",title: '职称', minWidth: 100}
                ,{field:'org_name', align: "center",title: '单位名称', minWidth: 100}
                ,{field:'status', title: '状态',align: "center", width:120,templet: function(d){
                        if(d.status.status == 10){
                            return '<button type="button" onclick="member_stop('+d.id+','+d.status+')" class="layui-btn layui-btn-normal">正常</button>'
                        }else{
                            return '<button type="button" onclick="member_stop('+d.id+','+d.status+')" class="layui-btn layui-btn-danger">禁用</button>'
                        }
                    } }
                ,{field:'audit_status',align:'center', title: '审核状态',templet: function(d){
                    if(d.audit_status == 0){
                        return  '未注册';
                    }else if(d.audit_status ==1){
                        return  '审核中';
                    }else if(d.audit_status ==2){
                        return  '审核通过';
                    }else if(d.audit_status ==3){
                        return  '拒绝 ';
                    }
                    } }
                ,{field:'audit_user_name', align: "center",title: '审核人姓名'}
                ,{field:'update_user_name',align: "center", title: '更新人'}
                ,{field:'update_time', align: "center",title: '更新时间'}
                ,{field:'create_time',align: "center", title: '创建时间'}
                ,{fixed: 'right', align: 'center', title:'操作', toolbar: '#barDemo', width:260}
            ]]
            ,page: true
            ,id: 'tableId'
        })
        //监听工具条
        //监听工具条
        table.on('tool(tableTool)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                xadmin.open('编辑',"/hp/user/editShow/"+data.id,800,650);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            }else if(obj.event === 'account'){
                xadmin.open('账户信息',"/hp/user/account/"+data.id,850,600);
            }else if(obj.event === 'audit'){
                xadmin.open('审核',"/hp/user/auditShow/"+data.id,500,400);
            }else if(obj.event === 'show'){
                xadmin.open('查看',"/hp/user/show/"+data.id,800,600);
            }
        });
        //执行重载
        var $ = layui.$,active = {
            reload: function (){
                var keywords = $("input[name='keywords']").val();
                //执行重载
                table.reload('tableId',{
                    page: {
                        curr: 1
                    }
                    , where: {
                        keywords:keywords,
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
        var msg = status ? '确认要停用吗？' : '确认要启用吗？';
        layer.confirm(msg,function(index){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: "{{ route('user_update_status') }}",
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
            url: "{{ route('user_del') }}",
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
                url: '{{route("user_del")}}',
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
