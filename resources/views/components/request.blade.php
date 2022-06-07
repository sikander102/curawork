<div class="my-2 shadow text-white bg-dark p-1" id="request_table">
</div>
<div id="request_skeleton" style="display: none;">
    <br>
    <div class="px-2">
      @for ($i = 0; $i < 10; $i++)
        <x-skeleton />
      @endfor
    </div>
  </div>
  <div class="d-flex justify-content-center mt-2 py-3 d-none" id="load_more_btn_parent">
      <button class="btn btn-primary" onclick="getMoreRequests()" id="load_more_btn_request">Load more</button>
  </div>
<script>
  //all requested user by calling function getRequests in public/Main.js
  $("#get_sent_requests_btn").click(function(){
    getRequests('sent');
  });
  //all received user by calling function getReceiveRequests in public/Main.js
  $("#get_received_requests_btn").click(function(){
    getReceiveRequests('receive');
  });
</script>