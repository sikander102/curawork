<div class="my-2 shadow text-white bg-dark p-1" id="connection_table" style="display: none;">
</div>
<div id="connection_skeleton" style="display: none;">
    <br>
    <div class="px-2">
      @for ($i = 0; $i < 10; $i++)
        <x-skeleton />
      @endfor
    </div>
  </div>
  <div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
      <button class="btn btn-primary" style="display:none;" onclick="getMoreConnections()" id="load_more_btn_connection">Load more</button>
  </div>
<script>
  //all connected user by calling function getConnections in public/Main.js
  $("#get_connections_btn").click(function(){
    getConnections();
  });
</script>