<?php 
require_once('views/template/header.php');

$MM = "Marketing Manager";
$CrM = "Creative Manager";
$CoM = "Coms Manager";
?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3>Manage <?=$status ?> requests <small><a href="index.php" class="pull-right btn btn-default">Menu</a></small></h3>
                <hr />
                
                <br />
                <div id="infoMessage"></div>
                <!-- register form -->
                
                <div class="taskList panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php 
                    $tasks = $management->viewTasks($status); 

                    while($row = $tasks->fetch_assoc()) { 
                        
                        $MM_status = $management->returnAuditStatus($row['request_id'], $MM);
                        $CrM_status = $management->returnAuditStatus($row['request_id'], $CrM);
                        $CoM_status = $management->returnAuditStatus($row['request_id'], $CoM);
                    
                        ?>

                    <div class="panel panel-default" id="pendingPanel<?=$row['request_id']?>">
                        <div class="panel-heading" role="tab" id="panel<?=$row['request_id']?>">
                          <h4 class="panel-title clearfix">
                            <a data-toggle="collapse" data-parent="#accordion" href="#heading<?=$row['request_id']?>" aria-expanded="false" aria-controls="heading<?=$row['request_id']?>">
                              <?=$row['request_name']?> <span class="small"><?=$row['date_due']?> </span>
                            </a>
                              <!-- Single button -->
                                <div class="btn-group pull-right">
                                <!-- STARS -->
                                <?php 
                                    echo displayStatusButton($CoM_status, $CoM); 
                                    echo displayStatusButton($CrM_status, $CrM); 
                                    echo displayStatusButton($MM_status, $MM); 
                                    ?>
                                <!-- // STARS -->
                                  <button type="button" class="btn btn-<?=$row['status']?> dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false" id="button<?=$row['request_id']?>">
                                    <?=$row['status']?> <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <li><a href="scope.php?rq_id=<?=$row['request_id']?>" class="scope" id="scope<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon-arrow-right"></span> Scope</a></li>
                                    <li><a href="scope.php?rq_id=<?=$row['request_id']?>" class="scope" id="scope<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon-repeat"></span> Re-scope</a></li>
                                    <li><a href="plan.php?pl_id=<?=$row['request_id']?>" class="plan" id="plan<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon glyphicon-calendar"></span> Plan</a></li>
                                    <li><a href="plan.php?pl_id=<?=$row['request_id']?>" class="approve" id="approve<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon-ok"></span> Approve</a></li>
                                    <li class="divider"></li>
                                      
                                    <li><a href="#" class="pending" id="pending<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon-flag"></span> Escalate</a></li>
                                    <li><a href="#" class="backlog" id="backlog<?=$row['request_id']?>" data-val="<?=$row['request_id']?>"><span class="glyphicon glyphicon-list-alt"></span> Backlog</a></li>
                                      
                                    <li><a href="#" class="decline" id="decline<?=$row['request_id']?>" data-val="<?=$row['request_id']?>" data-target="#declineModal" data-toggle="modal" ><span class="glyphicon glyphicon-remove"></span> Decline request</a></li>
                                  </ul>
                                </div>
                          </h4>
                        </div>
                        <div id="heading<?=$row['request_id']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="panel<?=$row['request_id']?>">
                          <div class="panel-body">
                            <p><b>Time submitted:</b> <?=$row['date_created']?><br />
                               <b>Category:</b> <?=$row['request_category']?><br />
                               <b>Assigned to:</b> <?=$row['request_assigned']?><br />
                               <b>Due by:</b> <?=$row['date_due']?><br />
                              </p>
                            <p><?=$row['description']?></p>
                          </div>
                        </div>
                    </div>
                
                 <?php } ?>
                </div>
                
                
                <p class="text-center">
                <?php
                // show potential errors / feedback (from registration object)
                $alertTop_Danger = '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                $alertTop_Success = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                $alertEnd = '</div>';
                
                if (isset($management)) {
                    if ($management->errors) {
                        foreach ($management->errors as $error) {
                            echo $alertTop_Danger;
                            echo $error;
                            echo $alertEnd;
                        }
                    }
                    if ($management->messages) {
                        foreach ($management->messages as $message) {
                            echo $alertTop_Success;
                            echo $message;
                            echo $alertEnd;
                        }
                    }
                } 
                ?></p>

            </div>
        </div>
    </div>
    
    
     
    <script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <!-- Ajax Script -->
    <script type="text/javascript">
        function updateButton(id, status) {
            var button = "#button" + id,
                btn = $(button);

                //btn.button('reset');

            if(status == "Approved") {
                btn.removeClass("btn-warning btn-danger").addClass("btn-success");
                btn.html("Approved <span class='glyphicon glyphicon-ok'></span>");
                console.log(status);
            } else if(status == "Declined") { 
                btn.removeClass("btn-warning btn-success").addClass("btn-danger");    
                btn.html("Declined <span class='glyphicon glyphicon-remove'></span>"); 
                console.log(status);           
            } else if(status == "Pending") {
                btn.removeClass("btn-success btn-danger").addClass("btn-warning"); 
                btn.html("<span class='glyphicon glyphicon-flag'></span> Escalated");  
                console.log(status);          
            } else if(status == "Backlog") {
                btn.removeClass("btn-success btn-danger").addClass("btn-warning"); 
                btn.html("<span class='glyphicon glyphicon-list-alt'></span> Backlog");  
                console.log(status);
            } else {
                console.log(status);
            }
        }
        
        $(document).ready(function() {
            
            // Send to backlog
            // Get all backlog buttons
                for(j = 0; j < $(".backlog").length; j++ ) {
                    // Get the request id and find the individual button
                    var rqId = $($('.backlog')[j]).attr('data-val');
                    
                    var backlog = "#backlog" + rqId;
                    
                    
                    $(backlog).click(function(){
                        event.preventDefault();
                        // On click change the buttons status
                        var rqId = $(this).attr("data-val"),
                            btn = "#button" +rqId;
                            $(btn).button('loading');
                        // Do the Ajax
                        $.ajax({
                            type: "GET",
                            url: "manage.php",
                            data: 'ajax=true&rq_id=' + rqId + '&send_to=Backlog',
                            success: function(msg){
                                var current = $('#infoMessage').html(),
                                    newMsg = current + " " + msg;
                                $('#infoMessage').html(newMsg);
                                updateButton(rqId, "Backlog");
                            }
                        }); // Ajax Call
                        
                    });
                }
            // Send to backlog
            // Get all backlog buttons
                for(i = 0; i < $(".pending").length; i++ ) {
                    // Get the request id and find the individual button
                    var rqId = $($('.pending')[i]).attr('data-val');
                    
                    var pending = "#pending" + rqId;
                    
                    
                    $(pending).click(function(){
                        event.preventDefault();
                        // On click change the buttons status
                        var rqId = $(this).attr("data-val"),
                            btn = "#button" +rqId;
                            $(btn).button('loading');
                        // Do the Ajax
                        $.ajax({
                            type: "GET",
                            url: "manage.php",
                            data: 'ajax=true&rq_id=' + rqId + '&send_to=Pending',
                            success: function(msg){
                                var current = $('#infoMessage').html(),
                                    newMsg = current + " " + msg;
                                $('#infoMessage').html(newMsg);
                                updateButton(rqId, "Pending");
                            }
                        }); // Ajax Call
                        
                    });
                }
        });
    </script>
    <!-- // Ajax Script -->
    <?php include('views/template/modal_decline.php'); ?>


<?php
require_once('views/template/footer.php'); ?>