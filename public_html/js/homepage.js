$(document).ready(function() {
    updatePrices();
    setInterval(function(){ updatePrices(); }, 120000);
});

function updatePrices() {
    

    // send logout request
    $.post('/php/price-get.php', {}, function(response) {
        var response = jQuery.parseJSON(response);

        if (response.success) {
            // rebuild price history table
            var code = '';
            var rowCode = '<tr><th scope="row"><!--price_id--></th><td><!--time--></td><td class="<!--text_color-->"><!--usd_value--> <i class="fa <!--fa_icon-->" aria-hidden="true"></i></td></tr>';
            for (i in response.prices) {
                i = parseInt(i);

                temp = rowCode
                    .replace('<!--price_id-->', response.prices[i].price_id)
                    .replace('<!--time-->', response.prices[i].time)
                    .replace('<!--usd_value-->', response.prices[i].usd_value);

                if (response.prices[i + 1] === undefined) {
                    temp = temp
                        .replace('<!--text_color-->', '')
                        .replace('<!--fa_icon-->', 'fa-minus-circle');
                } else if (response.prices[i].usd_value > response.prices[i + 1].usd_value) {
                    temp = temp
                    .replace('<!--text_color-->', 'text-success')
                    .replace('<!--fa_icon-->', 'fa-chevron-circle-up');
                } else if (response.prices[i].usd_value < response.prices[i + 1].usd_value) {
                    temp = temp
                    .replace('<!--text_color-->', 'text-danger')
                    .replace('<!--fa_icon-->', 'fa-chevron-circle-down');
                } else {
                    temp = temp
                        .replace('<!--text_color-->', '')
                        .replace('<!--fa_icon-->', 'fa-minus-circle');
                }

                code += temp;
            }
            $('#price-history').html(code);

            if (response.last_price != false) {
                $.notify({
                    message: 'BTC is now trading for $' + response.last_price + '.'
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