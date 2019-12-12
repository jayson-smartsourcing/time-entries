$( document ).ready(function() {
    console.log( "ready!" );
   
    var pathname = window.location.pathname;

    if(pathname == "/import-parse" || pathname == "/import-time-entries" ){
        $( "#login_header" ).hide();
    }


    $(".submit-button").click(function(){
        var api_key = $('#api-key').val();
        var file = $("#csv-file").val();

    
        if((api_key != null && api_key != undefined && api_key != "") && (file != null && file != undefined && file != "") ){
            $(".spin-button").removeClass("hidden");
            $(".submit-button").addClass("hidden");
        }

    });

    $(".spin-button").click(function(e){
        e.preventDefault();
        return false;
    })


   
    
});

