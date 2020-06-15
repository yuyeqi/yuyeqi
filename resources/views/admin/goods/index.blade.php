@extends('admin.layouts.app')
@section('title','商品列表')
@section('nav')
<!-- 导航开始 -->
<div class="x-nav">
<span class="layui-breadcrumb">
    <a href="">首页</a>
<a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<!-- 导航结束 -->
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
                    <button class="layui-btn" onclick="xadmin.open('添加用户','{{ route('admin_add_show') }}',700,500)"><i class="layui-icon"></i>添加</button>
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
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">查看评论</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    var table;
    layui.use(['laydate','form','table'], function(){
        var laydate = layui.laydate;
        var  form = layui.form;
        table = layui.table;
        var $ = layui.$;
        var username = $("input[name='username']").val();
        //表格展示
        table.render({
            elem: '#table'
            ,url: "{{ route('goods_lists') }}"
            ,cellMinWidth: 100
            ,cols: [[
                {type: 'checkbox',field: 'left',width: 30}
                ,{field:'id', width:80, title: 'ID', sort: true}
                ,{field:'goods_name', width:120, title: '商品名称'}
                ,{field:'cate_id', title: '商品类别', minWidth: 120}
                ,{field:'goods_cover', width:80, title: '封面图'}
                ,{field:'good_price', width:100, title: '价格'}
                ,{field:'book_price', width:100, title: '定金'}
                ,{field:'comment_num', width:80, title: '评价数'}
                ,{field:'goods_status', width:80, title: '状态'}
                ,{field:'is_news', title: '新品',type:'button', width: 80}
                ,{field:'is_hot', width:80, title: '热门'}
                ,{field:'is_recommend', title: '推荐', minWidth: 80}
                ,{field:'score', title: '赠送积分', minWidth: 120}
                ,{field:'sales_actual', width:120, title: '实际销量'}
                ,{field:'update_user_name', width:100, title: '更新人'}
                ,{field:'update_time', title: '更新时间',width: 200}
                ,{field:'create_time', title: '创建时间',width: 200}
                ,{fixed: 'right', align: 'center', title:'操作', toolbar: '#barDemo', width:250}
            ]]
            ,page: true
            ,id: 'tableId'
        })
        //监听工具条
        //监听工具条
        table.on('tool(tableTool)', function(obj){
            var data = obj.data;
            if(obj.event === 'detail'){
                xadmin.open('商品信息',"/goods/detail/"+data.id,800,600,true);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            } else if(obj.event === 'edit'){
                xadmin.open('编辑',"/admin/edit/"+data.id,600,650);
            }else if(obj.event === 'pwd'){
                setPasword(data.id);
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
        var status = status == 0 ? 1 : 0;
        var msg = status ? '确认要停用吗？' : '确认要启用吗？';
        layer.confirm(msg,function(index){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: "{{ route('admin_update_status') }}",
                dataType: 'json',
                data: {id:id,status:status},
                success: function (data) {
                    layer.msg(data.msg,{icon:1,time:1000});
                    //刷新页面
                    location.reload()
                },
                error: function (xhr,type) {

                }
            })
        });
    }
    /*用户-删除*/
    function member_del(id){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: '/admin/delete/'+id,
            dataType: 'json',
            success: function (data) {
                layer.msg(data.msg,{icon:1,time:1000});
                //刷新页面
                location.reload()
            },
            error: function (xhr,type) {

            }
        })
    }
    function delAll (argument) {
        var ids = [];
        var checkStatus = table.checkStatus('tableId').data;
        $.each(checkStatus,function (index,val) {
            ids.push(val['id'])
        })
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("admin_delete_all")}}',
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
    }
    //设置密码
    function setPasword(id) {
        //prompt层
        layer.prompt({title: '请输入新密码，并确认', formType: 1}, function(pass, index){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '{{route("admin_update_pwd")}}',
                dataType: 'json',
                data: {id: id,password: pass},
                success: function (data) {
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:1000});
                    }else{
                        layer.msg(data.msg,{icon:5,time:1000});
                    }

                    //刷新页面
                    //window.parent.location.reload();
                },
                error: function (xhr,type) {

                }
            })
            layer.close(index);
        });
    }
</script>
@endsection
