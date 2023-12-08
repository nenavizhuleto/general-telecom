<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebAster 2.0</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <style>
    	body {
    		padding-top:3.5rem;
    	}

    	#toast-container {
    		margin-top:21px;
	    	z-index:10000;
    	}

    	span.nav-link, span.dropdown-item {
    		cursor:pointer;
    	}

    	#topbar {
	    	height:3rem;
    	}

    	#topbar span.dropdown-item:hover {
    		background-color:#0d6efd;
    		color:#fff;
    	}

    	#topbar span.dropdown-item.danger {
    		color:#dc3545;
    	}

    	#topbar span.dropdown-item.danger:hover {
    		background-color:#dc3545;
    		color:#fff;
    	}
    </style>
		<?php echo view('xu'); ?>
  </head>
  <body>
	  <div id="toast-container" class="fixed-top"></div>
		<nav id="topbar" class="navbar fixed-top navbar-expand navbar-dark bg-dark shadow">
		  <div class="container-fluid">
		    <div class="navbar-brand" href="#">WebAster 2.0</div>
		    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto">
		        <li class="nav-item dropdown">
		          <span class="nav-link _active dropdown-toggle" data-bs-toggle="dropdown">Система</span>
		          <ul class="dropdown-menu">
		            <li><span id="topbar-info" class="dropdown-item">Информация</span></li>
		            <li><span class="dropdown-item danger">Перезагрузить Asterisk</span></li>
		          </ul>
		        </li>
		        <li class="nav-item">
		          <span id="topbar-peers" class="nav-link">Пиры</span>
		        </li>
		        <li id="topbar-queues" class="nav-item">
		          <span class="nav-link">Очереди</span>
		        </li>
		        <li id="topbar-dialplan" class="nav-item">
		          <span class="nav-link">Диалплан</span>
		        </li>
		        <li id="topbar-registrations" class="nav-item">
		          <span class="nav-link">Регистрации</span>
		        </li>
		      </ul>
		      <div class="navbar-text me-2 link"><?php echo $user->login; ?></div>
		      <button id="topbar-logout" class="btn btn-primary">Выход</button>
		    </div>
		  </div>
		</nav>
		<main class="d-flex flex-nowrap">
			<div id="content" class="container-fluid">
				<div id="page-info" class="d-none"><?php echo view('info'); ?></div>
				<div id="page-peers" class="d-none"><?php echo view('peers'); ?></div>
				<div id="page-queues" class="d-none"><?php echo view('queues'); ?></div>
				<div id="page-dialplan" class="d-none"><?php echo view('dialplan'); ?></div>
				<div id="page-registrations" class="d-none"><?php echo view('registrations'); ?></div>
			</div>
		</main>

    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>

    <script>
    	function topBarLogout() {
	    	$.post('/users/logout/', {
	    	}, (data) => {
	    		window.location.reload();
	    	},'json');
    	}

    	function topBarSetItem(item) {
    		$('#page-info').removeClass('d-block').addClass('d-none');
    		$('#page-peers').removeClass('d-block').addClass('d-none');
    		$('#page-queues').removeClass('d-block').addClass('d-none');
    		$('#page-dialplan').removeClass('d-block').addClass('d-none');
    		$('#page-registrations').removeClass('d-block').addClass('d-none');

    		$('#page-' + item).removeClass('d-none').addClass('d-block');

    		switch (item) {
    			case 'info':
						infoReload();
						break;

    			case 'peers':
    				peersReload();
    				break;

    			case 'queues':
    				queuesReload();
    				break;

    			case 'dialplan':
    				dialplanReload();
    				break;

    			case 'registrations':
    				registrationsReload();
    				break;
    		}
    	}

    	var toastContainer;
			window.addEventListener('DOMContentLoaded', (event) => {
				// Toast
				toastContainer = new XuToastContainer(null, { classes: ['top-0', 'start-50', 'translate-middle-x'] });
				document.getElementById('toast-container').appendChild(toastContainer.element);

	    	// Topbar
	    	$('#topbar-info').on('click', () => {
	    		topBarSetItem('info');
	  		});

	    	$('#topbar-peers').on('click', () => {
	    		topBarSetItem('peers');
	  		});
	    	$('#topbar-queues').on('click', () => {
	    		topBarSetItem('queues');
	  		});

	    	$('#topbar-dialplan').on('click', () => {
	    		topBarSetItem('dialplan');
	  		});

	    	$('#topbar-registrations').on('click', function() {
	    		topBarSetItem('registrations');
	  		});

	    	$('#topbar-logout').on('click', function() {
	    		topBarLogout();
	  		});

    		topBarSetItem('dialplan');
	   	});
    </script>
  </body>
</html>
