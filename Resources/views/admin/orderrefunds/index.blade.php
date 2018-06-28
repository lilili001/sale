@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('sale::orderrefunds.title.orderrefunds') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('sale::orderrefunds.title.orderrefunds') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.sale.orderrefund.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('sale::orderrefunds.button.create orderrefund') }}
                    </a>
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
                                <th>退款编号</th>
                                <th>订单编号</th>
                                <th>买家</th>
                                <th>交易金额</th>
                                <th>退款金额</th>
                                <th>是否要求退货</th>
                                <th>申请时间</th>
                                <th>退款时间</th>
                                <th>退款状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($orderrefunds)): ?>
                            <?php foreach ($orderrefunds as $orderrefund): ?>
                            <tr>
                                <td> {{$orderrefund->refund_no}} </td>
                                <td> {{$orderrefund->order_id}} </td>
                                <td> {{$orderrefund->payerId}} </td>
                                <td> {{$orderrefund->payerId}} </td>
                                <td> {{$orderrefund->amount}} </td>
                                <td> {{$orderrefund->need_return_goods ? '是' : '否'   }}   </td>
                                <td> {{$orderrefund->created_at}} </td>
                                <td> {{$orderrefund->updated_at}} </td>
                                <td> {{ $orderrefund->refund_status ? '已退款' : '未退款' }} </td>
                                <td>
                                    {{--如果不要求退货的 可以直接审批退款一个步骤--}}
                                    @if( $orderrefund->approve_status == 1 )
                                        {{--如果需要退货 则卖家收到货后才显示退款按钮--}}
                                    <?php
                                        $orderProduct = $orderrefund->item()->get()->first();
                                        $returnGood = $orderProduct->goods_return()->first();
                                    ?>
                                    {{--如果需要退货 则退货收到后显示退款按钮--}}
                                        @if( $orderrefund->need_return_goods &&  isset($returnGood->pickup_time)  && $orderrefund->refund_status == 0  )
                                            <span class="approve-refund" data-refund_id="{{ $orderrefund->refund_no }}">{{ $orderrefund->refund_status || $orderrefund->updated_at ? '' : '退款' }}</span>
                                        @elseif( $orderrefund->need_return_goods == 0 && $orderrefund->refund_status == 0 )
                                            <span class="approve-refund" data-refund_id="{{ $orderrefund->refund_no }}">{{ $orderrefund->refund_status || $orderrefund->updated_at ? '' : '退款' }}</span>
                                        @endif
                                     @endif
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
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('sale::orderrefunds.title.create orderrefund') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.sale.orderrefund.create') ?>" }
                ]
            });
        });
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
                "order": [[ 6, "asc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });

            //退款操作
            $('.approve-refund').click(function(){
                var _this = this;
                $.post(route('frontend.order.refund.approve',{refundId: $(_this).data('refund_id') }),
                    {
                        _token:'{{csrf_token()}}'
                    }).then(function(res){
                      if(res.code == 0){
                          alert('退款成功');
                          location.reload()
                      }
                })
            });
        });
    </script>
@endpush
