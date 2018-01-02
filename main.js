var bigOffset = 0;

function setScrollPosition1(){
	var offsetObj = $('.chat_window div:last').offset();
	if(bigOffset <= offsetObj.top) {
		bigOffset = offsetObj.top
	}	
	console.log(offsetObj);
	$('.chat_window').scrollTop(bigOffset);
}

function clearMessages()
{
	$.ajax({
		url: site_url + '/'+ installed_folder +'/',
		type: "POST",
		timeout: 5000,
		data: {
				clearMessage: '1',
			},
		success: function(data) {
			
		}	
	});
}

function getChatContent()
{
	$.ajax({
		url: site_url + '/'+ installed_folder +'/chat.php',
		type: "POST",
		timeout: 5000,
		success: function(data) {
			$('#chat_messages').html(data);
			setScrollPosition1();
		},
		error: function(x, t, m) {
			if(t === "timeout") {
				alert('Connection timeout please reload the page and try again');
			} else {
				//alert(t);
			}
		}
	});
}
$(document).ready(function () { 
	getChatContent();
	$( "#message_box" ).focus();
	setInterval( getChatContent , 4000);
});

$(document).ajaxComplete(function () {
  //setScrollPosition();
});

