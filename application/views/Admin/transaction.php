<div>
<h2>Transaction List</h2>
</div>
<hr>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaction Id</th>
                <th>Total Amount</th>
                <th>Transaction History</th>
                <th>Transaction Method</th>
                <th>Transaction Time</th>
                <th>Transaction Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transaction as $tr){ ?>
            <tr class="table-row" data-href="<?php echo base_url()?>index.php/transactiondetails/<?php echo $tr['id'];?>" style="cursor:pointer;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
</table>
