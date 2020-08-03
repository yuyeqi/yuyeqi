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
                        <input class="layui-input" name="keywords" autocomplete="off" placeholder="请输入用户名/商品名">
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
    @{{# if(d.deal_status == 10){ }}
    <a class="layui-btn layui-btn-xs audit" lay-event="audit">待处理</a>
    @{{# } }}
    @{{# if(d.deal_status == 20){ }}
    <a class="layui-btn layui-btn-xs audit" lay-event="audit">已发货</a>
    @{{# } }}
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
            ,url: "{{ route('exchange_record_lists') }}"
            ,cols: [[
                {type: 'checkbox',field: 'left'}
                ,{field:'id', width:80, title: 'ID', sort: true,align: "center"}
                ,{field:'deal_no', width:200, title: '兑换单号',align:"center"}
                ,{field:'goods_name', width:120,align: "center", title: '商品名称'}
                ,{field:'deal_score', width:100, title: '兑换积分',align:"center"}
                ,{field:'surplus_score', width:120,align: "center", title: '剩余积分'}
                ,{field:'deal_status', title: '状态', width:120,templet: function(d){
                        if(d.deal_status == 10){
                            return '待处理';
                        }else if (d.deal_status == 20){
                            return  '已发货';
                        }else if(d.deal_status == 30){
                            return  '已完成';
                        }else if (d.deal_status == 40){
                            return  '拒绝';
                        }else {
                            return  '';
                        }
                    } }
                ,{field:'reject_reason', width:120, title: '备注',align:"center"}
                ,{field:'deliver_time', width:200, title: '发货时间 ',align: "center"}
                ,{field:'update_user_name', title: '更新人',align: "center",width:100}
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
            if(obj.event === 'audit'){
                xadmin.open('审核',"/hp/exchange/auditShow/"+data.id,600,300);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            }else if(obj.event === 'showPic'){
                layer.photos({
                    photos: '.pic_' + data.id,
                    //0-6的选择，指定弹出图片动画类型，默认随机
                    anim: 5
                })
            }else if(obj.event === 'mulPic'){
                for (var k in data.picture){
                    layer.photos({
                        photos: '.pic_' + data.picture[k].id,
                        //0-6的选择，指定弹出图片动画类型，默认随机
                        anim: 5
                    })
                }
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
    /*用户-删除*/
    function member_del(id){
        var data = [id];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: {ids:data},
            url: "{{ route('exchange_delete_record') }}",
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
                url: '{{route("exchange_delete_record")}}',
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
