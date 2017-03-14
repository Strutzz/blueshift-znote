$(document).ready(function() {
	var showChar = 70;
	var ellipsestext = "...";
	var moretext = "Show more";
	var lesstext = "Show less";
	$('.more').each(function() {
		var content = $(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar-1, content.length - showChar);

			var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="newstickerContent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="newstickerLink label label-info" style="float: right;">'+moretext+'</a></span>';

			$(this).html(html);
		}

	});

	$(".newstickerLink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});
});