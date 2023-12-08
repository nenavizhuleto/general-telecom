<style>
	#peers-table thead th,
	#peers-table tbody td  {
		font-size:.75rem;
		padding:.2rem;
	}

	#peers-table tbody tr td:last-child {
		padding:.1rem 0;
	}

	.btn-peer-prune {
		margin:0 .1rem;
		padding:0 .25rem .1rem .25rem;
		font-size:.75rem;
	}
</style>

<h4 class="me-auto">Пиры</h4>
<div id="peers-toolbar-container"></div>
<hr>
<table id="peers-table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>objectname</th>
			<th>ipaddress</th>
			<th>ipport</th>
			<th>dynamic</th>
			<th>forcerportм
			<th>comedia</th>
			<th>videosupport</th>
			<th>realtimedevice</th>
			<th>status</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>
	// Toolbar functions
	function peersToolbarPruneAllButtonOnClick(event) {
		peersToolbarPruneAllButton.setEnabled(false);
		peersToolbarSipReloadButton.setEnabled(false);

		$.get('/peers/sip_prune_realtime_all/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			if (Object.hasOwn(response, 'message'))
				new XuToast(toastContainer, { classes: ['text-bg-success'], text: response.message });

			//
			peersReload();
		}, 'json').always(() => {
			peersToolbarPruneAllButton.setEnabled(true);
			peersToolbarSipReloadButton.setEnabled(true);
		});
	}

	function peersToolbarSipReloadButtonOnClick(event) {
		peersToolbarPruneAllButton.setEnabled(false);
		peersToolbarSipReloadButton.setEnabled(false);

		$.get('/peers/sip_reload/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			if (Object.hasOwn(response, 'message'))
				new XuToast(toastContainer, { classes: ['text-bg-success'], text: response.message });

			//
			peersReload();
		}, 'json').always(() => {
			peersToolbarPruneAllButton.setEnabled(true);
			peersToolbarSipReloadButton.setEnabled(true);
		});
	}

	// Table functions
	function peersReload() {
		$.get('/peers/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			let html = '';

			for (var _peer in response.data.peers) {
				html += '<tr data-key="' + response.data.peers[_peer].objectname + '">';
				html += '<td>' + response.data.peers[_peer].objectname + '</td>';
				html += '<td>' + response.data.peers[_peer].ipaddress + '</td>';
				html += '<td>' + response.data.peers[_peer].ipport + '</td>';
				html += '<td>' + response.data.peers[_peer].dynamic + '</td>';
				html += '<td>' + response.data.peers[_peer].forcerport + '</td>';
				html += '<td>' + response.data.peers[_peer].comedia + '</td>';
				html += '<td>' + response.data.peers[_peer].videosupport + '</td>';
				html += '<td>' + response.data.peers[_peer].realtimedevice + '</td>';

				if ('OK (' == response.data.peers[_peer].status.substr(0, 4))
					html += '<td class="text-success">' + response.data.peers[_peer].status + '</td>';
				else if ('UNREACHABLE' == response.data.peers[_peer].status)
					html += '<td class="text-danger">' + response.data.peers[_peer].status + '</td>';
				else
					html += '<td>' + response.data.peers[_peer].status + '</td>';

				html += '<td>';
				if ('yes' == response.data.peers[_peer].realtimedevice)
					html += '<button class="btn btn-sm btn-primary btn-peer-prune">prune</button>';
				html += '</td>';

				html += '</tr>';
			}

			$('#peers-table tbody').html(html);

			// Events
			$('.btn-peer-prune').on('click', (event) => {
				let key = $(event.target).parent().parent().data('key');

				$(event.target).prop('disabled', true);
				$.get('/peers/sip_prune_realtime_peer/', {
					peername: key
				}, (response) => {
					if (!response.result) {
						if (Object.hasOwn(response, 'message'))
							new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

						return;
					}

					if (Object.hasOwn(response, 'message'))
						new XuToast(toastContainer, { classes: ['text-bg-success'], text: response.message });

					//
					peersReload();
				}, 'json').always(() => {
					$(event.target).prop('disabled', false);
				});
			});
		}, 'json');
	}

	// Toolbar
	const peersToolbar = new XuToolbar(null);
	const peersToolbarButtonGroup1 = new XuButtonGroup(peersToolbar);

	const peersToolbarPruneAllButton = new XuButton(peersToolbarButtonGroup1, {
		classes: ['btn-primary', 'btn-sm'],
		content: 'sip prune realtime all'
	}).on('click', peersToolbarPruneAllButtonOnClick);

	const peersToolbarSipReloadButton = new XuButton(peersToolbarButtonGroup1, {
		classes: ['btn-danger', 'btn-sm'],
		content: 'sip reload'
	}).on('click', peersToolbarSipReloadButtonOnClick);

	// Table
	// Xu isn't used for it yet

	// DOMContentLoaded
	window.addEventListener('DOMContentLoaded', (event) => {
		document.getElementById('peers-toolbar-container').appendChild(peersToolbar.element);
	});
</script>
