<style>
	#registrations-table thead th,
	#registrations-table tbody td  {
		font-size:.75rem;
		padding:.2rem;
	}
</style>

<h4>Регистрации</h4>
<hr>
<table id="registrations-table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Название</th>
			<th>IP</th>
			<th>Порт</th>
			<th>domain</th>
			<th>domainport</th>
			<th>Состояние</th>
			<th>Время</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>
	function registrationsReload() {
		$.get('/registrations/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			let html = '';

			for (var _registration in response.data.registrations) {
				/*{
					"result":true,
					"data":{
						"registrations":[
							{
								"event":"RegistryEntry",
								"actionid":"1669059363.4114",
								"host":"192.168.13.208",
								"port":"6565",
								"username":"frompbx",
								"domain":"192.168.13.208",
								"domainport":"5060",
								"refresh":"120",
								"state":"No Authentication",
								"registrationtime":"0"
							}
						]
					}
				}*/

				html += '<tr>';
				html += '<td>' + response.data.registrations[_registration].username + '</td>';
				html += '<td>' + response.data.registrations[_registration].host + '</td>';
				html += '<td>' + response.data.registrations[_registration].port + '</td>';
				html += '<td>' + response.data.registrations[_registration].domain + '</td>';
				html += '<td>' + response.data.registrations[_registration].domainport + '</td>';
				html += '<td>' + response.data.registrations[_registration].state + '</td>';
				html += '<td>' + response.data.registrations[_registration].registrationtime + '</td>';
				html += '</tr>';
			}

			$('#registrations-table tbody').html(html);
		}, 'json');
	}
</script>
