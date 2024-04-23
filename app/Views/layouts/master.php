<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sinha& Co</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('public/assets/img/favicon.png') ?>" rel="icon">
  <link href="<?= base_url('public/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <?php if ($renderFlag) : ?>
  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Vendor CSS Files -->
  <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('public/assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('public/assets/css/boxicons.min.css') ?>" rel="stylesheet">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <!-- Multi Select -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css'>

  <!-- Template Main CSS File -->
  <link href="<?= base_url('public/assets/css/style.css') ?>" rel="stylesheet" media="all">

  <script> <?php echo "var baseURL = '" . base_url() . "';"; ?> </script>
  <?php else: ?>
    <style> <?php require_once($_SERVER['DOCUMENT_ROOT'].'/sinhaco/public/assets/css/pdf.css'); ?> </style>
  <?php endif; ?>
</head>

<body>
  <!-- ======= ModalPopup ======= -->
  <?php /* echo $this->include("partials/modelForm") */ ?>
  <!-- End ModalPopup -->

  <!-- ======= Header ======= -->
  <?php if ($renderFlag) : ?>
  <?= $this->include("includes/header") ?>
  <?php endif; ?>
  <!-- End Header -->
  
  <!-- ======= Sidebar ======= -->
  <?php if ($renderFlag) : ?>
    <?= $this->include("partials/leftMenubar") ?>
  <?php endif; ?>
  <!-- End Sidebar-->
  
  <!-- ======= Main ======= -->
  <?= $this->renderSection("body-content") ?>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php if ($renderFlag) : ?>
  <?= $this->include("includes/footer") ?>
  <?php endif; ?>
  <!-- End Footer -->
  
  
  
  
  <!-- ======= Scroll-up arrow btn ======= -->
  <?php if ($renderFlag) : ?>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php endif; ?>
  <!-- End Scroll-up -->

  
</body>

<?php if ($renderFlag) : ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
			crossorigin="anonymous">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"	integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
			crossorigin="anonymous">
	</script>

<!-- Vendor JS Files -->
<script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>
<!-- Multi Select -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js'></script>

<!-- CK Editor Related JS -->

<script src="<?= base_url('public/assets/styles.js') ?>"></script>
<script src="<?= base_url('public/assets/config.js') ?>"></script>
<script src="<?= base_url('public/assets/ckeditor.js') ?>"></script>
<script src="<?= base_url('public/assets/js/sample.js') ?>"></script>
<script src="<?= base_url('public/assets/build-config.js') ?>"></script>

<!-- Template Main JS File -->
<script src="<?= base_url('public/assets/js/custom.js') ?>"></script>
<script src="<?= base_url('public/assets/js/main.js') ?>"></script>
<script>
	initSample();
  </script>
<script>
  $(document).ready(function() {
    $( ".datepicker" ).datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
    });
    $('.withdate').datepicker().datepicker('setDate', 'today');
  });  
</script>
<script>
  
  $(document).on('focus', '.datepicker2', function() {
      //alert('Click event works!');
      $( this).datepicker({ 
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true
      });
      $('.withdate').datepicker().datepicker('setDate', 'today');
    }); 
</script>

<?php endif; ?>

</html>