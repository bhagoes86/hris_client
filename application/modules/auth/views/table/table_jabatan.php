<script src="<?php echo assets_url('js/edit_user.js'); ?>"></script>
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="1%">
                <div class="checkbox check-default">
                    <input id="checkbox10" type="checkbox" value="1" class="checkall">
                    <label for="checkbox10"></label>
                </div>
            </th>
            <!-- <th width="10%"><?php //echo anchor('auth/detail/'.$user->id.'/'.$ctitle_param.'/jabatan_title/'.(($sort_order == 'asc' && $sort_by == 'jabatan_title') ? 'desc' : 'asc'), lang('jabatan_description'));?></th> -->
            <th width="10%"><?php echo lang('organization');?></th>
            <th width="10%"><?php echo lang('position');?></th>
            <th width="10%"><?php echo lang('employee_group');;?></th>
            <th width="5%"><?php echo lang('grade');?></th>
            <th width="10%"><?php echo lang('start_date');?></th>
            <th width="10%"><?php echo lang('end_date');?></th>
            <!--<th width="10%"><?php echo lang('branch');?></th>-->
            <!--<th width="10%"><?php echo lang('personnel_action');?></th>-->
            <th width="10%"><?php echo lang('sk_date');?></th> 
            <th width="10%"><?php echo lang('index_action_th');?></th>                                  
        </tr>
    </thead>
    <tbody id="tabel">
    <?php if ($user_jabatan->num_rows() > 0){
                foreach($user_jabatan->result() as $row){?>
        <tr>
            <td valign="middle">
                 <div class="checkbox check-default">
                    <input id="checkbox<?php echo $row->id;?>" type="checkbox" value="<?php echo $row->id;?>">
                    <label for="checkbox<?php echo $row->id;?>"></label>
                </div>
            </td>


            <!-- <td valign="middle"><?php echo $row->id;?></td> -->
            <td valign="middle"><span class="muted"><?php echo $row->organization;?></span></td>
            <td valign="middle"><span class="muted"><?php echo $row->position;?></span></td>
            <td valign="middle"><span class="muted"><?php echo $row->groups;?></span></td>
            <td valign="middle"><span class="muted"><?php echo $row->grade;?></span></td>
            <td valign="middle"><span class="muted"><?php echo $row->start_date;?></span></td>
            <td valign="middle"><span class="muted"><?php echo $row->end_date;?></span></td>
            <!--<td valign="middle"><span class="muted"><?php //echo $row->branch;?></span></td>-->
            <!--<td valign="middle"><span class="muted"><?php //echo $row->personnel_action;?></span></td>-->
            <td valign="middle"><span class="muted"><?php echo $row->sk_date;?></span></td>
            <td valign="middle">
               <button type="button" class="btn btn-info btn-small" data-toggle="modal" data-target="#editjabatanModal<?php echo $row->id?>" title="<?php echo lang('edit_button')?>"><i class="icon-paste"></i></button>
               <button class='btn btn-danger btn-small' type="submit" name="remove_levels" value="Delete" data-toggle="modal" data-target="#deletejabatanModal<?php echo $row->id?>" title="<?php echo lang('edit_button')?>"><i class="icon-warning-sign"></i></button>
            </td>

        </tr>
    <?php }}else{?>
        <tr>
            <td valign="middle" colspan="9">
                <p class="text-center">No Data</p>
            </td>
        </tr>

    <?php } ?>

    </tbody>
</table>

<?php foreach($user_jabatan->result() as $row){?>
<!--Delete Modal-->
<div class="modal fade" id="deletejabatanModal<?php echo $row->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('delete_confirmation').' for '.$row->organization; ?></h4>
        </div>
      <?php echo form_open('auth/delete_jabatan/'.$row->id, array("id"=>"formdelete".$row->id))?>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="display:none"><span aria-hidden="true">&times;</span></button>
      <div class="modal-body">
        <p><?php echo lang('delete_this_data').$row->organization.' ?'; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="icon-ban-circle"></i>&nbsp;<?php echo lang('cancel_button')?></button> 
        <button type="submit" class="btn btn-danger lnkBlkWhtArw" style="margin-top: 3px;"><i class="icon-warning-sign"></i>&nbsp;<?php echo lang('delete_button')?></button>
      </div>
        <?php echo form_close()?>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function(){
 $('#formdelete<?php echo $row->id?>').submit(function(response){
                    $.post($('#formdelete<?php echo $row->id?>').attr('action'), $('#formdelete<?php echo $row->id?>').serialize(),function(json){
                        if(json.st == 0){
                            $('#MsgBad').text('Delete Failed').fadeIn();
                        }else{
                            getTable();
                            $("[data-dismiss=modal]").trigger({ type: "click" });
                            $('#MsgGood').text('Data Deleted').fadeIn().delay(4000).fadeOut("slow");
                        }
                    }, 'json');
                    return false;
                });
            });
</script>
<?php } ?>

<!--Edit Modal-->

<?php foreach($user_jabatan->result() as $row){?>
<div class="modal fade" id="editjabatanModal<?php echo $row->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <?php echo form_open('auth/edit_jabatan/'.$row->id, array('id'=>'formupdate'.$row->id))?> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_jabatan', 'edit_jabatan')?></h4>
        <p class="error_msg" id="MsgBad2<?php echo $row->id?>" style="background: #fff; display: none;"></p>
      </div>
      <div class="modal-body">
        
             <div class="row form-row">

                <div class="col-md-3">
                    <?php echo lang('organization', 'organization');?>
                </div>
                <div class="col-md-9">
                    <select name="organization_id" class="select2" id="organization_id" style="width:100%">
                        <?php
                            foreach ($organization->result_array() as $key => $value) {
                            $selected = ($row->organization_id <> 0 && $row->organization_id == $value['id']) ? 'selected = selected' : '';
                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['title'].'</option>';
                            }
                            ?>
                        </select>
                </div>

                <div class="col-md-3">
                    <?php echo lang('position', 'position');?>
                </div>
                <div class="col-md-9">
                    <select name="position_id" class="select2" id="position_id" style="width:100%">
                        <?php
                            foreach ($position->result_array() as $key => $value) {
                            $selected = ($row->position_id <> 0 && $row->position_id == $value['id']) ? 'selected = selected' : '';
                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['title'].'</option>';
                            }
                            ?>
                        </select>
                </div>

                <div class="col-md-3">
                    <?php echo lang('employee_group', 'groups');?>
                </div>
                <div class="col-md-9">
                    <select name="groups_id" class="select2" id="groups_id" style="width:100%">
                         <?php  if($q_groups->num_rows() > 0){
                            foreach ($groups->result_array() as $key => $value) {
                            $selected = ($row->employee_group_id <> 0 && $row->employee_group_id == $value['id']) ? 'selected = selected' : '';
                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
                            }}else{
                                echo '<option value="0">'.'No Data'.'</option>';
                            }
                        
                            ?>
                        </select>        
                </div>

                <div class="col-md-3">
                    <?php echo lang('grade', 'grade');?>
                </div>
                <div class="col-md-9">
                    <select name="grade_id" class="select2" id="grade_id" style="width:100%">
                        <?php
                            foreach ($grade->result_array() as $key => $value) {
                            $selected = ($row->grade_id <> 0 && $row->grade_id == $value['id']) ? 'selected = selected' : '';
                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['title'].'</option>';
                            }
                            ?>
                        </select>
                </div>

                <div class="col-md-3">
                    <?php echo lang('start_date', 'start_date');?>
                </div>
                <div class="col-md-9">
                        <div class="input-with-icon right">
                            <div class="input-append success date no-padding">
                                <input type="text" class="form-control" name="start_date" value="<?php echo $row->start_date?>">
                                <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> 
                            </div>
                        </div>
                </div>

                <div class="col-md-3">
                    <?php echo lang('end_date', 'end_date');?>
                </div>
                <div class="col-md-9">
                        <div class="input-with-icon right">
                            <div class="input-append success date no-padding">
                                <input type="text" class="form-control" name="end_date" value="<?php echo $row->end_date?>">
                                <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> 
                            </div>
                        </div>
                </div>

                <div class="col-md-3">
                    <?php echo lang('sk_date', 'sk_date');?>
                </div>
                <div class="col-md-9">
                        <div class="input-with-icon right">
                            <div class="input-append success date no-padding">
                                <input type="text" class="form-control" name="sk_date" value="<?php echo $row->end_date?>">
                                <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> 
                            </div>
                        </div>
                </div>
            </div>                
                                    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove"></i>&nbsp;<?php echo lang('close_button')?></button> 
        <button type="submit" class="btn btn-primary lnkBlkWhtArw" style="margin-top: 3px;"><i class="icon-ok-sign"></i>&nbsp;<?php echo lang('save_button')?></button>
      </div>
        
    </div>
  </div>
  <?php echo form_close()?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
                $('#formupdate<?php echo $row->id?>').submit(function(response){
                    $.post($('#formupdate<?php echo $row->id?>').attr('action'), $('#formupdate<?php echo $row->id?>').serialize(),function(json){
                        if(json.st == 0){
                            $('#MsgBad2<?php echo $row->id?>').html(json.errors).fadeIn();
                        }else{
                            getTable();
                            $("[data-dismiss=modal]").trigger({ type: "click" });
                            $('#MsgBad2<?php echo $row->id?>').hide();
                            $('#MsgGood').text('Data Updated').fadeIn().delay(3000).fadeOut("slow");
                        }
                    }, 'json');
                    return false;
                });
                //$('#course_status_id').select2();
            });
</script>
<?php } ?>
<!-- End Edit Modal-->