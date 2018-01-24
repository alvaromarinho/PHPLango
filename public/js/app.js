$(document).ready(function () {
	$('.delete').click(function() {
		$(this).Lmodal({
			modal: '#modal',
			style: 'danger',
			title: $(this).data('title'),
			body: $(this).data('body'),
			footer: $(this).data('footer')
		});
	});

	$('.table').Ltable({
		input_search: '.search-table',
		input_num_rows: '.num-rows'
	});
});