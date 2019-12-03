<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Setting</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($patients as $patient){ ?>
            <tr>
                <td><?php echo $patient['id'];?></td>
                <td><?php echo $patient['firstname'];?></td>
                <td><?php echo $patient['middlename'];?></td>
                <td><?php echo $patient['lastname'];?></td>
                <td><?php echo $patient['email'];?></td>
                <td><?php echo $patient['phone'];?></td>
                <td><i class="fa fa-gear" style="font-size:24px;"></i></td>
            </tr>
            <?php } ?>
    </table>