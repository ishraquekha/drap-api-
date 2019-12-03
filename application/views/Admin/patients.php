<div>
<h2>Patients List</h2>
</div>
<hr>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach($patients as $patient){ ?>
            <tr title="Click here to Edit" class="table-row" data-href="<?php echo base_url()?>index.php/patientsdetails/<?php echo $patient['id'];?>" style="cursor:pointer;">
                <td><?php echo $sno;?></td>
                <td><?php echo $patient['firstname'];?></td>
                <td><?php echo $patient['middlename'];?></td>
                <td><?php echo $patient['lastname'];?></td>
                <td><?php echo $patient['email'];?></td>
                <td><?php echo $patient['phone'];?></td>
                <td><?php echo $patient['address'];?></td>
            </tr>
            <?php $sno = $sno+1; } ?>
    </table>