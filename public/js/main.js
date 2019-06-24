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


    $.ajax({
        type: 'GET',
        url: '/api/check/token/a',
        headers: {"Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE1NWZiODc0ZTBkYmMzZmJkODM2YjdlNTRhMmViMmJmODJkNjdhMTlmOWY3MmQyNTQwNTdhMWY4OWM0YjM0YTBmMmE4ZTY5MTAwMzYxZmMxIn0.eyJhdWQiOiIzIiwianRpIjoiYTU1ZmI4NzRlMGRiYzNmYmQ4MzZiN2U1NGEyZWIyYmY4MmQ2N2ExOWY5ZjcyZDI1NDA1N2ExZjg5YzRiMzRhMGYyYThlNjkxMDAzNjFmYzEiLCJpYXQiOjE1NjEwOTg2ODksIm5iZiI6MTU2MTA5ODY4OSwiZXhwIjoxNTkyNzIxMDg5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.iofW90dtU8ttDjSEqxlFClXWsQFSMhDXN-2e8xwatbLl-L69c0Iryva0XNNRsCnNDzkDu45tVpAsxhIhhJX9--t4zkrR6mwjEkusNpvxGeU1mmovYUiWD-t69iQUpeqjNNQkXxIOrPJS7mb9oCpFLypmVNJnxcDb-Yj03mbEVLP1rN5fE1taFD0KCrvVl3Suvn0O-TwX3loVmIBFOF7gHm8ZvXSoyB0poNd5I44updRBTamdQO_lWxbv3Bxug6-v1dF_6u-5AQD2dLQHMPRqHfC3uBPyhRNAAy5-8Z267q9h68s9TVH_fA38SAxiKAVnpxIdgMDIVIHLF19x6nsG5WNkwx0TA2D6GQzOk20GGYX1dlAs2M-GXXUjHV2FMeKZAHBCONsKk0K8hfwUnS72PVG_-aKRJfENwzBKju261HB6C4N42Tw6xyUcF-NLS6llX9ukeZkG8Zkk-MD8G7pnzZKPvgmoDa54bzkcdVY6nXes7_zUy3gg7SkkXyPn1qapnWvCKozCNYU3LcluAj_rVJMqEdZpUjkbHQJOF_9Owv-V_uTMD_scZyymJoJ8AHya5DTzVB-AWT5tENa5hW3SOkNu4Zk-3mjAgxhTwrzEBJ3_1LjLQZIqkHnZMeh6mpL6EvTYjQKOU4CBXFaQM1GjaQYjClrqQ4BsaZqWuq6DIKc",
            "accept":"application/json"
        },
        success:function(data) {
           console.log(data);
        },
        error:function(data){
            console.log(data);
        }
    });

});