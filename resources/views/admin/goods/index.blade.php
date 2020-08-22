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
                    <button class="layui-btn" onclick="xadmin.open('添加商品','{{ route('goods_add_show') }}',850,600,true)"><i class="layui-icon"></i>添加</button>
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
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    var table;
    layui.use(['laydate','form','table'], function(){
        var laydate = layui.laydate,
        form = layui.form,
        table = layui.table,
        $ = layui.$;
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
                ,{field:'cate', title: '商品分类', width: 120,templet:function (d) {
                        if(d.cate != ''){
                            return d.cate.cate_name;
                        }else{
                            return '';
                        }
                    }}
                ,{field:'goods_cover',align: "center", title: '封面图',templet: function(d){
                        return  "<span id='pic_" + d.id + "'><img src='" + d.goods_cover + "'  style='width: 40px' lay-event='showPic' ></span>";
                    } }
                ,{field:'good_price', width:100, title: '价格'}
                ,{field:'book_price', width:100, title: '定金'}
                ,{field:'comment_num', width:80, title: '评价数'}
                ,{field:'goods_status', width:80, title: '状态',templet: function (d) {
                        if(d.goods_status == 10){
                            return '正常';
                        }else{
                            return  '下架';
                        }
                    }}
                ,{field:'is_new', title: '新品', width: 80,templet: function (d) {
                    if(d.is_new == 0){
                        return '正常';
                    }else{
                        return  '新品';
                    }
                    }}
                ,{field:'is_hot', width:80, title: '热门',templet: function (d) {
                        if(d.is_hot == 0){
                            return '正常';
                        }else{
                            return  '热卖';
                        }
                    }}
                ,{field:'is_recommend', title: '推荐', minWidth: 80,templet: function (d) {
                        if(d.is_recommend == 0){
                            return '正常';
                        }else{
                            return  '推荐';
                        }
                    }}
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
                xadmin.open('商品信息',"/hp/goods/detail/"+data.id,800,600,true);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    del_goods(data.id);
                })
            } else if(obj.event === 'edit'){
                xadmin.open('编辑',"/hp/goods/edit/"+data.id,600,650,true);
            }else if(obj.event === 'pwd'){
                setPasword(data.id);
            }else if(obj.event === 'showPic'){
                layer.photos({
                    photos: '#pic_' + data.id,
                    //0-6的选择，指定弹出图片动画类型，默认随机
                    anim: 5
                })
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
    function del_goods(id) {
        var ids = [id];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: "{{ route('goods_delete_all') }}",
            dataType: 'json',
            data: {ids:ids},
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
                url: '{{route("goods_delete_all")}}',
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
