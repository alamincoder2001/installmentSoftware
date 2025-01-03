<style>
	.module_title {
		background-color: #00BE67 !important;
		text-align: center;
		font-size: 18px !important;
		font-weight: bold;
		font-style: italic;
		color: #fff !important;
	}

	.module_title span {
		font-size: 18px !important;
	}
</style>

<?php
// print_r($this->session->userdata()); die();
$userID =  $this->session->userdata('userId');
$CheckSuperAdmin = $this->db->where('UserType', 'm')->where('User_SlNo', $userID)->get('tbl_user')->row();

$CheckAdmin = $this->db->where('UserType', 'a')->where('User_SlNo', $userID)->get('tbl_user')->row();

$userAccessQuery = $this->db->where('user_id', $userID)->get('tbl_user_access');
$access = [];
if ($userAccessQuery->num_rows() != 0) {
	$userAccess = $userAccessQuery->row();
	$access = json_decode($userAccess->access);
}

$module = $this->session->userdata('module');
if ($module == 'dashboard' or $module == '') {
?>
	<ul class="nav nav-list">
		<li class="active">
			<!-- module/dashboard -->
			<a href="<?php echo base_url(); ?>">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>module/SalesModule">
				<i class="menu-icon fa fa-usd"></i>
				<span class="menu-text"> Sales Module </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>module/PurchaseModule">
				<i class="menu-icon fa fa-cart-plus"></i>
				<span class="menu-text"> Purchase Module </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<!--  -->
			<a href="<?php echo base_url(); ?>module/AccountsModule">
				<i class="menu-icon fa fa-clipboard"></i>
				<span class="menu-text"> Accounts Module </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<!-- module/HRPayroll -->
			<a href="<?php echo base_url(); ?>module/HRPayroll">
				<i class="menu-icon fa fa-users"></i>
				<span class="menu-text"> HR & Payroll </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<!-- module/ReportsModule -->
			<a href="<?php echo base_url(); ?>module/ReportsModule">
				<i class="menu-icon fa fa-calendar-check-o"></i>
				<span class="menu-text"> Reports Module </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>module/Administration">
				<i class="menu-icon fa fa-cogs"></i>
				<span class="menu-text"> Administration </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>graph">
				<i class="menu-icon fa fa-bar-chart"></i>
				<span class="menu-text"> Business Monitor </span>
			</a>
			<b class="arrow"></b>
		</li>
	</ul>
<?php } elseif ($module == 'Administration') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/Administration" class="module_title">
				<span>Administration</span>
			</a>
		</li>

		<?php if (array_search("sms", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/sms' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>sms">
					<i class="menu-icon fa fa-mobile"></i>
					<span class="menu-text"> Send SMS </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("product", $access) > -1
			|| array_search("productlist", $access) > -1
			|| array_search("product_ledger", $access) > -1
			|| array_search("Administrator/products/multibarcodeGenerate", $access) > -1
			|| isset($CheckSuperAdmin)
			|| isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/Administrator/products/multibarcodeGenerate' || current_url() == '/product_ledger' || current_url() == '/productlist' || current_url() == '/product' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-product-hunt"></i>
					<span class="menu-text"> Product Info </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("product", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/product' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>product">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Entry
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("productlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/productlist' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>productlist">
								<i class="menu-icon fa fa-caret-right"></i>
								Product List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("product_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/product_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>product_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<li class="<?= current_url() == '/Administrator/products/multibarcodeGenerate' ? 'active' : '' ?>">
						<a href="<?php echo base_url(); ?>Administrator/products/multibarcodeGenerate">
							<i class="menu-icon fa fa-caret-right"></i>
							Multi Barcode
						</a>
						<b class="arrow"></b>
					</li>
				</ul>
			</li>
		<?php endif; ?>
		<?php if (
			array_search("damageEntry", $access) > -1
			|| array_search("damageList", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/damageList' || current_url() == '/damageEntry' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Damage Info </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("damageEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/damageEntry' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>damageEntry">
								<i class="menu-icon fa fa-caret-right"></i>
								Damage Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("damageList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/damageList' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>damageList">
								<i class="menu-icon fa fa-caret-right"></i>
								Damage List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>

		<!-- <?php if (
			array_search("product_transfer", $access) > -1
			|| array_search("transfer_list", $access) > -1
			|| array_search("received_list", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/received_list' || current_url() == '/transfer_list' || current_url() == '/product_transfer' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-exchange"></i>
					<span class="menu-text"> Product Transfer </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("product_transfer", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/product_transfer' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>product_transfer">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Transfer
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<?php if (array_search("transfer_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/transfer_list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>transfer_list">
								<i class="menu-icon fa fa-caret-right"></i>
								Transfer List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("received_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/received_list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>received_list">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Received List</span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?> -->

		<?php if (
			array_search("customer", $access) > -1
			|| array_search("supplier", $access) > -1
			|| array_search("category", $access) > -1
			|| array_search("unit", $access) > -1
			|| array_search("area", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/area' || current_url() == '/unit' || current_url() == '/category' || current_url() == '/supplier' || current_url() == '/customer' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-cog"></i>
					<span class="menu-text"> Settings </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("customer", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customer' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customer">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplier", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplier' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplier">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("category", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/category' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>category">
								<i class="menu-icon fa fa-caret-right"></i>
								Category entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("unit", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/unit' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>unit">
								<i class="menu-icon fa fa-caret-right"></i>
								Unit Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("area", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/area' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>area">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Add Area </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/user' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>user">
					<i class="menu-icon fa fa-user-plus"></i>
					<span class="menu-text"> Create User </span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) && $this->session->userdata('BRANCHid') == 1) : ?>
			<li class="<?= current_url() == '/user_activity' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>user_activity">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> User Activity</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (array_search("database_backup", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/database_backup' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>database_backup">
					<i class="menu-icon fa fa-database"></i>
					<span class="menu-text"> Database Backup </span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($this->session->userdata('BRANCHid') == 1 && (isset($CheckSuperAdmin) || isset($CheckAdmin))) : ?>
			<li class="<?= current_url() == '/deleted_data' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>deleted_data" style="display:flex;align-items:center;">
					<i class="menu-icon fa fa-trash" style="color: red !important;"></i>
					<span class="menu-text"> Deleted Data </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if ($this->session->userdata('BRANCHid') == 1 && (isset($CheckSuperAdmin) || isset($CheckAdmin))) : ?>
			<li class="<?= current_url() == '/companyProfile' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>companyProfile">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Company Profile </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'SalesModule') { ?>
	<ul class="nav nav-list">

		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/SalesModule" class="module_title">
				<span> Sale Module </span>
			</a>
		</li>

		<?php if (array_search("sales", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/sales' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>sales">
					<i class="menu-icon fa fa-usd"></i>
					<span class="menu-text"> Sales Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("salesrecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/salesrecord' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>salesrecord">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Sales Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("salesReturn", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/salesReturn' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>salesReturn">
					<i class="menu-icon fa fa-rotate-left"></i>
					<span class="menu-text"> Sale Return </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("returnList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/returnList' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>returnList">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Sale Return Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("quotation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/quotation' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>quotation">
					<i class="menu-icon fa fa-plus-square"></i>
					<span class="menu-text"> Quotation Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/quotation_record' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>quotation_record">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Quotation Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/currentStock' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>currentStock">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Stock Report </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("salesinvoice", $access) > -1
			|| array_search("sale_return_details", $access) > -1
			|| array_search("customerDue", $access) > -1
			|| array_search("customerPaymentReport", $access) > -1
			|| array_search("customer_payment_history", $access) > -1
			|| array_search("customerlist", $access) > -1
			|| array_search("price_list", $access) > -1
			|| array_search("quotation_invoice_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/quotation_invoice_report' || current_url() == '/price_list' || current_url() == '/customerlist' || current_url() == '/customer_payment_history' || current_url() == '/customerPaymentReport' || current_url() == '/customerDue' || current_url() == '/sale_return_details' || current_url() == '/salesinvoice' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("salesinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/salesinvoice' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>salesinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Sales Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/quotation_invoice_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("sale_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/sale_return_details' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>sale_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerDue' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Due List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerPaymentReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customer_payment_history' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment History
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerlist' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerlist">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/price_list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>price_list">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Price List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'PurchaseModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/PurchaseModule" class="module_title">
				<span> Purchase Module </span>
			</a>
		</li>

		<?php if (array_search("purchase", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/purchase' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>purchase">
					<i class="menu-icon fa fa-shopping-cart"></i>
					<span class="menu-text"> Purchase Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("purchaseRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/purchaseRecord' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>purchaseRecord">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text">Purchase Record</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("purchaseReturns", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/purchaseReturns' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>purchaseReturns">
					<i class="menu-icon fa fa-rotate-right"></i>
					<span class="menu-text"> Purchase Return </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("returnsList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/returnsList' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>returnsList">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Pur. Return Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("AssetsEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/AssetsEntry' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>AssetsEntry">
					<i class="menu-icon fa fa-shopping-cart"></i>
					<span class="menu-text"> Assets Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("assets_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/assets_report' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>assets_report">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Assets Report </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("purchaseInvoice", $access) > -1
			|| array_search("supplierDue", $access) > -1
			|| array_search("supplierPaymentReport", $access) > -1
			|| array_search("supplierList", $access) > -1
			|| array_search("purchase_return_details", $access) > -1
			|| array_search("reorder_list", $access) > -1
			|| array_search("assets_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/purchase_return_details' || current_url() == '/supplierList' || current_url() == '/supplierPaymentReport' || current_url() == '/reorder_list' || current_url() == '/supplierDue' || current_url() == '/purchaseInvoice' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("purchaseInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/purchaseInvoice' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>purchaseInvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>



					<?php if (array_search("supplierDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierDue' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplierPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierPaymentReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplierList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierList' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierList">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Supplier List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/purchase_return_details' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("reorder_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/reorder_list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>reorder_list">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Re-Order List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>
	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'AccountsModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/AccountsModule" class="module_title">
				<span> Account Module </span>
			</a>
		</li>

		<?php if (array_search("cashTransaction", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/cashTransaction' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>cashTransaction">
					<i class="menu-icon fa fa-medkit"></i>
					<span class="menu-text"> Cash Transaction </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("bank_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/bank_transactions' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>bank_transactions">
					<i class="menu-icon fa fa-dollar"></i>
					<span class="menu-text"> Bank Transactions </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("customerPaymentPage", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/customerPaymentPage' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>customerPaymentPage">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Customer Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("installment_payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/installment_payment' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>installment_payment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Installment Receive </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("supplierPayment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/supplierPayment' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>supplierPayment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Supplier Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/cash_view' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>cash_view">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text">Cash View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("loan_transactions", $access) > -1
			|| array_search("loan_view", $access) > -1
			|| array_search("loan_transaction_report", $access) > -1
			|| array_search("loan_ledger", $access) > -1
			|| array_search("loan_accounts", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="<?= current_url() == '/loan_view' || current_url() == '/loan_transaction_report' || current_url() == '/loan_ledger' || current_url() == '/loan_accounts' || current_url() == '/loan_transactions' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Loan </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("loan_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/loan_transactions' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>loan_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/loan_view' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>loan_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/loan_transaction_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>loan_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/loan_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>loan_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/loan_accounts' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>loan_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("investment_transactions", $access) > -1
			|| array_search("investment_transaction_report", $access) > -1
			|| array_search("investment_view", $access) > -1
			|| array_search("investment_ledger", $access) > -1
			|| array_search("investment_account", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="<?= current_url() == '/investment_account' || current_url() == '/investment_ledger' || current_url() == '/investment_transaction_report' || current_url() == '/investment_transactions' || current_url() == '/investment_view' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Investment </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("investment_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/investment_transactions' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>investment_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/investment_view' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>investment_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/investment_transaction_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>investment_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/investment_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>investment_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/investment_account' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>investment_account">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("account", $access) > -1
			|| array_search("bank_accounts", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="<?= current_url() == '/bank_accounts' || current_url() == '/account' ? 'open' : '' ?>">
				<a href="" class="dropdown-toggle">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Account Head </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/account' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>account">
								<i class="menu-icon fa fa-caret-right"></i>
								Transaction Accounts
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<?php if (array_search("bank_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/bank_accounts' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>bank_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Accounts
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("check/entry", $access) > -1
			|| array_search("check/list", $access) > -1
			|| array_search("check/reminder/list", $access) > -1
			|| array_search("check/pending/list", $access) > -1
			|| array_search("check/dis/list", $access) > -1
			|| array_search("check/paid/list", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="<?= current_url() == '/check/paid/list' || current_url() == '/check/dis/list' || current_url() == '/check/pending/list' || current_url() == '/check/reminder/list' || current_url() == '/check/entry' || current_url() == '/check/list' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Cheque </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("check/entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/entry' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/entry">
								<i class="menu-icon fa fa-caret-right"></i>
								New Cheque Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/reminder/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/reminder/list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/reminder/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Reminder Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/pending/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/pending/list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/pending/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Pending Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/dis/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/dis/list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/dis/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Dishonoured Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/paid/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/check/paid/list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>check/paid/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Paid Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("TransactionReport", $access) > -1
			|| array_search("bank_transaction_report", $access) > -1
			|| array_search("cash_ledger", $access) > -1
			|| array_search("bank_ledger", $access) > -1
			|| array_search("cashStatment", $access) > -1
			|| array_search("BalanceSheet", $access) > -1
			|| array_search("balance_sheet", $access) > -1
			|| array_search("day_book", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/day_book' || current_url() == '/balance_sheet' || current_url() == '/BalanceSheet' || current_url() == '/cashStatment' || current_url() == '/bank_ledger' || current_url() == '/cash_ledger' || current_url() == '/bank_transaction_report' || current_url() == '/TransactionReport' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Reports </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/TransactionReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/bank_transaction_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/cash_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/bank_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/cashStatment' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>cashStatment">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Statement
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("balance_sheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/balance_sheet' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>balance_sheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance Sheet
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/BalanceSheet' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance In Out
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("day_book", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/day_book' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>day_book">
								<i class="menu-icon fa fa-caret-right"></i>
								Day Book
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'HRPayroll') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/HRPayroll" class="module_title">
				<span>HR & Payroll</span>
			</a>
		</li>

		<?php if (array_search("salary_payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/salary_payment' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>salary_payment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Salary Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("employee", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/employee' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>employee">
					<i class="menu-icon fa fa-users"></i>
					<span class="menu-text"> Add Employee </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("designation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/designation' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>designation">
					<i class="menu-icon fa fa-binoculars"></i>
					<span class="menu-text"> Add Designation </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("depertment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/depertment' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>depertment">
					<i class="menu-icon fa fa-plus-square"></i>
					<span class="menu-text"> Add Department </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("month", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/month' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>month">
					<i class="menu-icon fa fa-calendar"></i>
					<span class="menu-text"> Add Month </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("emplists/all", $access) > -1
			|| array_search("emplists/active", $access) > -1
			|| array_search("emplists/deactive", $access) > -1
			|| array_search("salary_payment_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/salary_payment_report' || current_url() == '/emplists/deactive' || current_url() == '/emplists/active' || current_url() == '/emplists/all' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/all' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/all">
								<i class="menu-icon fa fa-caret-right"></i>
								All Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/active", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/active' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/active">
								<i class="menu-icon fa fa-caret-right"></i>
								Active Employee List
							</a>
							<b class="arrow"></b>
						</li>
						<?php endif; ?><?php if (array_search("emplists/deactive", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/deactive' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/deactive">
								<i class="menu-icon fa fa-caret-right"></i>
								Deactive Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/salary_payment_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Salary Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'ReportsModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/ReportsModule" class="module_title">
				<span>Reports Module</span>
			</a>
		</li>

		<?php if (array_search("profitLoss", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/profitLoss' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>profitLoss">
					<i class="menu-icon fa fa-medkit"></i>
					<span class="menu-text"> Profit & Loss Report </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/cash_view' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>cash_view">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text">Cash View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("purchaseInvoice", $access) > -1
			|| array_search("purchaseRecord", $access) > -1
			|| array_search("supplierDue", $access) > -1
			|| array_search("supplierPaymentReport", $access) > -1
			|| array_search("supplierList", $access) > -1
			|| array_search("returnsList", $access) > -1
			|| array_search("purchase_return_details", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/purchase_return_details' || current_url() == '/returnsList' || current_url() == '/supplierList' || current_url() == '/supplierPaymentReport' || current_url() == '/supplierDue' || current_url() == '/purchaseRecord' || current_url() == '/purchaseInvoice' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Purchase Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("purchaseInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/purchaseInvoice' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>purchaseInvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("purchaseRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/purchaseRecord' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>purchaseRecord">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplierDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierDue' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplierPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierPaymentReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Payment
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("supplierList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/supplierList' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>supplierList">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Supplier List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("returnsList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/returnsList' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>returnsList">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/purchase_return_details' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


		<?php if (
			array_search("salesinvoice", $access) > -1
			|| array_search("salesrecord", $access) > -1
			|| array_search("returnList", $access) > -1
			|| array_search("sale_return_details", $access) > -1
			|| array_search("customerDue", $access) > -1
			|| array_search("customerPaymentReport", $access) > -1
			|| array_search("customer_payment_history", $access) > -1
			|| array_search("customerlist", $access) > -1
			|| array_search("price_list", $access) > -1
			|| array_search("quotation_invoice_report", $access) > -1
			|| array_search("quotation_record", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/quotation_record' || current_url() == '/quotation_invoice_report' || current_url() == '/price_list' || current_url() == '/customerlist' || current_url() == '/customer_payment_history' || current_url() == '/customerPaymentReport' || current_url() == '/customerDue' || current_url() == '/sale_return_details' || current_url() == '/returnList' || current_url() == '/salesrecord' || current_url() == '/salesinvoice' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Sales Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("salesinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/salesinvoice' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>salesinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Sales Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salesrecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/salesrecord' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>salesrecord">
								<i class="menu-icon fa fa-caret-right"></i>
								Sales Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("returnList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/returnList' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>returnList">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("sale_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/sale_return_details' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>sale_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerDue' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Due List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerPaymentReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customer_payment_history' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment History
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/customerlist' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>customerlist">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/price_list' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>price_list">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Price List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/quotation_invoice_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/quotation_record' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>quotation_record">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


		<?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="<?= current_url() == '/currentStock' ? 'active' : '' ?>">
				<a href="<?php echo base_url(); ?>currentStock">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Stock </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>


		<?php if (
			array_search("TransactionReport", $access) > -1
			|| array_search("bank_transaction_report", $access) > -1
			|| array_search("cash_ledger", $access) > -1
			|| array_search("bank_ledger", $access) > -1
			|| array_search("cashStatment", $access) > -1
			|| array_search("BalanceSheet", $access) > -1
			|| array_search("day_book", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/day_book' || current_url() == '/balance_sheet' || current_url() == '/BalanceSheet' || current_url() == '/cashStatment' || current_url() == '/bank_ledger' || current_url() == '/cash_ledger' || current_url() == '/bank_transaction_report' || current_url() == '/TransactionReport' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Accounts Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/TransactionReport' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/bank_transaction_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/cash_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/bank_ledger' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/cashStatment' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>cashStatment">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Statement
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/BalanceSheet' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance In Out
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("day_book", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/day_book' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>day_book">
								<i class="menu-icon fa fa-caret-right"></i>
								Day Book
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


		<?php if (
			array_search("emplists/all", $access) > -1
			|| array_search("emplists/active", $access) > -1
			|| array_search("emplists/deactive", $access) > -1
			|| array_search("salary_payment_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="<?= current_url() == '/salary_payment_report' || current_url() == '/emplists/deactive' || current_url() == '/emplists/active' || current_url() == '/emplists/all' ? 'open' : '' ?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Employee Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/all' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/all">
								<i class="menu-icon fa fa-caret-right"></i>
								All Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/active", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/active' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/active">
								<i class="menu-icon fa fa-caret-right"></i>
								Active Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/deactive", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/emplists/deactive' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>emplists/deactive">
								<i class="menu-icon fa fa-caret-right"></i>
								Deactive Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="<?= current_url() == '/salary_payment_report' ? 'active' : '' ?>">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Salary Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>
	</ul><!-- /.nav-list -->
<?php } ?>