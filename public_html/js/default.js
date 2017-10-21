$('#logoutButton').click(function(e) {
    e.preventDefault();
    $.post('../php/login-destroy.php', function(response) {
        var response = jQuery.parseJSON(response);

        if (response.success) {
            $.notify({
                title: '<b>Success</b>',
            	message: 'You are now logged out!'
            },{
            	type: 'success',
                newest_on_top: true,
                placement: {
            		from: "top",
            		align: "center"
            	}
            });
            setTimeout(function() {
                window.location.reload(true);
            }, 1500);
        }
    });
});