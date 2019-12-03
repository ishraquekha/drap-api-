<div>
<h2>Appointments List</h2>
</div>
<hr>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Scheduled on</th>
                <th>Time</th>
                <th>Amount</th>
                <th>Patient Refund</th>
                <th>Response Status</th>
            </tr>
        </thead>
        <tbody><?php $dd=date('Y-m-d');
            $now = new DateTime();
            $now->setTimezone(new DateTimezone('Asia/Kolkata'));
            $dt=$now->format('H:i:s');
            $datetime = $dd . ' ' . $dt;?>
            <?php $sno = 1; foreach($data as $appoint){ ?>
            <tr>
                <td><?php echo $sno;?></td>
                <td><?php echo $appoint['ptname'].' '.$appoint['ptmname'].' '.$appoint['ptlname'];?></td>
                <td><?php echo $appoint['drname'].' '.$appoint['drmname'].' '.$appoint['drlname'];?></td>
                <td><?php echo $appoint['scheduleddate'];?></td>
                <td><?php echo $appoint['timeofarrivel'];?></td>
                <td><?php echo $appoint['amount'];?></td>
                <td><?php echo $appoint['patient_refund'];?></td>
                <td> 
                <?php if($appoint['isaccepted'] == 1){?>
                    <?php if($appoint['iscancelledbypatient'] == 1){?>
                        <button type="button" class="btn btn-default">Cancelled by Patient</button>
                        <?php }elseif($appoint['dat'] <= $datetime){?>
                            <button type="button" class="btn btn-success">Success</button>
                            <?php }
                else{ ?>
                    <button type="button" style="width: 50%;" class="btn btn-success">Accepted</button>
                    <?php } ?>
                <?php } else if($appoint['isrejected'] == 1){?>
                    <button type="button" style="background-color: #d9534f; width:50%;" class="btn btn-danger">Rejected</button>
                <?php } else if($appoint['iscancelledbypatient'] == 1){?>
                    <button type="button" class="btn btn-default">Cancelled by Patient</button>
                <?php }
                else{ ?>
                    <button type="button" style="width: 50%;" class="btn btn-danger">Pending</button>
                <?php } ?>
            </tr>
            <?php $sno = $sno + 1; }  ?>
</table>
