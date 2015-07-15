(function($){
    $(function() {
        if ($("#shipping\\:country_id").size()) {
            if ($("#shipping\\:country_id").is(':visible')) {
                if ($("#shipping\\:country_id").val()) {
                    var countryId = $("#shipping\\:country_id").val();
                    getShippingFee('new', countryId);
                }
            }
            $("#shipping\\:country_id").on('change', function () {
                if ($(this).val()) {
                    var countryId = $(this).val();
                    getShippingFee('new', countryId);
                }
            })
        }

        if ($('#shipping-address-select').size()) {
                var addressId = $('#shipping-address-select').val();
                getShippingFee('existed', addressId);

            $("#shipping-address-select").on('change', function () {
                if ($(this).val()) {
                    var addressId = $(this).val();
                    getShippingFee('existed', addressId);
                } else {
                    $("#shipping\\:country_id").trigger('change');
                }
            })
        }
    });
    function getShippingFee(type, id) {
        showOverlay();
        var data = null;
        if ('new' == type) {
            data = {country_id: id};
        } else {
            data = {address_id: id}
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: data,
            url: '/magento/shippingcustomiser/index/ajaxGetShipping'
        }).done(function(result) {
            hideOverlay();
            if (result.success) {
                $('#billing_shipping').html(result.content);
                $('#md_shipping_method').val($('input[name=shipping_method]').val());
            } else {
                alert(result);
            }
        }).error(function() {
            hideOverlay();
            alert('an error occured, please try again later');
        });
    }
    function showOverlay() {
        $('.delivery-rates').addClass('loading');
    }
    function hideOverlay() {
        $('.delivery-rates').removeClass('loading');
    }
})(jQuery);