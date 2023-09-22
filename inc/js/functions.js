$(document).ready(function() {
	$("#game_name").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: "cron_games.php?open=readDB",
				type: "GET",
				data: request,
				success: function (data) {
					response($.map(data, function (element) {
						return {
							value: element.id,
							label: element.name,
							img: element.img
						};
					}));
				}
			});
		},
		select: function (event, ui) {
			this.value = ui.item.label;
			$("#game_id").val(ui.item.value);
			event.preventDefault();	
		},
		html: true, 
        open: function(event, ui) {
        	$(".ui-autocomplete").css("z-index", 1000);
        }
		})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		//this.value = item.label;
		//$("#game_id").val(item.value);
		return $( "<li><div><img src='"+item.img+"'><span>"+item.label+"</span></div></li>" ).appendTo( ul );
	};
});