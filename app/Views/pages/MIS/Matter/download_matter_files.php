<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<main id="main" class="main">
    <div class="pagetitle">
    <h1>List of Uploaded Files</h1>
    </div>
    <section class="section dashboard">
    <table border="1" cellpadding="0" cellspacing="3" class="table">
        <tr class="fs-14">
            <th height="25">&nbsp;<b>Description</b></th>
            <th height="25">&nbsp;<b>Matter</b></th>
            <th height="25">&nbsp;<b>File Name</b></th>
            <th height="25">&nbsp;<b>Type</b></th>
            <th height="25">&nbsp;<b>Uploaded By</b></th>
            <th height="25">&nbsp;<b>Upload On</b></th>
            <th height="25">&nbsp;<b>Download</b></th>
        </tr>
        <?php
        foreach($qry as $row) {
            $file_type          = $row['file_type'];
            $file_name_system   = $row['file_name_system'];
            $file_name_original = $row['file_name_original'];
            $description        = stripslashes($row['description']);
            $emp_serial_no      = $row['emp_serial_no'];
            $serial_no          = $row['serial_no'];
            $uploaded_on        = date_conv($row['uploaded_on']);
            $uploaded_by        = strtoupper($row['user_name']);
        ?>
            <tr class="fs-14">
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $description;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $emp_serial_no;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $file_name_original;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $file_type;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $uploaded_by;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;<?php echo $uploaded_on;?></td>
                <td class="p-2" bgcolor="#dceff5">&nbsp;
                    <a href="dwlnd.php?f=<?php echo $file_name_system;?>&fc=<?php echo $file_name_original;?>">Download</a>
                </td>
            </tr>
            <?php
        }
        ?>
        </table>
    </section>
</main>

<?= $this->endSection() ?>