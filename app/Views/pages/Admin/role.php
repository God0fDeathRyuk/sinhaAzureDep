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
<?php endif; ?>
<div class="pagetitle col-md-5 float-start">
  <h1> Role Add </h1>
</div><!-- End Page Title -->
<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/user-role/add";} else{ echo '/sinhaco/admin/user-role/edit';}?>" method="post" id="queryDetailsAdd">

<div class="frms-sec d-inline-block w-100 bg-white p-3">
    
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Role</label>
						<input type="text" name="role_name" class="form-control w-100 me-2 float-start" value="<?= ($option=='edit')? $data['role_name'] : '' ?>"/>
                        <input type="hidden" name="id" class="form-control w-100 me-2 float-start" value="<?= ($option=='edit')? $data['id'] : '' ?>"/>
					</div>
    
</div>
					
                <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2" >Confirm</button> 
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</button>
    </form>
    <div class="d-inline-block w-100 mt-4">
					<table class="table table-bordered tblhdClr">
						<tbody>
							<tr>									
								<th>
									<span>&nbsp;</span>
								</th>
								<th>
									<span class="fntSml">Role</span>
								</th>
								<th>
									<span class="fntSml">Description</span>
								</th>
							</tr>
							<?php if($option!='edit'){ ?>
                            <?php foreach ($data2 as $key => $value) {?>
							<tr>
                                <td><a href="/sinhaco/admin/user-role/edit?id=<?php echo $value['id']?>">Edit</a></span></td>
								<td><?php echo $value['id']; ?></td>
								<td>
									<span><?php echo $value['role_name']; ?></span>
								</td>
								
							</tr>
                            <?php } }else{?>
							
							<tr>
                                <td><a href="/sinhaco/admin/user-role/edit?id=<?php echo $data['id']?>">Edit</a></span></td>
								<td><?php echo $data['id']; ?></td>
								<td>
									<span><?php echo $data['role_name']; ?></span>
								</td>
								
							</tr>
                            <?php }?>
						</tbody>
					</table>
				</div>	
</main>
<?= $this->endSection() ?>