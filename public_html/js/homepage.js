$(document).ready(function() {
    updatePrices();
    setInterval(function(){ updatePrices(); }, 50000);
});

function updatePrices() {
    

    // send logout request
    $.post('/php/price-get.php', {}, function(response) {
        var response = jQuery.parseJSON(response);

        if (response.success) {
            // rebuild price history table
            var code = '';
            var rowCode = '<tr><th scope="row"><!--price_id--></th><td><!--time--></td><td><!--usd_value--></td></tr>';
            for (i in response.prices) {
                code += rowCode
                    .replace('<!--price_id-->', response.prices[i].price_id)
                    .replace('<!--time-->', response.prices[i].time)
                    .replace('<!--usd_value-->', response.prices[i].usd_value);
            }
            $('#price-history').html(code);

            if (response.last_price != false) {
                $.notify({
                    title: '<b>New Value</b>',
                    message: 'BTC is now trading for ' + response.last_price + '.'
                },{
                    type: 'info',
                    newest_on_top: true,
                    placement: {
                        from: "top",
                        align: "center"
                    }
                });
            }
        } else {
            $.notify({
                title: '<b>Failed to get price history</b>',
                message: 'There was a problem retrieving the price history, please refresh the page.'
            },{
                type: 'danger',
                newest_on_top: true,
                placement: {
                    from: "top",
                    align: "center"
                }
            });
        }
    });
}