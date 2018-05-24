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
    <el-card>
        <div slot="header" class="clearfix">
            <span>订单基本信息</span>
            <el-button style="float: right;  " type="primary" data-toggle="modal" data-target="#shipModal">发货</el-button>
        </div>
        <section class="order_basic_info">
            <el-row>
                <el-col :span="8">
                    <span class="w80 label666">订单号:</span> <span>{{$order->order_id}}</span>
                </el-col>
                <el-col :span="8">
                    <span class="w80 label666">订单金额:</span> <span>{{$order->currency . $order->amount_current_currency}}</span>
                </el-col>
                <el-col :span="8">
                    <span class="w80 label666">付款方式:</span> <span>{{ $order->payment_gateway  }}</span>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="8"><span class="w80 label666">订单状态:</span> <span> </span></el-col>
                <el-col :span="8"><span class="w80 label666">发货方式:</span> <span> </span></el-col>
                <el-col :span="8"><span class="w80 label666" >追踪单号:</span> <span> </span></el-col>
            </el-row>

            <el-row>
                <el-col :span="8"><span class="w80 label666">货币:</span> <span> {{$order->currency}} </span></el-col>
                <el-col :span="8"><span class="w80 label666">下单时间:</span> <span>2018-05-23</span></el-col>
            </el-row>
        </section>
        <hr>

        <h4>Shipping Info</h4>
        <el-row>
            <span class="w80 label666">收货人:</span> <span>{{ $order->address->name  }}</span>
        </el-row>
        <el-row>
            <span class="w80 label666">收货地址:</span> <span>{{ $order->address->street . ' ,' .$order->address->city .  ' ,' .$order->address->state . ' ,' .$order->address->country  }}</span>
        </el-row>
        <el-row>
            <span class="w80 label666">收货人电话:</span> <span>{{ $order->address->telephone }}</span>
        </el-row>

        <hr>
        <h4>Product Info</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Items</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>SubTotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $order->product as $item )
                    <tr>
                        <td> {{$item->title}}  </td>
                        <td> {{ $order->currency . $item->unit_price_current_currency  }} </td>
                        <td> {{ $item->quantity  }}</td>
                        <td>{{ $item->subtotal  }}</td>
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

    </el-card>
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
