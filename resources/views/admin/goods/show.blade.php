@extends('admin.layouts.app')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">商品名称</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->goods_name or '' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">商品编码</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->goods_no or '' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">封面图</label>
                        <div class="layui-input-inline">
                            <img style="width: 100px;height: 100px" src="{{ $detail->cover or '' }}" class="layui-upload-img">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label"> 轮播图</label>
                       {{-- <div class="layui-input-block">
                            @foreach($pictures as $item)
                                <img style="width: 100px;height: 100px" src="{{ $item or '' }}" class="layui-upload-img">
                            @endforeach
                        </div>--}}
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">商品价格</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->good_price or '0.00' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">订金价格</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->book_price or '0.00' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">商品分类</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->cate_id or '0' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">赠送积分</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->score or '0' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">商品状态</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->goods_status['status_name'] }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否新品</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->is_news['status_name'] }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">是否热门</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->is_hot['status_name'] }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否推荐</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->is_recommend['status_name'] }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">初始销量</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->sales_initial or '0' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">实际销量</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->sales_actual or '0' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">修改人</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->update_user_name or '0' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">创建人</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->create_user_name or '0' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">修改时间</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->update_time or '0' }}" disabled class="layui-input layui-bg-gray" >
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">创建时间</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{ $detail->create_time or '0' }}" disabled class="layui-input layui-bg-gray">
                        </div>
                    </div>
                </div>
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品简介</label>
                        <div class="layui-input-block">
                            <textarea  disabled class="layui-textarea layui-bg-gray"> {{ $detail->goods_desc or '' }}</textarea>
                        </div>
                    </div>
                </form>

        </div>
    </div>
@endsection
