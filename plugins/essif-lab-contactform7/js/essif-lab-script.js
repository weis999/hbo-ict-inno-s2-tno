jQuery(document).ready(function( $ ) {

    $( "#essif-lab" ).click(function(e) {
        e.preventDefault();
        sendMockCall();
    });

    function sendMockCall() {
        $.ajax({
            type: 'GET',
            url: 'http://localhost:8000/api/credentialverifyrequest/1',
            success: function (data) {
                if (data != null) {
                    console.log(data);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus + errorThrown);
            }
        });
    }

});
