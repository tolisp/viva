jQuery(document).ready( function() {

    jQuery("#viva_submit").click( function(e) {
        e.preventDefault();

        nonce = jQuery("#nonce").val();
        name = jQuery("#name").val();
        email = jQuery("#email").val();
        stuff = {name:name, email:email};

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "viva_token"},
            success: function(response) {
                var result = response.responseText ;
                console.log(response.access_token);
                stuff.token = response.access_token;
                viva_order(stuff, nonce);
            },
            error: function(response) {
                var result = response.responseText;
                console.log(response);
            }
        })
    })
})


function viva_order(data, wp_nonce){


    console.log(data);
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {action: "viva_curl", stuff : data, nonce: wp_nonce},
        success: function(response) {
            jQuery("#result").html("<div class='success'>Success! You are being redirected to the payment provider <br/><i>https://demo.vivapayments.com/web/checkout?ref=" +response.orderCode+ "</i></div>");
            console.log(response.orderCode);

            location.href = "https://demo.vivapayments.com/web/checkout?ref=" + response.orderCode;

        },
        error: function(response) {
            var result = response;
            jQuery("#result").html("<div class='error'>"+ result.answer + "</div>");

            console.log("error");
        }

    })
}
