<!-- Modal -->
<div class="modal fade" id="return_approve_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">退货审批</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal">
                <div class="box-body">

                  <div class="form-group">
                    <label class="col-sm-2 control-label">申请人</label>
                    <div class="col-sm-10 pad-t7">
                         <div class="applier_name"></div>
                    </div>
                  </div>

                  <div class="form-group">
                        <label class="col-sm-2 control-label">申请原因</label>
                        <div class="col-sm-10 pad-t7">
                            <div class="reason-body"></div>
                            <div class="reason-img"></div>
                        </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label">审批意见</label>
                    <div class="col-sm-10">
                        <select name="approve_suggestion" id="" class="form-control" >
                            <option value="1">通过</option>
                            <option value="0">驳回</option>
                        </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label">内容</label>
                    <div class="col-sm-10">
                        <textarea name="content" class="form-control w100f" id="" cols="30" rows="4"></textarea>
                    </div>
                  </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer modal-footer text-right">
                    <button type="button" class="btn btn-default cancel">取消</button>
                    <button type="button" class="btn btn-primary confirm">确认</button>
                </div>
                <!-- /.box-footer -->
          </form>
      </div>

    </div>
  </div>
</div>