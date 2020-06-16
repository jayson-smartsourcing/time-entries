$( document ).ready(function() {
    console.log( "ready!" );
    // $("#modal-delete-btn").attr("disabled", true);
   
    var pathname = window.location.pathname;

    if(pathname == "/import-parse" || pathname == "/import-time-entries" ||  pathname == "/ticket-dashboard"){
        $( "#login_header" ).hide();
    }

    //Activtrak CSV Import - change title
    if( pathname == "/api/import/logs/csv"  ||  pathname == "/activtrak-csv-import"){
        $( "#login_header" ).hide();
        document.title = "Activtrak Working Hours Import";
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
                                    $('#api-key-refresh').val('');
                                    //remove loading
                                    $('.tx-spin-button').addClass("hidden");
                                    //success message
                                    $('#tx-success-msg').removeClass("hidden");
                                    $('#tx-error-msg').addClass("hidden");
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
                        $('#tx-success-msg').addClass("hidden");
                        //remove loading
                        $('.tx-spin-button').addClass("hidden");
                        //return to original button view
                        $('.tx-submit-button').removeClass("hidden");
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
    });


    $(".dropdown-menu a").click(function(){
        var valdiv =  $(this).attr("value");
        $(".action-form").hide();
        $("#"+valdiv).show();
      });

    var default_dropdown = $("#default_dropdown").val();
    
    if(default_dropdown == 'time_entries'){
        $("#te-import").trigger("click");
    }

    $("#api-key").change(function(e) {     
        $('#api-key').removeClass("input-error");
        $(".alert").addClass("hidden");
    });

    $("#api-key-refresh").change(function() {
        $(".alert").addClass("hidden");
        $('#api-key-refresh').removeClass("input-error");
    });

 
    $('.close').on('click', function() {
        $(".alert").addClass("hidden");
     });


     //activtrak csv import
    $(".submit-button-at").click(function(){
        
        var file = $("#file").val();

        $(".spin-button-at").removeClass("hidden");
            $(".submit-button-at").addClass("hidden");

        // if(file != null && file != undefined && file != ""){
        //     $(".spin-button-at").removeClass("hidden");
        //     $(".submit-button-at").addClass("hidden");
        // }
    });


    //delete log modal
    $('#confirm-delete-cb').click(function(event){
        
        var isChecked = $("#confirm-delete-cb").is(":checked");

        if (isChecked) {
            $(".modal-delete-btn").attr("disabled", false);
        } else {
            $(".modal-delete-btn").attr("disabled", true);
        }

    });

    //show selected log info
    $('#delete-modal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var user = button.data('user');
        var currdate = button.data('currdate');
        var key = button.data('key');
        var del_btn = document.getElementById('mod-del-btn');

        var modal = $(this);
        modal.find('#user_modal').val(user);
        modal.find('#currdate_modal').val(currdate);
        // modal.find('#modal-delte-btn').id("id", "btn-" + key);
        // del_btn.id = 'mod-del-btn-'+ key;

    })

    
});

