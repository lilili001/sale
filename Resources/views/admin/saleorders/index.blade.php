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
                                    <th>支付状态</th>
                                    <th>发货状态</th>
                                    <th>{{ trans('core::core.table.created at') }}</th>
                                    <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($saleorders)): ?>
                            <?php foreach ($saleorders as $key => $saleorder): ?>
                            <tr>
                                <td>{{ $key  }}</td>
                                <td>{{ $saleorder->order_id  }}</td>
                                <td>{{ $saleorder->payment_gateway  }}</td>
                                <td>{{ $saleorder->currency . $saleorder->amount_current_currency  }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="">
                                        {{ $saleorder->created_at }}
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group" data-orderid="{{ $saleorder->order_id  }}">
                                        <a href="{{ route('admin.sale.saleorder.detail', ['order' => $saleorder->order_id] ) }}">查看</a>
                                        <a  class="ship-button"><span>发货</span></a>
                                        <a href="javascript:;">退款</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </tfoot>
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

            $('.ship-button').unbind('click').click(function(){
                var order = $(this).parent().data('orderid');
                $('#shipModal').modal('show');
                $('#shipForm').validate({
                    messages: {
                        tracking_number: {
                            required: 'tracking number is required！'
                        }
                    },
                    submitHandler:function(form) {
                        alert('提交了');
                        $.post(route('admin.sale.saleorder.ship', {'order':order}
                            ),{
                            _token:"{{csrf_token()}}"
                        }).then(function(res){
                        })
                    },
                })
            });
        });
    </script>
@endpush
