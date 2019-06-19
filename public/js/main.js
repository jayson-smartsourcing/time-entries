$(function() {
    var url = $(location).attr('href'),
    parts = url.split("/"),
    last_part_url = parts[parts.length-1];

    $.ajax({
        type: 'GET',
        url: '/api/check-rating/'+last_part_url,
        beforeSend:function(){
            $(".loading").removeClass("hidden");
        },  
        success:function(data) {
            if(data.message == "done rating") {
                window.location.replace("/poll/success");
            } else {
                $(".loading").addClass("hidden");
            }
        }
    });

    $(".submit").on("click", function() {
        $(".submit").addClass("disabled");
        $(".submit").attr("disabled", true);
        var rate = $("input[name='rate']:checked").val();
        var reason = $("#reason").val();
        var email = "jayson@startsmartsourcing.com";
        $(".alert-primary").removeClass("showMe");
        
        var data = {
                        "rate" : rate, 
                        "reason" : reason,
                        "email" : email,
                        "id": last_part_url
                   };

        $.ajax({
            type:'POST',
            url:'/api/insert-rating',
            data: data,
            success:function(data) {
                if(data.success) {
                    window.location.replace("/poll/success");
                }
            },
            error:function(data) {
                $(".submit").removeClass("disabled");
                $(".submit").attr("disabled", false);
                $(".alert-primary").removeClass("hidden");
                var response = data.responseJSON;
                var error_string = "";
    
                $.each(response.errors, function(key, value) {
                    error_string +="<div class='errors'>"+value[0]+"</div>";
                });
               $(".error-class").empty();
               $(".error-class").append(error_string);

               setTimeout(function(){ $(".alert-primary").addClass("hidden"); }, 10000);


            }
        });
    });
});