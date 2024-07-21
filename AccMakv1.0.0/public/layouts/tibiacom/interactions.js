const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
	
function getPlayers() {
    $.ajax({
        type: "POST", url: "/ajax/players", data: JSON.stringify({player_action: "do"}), contentType: 'application/json',
        success: function(response) {
            $("#players").html(response.players);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching players data:", error);
        }
    });
}

function getVisitors() {
	$.ajax(
	{
		type: "POST", url: "/ajax/visitors", data: JSON.stringify({visitor_action: "do"}), contentType: 'application/json',
		success: function(response) {
			$("#visitors").html(response.visitors);
		},
		error: function(xhr, status, error) {
			console.error("Error fetching visitors data:", error);
		}
	});
}

$(document).ready(function() {
    setInterval(getPlayers, 5000);
    setInterval(getVisitors, 10000);
});