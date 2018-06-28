<!-- Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Shipping</h4>
      </div>

      <form class="form-horizontal" id="shipForm" autocomplete="off" novalidate="novalidate">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Shipping Method</label>
                      <div class="col-sm-10">
                        <select class="form-control" name="shipping_method" id="">
                            @foreach( getCarrierList()['data'] as  $key=>$shipping )
                            <option value="{{$shipping['code']}}">{{$shipping['name']}}</option>
                              @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="form-group is-required">
                      <label for="tracking_number" class="col-sm-2 control-label">Tracking Number</label>
                      <div class="col-sm-10">
                        <input  name="tracking_number" number min="0"  required  type="text" class="form-control" id="tracking_number" placeholder="text">
                      </div>
                    </div>

                </div>
          </div>
            <div class="error"></div>
          <div class="modal-footer">
                <button type="button" class="btn btn-default"   data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="confirm-btn">Save changes</button>
          </div>

      </form>
    </div>
  </div>
</div>
