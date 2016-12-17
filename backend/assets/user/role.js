$(function() {

	//events
	$(document).on('click', '.role-form .assign', assignClick);
	$(document).on('keypress', '.role-form [name="email"]', assignKeypress);
	$(document).on('keyup', '.role-form [name="email"]', assignKeyup);
	$(document).on('click', '.role-form .revoke', revokeClick);

	function assignClick(e) {
		e.preventDefault();
		assign($(this));
	};

	function assignKeypress(e) {
		if (e.which == 13) {
			e.preventDefault();
			assign($(this).closest('form').find('.assign'));
		};
	};

	function assignKeyup() {
		$(this).closest('.role-form').find('.assign').attr('disabled', $(this).val() == '');
	}

	function assign($a) {
		var $form = $a.closest('form'), $input = $form.find('[name="email"]');
		if ($input.val() == '') return false;
		$.get($a.attr('href'), {
			'email': $input.val()
		}, function(data) {
			if (data.error) alert(data.error);
			else {
				var $tbody = $form.find('#role-users tbody'), $tr = $(data.content).find('#role-users tbody tr');
				//remove empty tr
				$tbody.find('tr > td > div.empty').parent().parent().remove();
				var $dest = $tbody.find('tr[data-id="'+$tr.data('id')+'"]');
				if ($dest.length == 0) $dest = $tr.appendTo($tbody);
				$dest.effect('highlight', {}, 1000);
				//clear input
				$input.val('').trigger('keyup');
			};
		}, 'json');
	};

	function revokeClick(e) {
		e.preventDefault();
		$(this).closest('tr').remove();
	};

});
