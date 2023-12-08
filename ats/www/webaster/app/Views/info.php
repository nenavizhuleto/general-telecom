<style>
	#info-table tbody th {
		white-space:nowrap;
	}

	#info-table tbody td {
		width:99%;
	}
</style>
<h4>Информация</h4>
<hr>
<table id="info-table" class="table table-sm table-hover">
	<tbody>
	</tbody>
</table>

<script>
	function infoReload() {
		$.get('/info/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			let html = '';
			html += '<tr><th>Сервер:</th><td>' + response.data.hostname + '</td></tr>';
			html += '<tr><th>Uptime:</th><td>' + response.data.uptime + '</td></tr>';
			html += '<tr><th>ОС:</th><td>' + response.data.os + '</td></tr>';
			html += '<tr><th>IP:</th><td>' + response.data.ips + '</td></tr>';
			html += '<tr><th>Маршруты:</th><td>' + response.data.routes + '</td></tr>';

			html += '<tr>';
			html += '<th>Статус Asterisk:</th>';
			switch (response.data.asterisk_status) {
				case 'active (running)':
					html += '<td class="text-success">' + response.data.asterisk_status + '</td>';
					break;

				case 'inactive (dead)':
					html += '<td class="text-danger">' + response.data.asterisk_status + '</td>';
					break;

				default:
					html += '<td>' + response.data.asterisk_status + '</td>';
					break;
			}
			html += '</tr>';

			html += '<tr><th>Uptime Asterisk:</th><td>' + response.data.asterisk_uptime + '</td></tr>';
			html += '<tr><th>Версия Asterisk:</th><td>' + response.data.asterisk_version + '</td></tr>';
			html += '<tr><th>Версия AMI:</th><td>' + response.data.asterisk_ami_version + '</td></tr>';
			$('#info-table tbody').html(html);
		}, 'json');
	}
</script>
