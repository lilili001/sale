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

            {{--************物流信息****************--}}
            @if( $tracking['meta']['code'] == 200 )
                <hr>
                <section>
                    <h4>物流信息</h4>

                    <div>运单号：{{$tracking['data']['tracking_number']}} ,{{$tracking['data']['carrier_code']}} ,{{$tracking['data']['status']}} </div>
                    <div>发货国家：{{$tracking['data']['original_country']}}, 收货国家：{{$tracking['data']['destination_country']}}</div>
                    <div>{{$tracking['data']['updated_at']}} <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#shippingDetail">
                            查看详情
                        </button> </div>

                {{--alix start--}}
                <!-- Modal -->
                    <div class="modal fade" id="shippingDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">物流追踪</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>日期</th>
                                            <th>状态</th>
                                            <th>信息</th>
                                            <th>节点状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $origin_tracking_info = $tracking['data']['origin_info']['trackinfo'];
                                        $destination_tracking_info = $tracking['data']['destination_info']['trackinfo'];
                                        ?>
                                        @if( !empty($origin_tracking_info)   )
                                            <tr>
                                                <td>{{$origin_tracking_info['date']}}</td>
                                                <td>{{ $origin_tracking_info['StatusDescription'] }}</td>
                                                <td>{{ $origin_tracking_info['Details'] }}</td>
                                                <td>{{ $origin_tracking_info['checkpoint_status'] }}</td>
                                            </tr>
                                        @endif

                                        @if( !empty( $destination_tracking_info )  )
                                            <tr>
                                                <td>{{ $destination_tracking_info['date']}}</td>
                                                <td>{{ $destination_tracking_info['StatusDescription'] }}</td>
                                                <td>{{ $destination_tracking_info['Details'] }}</td>
                                                <td>{{ $destination_tracking_info['checkpoint_status'] }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--alix end--}}
                </section>
            @endif

            <section class="product_info_box mar-t20">
                <hr>
                <h4>Product Info</h4>
                <table class="table">
                    <thead class="bg-greyf9f8f7">
                    <tr >
                        <th width="300">Items</th>
                        <th>Unit Price</th>
                        <th>Qty(pcs)</th>
                        <th>SubTotal</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                                <span>
                                                                        @if( $loop->first  == false )
                                                        ,
                                                    @endif
                                                    {{ $key .':'.$value  }}
                                                                    </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td> {{ $order->currency . ' ' . $item->unit_price_current_currency  }} </td>
                            <td> {{ $item->quantity  }}</td>
                            <td>{{ $order->currency . ' ' . $item->subtotal_current_currency  }}</td>

                            <td>
                                {{--*********************如果有退款 退货相关则显示 如果没有则按订单状态显示***********************--}}
                                <?php
                                $refund = $item->refund()->get()->first();
                                $return = $item->goods_return()->get()->first();
                                ?>
                                {{--如果有退款申请 并且无需退货 【退款申请,等待卖家审批】--}}
                                {{--如果有退款申请 并且无需退货 已退款 【已退款】--}}
                                {{--如果有退款申请 并且需退货 【退货申请,等待卖家审批】--}}
                                {{--如果有退款申请 并且需退货 卖家已审批 【卖家同意退货】  --}}
                                {{--如果有退款申请 已填写退货单【商品退货中】  --}}
                                {{--如果有退款申请 已填写退货单【已退款】  --}}

                                @if( !empty($refund) && $item->id == $refund->item_id
                                    && $refund->need_return_goods == 0
                                    && $refund->approve_status == 0 )
                                    退款申请中,等待卖家审批
                                @endif

                                @if(!empty($refund) && $item->id == $refund->item_id
                                    && $refund->refund_status == 1 )
                                    已退款
                                @endif

                                @if(!empty($refund) && $item->id == $refund->item_id
                                 && $refund->need_return_goods == 1
                                 && $refund->approve_status == 0
                                 && $refund->refund_status == 0
                                 )
                                    退货申请中,等待卖家审批
                                @endif

                                @if(!empty($refund) && $item->id == $refund->item_id
                                 && $refund->need_return_goods == 1
                                 && $refund->approve_status == 1
                                 && $refund->refund_status == 0
                                 && empty($return)
                                 )
                                    卖家同意退货
                                @endif

                                @if(!empty($refund) && !empty($return) && $item->id == $return->goods_id
                                    && empty($return->shipping_time) == false
                                    && $return->pickup_time ==false
                                    && $refund->refund_status == 0
                                )
                                    商品退货中
                                @endif

                                @if(!empty($refund) && !empty($return) && $item->id == $return->goods_id
                                    && empty($return->shipping_time) == false
                                    && empty($return->pickup_time) == false
                                    && $refund->refund_status == 0
                                )
                                    卖家已收到退货
                                @endif

                                {{--<span>{{  config('order')['status'][$order->order_status]  }}</span>--}}

                            </td>

                            <td data-orderid="{{$order->order_id}}"
                                data-itemid="{{ $item->id }}"
                                data-amount="{{$item->subtotal_current_currency}}"
                                data-currency="{{$order->currency}}"
                            >

                                @if(!empty($refund) && !empty($return) && $item->id == $return->goods_id
                                   && empty($return->shipping_time) == false
                                   && $return->pickup_time ==false
                                   && $refund->refund_status == 0
                               )
                                    <a href="javascript:;" class="view-return-tracking"
                                       data-carrier="{{$return->delivery}}"
                                       data-tracking_number="{{$return->tracking_no}}"
                                    >查看退货物流</a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>

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

        <!-- 查看退货物流信息 Modal -->
        <div class="modal fade" id="return_modal" tabindex="-1" role="dialog" aria-labelledby="#return_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Tracking Info</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
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
                $('#refund-comments').modal('show');
            });

            $('.view-return-tracking').click(function(){
                var carrier = $(this).data('carrier');
                var tracking_number = $(this).data('tracking_number');
                $.post(route('frontend.order.getSingleTrackingResult'),{
                    _token:'{{csrf_token()}}',
                    carrier_code: carrier,
                    tracking_number:tracking_number
                }).then(function(res){
                    var data = res.result;
                    if(data.meta.code == 200){
                        var d = data.data;
                        var str = '';
                        var str_above='';

                        var str_origin_info = '';
                        var str_destination_info = '';

                        str_above += 'Tracking Number: '+ d.tracking_number + ', '+d.carrier_code+'<br>'+
                            'Start country: ' + d.original_country +'<br>Delivery country: '+d.destination_country+'<br>'+
                            'Updated At: '+ d.updated_at;

                        if( d.origin_info.trackinfo !== null ){
                            for (var i = 0; i<d.origin_info.trackinfo.length ; i++){
                                var tracking_info = d.origin_info.trackinfo[i];
                                str_origin_info+= '<tr><td>'+tracking_info['date']+'</td>' +
                                    '<td>\'+tracking_info[\'StatusDescription\']+\'</td>' +
                                    '<td>\'+tracking_info[\'Details\']+\'</td>' +
                                    '<td>\'+tracking_info[\'checkpoint_status\']+\'</td></tr>'
                            }
                        }

                        if( d.destination_info.trackinfo !== null ){
                            for (var i = 0; i<d.destination_info.trackinfo.length ; i++){
                                var tracking_info = d.origin_info.trackinfo[i];
                                str_destination_info+= '<tr><td>'+tracking_info['date']+'</td>' +
                                    '<td>\'+tracking_info[\'StatusDescription\']+\'</td>' +
                                    '<td>\'+tracking_info[\'Details\']+\'</td>' +
                                    '<td>\'+tracking_info[\'checkpoint_status\']+\'</td></tr>'
                            }
                        }

                        str+= '<div>'+str_above+'</div><table class="table table-bordered"><thead><th>date</th><th>StatusDescription</th><th>Details</th><th>checkpoint_status</th></thead>' +
                            '<tbody>'+str_origin_info+str_destination_info+'</tbody></table>';

                        $('#return_modal .modal-body').html(str);
                        $('#return_modal').modal('show');

                    }
                })
            });
        });
    </script>
@endpush
