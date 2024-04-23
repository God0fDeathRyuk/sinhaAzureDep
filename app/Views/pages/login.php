<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sinha& Co</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="public/assets/img/favicon.png" rel="icon">
  <link href="public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Vendor CSS Files -->
  <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('public/assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('public/assets/css/boxicons.min.css') ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url('public/assets/css/style.css') ?>" rel="stylesheet">
  
</head>

<body>
<main class="logbg">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 d-block">				
              <div class="card mb-3 w-45 d-block float-start lftPrt rounded-0">
				<div class="imigShprte text-center">
					<i class="fa-solid fa-user-lock"></i>
				</div>
                <div class="card-body">
                  <div class="lftBdyCntnt">
                    <h5 class="card-title text-left pb-0 fs-4 text-uppercase mb-0">Sign In To</h5>
                    <span class="text-left small text-uppercase">Our portal</span>
					<p>
						Use the scaling classes for larger or smaller rounded corners. 
						Sizes range from 0 to 3, and can be configured by modifying the utilities API
					</p>
                  </div>
                </div>				
              </div>
			  
			  <div class="card mb-3 w-55 d-block float-start">
                <div class="card-body text-center">
                  <div class="pt-4 pb-2 text-center">
                    <img src="<?= base_url('public/assets/img/logo.jpg') ?>" class="imgLogo"/>
                  </div>
                        
				  <div class="lgin-sgnup d-inline-block w-50 text-center mt-2">
					<!-- <p class="d-block float-start pb-1 lginTxt txt">Login</p> -->
				  </div>
                  <form action="<?= base_url('/login') ?>" method="POST" class="row g-3 needs-validation text-start px-5">
                    <div class="col-7">
                      <label for="yourUsername" class="form-label lblTxt">User Id</label>
                      <div class="input-group has-validation">
                        <input type="text" name="userid" class="form-control rounded-0" id="userId" onchange="getUserName(this)" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>
					
					<div class="col-5"> 
                      <label for="yourUsername" class="form-label lblTxt">User Name</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control rounded-0" id="userName" readonly>
                        <input type="hidden" name="roleid" class="form-control rounded-0" id="roleId">
                        <input type="hidden" name="permission" class="form-control rounded-0" id="permission">
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label lblTxt">Password</label>
                      <input type="password" name="password" class="form-control rounded-0" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
					          <div class="col-12">
                      <label for="financialyr" class="form-label lblTxt">Financial Year</label>
                      
                      <select class="form-select cstm-inpt" name="finyr" tabindex="3" id="financialyr">
                         <?php foreach($fin_years as $finyr_row) { ?>
                          <option value="<?php echo $finyr_row['fin_year'];?>" <?php if($current_fin_year == $finyr_row['fin_year']) { echo 'selected'; } ?>><?php echo $finyr_row['fin_year'];?></option>
                         <?php } ?>
                      </select>
                    </div>                        
                    <div class="col-12">
                      <button class="btn btn-primary w-100 rounded-0 btnSbmt py-2" type="submit">Login</button>
                    </div>
                  </form>
                </div>				
              </div>


            </div>
          </div>
        </div>

      </section>
  </main>
  </body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function getUserName(e) {
    let userId = e.value;

    if(userId != '') {
      fetch(`/sinhaco/api/username/${userId}`)
      .then((response) => response.json())
      .then((user) => {
        if(user.username != null) {
          let userName = user.username;
          let roleId = user.role;
          let permission = user.permission;
          document.getElementById('userName').value = userName;
          document.getElementById('roleId').value = roleId;
          document.getElementById('permission').value = permission;
          
        } else {
          document.getElementById('userName').value = ''; e.value = '';
          document.getElementById('roleId').value = ''; e.value = '';
          alertMSG('info', '<strong> Invalid <mark> USER ID </mark> </strong>');
        }
      });
    }
  }
  // Alert
  function alertMSG(ICON = '', TITLE = '', TEXT = '', HTML = '') {
    Swal.fire({
      position: 'top-center',
      icon: ICON,
      html: HTML,
      title: TITLE,
      text: TEXT,
      showConfirmButton: true
    });
  }
</script>

<?php if(isset(session()->message)) { 
	$msg = session()->message;
    echo "<script> alertMSG('error', '<mark>$msg</mark>'); </script>";
} ?>

<?php if(isset(session()->getFlashdata)) { 
	$msg = session()->getFlashdata('message') ;
    echo "<script> alertMSG('error', '<mark>$msg</mark>'); </script>";
} ?>
            
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<!-- Vendor JS Files -->
<script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Template Main JS File -->
<script src="<?= base_url('public/assets/js/custom.js') ?>"></script>
<script src="<?= base_url('public/assets/js/main.js') ?>"></script>
</html>