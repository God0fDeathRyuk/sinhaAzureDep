<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Upcoming Matters</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-7 mt-3">
			<div class="matter d-inline-block w-100">
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">20</b>
							<b class="mnth d-block w-100">March 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">Immunities and Criminal Proceedings </p>
						<p class="mnMtterDesc">Law Text Culture publishes an annual thematic special issue ...</p>
					</div>
				</div>
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">10</b>
							<b class="mnth d-block w-100">April 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">False Report of a Criminal Threat </p>
						<p class="mnMtterDesc">People v. Klein is the trial of Reagan Klein. Reagan is charged..</p>
					</div>
				</div>
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">28</b>
							<b class="mnth d-block w-100">April 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">Human Trafficking and False Imprisonment  </p>
						<p class="mnMtterDesc">Law Text Culture publishes an annual thematic special issue...</p>
					</div>
				</div>
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">05</b>
							<b class="mnth d-block w-100">May 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">Theft by larceny and consent to search </p>
						<p class="mnMtterDesc">A graduate student in fine arts, defendant Evan Shem is a...</p>
					</div>
				</div>
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">15</b>
							<b class="mnth d-block w-100">May 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">Law Text Culture publishes </p>
						<p class="mnMtterDesc">Law Text Culture publishes an annual thematic special issue ...</p>
					</div>
				</div>
				<div class="mtrAll d-inline-block w-100 mb-2">
					<div class="mtrDte">
						<span>
							<b class="dte d-block w-100">30</b>
							<b class="mnth d-block w-100">June 2023</b>
						</span>
					</div>
					<div class="mtrDtls d-block float-start mt-1">
						<p class="mnMtter">Immunities and Criminal Proceedings </p>
						<p class="mnMtterDesc">Law Text Culture publishes an annual thematic special issue ...</p>
					</div>
				</div>
			</div>
			
		  </div>
		  
		  <div class="col-md-5 mt-3">
			  <div class="ChartSec">
				  <div class="ChartSecTop mb-2">
					<div class="d-block float-start w-50 text-start px-2">
						<p class="lftprtTop d-blobk w-100">
							<i class="fa-solid fa-file-invoice"></i>
							<span>Bills</span>
						</p>
					</div>
					<div class="d-block float-start w-50 text-start px-2">
						<span class="d-block float-start mr-2 blmnth">Month</span>
						<select class="form-select d-block float-start mnthLst" aria-label="Default select example">
							<option>Jan</option>
							<option>Feb</option>
							<option>Mar</option>
							<option>Apr</option>
							<option>May</option>
							<option>Jun</option>
							<option>Jul</option>
							<option>Aug</option>
							<option>Sept</option>
							<option>Oct</option>
							<option>Nov</option>
							<option>Dec</option>
						</select>
					</div>
				</div>
				<div class="dueAmunt d-inline-block w-100 p-3">
					<span class="d-inline-block w-100 p-2 mb-2">This Month Due Amount : <b>10,00,00,00.00</b></span>
					<span class="d-inline-block w-100 p-2 mb-2">Total Due Amount : <b>80,00,00,00.00</b></span>
				</div>
				<canvas id="myChart" style="width:100%;max-width:1000px; height:300px;" class="mb-4"></canvas>
				<div class="chrtLgnd d-inline-block w-100 mb-4 px-2">
					<p class="d-block float-start w-50">
						<span class="amntBx relsd">&nbsp;</span>
						<span class="amntNm">Realized Amount</span>						
					</p>
					<p class="d-block float-start w-50 text-end">					
						<span class="amntBx unrelsd me-0 ms-2">&nbsp;</span>
						<span class="amntNm">Unrealized Amount</span>
					</p>
				</div>
			</div>
		  </div>
      </div>
    </section>

  </main><!-- End #main -->

  
<?= $this->endSection() ?>