$.fn.Lmodal = function(args) {
	var footer, modal, settings, text;
	var defaults = {
		modal: null,
		style: 'light',
		title: 'Aguarde',
		body: 'Carregando...',
		footer: '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'
	}
	settings = $.extend(defaults, args);
	if(settings.modal != null){
		modal = $(settings.modal);
		text  = (settings.style == 'warning' || settings.style == 'secondary' || settings.style == 'light') ? 'text-dark' : 'text-white';
		modal.find('.modal-header').attr('class','modal-header '+ text +' bg-'+ settings.style);
		modal.find('.modal-title').html(settings.title);
		modal.find('.modal-body').html(settings.body);

		footer = '';
		if($.isPlainObject(settings.footer))
			$.each(settings.footer, function(index, button) {
				var attr = '';
				$.each(button, function(key, value) {
					attr += key+'="'+value+'" ';
				});
				footer += '<a '+attr+'>'+index+'</a>';
			});
		else
			footer = settings.footer;
		modal.find('.modal-footer').html(footer);
		modal.modal('show');
	}
}

$.fn.Ltable = function(args) {
	var current_page, num_per_page, num_rows, num_pages, pager, patt, settings, table, td, tr;
	var defaults = {
		input_search: null,
		input_num_rows: null,
		style_buttons: 'primary',
		num_per_page: 5,
		current_page: 1
	}
	settings 	 = $.extend(defaults, args);
	table    	 = $(this);
	current_page = settings.current_page-1;
	num_per_page = (settings.input_num_rows != null) ? $(settings.input_num_rows).val() : settings.num_per_page;
	
	table.bind('repaginate', function() {
		table.find('tbody tr').hide().slice(current_page * num_per_page, (current_page + 1) * num_per_page).show();
		num_rows  = table.find('tbody tr').length;
		num_pages = Math.ceil(num_rows / num_per_page);
		pager = $('<div class="btn-group table-pagination mb-3" role="group"></div>');
		for (var page = 0; page < num_pages; page++) {
			$('<button type="button" class="btn btn-'+settings.style_buttons+' btn-sm"></button>').text(page + 1).bind('click', {new_page: page}, 
			function(event) {
				current_page = event.data['new_page'];
				table.find('tbody tr').hide().slice(current_page * num_per_page, (current_page + 1) * num_per_page).show();
				$(this).addClass('active').siblings().removeClass('active');
			}).appendTo(pager);
		}
		if($('.table-pagination').length)
			$('.table-pagination').remove();
		pager.insertAfter(table).children().eq(current_page).addClass('active');
	});
	
	if(settings.input_search != null) {
		$(settings.input_search).on('keyup', function() {
			patt  = new RegExp($(this).val(), "i");
			tr    = table.find('tr');
			$('.table-pagination').hide();
			tr.each(function(key) {
				td = $(this).find('td').not('.not-search');
				if (key != 0 && td.text().search(patt) < 0)
					$(this).hide();
				if (td.text().search(patt) > 0)
					$(this).show();
				if( td.text().search(patt) == 0){
					table.trigger('repaginate');
					$('.table-pagination').show();
				}
			});
		});
	}

	if(settings.input_num_rows != null) {
		$(settings.input_num_rows).on('change', function() {
			if(settings.input_search != null)
				$(settings.input_search).val('');
			num_per_page = $(settings.input_num_rows).val();
			current_page = settings.current_page-1;
			table.trigger('repaginate');
		});
	}

	table.trigger('repaginate');
}
