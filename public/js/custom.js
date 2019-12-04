$( document ).ready(function() {
    console.log( "ready!" );
   
    var pathname = window.location.pathname;

    if(pathname == "/import-parse" || pathname == "/import-time-entries" ){
        $( "#login_header" ).hide();
    }

   
    
});

