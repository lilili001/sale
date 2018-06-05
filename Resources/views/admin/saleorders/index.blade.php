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

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">

                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>订单号</th>
                                    <th>支付类型</th>
                                    <th>支付金额</th>
                                    <th>订单状态</th>
                                    <th>{{ trans('core::core.table.created at') }}</th>
                                    <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($orders)): ?>
                            <?php foreach ($orders as $key => $order): ?>
                            <tr>
                                <td>{{ $key+1  }}</td>
                                <td> <a href="{{ route('admin.sale.saleorder.detail', ['order' => $order->order_id] ) }}">{{ $order->order_id  }}</a> </td>
                                <td>{{ $order->payment_gateway  }}</td>
                                <td>{{ $order->currency . $order->amount_current_currency  }}</td>
                                <td>{{  config('order.status')[$order->order_status]  }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    <div class="btn-group" data-orderid="{{ $order->order_id  }}">

                                        {{-- 订单已付款3 卖家订货 订货完成 状态变为6 --}}
                                        @if( $order->is_paid && $order->order_status == 3)
                                        <a class="order_with_supplier" href="javascript:;">订货</a>
                                        @endif

                                        {{--case2: 如果订单已付款 并且已和供应商订货 订单状态为6 准备出库 则卖家可以操作[发货] 发货完毕 状态变为7 已发货--}}
                                        @if( $order->is_paid &&  $order->order_status == 6 )
                                        <a  class="ship"><span>发货</span></a>
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
                                        <a href="javascript:;" class="refund-approve">审批通过</a>
                                        @endif

                                        @if($order->order_status == 17 )
                                            <span>退款成功</span>
                                        @endif

                                        {{--case4: 已收到货 退货申请10, 审批完毕状态变为11 --}}
                                        @if( $order->order_status == 10 )
                                        <a href="javascript:;" class="return-approve">退货审批通过</a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>

                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('sale::admin.saleorders.partials.ship')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd> </dd>
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

            //和供应商订货
            $('.order_with_supplier').click(function(){
                var _this = this;
                var order = $(this).parent().data('orderid');
                $.post(
                    route('frontend.order.order_with_supplier',{order:order}),
                    {
                        _token:'{{csrf_token()}}'
                    }
                ).then(function(res){
                    if(res.code == 0){
                        $(_this).text('发货')
                        $(_this).addClass('ship').removeClass('order_with_supplier')
                    }
                })
            });

            //发货
            $('body').on('click','.ship',function(){
                var order = $(this).parent().data('orderid');
                var _this = this;
                $('#shipModal').modal('show');
                $('#shipForm').validate({
                    messages: {
                        tracking_number: {
                            required: 'tracking number is required！'
                        }
                    },
                    submitHandler:function(form) {
                        $.post(route('admin.sale.saleorder.ship', {'order':order}
                        ), $('#shipForm').serializeArray()).then(function(res){
                            if( res.code == 0 ){
                                //发货成功 关闭弹窗
                                $('#shipModal').modal('hide');
                                $(_this).text('')
                            }
                        })
                    },
                })
            });

            //退款审批通过
            $('.refund-approve').click(function(){
                var order = $(this).parent().data('orderid');
                var _this = this;
                $.post( route('frontend.order.refund.approve',{'order':order})  ,  {
                    _token:'{{csrf_token()}}'
                } ).then( (res)=>{
                    if(res.code == 0){
                        $(_this).text('审批通过')
                    }
                })
            });

            //退货审批通过
            $('.return-approve').click(function(){
                var order = $(this).parent().data('orderid');
                var _this = this;
                $.post( route('frontend.order.return.approve',{'order':order})  ,  {
                    _token:'{{csrf_token()}}'
                } ).then( (res)=>{
                    if(res.code == 0){
                        location.reload()
                    }
                })
            })

        });
    </script>
@endpush
