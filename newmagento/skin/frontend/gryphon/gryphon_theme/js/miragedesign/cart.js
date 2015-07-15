(function($){
    $(function() {
        $('.product-qty .add').click(function() {
            var qty = $(this).parent('.product-qty').find('span.qty');
            var hiddenQty = $(this).parent('.product-qty').find('input.qty');

            if (qty.size()) {
                var value = parseInt(qty.html()) + 1;
                qty.html(value);
                hiddenQty.val(value);
            }
        });
        $('.product-qty .minus').click(function() {
            var qty = $(this).parent('.product-qty').find('span.qty');
            var hiddenQty = $(this).parent('.product-qty').find('input.qty');

            if (qty.size()) {
                var value = parseInt(qty.html());
                if (value > 1) {
                    qty.html(value - 1);
                    hiddenQty.val(value - 1);
                }
            }
        });
    });
})(jQuery);