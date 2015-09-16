(function($){
    $(function() {
        // $('input.ajaxsearch').click(function() {
        //     ajaxSearch.submit();
        // });
        // $('.clear-all').click(function() {
        //     ajaxSearch.reset();
        // });
        $('.all-products').on('click', '.load-more-products', function() {
            if ($('#cur_p').size()) {
                var curPage = $('#cur_p').val();
                ajaxSearch.more(parseInt(curPage) + 1);
            }
        });
        $('.all-products').on('click', '.load-more-mobile-products', function() {
            if ($('#cur_p').size()) {
                var curPage = $('#cur_p').val();
                ajaxSearchMobile.more(parseInt(curPage) + 1);
            }
        });
    });
})(jQuery);

Ajaxsearch = function (formId) {
    this.formId = formId;
    this.inputClass = "ajaxsearch";
    this.categoryAttribute = "categoryId";
};
Ajaxsearch.prototype.request = function(params, update) {
    var ajax = new Ajax.Request(
        AJAXSEARCH_URL,
        {
            method: 'post',
            parameters: params,
            onLoading: function() {
                $("loading-mask").show();
            },
            onSuccess: function(transport) {
                //once loading is done
                if (parseInt(transport.responseJSON.update)) {
                    $("products_list").innerHTML += transport.responseJSON.result_text;
                    $("cur_p").value = parseInt($("cur_p").value) + 1;
                    // if last page
                    if (parseInt($("cur_p").value) == parseInt($("last_p").value)) {
                        jQuery('.load-more-wrapper').hide();
                    }
                } else {
                    $("result_products").innerHTML = transport.responseJSON.result_text;
                }
                $("loading-mask").hide();
            },
            onLoaded: function() {
                $("loading-mask").hide();
            }
        }
    );
};
Ajaxsearch.prototype.makeParams = function() {
    var params = $(this.formId).serialize();
    return params;
};
Ajaxsearch.prototype.submit = function(isMouseEvent) {
    var params = this.makeParams();
    this.request(params);
};
Ajaxsearch.prototype.more = function(page) {
    var params = this.makeParams();
    if (params) {
        params += '&p=' + page;
    } else {
        params = 'p=' + page;
    }
    this.request(params);
};
Ajaxsearch.prototype.reset = function() {
    $(this.formId).reset();
    this.submit();
};


//////////////////////

Ajaxsearchmobile = function (formId) {
    this.formId = formId;
    this.inputClass = "ajaxsearch";
    this.categoryAttribute = "categoryId";
};
Ajaxsearchmobile.prototype.request = function(params, update) {
    var ajax = new Ajax.Request(
        AJAXSEARCH_URL,
        {
            method: 'post',
            parameters: params,
            onLoading: function() {
                $("loading-mask").show();
            },
            onSuccess: function(transport) {
                //once loading is done
                if (parseInt(transport.responseJSON.update)) {
                    $("products_list").innerHTML += transport.responseJSON.result_text;
                    $("cur_p").value = parseInt($("cur_p").value) + 1;
                    // if last page
                    if (parseInt($("cur_p").value) == parseInt($("last_p").value)) {
                        jQuery('.load-more-wrapper').hide();
                    }
                } else {
                    $("result_products").innerHTML = transport.responseJSON.result_text;
                }
                $("loading-mask").hide();
            },
            onLoaded: function() {
                $("loading-mask").hide();
            }
        }
    );
};
Ajaxsearchmobile.prototype.makeParams = function() {
    var params = $(this.formId).serialize();
    return params;
};
Ajaxsearchmobile.prototype.submit = function(isMouseEvent) {
    var params = this.makeParams();
    this.request(params);
};
Ajaxsearchmobile.prototype.more = function(page) {
    var params = this.makeParams();
    if (params) {
        params += '&p=' + page;
    } else {
        params = 'p=' + page;
    }
    this.request(params);
};
Ajaxsearchmobile.prototype.reset = function() {
    $(this.formId).reset();
    this.submit();
};