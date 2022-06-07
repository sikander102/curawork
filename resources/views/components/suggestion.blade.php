<div class="my-2 shadow  text-white bg-dark p-1" id="suggestion_table">
</div>
<div id="connections_in_common_skeleton" class="">
    <br>
    <!-- <span class="fw-bold text-white">Loading Skeletons</span> -->
    <div class="px-2">
      @for ($i = 0; $i < 10; $i++)
        <x-skeleton />
      @endfor
    </div>
  </div>
  <div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
      <button class="btn btn-primary" onclick="getMoreSuggestions()" id="load_more_btn">Load more</button>
  </div>
<script>
  $( document ).ready(function() {
    // On document ready getting all suggested user by calling function getSuggestions in public/Main.js
    getSuggestions();
  });
  //all suggested user by calling function getSuggestions in public/Main.js
  $("#get_suggestions_btn").click(function(){
    getSuggestions();
  });
</script>