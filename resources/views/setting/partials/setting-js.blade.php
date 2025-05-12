<script>

$(document).ready(function() {
    $(".mode-radio").change(function() {
        var ActiveToken = $(this).val();
        $("#mode_text").text(toUpperFirstLetter(ActiveToken));
        $.ajax({
        url: `{{route('update-token',':tokenMode')}}`.replace(":tokenMode",ActiveToken), // Example URL
        method: "GET",
        success: function(data) {
            if(data.message=="showElemet"){
              $("#token_show").removeClass("d-none");
                $("#token").val("");
            }else{
                showSawl(`Token SuccessFully Change To ${toUpperFirstLetter(ActiveToken)} API`,"success",false,"ok");
                show_token();
            }
        },
        error: function(xhr, status, error) {
          alert("error");
        }
      });
    });
  });
    
function show_token(){
    $.ajax({
        url: "{{route('get-setting-token-active')}}", // Example URL
        method: "GET",
        success: function(data) {
            var modeActive = data.ActiveToken.mode;
            var token = data.ActiveToken.token;
            $("#mode_text").text(toUpperFirstLetter(data.ActiveToken.mode));
            $("#"+modeActive).prop("checked", true);
            $("#token_show").removeClass("d-none");
            $("#token").val(token);
        },
        error: function(xhr, status, error) {

          $("#token_show").removeClass("d-none");
          console.error("Error:", status, error);
        }
      });
}

$(document).ready(function(){
    show_token();
});


</script>