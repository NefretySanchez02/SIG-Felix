$(document).ready(function() {
	
	//Auto complete off
	$("input.autocomplete-off").attr("autocomplete", "off");
	
	//Refresh captcha image
	$(".change-captcha").click(function(){
		var rnd = new Date().getTime();
		var src = $("img.captcha-img").attr("src");
		
		if (src.indexOf("?")!=-1) {
			var ind = src.indexOf("?");
			src = src.substr(0, ind);
		}
		
		src += "?"+rnd;
		$("img.captcha-img").attr("src", src);
		$("#verify").val("");
	});	
	
	//Closing divs - used on Notification boxes
	notificationReady = function(cls) {
		//Hide button event
		if (!$(".canhide").find("close").length) {
			$(".canhide").append('<a class="close" href="#">Close</a>');
			
			$(".notification .close").click(function(e) {
				e.preventDefault();
				$(this).parent().fadeOut(300);
			});
		}
		
		//Notification type
		$(".notification").addClass(cls);
	}
	
	//Submit form
	$("#frm_contact").submit(function(){
		var action = $(this).attr("action");
		$("#submit").attr("disabled", "disabled");
		
		//Add preloader
		if (!$(".form-submit").find("img.preloader").length) {
			$("#submit").after('<img src="images/preloader.gif" class="preloader" />');
		}
		
		//Post form
		$(".notification").fadeOut(300, function() {
			$.post(action, {
				name: 		$("#name").val(),
				email: 		$("#email").val(),
				phone: 		$("#phone").val(),
				subject: 	$("#subject").val(),
				message: 	$("#message").val(),
				verify: 	$("#verify").val()
			},
				function(data) {
					//Show notification
					$(".notification .inner").html(data);
					$(".notification").fadeIn(300);
					
					//Remove preloader
					$(".form-submit img.preloader").fadeOut("fast", function() {
						$(this).remove();
					});
					
					//Enable submit button
					$("#submit").removeAttr("disabled");
					
					//Hide form if success
					if(data.match("success")!=null) {
						$("#frm_contact").fadeOut("slow");
					}
				}
			);
		});
		
		return false;
	});
	
});