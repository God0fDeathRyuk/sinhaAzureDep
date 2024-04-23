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

<div class="pagetitle">

      <h1>Role-vs-Menu</h1>      

    </div><!-- End Page Title -->



    <section class="section dashboard">

      <div class="row">

		  <div class="col-md-12 mt-1">

			<div class="frms-sec d-inline-block w-100 bg-white p-3">

				<div class="d-inline-block w-100">

                <form action="/sinhaco/admin/system-menu-perm/list" method="get" id="systemMenu">

					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">

						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Head</label>

						<select class="form-select" name="user_id">

                            <?php foreach ($data as $key => $menu_head) {?>

							<option value="<?= $menu_head['id'];?>" <?php if ($option == 'list') if($user_id == $menu_head['id']) echo "selected";?>><?php echo $menu_head['role_name']; ?></option>

                            <?php } ?>

						</select>

					</div>	

                    <input type="hidden" name="select_button" value="Ok" >

					<button type="submit" class="btn btn-primary cstmBtn mt-31 ms-3 float-start <?php if($select_button!='Ok'){ echo 'd-block';}else{ echo 'd-none';} ?>">Ok</button>

                </form>

                    <div class="<?php if($select_button!='Ok'){ echo 'd-none';}else{ echo 'd-block';} ?> float-start w-25 mt-31 ms-4" id="sysmenuperm">

						<div class="float-start me-4">

							<input type="radio"  class="me-1" onClick="return grant_perm('C');" name="all_perm" >

							<label for="grntAll">Grant All</label>

						</div>

						<div class="float-start">

							<input type="radio"  onClick="return grant_perm2('N');" class="me-1"  name="all_perm">

							<label for="revcAll">Revoke All</label>

						</div>

					</div>

				</div>

				<form action="/sinhaco/admin/system-menu-perm/edit" method="post" id="systemMenu2">

				<div class="d-inline-block w-100 mt-4">

					<table class="table table-bordered tblhdClr">

						<tbody>

							<tr>									

								<th>

									<span>&nbsp;</span>

								</th>

								<th>

									<span class="fntSml">Head</span>

								</th>

								<th>

									<span class="fntSml">Description</span>

								</th>

								<th>

									<span class="fntSml">ID</span>

								</th>

								<th>

									<span class="fntSml">Program</span>

								</th>

								<th>

									<span class="fntSml">Type</span>

								</th>

								<th>

									<span class="fntSml">QLink</span>

								</th>

							</tr>

                            <?php $count=0; 

							$my_menuid='';

							$index=0;

							foreach ($data1 as $key => $value) {$count++;$key=$key+1;?>

							<tr>

								<td>

                                <?php  

                                    if($value['sele_ind']=='YES' and $select_button == 'Ok')

                                    {

                                    ?>

									<input type="checkbox" name="<?php echo 'chk'.$key?>" id="<?php echo 'chk'.$key?>" value="<?= $value['menu_id'] ?>" checked />

                                <?php }

								if($value['sele_ind']=='NO' and $select_button == 'Ok')

									{

									?>

									<input type="checkbox" name="<?php echo 'chk'.$key;?>" id="<?php echo 'chk'.$key;?>" value="<?php echo $value['menu_id'];?>">

									<?php

									}

									?>

								</td>

								<td>

									<span><?php echo $value['menu_head'];?></span>

								</td>

								<td>

									<span><?php echo $value['menu_desc'];?></span>

								</td>

								<td>

									<span><?php echo $value['menu_id']; $my_menuid=$value['menu_id'];?></span>

								</td>

								<td class="brkwrd" style="width:30%;">

									<span><?php echo $value['menu_prog'];?></span>

								</td>

								<td style="width:10%;">

									<span><?php echo $value['menu_type'];?></span>

								</td>

								<td style="width:10%;">

								<?php

								if($select_button == 'Ok' && $value['menu_type'] == "Program")

								{

								?>

								<input type="checkbox" name="quick_link<?php echo $key;?>" id="quick_link<?php echo $key;?>" value="Y" onClick="check_quick_link(<?php echo $key?>)" <?php if ($value['quick_link'] == 'Y') echo 'checked'?>>

								<?php

								} ?>

								</td>

							</tr>

                            <?php } ?>

						</tbody>

					</table>

				</div>	

                <div class="<?php if($select_button!='Ok'){ echo 'd-none';}else{ echo 'd-block';} ?>" id="sysMenuPerm" >	

				<div class="d-block float-start col-md-3" >

					<span class="d-block float-start mb-2 lbl-mn mt-1">Total Caption</span>

					<input type="text" name="total_row" id="total_row" class="d-block float-start form-control ms-2" value="<?php echo $count; ?>" style="width:120px;"/>

				</div>

				<input type="submit" class="btn btn-primary cstmBtn mt-31 ms-3" value="submit">		

				<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-31 ms-2">Cancel</button>

				<input type="hidden" name="menu_id" value="<?= ($option=='list')? $my_menuid : ''?>">

				<input type="hidden" name="user_id" value="<?= ($option=='list')?$user_id : ''?>">

				<input type="hidden" name="max_chk_cnt" value="<?php echo $count;?>">

				</form>

			</div>		

							

		  </div>

      </div>

    </section>



  </main><!-- End #main -->

<?= $this->endSection() ?>