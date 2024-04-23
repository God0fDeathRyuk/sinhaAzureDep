<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<?php if (!isset($reports)) { ?> 

<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

	<div class="pagetitle w-100 float-start border-bottom pb-1">
		<h1 class="col-md-8 float-start"> List of Unbilled Notice </h1>
	</div>

	<form action="" method="post">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="d-inline-block w-100">
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">AS On Date</label>
					<input type="text" class="form-control" value="<?= $global_curr_date2 ?>" name="ason_date" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code">
					<?php foreach($data['branches'] as $branch) { ?>
					<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-2 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
					<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" />
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
					<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly/>
				</div>
				<div class="col-md-2 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
					<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Description</label>
					<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly/>
				</div>
		</div>
		<button type="submit" class="btn btn-primary cstmBtn mt-3">Proced</button>
		<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
	</form>
</main>
<!-- End #main -->
<?php } else { ?>
	<script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<div class="tbl-sec d-inline-block w-100 p-3 position-relative">
			<div class="position-absolute btndv">
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>
				<a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
			</div>

			<?php
				$maxline = 42 ;
				$lineno  = 0 ;
				$pageno  = 0 ;
				$rowcnt     = 1 ;
				$report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ;     
				$report_cnt = $params['notice_cnt'] ;
				while ($rowcnt <= $report_cnt)
				{
					if ($lineno == 0 || $lineno >= $maxline)
					{
					if($lineno >= $maxline)
					{ 
			?>
									</table>
								</td>
							</tr>
						</table>
						<BR CLASS="pageEnd"> 
			<?php
					}
					$pageno = $pageno + 1 ;
			?>
					<table width="950" align="center" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>    
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
									<tr>
									<td width="08%">&nbsp;</td>
									<td width="72%">&nbsp;</td>
									<td width="08%">&nbsp;</td>
									<td width="12%">&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b>Sinha & Co.</b></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
									</tr>
									<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									</tr>
									<tr>
									<td height="16" class="report_label_text">&nbsp;Branch</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
									<td height="16" class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo $params['date']?></td>
									</tr>
									<tr>
									<td height="16" class="report_label_text">&nbsp;As On</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
									<td height="16" class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
									</tr>
									<tr>
									<td height="16" class="report_label_text">&nbsp;Client</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></td>
									<td height="16" class="report_label_text">&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
									<td height="16" class="report_label_text">&nbsp;Matter</td>
									<td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo strtoupper($params['matter_name']) ; } else { echo 'ALL' ; } ?></b></td>
									<td height="16" class="report_label_text">&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
									<td height="16" class="report_label_text">&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;</td>
									<td height="16" class="report_label_text">&nbsp;</td>
									</tr>
								</table>
							</td>    
						</tr>
						<tr>
							<td colspan="4" class="grid_header">
								<table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
									<tr>
									<td height="18" width="03%" align="left"   class="report_detail_all">&nbsp;Sl</td>
									<td height="18" width="06%" align="left"   class="report_detail_rtb">&nbsp;RecSrl#</td>
									<td height="18" width="09%" align="left"   class="report_detail_rtb">&nbsp;Date</td>
									<td height="18" width="07%" align="left"   class="report_detail_rtb">&nbsp;Matter</td>
									<td height="18" width="75%" align="left"   class="report_detail_rtb">&nbsp;Client / Matter Description</td>
									</tr>
			<?php
						$lineno = 10 ;
					}
			?>
									<tr>
									<td height="18" align="right" class="report_detail_left" ><?php echo $rowcnt ?>&nbsp;</td> 
									<td height="18" align="right" class="report_detail_none" ><?php echo $report_row['serial_no'] ?>&nbsp;</td>
									<td height="18" align="left"  class="report_detail_none" >&nbsp;<?php echo date_conv($report_row['letter_date'],'-') ?></td>
									<td height="18" align="left"  class="report_detail_none" >&nbsp;<?php echo $report_row['matter_code']?></td>
									<td height="18" align="left"  class="report_detail_right">&nbsp;<?php echo $report_row['client_name']?></td>
									</tr>
									<tr>
									<td height="18" align="left"  class="report_detail_lb"    >&nbsp;</td> 
									<td height="18" align="left"  class="report_detail_bottom">&nbsp;</td> 
									<td height="18" align="left"  class="report_detail_bottom">&nbsp;</td>
									<td height="18" align="left"  class="report_detail_bottom">&nbsp;</td>
									<td height="18" align="left"  class="report_detail_rb"    >&nbsp;<?php echo strtoupper($report_row['matter_desc'])?></td>
									</tr>
									<?php $lineno = $lineno + 2 ;  ?>
			<?php     
					if ($maxline - $lineno < 1) { $lineno = $maxline ; }  
					$report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
					//$report_row = mysql_fetch_array($notice_qry);
					$rowcnt = $rowcnt + 1 ;
				}  
			?>
							</table>
							</td>
						</tr>
					</table> 
		</div>
<?php } ?>

<?= $this->endSection() ?>