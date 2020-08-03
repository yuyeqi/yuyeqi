@extends('Admin.layouts.app')
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
                    <div class="layui-inline">
                        <input class="layui-input" name="keywords" autocomplete="off" placeholder="请输入用户名">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input" name="userId" autocomplete="off" placeholder="请输入用户id">
                    </div>
                    <div class="layui-input-inline layui-show-xs-block">
                        <select id="dealType" class="layui-select" style="width: 150px">
                            <option value="0">交易类型</option>
                            <option value="1">用户提现</option>
                            <option value="2">积分充值</option>
                            <option value="3">用户注册</option>
                            <option value="4">用户推广</option>
                        </select>
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
            ,url: "{{ route('user_walletDeal_lists') }}"
            ,cols: [[
                {type: 'checkbox',field: 'left'}
                ,{field:'id', width:80, title: 'ID', sort: true,align: "center"}
                ,{field:'deal_no', width:200, title: '交易单号',align:"center"}
                ,{field:'user_name', width:100, title: '用户名 ',align: "center"}
                ,{field:'amount', width:120, title: '交易金额',align:"center"}
                ,{field:'surplus_amount', width:120, title: '账户余额 ',align: "center"}
                ,{field:'deal_type', title: '交易类型',align: "center", width:120,templet: function(d){
                        if(d.deal_type == 1){
                            return '现金提现';
                        }else if(d.deal_type == 2){
                            return '积分充值';
                        }else if(d.status == 3){
                            return  '用户注册';
                        }else {
                            return  '用户推广';
                        }
                    } }
                ,{field:'remark',align: "center", width:200, title: '提现备注'}
                ,{field:'create_time', title: '创建时间',align: "center",width:200}
                ,{fixed: 'right', align: 'center', title:'操作', toolbar: '#barDemo', width:120}
            ]]
            ,page: true
            ,id: 'tableId'
        })
        //监听工具条
        //监听工具条
        table.on('tool(tableTool)', function(obj){
            var data = obj.data;
            if(obj.event === 'audit'){
                xadmin.open('编辑',"/hp/goodsCate/editShow/"+data.id,500,300);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            }
        });
        //执行重载
        var $ = layui.$,active = {
            reload: function (){
                var keywords = $("input[name='keywords']").val();
                var userId = $("input[name='userId']").val();
                var dealType  = $("#dealType").val();
                //执行重载
                table.reload('tableId',{
                    page: {
                        curr: 1
                    }
                    , where: {
                        keywords:keywords,
                        userId:userId,
                        dealType:dealType
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
    /*用户-删除*/
    function member_del(id){
        var data = [id];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: {ids:data},
            url: "{{ route('user_wallet_del') }}",
            dataType: 'json',
            success: function (data) {
                if(data.code == 0){
                    layer.msg(data.msg,{icon:1,time:1000});
                }else{
                    layer.msg(data.msg,{icon:5,time:1000});
                }
                //刷新页面
                //location.reload();
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
                url: '{{route("user_wallet_del")}}',
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
