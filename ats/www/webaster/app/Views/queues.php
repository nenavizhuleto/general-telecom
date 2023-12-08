<style>
	#queues-table thead th,
	#queues-table tbody td  {
		font-size:.75rem;
		padding:.2rem;
	}

	#queues-members-table thead th,
	#queues-members-table tbody td  {
		font-size:.75rem;
		padding:.2rem;
	}
</style>

<h4>Очереди</h4>
<div id="queues-toolbar-container"></div>
<hr>
<table id="queues-table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>queue</th>
			<th>max</th>
			<th>strategy</th>
			<th>calls</th>
			<th>holdtime</th>
			<th>talktime</th>
			<th>completed</th>
			<th>abandoned</th>
			<th>servicelevel</th>
			<th>servicelevelperf</th>
			<th>servicelevelperf2</th>
			<th>weight</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table id="queues-members-table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>queue</th>
			<th>name</th>
			<th>location</th>
			<th>stateinterface</th>
			<th>membership</th>
			<th>penalty</th>
			<th>callstaken</th>
			<th>lastcall</th>
			<th>lastpause</th>
			<th>incall</th>
			<th>status</th>
			<th>paused</th>
			<th>pausedreason</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>
	function queuesToolbarQueueReloadAllButtonOnClick(event) {
		queuesToolbarQueueReloadAllButton.setEnabled(false);

		$.get('/queues/queue_reload_all/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			if (Object.hasOwn(response, 'message'))
				new XuToast(toastContainer, { classes: ['text-bg-success'], text: response.message });

			//
			queuesReload();
		}, 'json').always(() => {
			queuesToolbarQueueReloadAllButton.setEnabled(true);
		});
	}

	// Table functions
	function queuesReload() {
		$.get('/queues/', {}, (response) => {
			if (!response.result) {
				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, { classes: ['text-bg-danger'], text: response.message, timeout: 8000 });

				return;
			}

			let html = '';
			let html_members = '';

			for (var _queue in response.data.queues) {
				html += '<tr>';
				html += '<td>' + response.data.queues[_queue].queue + '</td>';
				html += '<td>' + response.data.queues[_queue].max + '</td>';
				html += '<td>' + response.data.queues[_queue].strategy + '</td>';
				html += '<td>' + response.data.queues[_queue].calls + '</td>';
				html += '<td>' + response.data.queues[_queue].holdtime + '</td>';
				html += '<td>' + response.data.queues[_queue].talktime + '</td>';
				html += '<td>' + response.data.queues[_queue].completed + '</td>';
				html += '<td>' + response.data.queues[_queue].abandoned + '</td>';
				html += '<td>' + response.data.queues[_queue].servicelevel + '</td>';
				html += '<td>' + response.data.queues[_queue].servicelevelperf + '</td>';
				html += '<td>' + response.data.queues[_queue].servicelevelperf2 + '</td>';
				html += '<td>' + response.data.queues[_queue].weight + '</td>';
				html += '</tr>';

				for (var _member in response.data.queues[_queue].members) {
					/*{
						"result":true,
						"data":{
							"queues":{
								"first-queue":{
									"event":"QueueParams",
									"queue":"first-queue",
									"max":"0",
									"strategy":"ringall",
									"calls":"0",
									"holdtime":"0",
									"talktime":"0",
									"completed":"0",
									"abandoned":"0",
									"servicelevel":"0",
									"servicelevelperf":"0.0",
									"servicelevelperf2":"0.0",
									"weight":"0",
									"actionid":"1669071464.6309",
									"members":[
										{
											"event":"QueueMember",
											"queue":"first-queue",
											"name":"David",
											"location":"SIP\/3",
											"stateinterface":"SIP\/3",
											"membership":"static",
											"penalty":"0",
											"callstaken":"0",
											"lastcall":"0",
											"lastpause":"0",
											"incall":"0",
											"status":"4",
											"paused":"0",
											"pausedreason":"",
											"actionid":"1669071464.6309"
										}, ...
									]
								}
							}
						}
					}*/

					html_members += '<tr>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].queue + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].name + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].location + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].stateinterface + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].membership + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].penalty + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].callstaken + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].lastcall + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].lastpause + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].incall + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].status + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].paused + '</td>';
					html_members += '<td>' + response.data.queues[_queue].members[_member].pausedreason + '</td>';
					html_members += '</tr>';
				}
			}

			$('#queues-table tbody').html(html);
			$('#queues-members-table tbody').html(html_members);
		}, 'json');
	}

	// Toolbar
	const queuesToolbar = new XuToolbar(null);
	const queuesToolbarButtonGroup1 = new XuButtonGroup(queuesToolbar);

	const queuesToolbarQueueReloadAllButton = new XuButton(queuesToolbarButtonGroup1, {
		classes: ['btn-primary', 'btn-sm'],
		content: 'queue reload all'
	}).on('click', queuesToolbarQueueReloadAllButtonOnClick);

	// Table
	// Xu isn't used for it yet

	// DOMContentLoaded
	window.addEventListener('DOMContentLoaded', (event) => {
		document.getElementById('queues-toolbar-container').appendChild(queuesToolbar.element);
	});
</script>
