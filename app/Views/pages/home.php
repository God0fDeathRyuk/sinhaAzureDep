<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>


<main id="main" class="main">

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">
  <div class="col-md-12 mt-3">
  <div class="tblMn">
    <table class="table table-bordered tblmn">
      <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Cl1</th>
        <th scope="col">CL2</th>
        <th scope="col">CL3</th>
        <th scope="col">CL4</th>
        <th scope="col">CL5</th>
        <th scope="col">Action</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>1</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>
        <a href="javascript:void(0);" class="me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#popupEdit"><i class="fa-sharp fa-solid fa-pen edit"></i></a>
        <a href="javascript:void(0);" class="me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#popupDelete"><i class="fa-solid fa-trash delt"></i></a>
        <a href="javascript:void(0);" title="View" data-bs-toggle="modal" data-bs-target="#popupView"><i class="fa-solid fa-eye view"></i></a>
        </td>
      </tr>
      <tr>
        <td>2</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>
        <a href="javascript:void(0);" class="me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#popupEdit"><i class="fa-sharp fa-solid fa-pen edit"></i></a>
        <a href="javascript:void(0);" class="me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#popupDelete"><i class="fa-solid fa-trash delt"></i></a>
        <a href="javascript:void(0);" title="View" data-bs-toggle="modal" data-bs-target="#popupView"><i class="fa-solid fa-eye view"></i></a>
        </td>
      </tr>
      <tr>
        <td>3</td>
        <td>Mark</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>Otto</td>
        <td>
        <a href="javascript:void(0);" class="me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#popupEdit"><i class="fa-sharp fa-solid fa-pen edit"></i></a>
        <a href="javascript:void(0);" class="me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#popupDelete"><i class="fa-solid fa-trash delt"></i></a>
        <a href="javascript:void(0);" title="View" data-bs-toggle="modal" data-bs-target="#popupView"><i class="fa-solid fa-eye view"></i></a>
        </td>
      </tr>
      </tbody>
    </table>
    </div>
    <button type="button" class="btn btn-primary cstmBtn mt-3" data-bs-toggle="modal" data-bs-target="#popupAdd">Add</button>
  </div>
  </div>
</section>

</main><!-- End #main -->


<?= $this->endSection() ?>