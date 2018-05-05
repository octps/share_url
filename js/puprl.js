
$(document).ready(function(){
	window.ajaxOpg();
});

window.ajaxOpg = function() {
	var opgs = $(".opg");
	for (var i = 0; i < opgs.length; i++) {
		var sourceUrl = $(opgs).eq(i).attr("opghtml");
		$.ajax(
			"/getOpg.php",
			{
				type:"POST",
				data: {source:sourceUrl, number:i}
			}
		)
		.done(function(string){
			var val = JSON.parse(string);
			$(".opg").eq(val.number).attr("src", val.image);
		})
	};
}
