  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= base_url() ?>" class="logo d-flex align-items-center">
        <img src="<?= base_url('public/assets/img/logo.jpg') ?>" alt="">
		<p class="d-block w-100 title">
			<span class="d-block w-100">Integrated Billing & Financial System</span>
			<span class="d-block w-100 mt-1">(<?= session()->financialYear ?>)</span>
		</p>		
      </a>
      <i class="bi bi-list toggle-sidebar-btn mnuicn-btn" id="burgerMenu"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <!-- Digital Clock HTML -->
        <div id="clockdate" class="d-block float-start me-3 mt-2 fw-bold clrDpblue">
        	<div class="clockdate-wrapper">
        		<div id="clock" class="d-block float-start me-2">-</div>
        		<div id="date"class="d-block float-start me-2"></div>
        	</div>
        </div>
        <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->        

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?= base_url('/public/assets/img/user.png') ?>" alt="Profile" class="rounded-circle me-3 prfle-img">
            <span class="d-none d-md-block fw-bold"> Welcome </span>
            <?php $session = session(); ?>
            <span class="d-none d-md-block dropdown-toggle ps-2 text-uppercase"><?php echo $session->userName;?> </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">			  
              <h6><?php echo $session->userName;?></h6>
              <span><?php echo $session->userId;?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <!--<li>-->
            <!--  <a class="dropdown-item d-flex align-items-center" href="users-profile.html">-->
            <!--    <i class="bi bi-person"></i>-->
            <!--    <span>My Profile</span>-->
            <!--  </a>-->
            <!--</li>-->
            <li>
              <hr class="dropdown-divider">
            </li>

            <!--<li>-->
            <!--  <a class="dropdown-item d-flex align-items-center" href="users-profile.html">-->
            <!--    <i class="bi bi-gear"></i>-->
            <!--    <span>Account Settings</span>-->
            <!--  </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--  <hr class="dropdown-divider">-->
            <!--</li>-->

            <!--<li>-->
            <!--  <hr class="dropdown-divider">-->
            <!--</li>-->

            <li>
              <a class="dropdown-item d-flex align-items-center" onclick="logout()">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

<script>

function logout() {
	Swal.fire({
  		title: 'Are you sure?',
  		text: "Do you want to logout ??",
  		icon: 'warning',
  		showCancelButton: true,
  		confirmButtonColor: '#3085d6',
  		cancelButtonColor: '#d33',
  		confirmButtonText: 'Yes, Logout'
	}).then((result) => {
  		if (result.isConfirmed) {
    		Swal.fire({
  				title: 'Logout!',
  				text: 'Logout Successful.',
  				icon: 'success',
  				showConfirmButton: false
            })
        	window.location.replace(`${baseURL}/logout`);
  		}
	})          
}
</script>