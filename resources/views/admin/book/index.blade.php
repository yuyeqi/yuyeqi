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
                    <div class="layui-collapse" lay-filter="test">
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">条件筛选<i class="layui-icon layui-colla-icon"></i></h2>
                            <div class="layui-colla-content">
                                <form class="layui-form" action="javascript:">
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">预约码</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="book_no" placeholder="请输入预约码" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">销售姓名</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="user_name" placeholder="请输入销售姓名" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">客户姓名</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="client_name" placeholder="请输入客户姓名" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">客户电话</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="client_phone" placeholder="请输入客户电话" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">小区名称</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="community" placeholder="请输入小区名称" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">楼层地址</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="house_name" placeholder="请输入楼层地址" autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">创建时间</label>
                                            <div class="layui-input-inline layui-show-xs-block">
                                                <input class="layui-input" placeholder="开始日" name="start"></div>
                                            <div class="layui-input-inline layui-show-xs-block">
                                                <input class="layui-input" placeholder="截止日" name="end" ></div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">成交时间</label>
                                            <div class="layui-input-inline layui-show-xs-block">
                                                <input class="layui-input" placeholder="开始日" name="deal_start"></div>
                                            <div class="layui-input-inline layui-show-xs-block">
                                                <input class="layui-input" placeholder="截止日" name="deal_end"></div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">预约状态</label>
                                            <div class="layui-input-inline">
                                                <select name="status"  id="selectStatus" lay-search="">
                                                    <option value="">全部</option>
                                                    <option value="10">预约</option>
                                                    <option value="20">到店</option>
                                                    <option value="30">预算</option>
                                                    <option value="40">成交</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" id="subBtn" data-type="reload">搜索</button>
                                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                    <button class="layui-btn" onclick="xadmin.open('添加案例','{{ route('cases_add_show') }}',700,500)"><i class="layui-icon"></i>添加</button>
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
            ,url: "{{ route('book_lists') }}"
            ,cols: [[
                {type: 'checkbox',field: 'left'}
                ,{field:'id', width:80, title: 'ID', sort: true,align: "center"}
                ,{field:'book_no', width:120, title: '预约码', align:"center"}
                ,{field:'client_name', width:120, title: '客户姓名',align: "center"}
                ,{field:'client_phone', width:120, title: '客户电话',align: "center"}
                ,{field:'sex', width:120, title: '性别',align: "center"}
                ,{field:'province', width:120, title: '省市区', align:"center"}
                ,{field:'community', width:120, title: '小区名称',align: "center"}
                ,{field:'house_name', width:120, title: '楼层地址',align: "center"}
                ,{field:'arrive_time', width:150, title: '到店时间',align: "center"}
                ,{field:'actual_arrive_time', width:150, title: '实际到店时间',align: "center"}
                ,{field:'deal_finished_time', width:150, title: '交易完成时间',align: "center"}
                ,{field:'arrive_time', width:150, title: '到店时间',align: "center"}
                ,{field:'status', title: '状态',align: "center", width:120,templet: function(d){
                        return d.status.status_name
                    } }
                ,{field:'create_user_name',align: "center", width:100, title: '创建人'}
                ,{field:'create_time', title: '创建时间',align: "center",width:200}
                ,{field:'update_user_name',align: "center", width:100, title: '更新人'}
                ,{field:'update_time', title: '更新时间',align: "center",width:200}
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
                xadmin.open('编辑',"/hp/cases/editShow/"+data.id,600,650);
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
                var book_no = $("input[name='book_no']").val();
                var client_name = $("input[name='client_name']").val();
                var client_phone = $("input[name='client_phone']").val();
                var community = $("input[name='community']").val();
                var house_name = $("input[name='house_name']").val();
                var start_time = $("input[name='start']").val();
                var end_time = $("input[name='end']").val();
                var deal_start_time = $("input[name='deal_start']").val();
                var deal_end_time = $("input[name='deal_end']").val();
                var status = $("input[name='status']").val();
                var user_name = $("#selectStatus").val();
                //执行重载
                table.reload('tableId',{
                    page: {
                        curr: 1
                    }
                    , where: {
                        book_no:book_no,
                        client_name:client_name,
                        client_phone:client_phone,
                        community:community,
                        house_name:house_name,
                        start_time:start_time,
                        end_time:end_time,
                        deal_start_time:deal_start_time,
                        deal_end_time:deal_end_time,
                        status:status,
                        user_name:user_name
                    }
                })
            }
        }
        //点击搜索
        $('#subBtn').on('click',function (){
            var type = $(this).data('type');
            console.log(type)
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
