<!-- Modal -->
<div class="modal fade" id="refund-comments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">退款流程</h4>
      </div>
      <div class="modal-body">
          <ul>
              @if( !empty($refund_comments) ):
              @foreach( $refund_comments as $text )
                <li>
                    <span class="user-name"> {{ getUser($text['user_id'])->first_name  }}: </span>
                    <span class="words">{{ $text['body']  }}</span>
                </li>
               @endforeach
                  @endif
          </ul>
      </div>
    </div>
  </div>
</div>