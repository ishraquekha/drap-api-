<div>
<h2>Doctors List</h2>
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
                <th>Is Verified</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach($doctors as $doctor){ ?>
            <tr title="click here to Edit" class="table-row" data-href="<?php echo base_url()?>index.php/doctorsdetails/<?php echo $doctor['id'];?>" style="cursor:pointer;">
                <td><?php echo $sno;?></td>
                <td><?php echo $doctor['firstname'];?></td>
                <td><?php echo $doctor['middlename'];?></td>
                <td><?php echo $doctor['lastname'];?></td>
                <td><?php echo $doctor['email'];?></td>
                <td><?php echo $doctor['phone'];?></td>
                <td><?php if($doctor['isverified'] == 0){?>
                    <button type="button" class="btn btn-danger">Not Verified</button>
                <?php } else{ ?>
                    <button type="button" class="btn btn-success">Verified</button>
                <?php } ?>
            </tr>
            <?php $sno = $sno + 1; }  ?>
</table>
