<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<script>
    document.getElementById('sidebar').style.display = "none";
    document.getElementById('burgerMenu').style.display = "none";
</script> 
<div class="mnBdy bg-white">
<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-end btn btn-dark me-5">Back</a>

    <div class="tblPrnt py-3 px-4">
        <table class="table border-0 tblLtr" cellpadding="0" cellspacing="0"> 
            
        <?php if($user_option == 'Letter Head' || $user_option == 'Letter Head BR') { ?>
        <!-- Logo & rest top part start -->
            <tr>
                <td colspan="2" class="text-center py-0"><img class="imlogoLtrpg" src="<?= base_url('public/assets/img/logo.jpg') ?>"/></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0">
                    <p class="fw-bold"><?= ($user_option == 'Letter Head') ? strtoupper($params['branch_addr1']) : strtoupper($params['fancy_branch_addr1'])?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0">
                    <p class="fw-bold"><?= ($user_option == 'Letter Head') ? strtoupper($params['branch_addr2']) : strtoupper($params['fancy_branch_addr2']) ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0 pb-3 emlSecTp">
                    <p class="fw-bold"><?= ($user_option == 'Letter Head') ? $params['branch_addr3'] : $params['fancy_branch_addr3'] ?></p>
                </td>
            </tr>

             <!-- Logo & rest top part end -->
             <?php } ?>

            <tr>
                <td class="pt-3"> <?= $letter['letter_no'] ?> </td>
                <td class="text-end"> <?= $letter['letter_dt'] ?> </td>
            </tr>
            <tr>
                <td colspan="2" class="text-uppercase text-center fw-bold"> <?= $letter['send_mode'] ?> </td>
                <td colspan="2" class="text-uppercase text-center fw-bold"> <?= $letter['remarks'] ?> </td>
            </tr>
            <tr>
                <td colspan="2" class="mt-4 d-block w-100 mb-4">
                    <!-- <span class="d-block w-100">To,</span> -->
                    <span class="d-block w-100"> <?= nl2br($letter['letter_address']) ?> </span>
                </td>
            </tr>
            <?php if($letter['letter_desc'] != '') { ?>
                <tr>
                    <td colspan="2" class="text-center pb-3">
                        <p class="d-block text-start m-auto w-50">
                            <strong>Re : </strong>
                            <span> <?= nl2br($letter['letter_desc']) ?> </span>
                        </p>
                    </td>
                </tr>
            <?php } ?>
            <?php if($letter['letter_client'] != '') { ?>
                <tr>
                    <td colspan="2" class="text-center">
                        <p class="d-block text-start m-auto w-50">
                            <strong>Our Client : </strong>
                            <span> <?= nl2br($letter['letter_client']) ?> </span>
                        </p>
                    </td>
                </tr>
            <?php } ?>
            <?php if($letter['your_client'] != '') { ?>
                <tr>
                    <td colspan="2" class="text-center">
                        <p class="d-block text-start m-auto w-50">
                            <strong>Your Client : </strong>
                            <span> <?= nl2br($letter['your_client']) ?> </span>
                        </p>
                    </td>
                </tr>
            <?php } ?>
            <?php if($letter['letter_desc_ref'] != '') { ?>	
            <tr>
                <td colspan="2" class="text-center">
                    <p class="d-block text-start m-auto w-50">
                        <strong>Ref : </strong>
                        <span> <?= nl2br($letter['letter_desc_ref']) ?> </span>
                    </p>
                </td>
            </tr>
            <?php } ?>
            <?php if($letter['letter_desc_sub'] != '') { ?>	
            <tr>
                <td colspan="2" class="text-center">
                    <p class="d-block text-start m-auto w-50">
                        <strong>Subject : </strong>
                        <span> <?= nl2br($letter['letter_desc_sub']) ?> </span>
                    </p>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="2">
                    <p> <?= nl2br($letter['letter_body']) ?> </p>
                </td>
            </tr>
        </table>
    </div>
</div>
<?= $this->endSection() ?>