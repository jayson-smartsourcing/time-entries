$( document ).ready(function() {
    console.log( "ready!" );
   
    var pathname = window.location.pathname;

    if(pathname == "/import-parse" || pathname == "/import-time-entries" ){
        $( "#login_header" ).hide();
    }


    $(".submit-button").click(function(){

        if(  ($("#api-key").value != null || $("#api-key").value != undefined) || ( $("#csv-file").value != null || $("#csv-file").value != undefined)){
            $(".spin-button").removeClass("hidden");
            $(".submit-button").addClass("hidden");
        }

    
    });

    $(".spin-button").click(function(e){
        e.preventDefault();
        return false;
    })


   
    
});

