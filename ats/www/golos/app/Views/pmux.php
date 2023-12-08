<?php echo view('pmux-pmux-form'); ?>
<h4>PMUX</h4>
<hr>
<div id="pmux-toolbar-container"></div>
<div id="pmux-pager-container"></div>
<div id="pmux-table-container"></div>

<style>
	#pmux-table-container thead th {
		vertical-align: top;
	}
</style>

<script>
	// TODO: sip prune peer
	// TODO: dialplan reload

	// Toolbar functions
	function pmuxToolbarCreateButtonOnClick() {
		// new XuToast(toastContainer, { classes: ['text-bg-primary'], text: 'Создание устройств временно недоступно.', timeout: 8000 });
		pmuxDeviceFormCreate();
	}

	function pmuxToolbarEditButtonOnClick() {
		let keys = pmuxTable.getCheckedKeys();
		if (keys.length < 1)
			return;

		pmuxDeviceFormEdit(keys[0]);
	}

	function pmuxToolbarDeleteButtonOnClick() {
		let keys = pmuxTable.getCheckedKeys();
		if (keys.length < 1)
			return;

		if (!confirm('Будет удалено устройств: ' + keys.length + '. Вы уверены?'))
			return;

		pmuxTable.dataPage = 1;
		$.post('/devices/delete/', {
			ids: keys
		}, (data) => {
			if (Object.hasOwn(data, 'message'))
				if (data.result)
					new XuToast(toastContainer, {
						classes: ['text-bg-success'],
						text: data.message
					});
				else
					new XuToast(toastContainer, {
						classes: ['text-bg-danger'],
						text: data.message,
						timeout: 6000
					});

			pmuxTable.reloadData();
		}, 'json');
	}

	function pmuxToolbarUpdate() {
		let checkedCount = pmuxTable.getCheckedKeys().length;
		pmuxToolbarEditButton.setEnabled(1 == checkedCount);
		pmuxToolbarDeleteButton.setEnabled(checkedCount);
	}

	// Pager function
	function pmuxPagerOnLimitChanged(limit) {
		pmuxTable.dataLimit = limit;
		pmuxTable.dataPage = 1;
		pmuxTable.reloadData();
	}

	function pmuxPagerOnPageChanged(page) {
		pmuxTable.dataPage = page;
		pmuxTable.reloadData();
	}

	// Table functions
	function pmuxTableOnDataLoaded(data) {
		pmuxToolbarUpdate();
		pmuxPager.setTotalPages(data.pageCount);
		pmuxPager.setPage(data.page);
	}

	function pmuxTableOnCheckedKeysChanged() {
		pmuxToolbarUpdate();
	}

	function pmuxTableOnRowClick(key) {
		pmuxDeviceFormEdit(key);
	}

	// Toolbar
	const pmuxToolbar = new XuToolbar(null);
	const pmuxToolbarButtonGroup1 = new XuButtonGroup(pmuxToolbar);

	const pmuxToolbarCreateButton = new XuButton(pmuxToolbarButtonGroup1, {
		classes: ['btn-success', 'btn-sm'],
		content: 'Создать',
	}).on('click', pmuxToolbarCreateButtonOnClick);

	const pmuxToolbarEditButton = new XuButton(pmuxToolbarButtonGroup1, {
		classes: ['btn-primary', 'btn-sm'],
		content: 'Изменить',
		enabled: false
	}).on('click', pmuxToolbarEditButtonOnClick);

	const pmuxToolbarDeleteButton = new XuButton(pmuxToolbarButtonGroup1, {
		classes: ['btn-danger', 'btn-sm'],
		content: 'Удалить',
		enabled: false
	}).on('click', pmuxToolbarDeleteButtonOnClick);

	// Pager
	const pmuxPager = new XuPager(null, {
		limits: [10, 25, 50, 100],
		limit: 10,
		neighbours: 2,
		onLimitChanged: (limit) => pmuxPagerOnLimitChanged(limit),
		onPageChanged: (page) => pmuxPagerOnPageChanged(page)
	});

	// Table
	const pmuxTable = new XuDataTable(null, {
		classes: ['table-sm', 'table-hover'],
		checkboxes: true,
		columns: [{
			name: 'phone',
			title: 'Полный номер',
			classes: ['text-nowrap'],
			cellClasses: ['text-noerap']
		}, {
			name: 'type_name',
			title: 'Тип',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap']
		}, {
			name: 'num',
			title: '№',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap', 'text-end']
		}, {
			name: 'objtype',
			title: 'Тип объекта',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap']
		}, {
			name: 'obj',
			title: 'Объект',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap']
		}, {
			name: 'sipusername',
			title: 'Пользователь SIP',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap']
		}, {
			name: 'sippassword',
			title: 'Пароль SIP',
			classes: ['text-nowrap'],
			cellClasses: ['text-nowrap']
		}, {
			classes: ['w-100'],
			name: 'comment',
			title: 'Комментарий'
		}],
		dataSource: '/devices/pmux',
		dataMethod: 'GET',
		dataKey: 'id',
		dataLimit: 10,

		onDataLoaded: (data) => pmuxTableOnDataLoaded(data),
		onCheckedKeysChanged: () => pmuxTableOnCheckedKeysChanged(),
		onRowClick: (key) => pmuxTableOnRowClick(key)
	});

	// DOMContentLoaded
	window.addEventListener('DOMContentLoaded', (event) => {
		document.getElementById('pmux-toolbar-container').appendChild(pmuxToolbar.element);
		document.getElementById('pmux-pager-container').appendChild(pmuxPager.element);
		document.getElementById('pmux-table-container').appendChild(pmuxTable.element);

		// altering pmuxTable
		let pmuxTableFilterComment = document.createElement('input');
		pmuxTableFilterComment.setAttribute('id', 'pmux-table-filter-comment');
		pmuxTableFilterComment.setAttribute('type', 'text');
		pmuxTableFilterComment.classList.add('form-control');
		pmuxTableFilterComment.addEventListener('input', (event) => {
			if (0 == event.target.value.length)
				pmuxTable.filters.comment = null;
			else
				pmuxTable.filters.comment = event.target.value;

			pmuxTable.reloadData();
		});

		pmuxTable.childs[0].childs[0].childs[8].element.appendChild(pmuxTableFilterComment);
	});
</script>