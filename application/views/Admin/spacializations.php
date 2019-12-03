<div>
<h2>specialization List</h2>
</div>
<hr>
<form action="<?php echo base_url()?>index.php/addspacialization" method="post">
<input style="width:50%;" type="text" name="spacalization" placeholder="Enter any specialization here..">
<button style="padding: 6px; border-radius: 10px; width: 20%;" class="btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Add</button>
</form>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="width: 40px;">S.No.</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach($list as $sp){ ?>
            <tr title="Click here to Edit or Delete" class="table-row" data-href="<?php echo base_url()?>index.php/spacilizationdetails/<?php echo $sp['id'];?>" style="cursor:pointer;">
                <td><?php echo $sno;?></td>
                <td><?php echo $sp['spacialist'];?></td>
            </tr>
            <?php $sno = $sno+1; } ?>
    </table>