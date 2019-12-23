$( document ).ready(function() {
    console.log( "ready!" );
   
    var pathname = window.location.pathname;

    if(pathname == "/import-parse" || pathname == "/import-time-entries" ||  pathname == "/ticket-dashboard"  ){
        $( "#login_header" ).hide();
    }

    //time entries import
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
    });


    //ticket refresh link
    $(".tx-submit-button").click(function(e){
        e.preventDefault();
        var api_key = $('#api-key-refresh').val();

        if(api_key != null && api_key != undefined && api_key != "" && api_key != " " ){

            $(".tx-spin-button").removeClass("hidden");
            $(".tx-submit-button").addClass("hidden");

            $.ajax({

                url : '/api/refresh-tickets/'+api_key,
                type : 'GET',
                dataType:'json',
                success : function(data) {              
                    if(data.success) {

                        var url = data.link;
                        $.ajax({

                            url : url,
                            type : 'GET',
                            dataType:'json',
                            success : function(data) {              
                                if(data.success) {
                                    $('#api-key-refresh').val(" ");

                                    //remove loading
                                    $('.tx-spin-button').addClass("hidden");
                                    
                                    //success message
                                    $('#tx-success-msg').removeClass("hidden");

                                    //return to original button view
                                    $('.tx-submit-button').removeClass("hidden");

                                    $('.error').addClass("hidden");
                                    $('#api-key-refresh').removeClass('input-error');
                                }
                            }
                        });
                        
                    }else{
                        var error = data.message;

                        $('#tx-error-msg').removeClass("hidden");

                        //remove loading
                        $('.tx-spin-button').addClass("hidden");

                        //return to original button view
                        $('.tx-submit-button').removeClass("hidden");

                        // $('.error').text(error).removeClass("hidden");
                        $('#api-key-refresh').addClass("input-error");
                    }
                }
            });

        } else{
            $('.error').text('Field is required').removeClass("hidden");
            $('#api-key-refresh').addClass("tx-input-error");
        }

    });

    $(".tx-spin-button").click(function(e){
        e.preventDefault();
    });

    $("#api-key-refresh").keypress(function(e) {
        $('.error').text('').addClass("hidden");
        $('#api-key-refresh').removeClass("input-error");
        $('#api-key-refresh').removeClass("tx-input-error");
    })


    $(".dropdown-menu a").click(function(){
        var valdiv =  $(this).attr("value");
        $(".action-form").hide();
        $("#"+valdiv).show();
      });

    var default_dropdown = $("#default_dropdown").val();
    
    if(default_dropdown == 'time_entries'){
        $("#te-import").trigger("click");
    }

    $("#api-key").keypress(function(e) {     
        $('#api-key').removeClass("input-error");
    })

    




   
    
});

