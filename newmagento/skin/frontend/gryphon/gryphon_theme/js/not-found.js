jQuery(document).ready(function($){    
	$("#not-found-search").on("keyup paste", function() {
	  console.log($(this).val())
	  $(".search-select input").val($(this).val());
	});

	$(".not-found .search-button").on("click", function(e){
		e.preventDefault();
		$(".search-select .search-button").trigger('click');
	})
});