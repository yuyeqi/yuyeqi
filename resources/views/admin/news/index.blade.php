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
                    <div class="layui-inline">
                        <input class="layui-input" name="keywords" autocomplete="off">
                    </div>
                    <button class="layui-btn" data-type="reload">立即提交</button>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                    <button class="layui-btn" onclick="xadmin.open('添加新闻','{{ route('news_add_show') }}',800,600)"><i class="layui-icon"></i>添加</button>
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
        var username = $("input[name='username']").val();
        //表格展示
        table.render({
            elem: '#table'
            ,url: "{{ route('news_lists') }}"
            ,cols: [[
                {type: 'checkbox',field: 'left',data:'id'}
                ,{field:'id', width:80, title: 'ID',align: "center", sort: true}
                ,{field:'news_title', width:150,align: "center", title: '标题',align: "center"}
                ,{field:'news_cover',align: "center", title: '主图',templet: function(d){
                       return  "<span id='pic_" + d.id + "'><img src='" + d.news_cover + "'  style='width: 40px' lay-event='showPic' ></span>";
                    } }
                ,{field:'read_num', width:150, title: '点击量', width:80,align: "center"}
                ,{field:'is_recommend', title: '推荐',align: "center", width:120,templet: function(d){
                        if(d.is_recommend == 0){
                            return '<button type="button" onclick="member_recommend('+d.id+','+d.is_recommend+')" class="layui-btn layui-btn-normal">正常</button>'
                        }else{
                            return '<button type="button" onclick="member_recommend('+d.id+','+d.is_recommend+')" class="layui-btn layui-btn-warm">推荐</button>'
                        }
                    } }
                ,{field:'status', title: '状态',align: "center", width:120,templet: function(d){
                        if(d.status == 10){
                            return '<button type="button" onclick="member_stop('+d.id+','+d.status+')" class="layui-btn layui-btn-normal">正常</button>'
                        }else{
                            return '<button type="button" onclick="member_stop('+d.id+','+d.status+')" class="layui-btn layui-btn-danger">禁用</button>'
                        }
                    } }
                ,{field:'update_user_name',align: "center", width:100, title: '更新人'}
                ,{field:'update_time', title: '更新时间',align: "center",width:200}
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
            if(obj.event === 'edit'){
                xadmin.open('编辑',"/hp/news/editShow/"+data.id,800,600);
            } else if(obj.event === 'del'){
                layer.confirm('确认要删除吗？',function (){
                    member_del(data.id);
                })
            } else if(obj.event === 'showPic'){
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
                url: "{{ route('news_update_status') }}",
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
    /*新闻推荐*/
    function member_recommend(id,is_recommend){
        var is_recommend = is_recommend == 0 ? 1 : 0;
        console.log(is_recommend)
        var msg = is_recommend == 0 ? '确认要取消吗？' : '确认要推荐吗？';
        layer.confirm(msg,function(index){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: "{{ route('news_update_recommend') }}",
                dataType: 'json',
                data: {id:id,is_recommend:is_recommend},
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
        var data = [id];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: {ids:data},
            url: "{{ route('news_del') }}",
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
                url: '{{route("news_del")}}',
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
