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
                        <button class="layui-btn" data-type="reload">搜索</button>
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
                ,url: "{{ route('config_lists') }}"
                ,cols: [[
                    {type: 'checkbox',field: 'left'}
                    ,{field:'config_no', width:120, title: '配置编号', align: "center"}
                    ,{field:'config_name', width:100, title: '配置名称',align:"center"}
                    ,{field:'config_value', width:200, title: '配置值',align: "center"}
                    ,{field:'background',width: 100,align: "center", title: '配置图',templet: function(d){
                            return  "<span id='pic_" + d.id + "'><img src='" + d.background + "'  style='width: 40px' lay-event='showPic' ></span>";
                        } }
                    ,{field:'content', width:300, title: '配置内容',align: "center"}
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
                    xadmin.open('编辑',"/hp/config/editShow/"+data.config_no,800,600);
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
    </script>
@endsection
