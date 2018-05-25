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
                <span>订单基本信息</span>
                <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal">发货</el-button>
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
                    <div class="col-md-4"><span class="w80 label666">订单状态:</span> <span> </span></div>
                    <div class="col-md-4"><span class="w80 label666">发货方式:</span> <span> </span></div>
                    <div class="col-md-4"><span class="w80 label666" >追踪单号:</span> <span> </span></div>
                </div>

                <div class="row mar-b10">
                    <div class="col-md-4"><span class="w80 label666">货币:</span> <span> {{$order->currency}} </span></div>
                    <div class="col-md-4"><span class="w80 label666">下单时间:</span> <span>2018-05-23</span></el-col>
                </div>
            </section>
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
                                    <dl>
                                        @foreach( json_decode($item->options)->selectedItemLocale as $key=>$value )
                                            <dd>{{ $key .':'.$value  }}</dd>
                                        @endforeach
                                    </dl>
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
    <script type="text/javascript">

    </script>
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
        });
    </script>
@endpush
