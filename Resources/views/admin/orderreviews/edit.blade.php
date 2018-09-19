@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('sale::orderreviews.title.edit orderreview') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.sale.orderreview.index') }}">{{ trans('sale::orderreviews.title.orderreviews') }}</a></li>
        <li class="active">{{ trans('sale::orderreviews.title.edit orderreview') }}</li>
    </ol>
@stop

@section('content')
    {{--{!! Form::open(['route' => ['admin.sale.orderreview.update', $orderreview->id], 'method' => 'put']) !!}--}}
    {!! Form::open(['route' => ['admin.sale.orderreview.store' ], 'method' => 'post']) !!}

    <div class="row">
        <button type="submit" id="approve" class="btn btn-primary btn-flat pull-right" style="margin: 0px 15px 15px 0px;">Approve</button>
    </div>

    <div class="row">
        <div class="col-md-12">
                <div class="box padding2030">

                    {{--获取订单产品--}}
                    <div class="row">
                        <div class="col-md-4">订单编号:{{$orderreview->order_id}} </div>
                        <div class="col-md-4">下单时间:{{ getOrder( $orderreview->order_id )->created_at }}</div>
                        <div class="col-md-4 text-right">评论时间：{{$orderreview->created_at}}</div>
                    </div>
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="{{$product->pic_path}}" alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">{{$product->title}}</h4>
                            <div>
                                <?php
                                $i = 0;
                                $options = json_decode( $product->options );
                                ?>
                                @foreach(  $options->selectedItemLocale as  $key=>$option )

                                    @if($loop->first)
                                    @else
                                        |
                                    @endif

                                    <span>{{ $key .':'. $option  }}</span>

                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{--获取评论--}}
                    <div id="score"></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">评论内容：</label>
                        <div>
                            <b data-userid="{{$orderreview->urser_id}}">
                                {{ getUser( $orderreview->user_id )->first_name .' '.getUser( $orderreview->user_id )->last_name }}:
                            </b>
                            {{$orderreview->content}}
                        </div>
                        <?php
                            $appraise_img_path =  mb_split(';',$orderreview->appraise_img_path);
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($appraise_img_path as $key => $img)
                                    <a class="review_img" href="/images/{{$img}}" data-lightbox="roadtrip">
                                        <img width="130" src="/images/{{$img}}" alt=""></a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    @if( count( $orderreview->replies->toArray() )  )
                        <div class="form-group">
                           <b>
                               {{ getUser( $orderreview->replies->first()->user_id )->first_name .' '.getUser( $orderreview->user_id )->last_name }}回复:
                               <b data-userid="{{$orderreview->urser_id}}">
                                   {{ getUser( $orderreview->user_id )->first_name .' '.getUser( $orderreview->user_id )->last_name }}:
                               </b>
                           </b> {{ $orderreview->replies->first()->content  }}
                        </div>
                    @endif

                    <div class="clearfix"></div>

                    {{--评论回复--}}
                    <a href="javascript:;" id="reply">回复</a>
                          <textarea name="review_reply" class="w100f form-control" cols="30" rows="4"></textarea>
                          <input type="hidden" _token="{{csrf_token()}}">
                          <input type="hidden" name="goods_id" value="{{$product->item_id}}">
                          <input type="hidden" name="review_id" value="{{$orderreview->id}}">
                          <input type="hidden" name="to_user_id" value="{{$orderreview->user_id}}">
                </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.sale.orderreview.index') ?>" }
                ]
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            $('#score').raty({ readOnly: true, score: '{{ $orderreview->goods_score }}' ,
                starOff:'{{Theme::url('img/star-off.png')}}',
                starOn:'{{Theme::url('img/star-on.png')}}',
                starHalf:'{{Theme::url('img/star-half.png')}}',
            });

            $('#reply').click(function(){
                $('#reply-form').show()
            })


        });
    </script>
@endpush
