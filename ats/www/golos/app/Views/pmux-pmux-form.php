<div id="pmux-device-form" class="modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h5 class="modal-title"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form>
					<div class="row mb-2">
						<div class="col-3">
							<label class="col-form-label">Тип объекта</label>
						</div>
						<div class="col-9">
							<label class="col-form-label">Подъезд</label>
						</div>
					</div>
					<div class="pmux-device-form-porch_device_type-row row mb-2">
						<div class="col-3">
							<label class="col-form-label">Устройство</label>
						</div>
						<div class="col-9">
							<label class="col-form-label">PMUX</label>
						</div>
					</div>
					<div class="pmux-device-form-street_id-row row mb-2">
						<div class="col-3">
							<label class="col-form-label">Улица</label>
						</div>
						<div class="col-9">
							<select id="pmux-device-form-street_id" class="form-select"></select>
						</div>
					</div>
					<div class="row">
						<div class="col-3 mb-2 pmux-device-form-building_id-row">
							<label class="col-form-label">Дом</label>
						</div>
						<div class="col-3 mb-2 pmux-device-form-building_id-row">
							<select id="pmux-device-form-building_id" class="form-select"></select>
						</div>
						<div class="col-3 mb-2 pmux-device-form-porch_id-row">
							<label class="col-form-label">Подъезд</label>
						</div>
						<div class="col-3 mb-2 pmux-device-form-porch_id-row">
							<select id="pmux-device-form-porch_id" class="form-select"></select>
						</div>
					</div>
					<div class="row">
						<div class="col-3 mb-2 pmux-device-form-sipusername-row">
							<label class="col-form-label">SIP-логин</label>
						</div>
						<div class="col-3 mb-2 pmux-device-form-sipusername-row">
							<label id="pmux-device-form-sipusername" class="col-form-label"></label>
						</div>
						<div class="col-3 mb-2 pmux-device-form-sippassword-row">
							<label class="col-form-label">SIP-пароль</label>
						</div>
						<div class="col-3 mb-2 pmux-device-form-sippassword-row">
							<input id="pmux-device-form-sippassword" type="text" class="form-control" maxlength="8">
						</div>
					</div>

					<div class="pmux-device-form-comment-row row mb-2">
						<div class="col-3">
							<label class="col-form-label">Комментарий</label>
						</div>
						<div class="col-9">
							<textarea id="pmux-device-form-comment" class="form-control" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer border-0">
				<button id="pmux-device-form-save" type="button" class="btn btn-success"></button>
				<button id="pmux-device-form-cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal"></button>
			</div>
		</div>
	</div>
</div>

<script>
	function pmuxDeviceFormGetStreets(street_id = 0, user_inited = true, callback = null) {
		let el = $('#pmux-device-form-street_id')
			.prop('disabled', true)
			.html('<option value="0">- выберите улицу -</option>')
			.val(0);

		if (user_inited)
			el.trigger('input');

		return $.get('/streets/', {}, (data) => {
			let html = '';
			for (let i in data.rows) {
				html += '<option value="' + data.rows[i].id + '">' + data.rows[i].title + '</option>';
			}

			let el = $('#pmux-device-form-street_id');
			el.append(html);

			if (street_id) {
				el.val(street_id);

				if (user_inited)
					el.trigger('input');
			}

			el.prop('disabled', false);

			if (callback instanceof Function)
				callback();
		}, 'json');
	}

	function pmuxDeviceFormGetBuildings(street_id = 0, building_id = 0, user_inited = true, callback = null) {
		let el = $('#pmux-device-form-building_id')
			.prop('disabled', true)
			.html('<option value="0">- выберите дом -</option>')
			.val(0);

		if (!street_id) {
			if (user_inited)
				el.trigger('input');

			return null;
		}

		return $.get('/buildings/', {
			street_id: street_id
		}, (data) => {
			let html = '';
			for (let i in data.rows) {
				html += '<option value="' + data.rows[i].id + '">' + data.rows[i].num + '</option>';
			}

			let el = $('#pmux-device-form-building_id');
			el.append(html);

			if (building_id) {
				el.val(building_id);

				if (user_inited)
					el.trigger('input');
			}

			el.prop('disabled', false);

			if (callback instanceof Function)
				callback();
		}, 'json');
	}

	function pmuxDeviceFormGetPorches(building_id = 0, porch_id = 0, user_inited = true, callback = null) {
		let el = $('#pmux-device-form-porch_id')
			.prop('disabled', true)
			.html('<option value="0">- выберите подъезд -</option>')
			.val(0);

		if (!building_id) {
			if (user_inited)
				el.trigger('input');

			return null;
		}

		return $.get('/porches/', {
			building_id: building_id
		}, (data) => {
			let html = '';
			for (let i in data.rows) {
				html += '<option value="' + data.rows[i].id + '">' + data.rows[i].num + '</option>';
			}

			let el = $('#pmux-device-form-porch_id');
			el.append(html);

			if (porch_id) {
				el.val(porch_id)

				if (user_inited)
					el.trigger('input');
			}

			el.prop('disabled', false);

			if (callback instanceof Function)
				callback();
		}, 'json');
	}

	function pmuxDeviceFormReset(callback = null) {
		pmuxDeviceForm._config.id = null;

		// object_type
		$('.pmux-device-form-street_id-row').hide();
		$('.pmux-device-form-building_id-row').hide();
		$('.pmux-device-form-porch_id-row').hide();


		$('.pmux-device-form-street_id').val(0);
		$('.pmux-device-form-building_id').val(0);
		$('.pmux-device-form-porch_id').val(0);

		$('.pmux-device-form-porch_device_type-row').show();
		$('.pmux-device-form-street_id-row').show();
		$('.pmux-device-form-building_id-row').show();
		$('.pmux-device-form-porch_id-row').show();



		// common fields
		$('.pmux-device-form-sipusername-row').hide();
		$('#pmux-device-form-sipusername').html('');
		$('.pmux-device-form-sippassword-row').hide();
		$('#pmux-device-form-sippassword').val('');
		$('.pmux-device-form-comment-row').hide();
		$('#pmux-device-form-comment').val('');

		// validate
		pmuxDeviceFormValidate();

		// callback
		if (callback instanceof Function)
			callback();
	}

	function pmuxDeviceFormLoad(id, callback = null) {
		pmuxDeviceForm._config.id = null;

		$.get('/devices/pmux', {
			id: id
		}, (data) => {
			if (!data.result)
				return;

			if (!data.rows.length)
				return;

			//
			let row = data.rows[0];
			pmuxDeviceForm._config.id = row.id;

			// common fields
			$('#pmux-device-form-sipusername').html(row.sipusername);
			$('.pmux-device-form-sipusername-row').show();
			$('#pmux-device-form-sippassword').val(row.sippassword);
			$('.pmux-device-form-sippassword-row').show();
			$('#pmux-device-form-comment').val(row.comment);
			$('.pmux-device-form-comment-row').show();

			// porch device
			if (null != row.porch_id) {

				$.when(
					pmuxDeviceFormGetStreets(row.porch_street_id, false),
					pmuxDeviceFormGetBuildings(row.porch_street_id, row.porch_building_id, false),
					pmuxDeviceFormGetPorches(row.porch_building_id, row.porch_id, false)
				).done(() => {
					pmuxDeviceFormValidate();

					if (callback instanceof Function) {
						callback();
					}
				});

				return;
			}

		}, 'json');
	}

	function pmuxDeviceFormCreate() {
		$('#pmux-device-form .modal-title').html('Новое устройство PMUX');
		$('#pmux-device-form-save')
			.removeClass('btn-primary')
			.addClass('btn-success')
			.html('Создать');
		$('#pmux-device-form-cancel').html('Отмена');

		pmuxDeviceFormReset(() => {
			pmuxDeviceForm.show();
		});
	}

	function pmuxDeviceFormEdit(id) {
		$('#pmux-device-form .modal-title').html('Устройство #' + id);
		$('#pmux-device-form-save')
			.removeClass('btn-success')
			.addClass('btn-primary')
			.html('Сохранить');
		$('#pmux-device-form-cancel').html('Закрыть');

		pmuxDeviceFormLoad(id, () => {
			pmuxDeviceForm.show();
		});
	}

	function pmuxDeviceFormValidate() {
		let device_type;
		let porch_id;
		let ok = true;

		porch_id = parseInt($('#pmux-device-form-porch_id').val());
		if (!porch_id)
			ok = false;

		if (null != pmuxDeviceForm._config.id) {
			// sippassword
			let sippassword = $('#pmux-device-form-sippassword').val();
			if (!sippassword.length)
				ok = false;
		}

		$('#pmux-device-form-save').prop('disabled', !ok);
	}

	var pmuxDeviceForm = null;

	// DOMContentLoaded
	window.addEventListener('DOMContentLoaded', (event) => {
		pmuxDeviceForm = new bootstrap.Modal('#pmux-device-form', {
			id: null
		});

		let user_inited = true;

		if (user_inited) {
			pmuxDeviceFormGetStreets();
		}

		if (user_inited)
			pmuxDeviceFormValidate();

		$('#pmux-device-form-street_id').on('input', function(event, user_inited = true) {
			if (user_inited) {
				let street_id = parseInt($(this).val());
				pmuxDeviceFormGetBuildings(street_id, 0);
			}
		});

		$('#pmux-device-form-building_id').on('input', function(event, user_inited = true) {
			if (user_inited) {
				let building_id = parseInt($(this).val());
				pmuxDeviceFormGetPorches(building_id, 0);
			}
		});

		$('#pmux-device-form-porch_id').on('input', function(event, user_inited = true) {
			pmuxDeviceFormValidate();

		});


		$('#pmux-device-form-sippassword').on('input', function() {
			pmuxDeviceFormValidate();
		});

		$('#pmux-device-form-save').on('click', function() {
			let data = {};

			// id
			let id = pmuxDeviceForm._config.id;
			if (null != id)
				data.id = id;

			// type, (block_id / porch_id / room_id)
			data.type = 4 //parseInt($('#pmux-device-form-porch_device_type').val());
			data.porch_id = parseInt($('#pmux-device-form-porch_id').val());
			data.building_id = parseInt($('#pmux-device-form-building_id').val());


			if (null != id) {
				// sipassword
				data.sippassword = $('#pmux-device-form-sippassword').val();

				// comment
				data.comment = $('#pmux-device-form-comment').val();
			}

			// disabling inputs while saving
			// el.prop('disabled', true);

			$('#pmux-device-form-porch_device_type').prop('disabled', true);
			$('#pmux-device-form-street_id').prop('disabled', true);
			$('#pmux-device-form-building_id').prop('disabled', true);
			$('#pmux-device-form-porch_id').prop('disabled', true);

			if (data.id) {
				$('#pmux-device-form-sippassword').prop('disabled', true);
				$('#pmux-device-form-comment').prop('disabled', true);
			}

			$('#pmux-device-form-save').prop('disabled', true);

			// saving
			// TODO: rename first closure's param of .post(.get) to "response" everywhere!
			// TODO: because of ambiguity with second param
			$.post('/devices/pmux/', data, (response) => {
				if (!response.result) {
					if (Object.hasOwn(response, 'message'))
						new XuToast(toastContainer, {
							classes: ['text-bg-danger'],
							text: response.message,
							timeout: 8000
						});

					return;
				}

				if (Object.hasOwn(response, 'message'))
					new XuToast(toastContainer, {
						classes: ['text-bg-success'],
						text: response.message
					});

				pmuxDeviceForm.hide();
				pmuxTable.reloadData();

				// TODO: should we reopen new created entity everywhere?
				if (Object.hasOwn(response, 'id')) {
					// was created
					pmuxDeviceFormEdit(response.id);
				}
			}, 'json').always(() => {
				// restoring inputs after save
				// el.prop('disabled', false);

				$('#pmux-device-form-porch_device_type').prop('disabled', false);
				$('#pmux-device-form-street_id').prop('disabled', false);
				$('#pmux-device-form-building_id').prop('disabled', false);
				$('#pmux-device-form-porch_id').prop('disabled', false);

				if (data.id) {
					$('#pmux-device-form-sippassword').prop('disabled', false);
					$('#pmux-device-form-comment').prop('disabled', false);
				}

				$('#pmux-device-form-save').prop('disabled', false);
			});
		});
	});
</script>