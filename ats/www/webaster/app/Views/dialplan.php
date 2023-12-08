<style>
	#dialplan-table thead th,
	#dialplan-table tbody td  {
		font-size:.75rem;
		padding:.2rem;
	}

	.link-context {
		cursor:pointer;
	}

	.link-context:hover {
		text-decoration:underline;
	}
</style>

<h4>Диалплан</h4>
<div id="dialplan-toolbar-container"></div>
<hr>
<div class="row">
	<div class="col-3">
		<table id="dialplan-contexts-table" class="table table-sm table-borderless">
			<thead>
				<tr>
					<th>Контекст</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div class="col-9">
		<table id="dialplan-extensions-table" class="table table-sm table-hover _table-borderless">
			<thead>
				<tr>
					<th>extension</th>
					<th>priority</th>
					<th>application</th>
					<th class="w-100">appdata</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<script>
	function dialplanToolbarDialplanReloadButtonOnClick(event) {
		dialplanToolbarDialplanReloadButton.setEnabled(false);

		$.get('/dialplan/dialplan_reload/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			if (Object.hasOwn(response, 'message'))
				new XuToast(toastContainer, { classes: ['text-bg-success'], text: response.message });

			//
			dialplanReload();
		}, 'json').always(() => {
			dialplanToolbarDialplanReloadButton.setEnabled(true);
		});
	}

	// Table functions
	var dialplanData; // yeah, i know, global var is a wrong way

	function dialplanRebuildContextsTable() {
		// HTML
		let html = '';
		for (var _context in dialplanData.contexts) {
			html += '<tr>';
			html += '<td class="pt-0 pb-1"><button class="btn btn-sm btn-outline-success w-100 p-0" data-context="' + _context + '">' + _context + '</button></td>';
			html += '</tr>';
		}

		$('#dialplan-contexts-table tbody').html(html);

		// Events
		$('#dialplan-contexts-table button').on('click', (event) => {
			$('#dialplan-contexts-table button').removeClass('active');

			let el = $(event.target);
			el.addClass('active');

			let context = el.data('context');
			dialplanRebuildExtensionsTable(context);
		});
	}

	function dialplanRebuildExtensionsTable(context) {
		// HTML
		let html = '';
		for (var _context in dialplanData.contexts) {
			if (_context != context)
				continue;

			for (var _extension in dialplanData.contexts[_context]) {
				if (1 == parseInt(dialplanData.contexts[_context][_extension].priority))
					html += '<tr style="border-top:2px dashed #198754e0;">';
				else
					html += '<tr>';

				html += '<td>' + dialplanData.contexts[_context][_extension].extension + '</td>';
				html += '<td>' + dialplanData.contexts[_context][_extension].priority + '</td>';

				// application
				if ('noop' == dialplanData.contexts[_context][_extension].application.toLowerCase())
					html += '<td class="text-secondary">';
				else
					html += '<td>';

				html += dialplanData.contexts[_context][_extension].application;
				html += '</td>';

				// appdata
				if ('noop' == dialplanData.contexts[_context][_extension].application.toLowerCase())
					html += '<td class="text-secondary">';
				else
					html += '<td>';


				let appdata = dialplanData.contexts[_context][_extension].appdata;
				if (
					'goto' == dialplanData.contexts[_context][_extension].application.toLowerCase() ||
					'gosub' == dialplanData.contexts[_context][_extension].application.toLowerCase()
				) {
					let comma_pos = appdata.indexOf(',');
					if (comma_pos >= 0) {
						html += '<span class="link-context text-primary fw-semibold" data-context="' + appdata.substring(0, comma_pos) + '">';
						html += appdata.substring(0, comma_pos);
						html += '</span>';
						html += appdata.substring(comma_pos);
					} else
						html += appdata;
				} else
					html += appdata;

				html += '</td>';

				html += '</tr>';
			}
		}

		$('#dialplan-extensions-table tbody').html(html);

		// Events
		$('#dialplan-extensions-table .link-context').on('click', (event) => {
			let context = $(event.target).data('context');

			$('#dialplan-contexts-table button[data-context="' + context + '"]')
				.trigger('click');
		});
	}

	function dialplanReload() {
		$.get('/dialplan/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			dialplanData = response.data;
			dialplanRebuildContextsTable();
		}, 'json');
	}

	// Toolbar
	const dialplanToolbar = new XuToolbar(null);
	const dialplanToolbarButtonGroup1 = new XuButtonGroup(dialplanToolbar);

	const dialplanToolbarDialplanReloadButton = new XuButton(dialplanToolbarButtonGroup1, {
		classes: ['btn-primary', 'btn-sm'],
		content: 'dialplan reload'
	}).on('click', dialplanToolbarDialplanReloadButtonOnClick);

	// Table
	// Xu isn't used for it yet

	// DOMContentLoaded
	window.addEventListener('DOMContentLoaded', (event) => {
		document.getElementById('dialplan-toolbar-container').appendChild(dialplanToolbar.element);
	});
</script>
