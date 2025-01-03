<?php
$companyInfo = $this->db->query("select * from tbl_company c order by c.Company_SlNo desc limit 1")->row();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title><?php echo $companyInfo->Company_Name; ?> || <?php echo $title; ?></title>

	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />

	<!-- page specific plugin styles -->

	<!-- text fonts -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fonts.googleapis.com.css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/fancyBox/css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<!-- ace styles -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-skins.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" />

	<!-- ace settings handler -->
	<script src="<?php echo base_url(); ?>assets/js/ace-extra.min.js"></script>
	<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>uploads/favicon.png">
</head>

<body class="skin-2">
	<?php
	$proggress = $this->session->userdata('progressBar');
	if ($proggress) {
	?>
		<div id="preloader">
			<div id="progress-bar">
				<div id="progress"></div>
			</div>
			<p id="loading-text">Loading... <span id="percent">0%</span></p>
		</div>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				let progressBar = document.getElementById('progress');
				let percentText = document.getElementById('percent');
				let preloader = document.getElementById('preloader');

				let loadingProgress = 0;
				let loadingInterval = setInterval(function() {
					loadingProgress += 40;
					progressBar.style.width = loadingProgress + "%";
					percentText.innerText = loadingProgress + "%";

					if (loadingProgress >= 100) {
						clearInterval(loadingInterval);
						preloader.style.display = 'none';
					}
				}, 200);
			});
		</script>
	<?php }
	$this->session->unset_userdata('progressBar');
	?>
	<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top" style="background:#3e2e6b !important;">
		<div class="navbar-container ace-save-state" id="navbar-container">
			<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
				<span class="sr-only">Toggle sidebar</span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>
			</button>

			<div class="navbar-header pull-left">
				<a href="<?php echo base_url(); ?>" class="navbar-brand">
					<small>
						<i class="fa fa-shopping-cart"></i><?php echo $companyInfo->Company_Name; ?> <span style="color:#000;font-weight:700;letter-spacing:1px;font-size:16px;"> </span>
					</small>
				</a>
			</div>

			<div class="navbar-buttons navbar-header pull-right" role="navigation">
				<ul class="nav ace-nav">
					<?php
					$userID =  $this->session->userdata('userId');
					$CheckSuperAdmin = $this->db->where('UserType', 'm')->or_where('UserType', 'a')->where('User_SlNo', $userID)->get('tbl_user')->row();
					if (isset($CheckSuperAdmin)) :
					?>
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<big>Branch Acess</big>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

								<?php
								$sql = $this->db->query("SELECT * FROM tbl_branch where status = 'a' order by Branch_name asc ");
								$row = $sql->result();
								foreach ($row as $row) { ?>
									<li>
										<a class="btn-add fancybox fancybox.ajax" href="<?php echo base_url(); ?>brachAccess/<?php echo $row->branch_id; ?>">
											<i class="ace-icon fa fa-bank"></i>
											<?php echo $row->Branch_name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php endif; ?>

					<li class="clock_li">
						<a class="clock" style="background:#3e2e6b !important;">
							<span style="font-size:20px;"><i class="ace-icon fa fa-clock-o"></i></span> <span style="font-size:15px;"><?php date_default_timezone_set('Asia/Dhaka');
																																		echo date("l, d F Y"); ?>,&nbsp;<span id="timer" style="font-size:15px;"></span></span>
						</a>
					</li>

					<li class="light-blue dropdown-modal">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<?php if ($this->session->userdata('user_image')) { ?>

								<img class="nav-user-photo" src="<?php echo base_url(); ?><?php echo $this->session->userdata('user_image'); ?>" alt="<?php echo $this->session->userdata('FullName'); ?>" />
							<?php } else { ?>

								<img class="nav-user-photo" src="<?php echo base_url(); ?>uploads/no_user.png" alt="<?php echo $this->session->userdata('FullName'); ?>" />
							<?php } ?>
							<span class="user-info">
								<small>Welcome,</small>
								<?php echo $this->session->userdata('FullName'); ?>
							</span>

							<i class="ace-icon fa fa-caret-down"></i>
						</a>

						<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							<li>
								<a href="<?php echo base_url(); ?>profile">
									<i class="ace-icon fa fa-user"></i>
									Profile
								</a>
							</li>

							<li class="divider"></li>

							<li>
								<a href="<?php echo base_url(); ?>Login/logout">
									<i class="ace-icon fa fa-power-off"></i>
									Logout
								</a>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</div><!-- /.navbar-container -->
	</div>

	<div class="main-container ace-save-state">
		<div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed sidebar-scroll">
			<div class="sidebar-shortcuts" id="sidebar-shortcuts">
				<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a href="/graph" class="btn btn-success">
						<i class="ace-icon fa fa-signal"></i>
					</a>

					<a href="/module/AccountsModule" class="btn btn-info">
						<i class="ace-icon fa fa-pencil"></i>
					</a>

					<a href="/module/HRPayroll" class="btn btn-warning">
						<i class="ace-icon fa fa-users"></i>
					</a>

					<a href="/module/Administration" class="btn btn-danger">
						<i class="ace-icon fa fa-cogs"></i>
					</a>
				</div>

				<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
					<span class="btn btn-success"></span>

					<span class="btn btn-info"></span>

					<span class="btn btn-warning"></span>

					<span class="btn btn-danger"></span>
				</div>
			</div><!-- /.sidebar-shortcuts -->

			<?php include('menu.php'); ?>

			<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
				<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
			</div>
		</div>

		<div class="main-content">
			<div class="main-content-inner">
				<div class="breadcrumbs ace-save-state" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="<?php echo base_url(); ?>module/dashboard">Home</a>
						</li>
						<li class="active">Dashboard</li>
					</ul>

					<div class="nav-search" id="nav-search">
						<span style="font-weight: bold; color: #972366; font-size: 16px;">
							<?php echo $this->session->userdata('Branch_name');  ?>
						</span>
					</div>
				</div>

				<div class="page-content">

					<div id="loader" hidden style="position: fixed; z-index: 1000; margin: auto; height: 100%; width: 100%; background:rgba(255, 255, 255, 0.72);;">
						<img src="<?php echo base_url(); ?>assets/loader.gif" style="top: 30%; left: 50%; opacity: 1; position: fixed;">
					</div>
					<?php echo $content; ?>
				</div>
			</div>
		</div>

		<div class="footer">
			<div class="footer-inner">
				<div class="footer-content">
					<div class="row">
						<div class="col-md-9" style="padding-right: 0;">
							<marquee scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();" direction="left" height="30" style="padding-top: 3px;color: red;margin-bottom: -10px;font-size: 15px;" id="linkup_api"></marquee>
						</div>
						<div class="col-md-3" style="padding: 4px 0;background-color: #3e2e6b;color:white; margin-bottom: -1px;">
							<span style="font-size: 12px;">
								Developed by <span class="blue bolder"><a href="http://linktechbd.com/" target="_blank" style="color: white;text-decoration: underline;font-weight: normal;">Link-Up Technology</a></span>
							</span>
						</div>
					</div>

				</div>
			</div>
		</div>

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
			<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
		</a>
	</div>

	<!-- basic scripts -->
	<script src="<?php echo base_url(); ?>assets/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript">
		if ('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
	</script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>


	<script src="<?php echo base_url(); ?>assets/js/jquery-ui.custom.min.js"></script> <!-- ace scripts -->
	<script src="<?php echo base_url(); ?>assets/js/ace-elements.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/fancyBox/js/jquery.fancybox.js?v=2.1.5"></script>

	<script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
	<script type="text/javascript">
		setInterval(function() {

			var currentTime = new Date();

			var currentHours = currentTime.getHours();

			var currentMinutes = currentTime.getMinutes();

			var currentSeconds = currentTime.getSeconds();

			currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;

			currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

			var timeOfDay = (currentHours < 12) ? "AM" : "PM";

			currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

			currentHours = (currentHours == 0) ? 12 : currentHours;

			var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

			document.getElementById("timer").innerHTML = currentTimeString;

		}, 1000);
	</script>

	<script type="text/javascript">
		$(document).ready(function() {

			$.ajax({
				method: 'get',
				url: '/get_mother_api_content',
				success: function(res) {
					$('#linkup_api').text(res);
				}
			})

		});

		$(".fancybox").fancybox({
			padding: 0,
			transitionIn: 'elastic',
			transitionOut: 'elastic',
			loop: true
		});
	</script>

	<script>
		var today = "<?php echo date('Y-m-d') ?>";

		function getTodayPayment() {
			$.ajax({
				url: '/get_installment',
				method: 'POST',
				dataType: 'JSON',
				contentType: 'application/json',
				data: JSON.stringify({
					today: today,
					groupBy: 'yes'
				}),
				beforeSend: () => {
					$(".todayPayment").find('tbody').html("");
				},
				success: res => {
					if (res.length > 0) {
						$.each(res, (index, item) => {
							let months = '';
							$.each(item.months, (sl, val) => {
								months += `<span>${moment(val.due_date).format('MMM-YYYY')}${item.months.length - 1 == sl ? '' : ', '}</span>`;
							});
							let tr = `
							<tr>
								<td style="font-size:11px;padding:4px;">${index + 1}</td>
								<td style="font-size:11px;padding:4px;">${months}</td>
								<td style="font-size:11px;padding:4px;">${item.Customer_Name}</td>
								<td style="font-size:11px;padding:4px;">${item.months.reduce((pre, cur) => {return pre + parseFloat(cur.payment_amount)},0).toFixed(2)}</td>
								<td style="font-size:11px;padding:4px;">
									<i onclick="updateInstallment('today', '${item.customer_id}')" class="fa fa-eye" style="font-size:13px;cursor:pointer;"></i>
								</td>
							</tr>
						`;
							$(".todayPayment").find('tbody').append(tr);
						})
					} else {
						$(".todayPayment").find('tbody').html(`<tr><td colspan="5" style="font-size:11px;padding:4px;">Not Found Data</td></tr>`);
					}
				}
			})
		}
		getTodayPayment();

		function getPastPayment() {
			$.ajax({
				url: '/get_installment',
				method: 'POST',
				dataType: 'JSON',
				contentType: 'application/json',
				data: JSON.stringify({
					pastday: today,
					groupBy: 'yes'
				}),
				beforeSend: () => {
					$(".pastPayment").find('tbody').html("");
				},
				success: res => {
					if (res.length > 0) {
						$.each(res, (index, item) => {
							let months = '';
							$.each(item.months, (sl, val) => {
								months += `<span>${moment(val.due_date).format('MMM-YYYY')}${item.months.length - 1 == sl ? '' : ', '}</span>`;
							});
							let tr = `
								<tr>
									<td style="font-size:11px;padding:4px;">${index + 1}</td>
									<td style="font-size:11px;padding:4px;">${months}</td>
									<td style="font-size:11px;padding:4px;">${item.Customer_Name}</td>
									<td style="font-size:11px;padding:4px;">${item.months.reduce((pre, cur) => {return pre + parseFloat(cur.payment_amount)},0).toFixed(2)}</td>
									<td style="font-size:11px;padding:4px;">
										<i onclick="updateInstallment('past', '${item.customer_id}')" class="fa fa-eye" style="font-size:13px;cursor:pointer;"></i>
									</td>
								</tr>`;
							$(".pastPayment").find('tbody').append(tr);
						})
					} else {
						$(".pastPayment").find('tbody').html(`<tr><td colspan="5" style="font-size:11px;padding:4px;">Not Found Data</td></tr>`);
					}
				}
			})
		}
		getPastPayment();

		function getUpcomingPayment() {
			$.ajax({
				url: '/get_installment',
				method: 'POST',
				dataType: 'JSON',
				contentType: 'application/json',
				data: JSON.stringify({
					upcomingday: today
				}),
				beforeSend: () => {
					$(".upcomingPayment").find('tbody').html("");
				},
				success: res => {
					if (res.length > 0) {
						$.each(res, (index, item) => {
							let tr = `
								<tr>
									<td style="font-size:11px;padding:4px;">${moment(item.due_date).format('DD-MM-YYYY')}</td>
									<td style="font-size:11px;padding:4px;">${moment(item.due_date).format('MMMM-YYYY')}</td>
									<td style="font-size:11px;padding:4px;">${item.Customer_Name}</td>
									<td style="font-size:11px;padding:4px;">${item.payment_amount}</td>
								</tr>
							`;
							$(".upcomingPayment").find('tbody').append(tr);
						})
					} else {
						$(".upcomingPayment").find('tbody').html(`<tr><td colspan="4" style="font-size:11px;padding:4px;">Not Found Data</td></tr>`);
					}
				}
			})
		}

		getUpcomingPayment();

		//update installment
		function updateInstallment(type, customerId = null) {
			window.open(`/installment_customer_list/${customerId}/${type}`, '_blank');
		}
	</script>
</body>

</html>