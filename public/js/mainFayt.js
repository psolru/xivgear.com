$(document).ready(function(){
	document.addEventListener('click', function() {
		$('#fayt_result').removeClass('d-block');return false;
	})

	document.getElementById('fayt_input').addEventListener('click', function(e) {
		let target = $('#fayt_result')
		if (target.val().length != 0) {
			e.stopPropagation();
			target.toggleClass('d-block');
		}
		return false;
	})
})