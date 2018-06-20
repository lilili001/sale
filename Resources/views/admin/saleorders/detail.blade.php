@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('sale::saleorders.title.saleorders') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('sale::saleorders.title.saleorders') }}</li>
    </ol>
@stop

@section('content')
        <div class="bgary bg-white bg-shadow radius4">
            <div slot="header" class="clearfix">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <span>订单基本信息</span> ( <span class="text <?php
                    //如果是退货相关的 点击此按钮 弹出 买卖双方 退货对话
                    if( $order->is_shipped && in_array( $order->order_status , [15,16,17,10,11,12,13,21] ) ){
                        echo "refund_process";
                    }

                ?>" style="color:red">{{config('order.status')[$order->order_status]}}</span> )

                {{-- 订单已付款3 卖家订货 订货完成 状态变为6 --}}
                @if( $order->is_paid && $order->order_status == 3)
                    <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal"><a href="">订货</a></el-button>
                @endif

                {{--case2: 如果订单已付款 并且已和供应商订货 订单状态为6 准备出库 则卖家可以操作[发货] 发货完毕 状态变为7 已发货--}}
                @if( $order->is_paid &&  $order->order_status == 6 )
                    <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal"><a href="">发货</a></el-button>
                @endif

                {{--case3:
                如果已付款 并且没和供应商订货 则买家可以提退款申请15
                或者是买家退货退款申请 退货收到 状态变为13的时候
                卖家可以审批 审批完状态变成16--}}
                @if(
                   ($order->is_paid &&
                    $order->is_ordered_with_supplier == false &&
                    $order->order_status == 15) ||
                    $order->order_status == 13
                )
                    {{-- 退款完毕后 状态变成 17 --}}
                    <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal"><a href="">退款审批通过</a></el-button>
                @endif

                {{--case4: 已收到货 退货申请10, 审批完毕状态变为11 --}}
                @if( $order->order_status == 10 )
                    <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal"><a href="">退货审批通过</a></el-button>
                @endif
            </div>

            <section class="order_basic_info">
                <div class="row mar-b10">
                    <div class="col-md-4">
                        <span class="w80 label666">订单号:</span> <span>{{$order->order_id}}</span>
                    </div>
                        <div class="col-md-4">
                        <span class="w80 label666">订单金额:</span> <span><b>{{$order->currency .' '. $order->amount_current_currency}}</b></span>
                    </div>
                            <div class="col-md-4">
                        <span class="w80 label666">付款方式:</span> <span>{{ $order->payment_gateway  }}</span>
                    </div>
                </div>

                <div class="row mar-b10">
                    <div class="col-md-4"><span class="w80 label666">订单状态:</span> <span> {{ config('order.status')[$order->order_status]  }} </span></div>
                    <div class="col-md-4"><span class="w80 label666">货币:</span> <span> {{$order->currency}} </span></div>
                    <div class="col-md-4"><span class="w80 label666">下单时间:</span> <span> {{ $order->created_at  }} </span></div>
                </div>

                <div class="row mar-b10">
                        @if( $order->is_shipped )
                            <div class="col-md-4"><span class="w80 label666">发货方式:</span> <span>{{  $order->delivery->delivery  }} </span></div>
                            <div class="col-md-4"><span class="w80 label666" >追踪单号:</span> <span>{{  $order->delivery->tracking_number  }} </span></div>
                            <div class="col-md-4"><span class="w80 label666" >发货时间:</span> <span>{{  $order->delivery->created_at  }} </span></div>
                        @endif
                </div>
            </section>

            @if($order->is_shipped)
                <hr>
                <h4>Shipping Info</h4>
                <div class="mar-b10">
                    <span class="w80 label666">收货人:</span> <span>{{ $order->address->name  }}</span>
                </div>
                <div class="mar-b10">
                    <span class="w80 label666">收货地址:</span> <span>{{ $order->address->street . ' ,' .$order->address->city .  ' ,' .$order->address->state . ' ,' .$order->address->country  }}</span>
                </div>
                <div>
                    <span class="w80 label666">收货人电话:</span> <span>{{ $order->address->telephone }}</span>
                </div>
            @endif

            <hr>
            <h4>Product Info</h4>
            <table class="table">
                <thead>
                <tr>
                    <th width="280">Items</th>
                    <th>Unit Price</th>
                    <th>Qty(pcs)</th>
                    <th>SubTotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $order->product as $item )
                    <tr>
                        <td> <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object" src="{{$item->pic_path}}" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">{{ $item->title  }}</h5>
                                    <div>
                                        @foreach( json_decode($item->options)->selectedItemLocale as $key=>$value )
                                            <span> {{ $loop->first ? '' : ','  }} {{ $key .':'.$value  }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>  </td>
                        <td> {{ $order->currency . ' ' . $item->unit_price_current_currency  }} </td>
                        <td> {{ $item->quantity  }}</td>
                        <td>{{ $order->currency . ' ' . $item->subtotal_current_currency  }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <hr>
            <h4>Supplier Info</h4>
            <table class="table">
                <thead>
                <tr>
                    <th>item</th>
                    <th>price(rmb)</th>
                    <th>suppliy price</th>
                    <th>supplier</th>
                    <th>supplier sku</th>
                    <th>supplier item slug</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    @include('sale::admin.saleorders.partials.refund-comments')

@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('sale::saleorders.title.create saleorder') }}</dd>
    </dl>
@stop

@push('js-stack')
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });

            var orderid = $('[name="order_id"]').val();
            $('.refund_process').click(function(){
                console.log(123)
                $('#refund-comments').modal('show');
            });

        });
    </script>
@endpush
