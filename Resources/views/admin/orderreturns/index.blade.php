@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('sale::orderreturns.title.orderreturns') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('sale::orderreturns.title.orderreturns') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    {{--<a href="{{ route('admin.sale.orderreturn.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">--}}
                        {{--<i class="fa fa-pencil"></i> {{ trans('sale::orderreturns.button.create orderreturn') }}--}}
                    {{--</a>--}}
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
                                <th>序号</th>
                                <th>order_id</th>
                                <th>goods_id</th>
                                <th>user_id</th>
                                <th>delivery</th>
                                <th>tracking_no</th>
                                <th>return_status</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.updated at') }}</th>
                                <th>shipping_time</th>
                                <th>pickup_time</th>

                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($orderreturns)): ?>
                            <?php foreach ($orderreturns as $key=>$orderreturn): ?>
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $orderreturn->order_id }}</td>
                                <td>{{ $orderreturn->goods_id }}</td>
                                <td>{{ $orderreturn->user_id }}</td>
                                <td>{{ $orderreturn->delivery }}</td>
                                <td>{{ $orderreturn->tracking_no }}</td>
                                <td>{{ $orderreturn->return_status }}</td>
                                <td>{{ $orderreturn->created_at }}</td>
                                <td>{{ $orderreturn->updated_at }}</td>
                                <td>{{ $orderreturn->shipping_time }}</td>
                                <td>{{ $orderreturn->pickup_time }}</td>

                                <td>
                                    {{--<div class="btn-group">--}}
                                        {{--<a href="{{ route('admin.sale.orderreturn.edit', [$orderreturn->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>--}}
                                        {{--<button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.sale.orderreturn.destroy', [$orderreturn->id]) }}"><i class="fa fa-trash"></i></button>--}}
                                    {{--</div>--}}
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
        <dd>{{ trans('sale::orderreturns.title.create orderreturn') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.sale.orderreturn.create') ?>" }
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
                "order": [[ 8, "asc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@endpush
