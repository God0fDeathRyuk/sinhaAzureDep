<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php   endif; ?>
<?php
 //echo $privetkey;die;
$ciphering = "AES-128-CTR";
 
// Use OpenSSl Encryption method
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
 
// Non-NULL Initialization Vector for decryption
$decryption_iv = '1234567891011121';

if($option == 'edit'){
 $encryption=($option == 'edit') ? $data['user_password'] :  '';
}
if($option == 'view'){
  $encryption=($option == 'view') ? $data['user_password'] :  '';
 }
 if($option == 'list'){
  $encryption='';
 }
 if($option == 'add'){
  $encryption='';
 }
// Store the decryption key
$decryption_key = $privetkey;
 
// Use openssl_decrypt() function to decrypt the data
$decryption=openssl_decrypt ($encryption, $ciphering,
        $decryption_key, $options, $decryption_iv);
      
?>

<div class="pagetitle col-md-5 float-start">
  <h1> System User Details (List of users) </h1>
</div><!-- End Page Title -->

<?php if($option == 'list') { ?> 
<div class="col-md-3 float-start text-end mt-2">
  
  <a href="/sinhaco/admin/user-details/add" class="btn btn-primary cstmBtn mt-0"> Add </a>
</div>

<section class="section dashboard d-inline-block w-100">
  <div class="row">
    <div class="col-md-12 mt-2">
      <div class="">
        <table class="table table-bordered tblmn">
            <thead>
              <tr>
                  <th scope="col">Name</th>
                  <th scope="col">ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Gender</th>
                  <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            if(count($data) != 0) {
            foreach ($data as $key => $user) { ?>
                <tr>
                    <td> <?= $user['user_name'] ?> </td>
                    <td> <?= $user['user_id'] ?> </td>
                    <td> <?= $user['status_code'] ?> </td>
                    <td> <?= $user['user_gender'] ?> </td>      
                    <td> 
                      <a href="/sinhaco/admin/user-details/view?user_id=<?= $user['user_id'] ?>" title="View"><i class="fa-solid fa-eye view"></i></a> 
                      <a href="/sinhaco/admin/user-details/edit?user_id=<?= $user['user_id'] ?>" class="me-1" title="Edit"><i class="fa-sharp fa-solid fa-pen edit"></i></a> 
                      <a href="/sinhaco/admin/user-details/delete?user_id=<?= $user['user_id'] ?>" class="me-1" title="Delete" onclick="return confirm('Are you sure want to Delete this User?');"><i class="fa-solid fa-trash delt"></i></a> 
                    </td>
                </tr>
                <?php }
            } else { ?>
            	<tr>
                    <td colspan= <?= $tableCols ?> class="text-center"> No Records Found !! </td>
                </tr>
            <?php } ?>
              </tbody>
        </table>
      </div>    
    </div>
  </div>
</section> 
<?php } else {?> 
<section class="section dashboard d-inline-block w-100">
  <div class="row">
    <div class="col-md float-end text-end mb-2">
        <a href="/sinhaco/admin/user-details?display_id=&menu_id=9901" class="btn-bck btn btn-dark me-2">Back</a>
		<!-- <a href="<?= $params['requested_url'] ?>" class="btn-bck btn btn-dark me-2">Back</a> -->
    </div>
  </div>
  <form action="" method="post" id="caseStatus">
      <div class="row">
        <div class="inpt-grp col-md-4 pe-0 position-relative">
            <label class="d-block w-100 mb-2">User ID</label>
            <input type="text" class="form-control cstm-inpt" value="<?= ($option != 'add') ? $data['user_id'] :  ''?>" name="user_id" onchange="checkUserName(this)" <?= ($option != 'add') ? $permission : '' ?>  required/>
            <input type="hidden" class="form-control cstm-inpt" value="<?= ($option == 'edit') ? $data['user_id'] :  ''?>" name="hiddenuser_id" />
        </div>
        <div class="inpt-grp col-md-4 pe-0 position-relative">
            <label class="d-block w-100 mb-2">User Name</label>
            <input type="text" class="form-control cstm-inpt" value="<?= ($option != 'add') ? $data['user_name'] : '' ?>" name="user_name" <?= ($option != 'add') ? $permission : '' ?> required />
        </div>
        <div class="inpt-grp col-md-4 pe-0">
            <label class="d-block w-100 mb-2">Role</label>
            <select class="form-select cstm-inpt" <?= ($option != 'add') ? $permission : '' ?> name="user_type" >
            <?php foreach ($data1 as $key => $value) {?>
              <option value="<?= $value['id']; ?>" <?php if ($option != 'add') if($data['role'] == $value['id']) echo "selected";?>><?php  echo $value['role_name']; ?></option>
              <?php } ?>             
            </select>
        </div>
        <div class="inpt-grp col-md-4 pe-0 position-relative">
            <label class="d-block w-100 mb-2">Password</label>
            
            <input type="text" class="form-control cstm-inpt" value="<?= ($option != 'add') ? $decryption : '' ?>" name="user_password" <?= ($option != 'add') ? $permission : '' ?> required/>
        </div>
        <div class="inpt-grp col-md-4 pe-0">
            <label class="d-block w-100 mb-2">Status</label>
            <select class="form-select cstm-inpt" <?= ($option != 'add') ? $permission : '' ?> name="status_code">
              <option value="Active" <?php if ($option != 'add') if($data['status_code'] == 'Active') echo "selected";?>>Active</option>
              <option value="Old"    <?php if ($option != 'add') if($data['status_code'] == 'Old')    echo "selected";?>>Old</option>
            </select>
        </div>
        <div class="inpt-grp col-md-4 pe-0">
            <label class="d-block w-100 mb-2">Gender</label>
            <select class="form-select cstm-inpt" <?= ($option != 'add') ? $permission : '' ?> name="user_gender" >
              <option value="M" <?php if ($option != 'add') if($data['user_gender'] == 'M') echo "selected";?>>Male</option>
              <option value="F" <?php if ($option != 'add') if($data['user_gender'] == 'F') echo "selected";?>>Female</option>
            </select>
        </div>
      </div>
      <div class="w-100 float-start text-start mt-4 top-btn-fld">
        <?php if ($option != 'view') { ?>
          <button type="submit" id="sub" class="btn btn-primary cstmBtn mt-0 me-2" onclick="submitUserForm()">Save</button>
          <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2">Reset</button>
        <?php } ?>
        </div>
</section> 
<?php } ?>

</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function checkUserName(e) {
      let userId = e.value;
        if(userId != '') {
              fetch(`/sinhaco/api/checkusername/${userId}`)
              .then((response) => response.json())
              .then((user) => {
                if(user.user_id != '') {
                  alertMSG('info', '<strong> USER ID <mark> ALREADY EXIST </mark> </strong>');
                  document.getElementById("sub").disabled = true;
                 
                } 
                else
                {
                  document.getElementById("sub").disabled = false;
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

  function(confirm){
if (confirm) {
  alertMSG('info', '<strong> ARE YOU SURE YOU WANT TO DELETE THE USER </strong>');
} else {
  alertMSG('info', '<strong> USER WAS NOT REMOVED  </mark> </strong>');
}
}
  </script>


<?= $this->endSection() ?>