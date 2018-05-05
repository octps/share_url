
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

window.form_submit_check = function() {
	if(window.confirm('削除してよろしいですか？')){
		return true;
	}
	else{
		return false; // 送信を中止
	}
};