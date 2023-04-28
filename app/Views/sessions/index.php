<?php

$this->db = \Config\Database::connect();
$this->validation = \Config\Services::validation();


?>



<div class="row">
    <div class="col-md-5">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><?=translate('add_session')?></h4>
            </header>
            <?php echo form_open(uri_string()); ?>
            <div class="panel-body">
                <div class="form-group mb-md">
                    <label class="control-label"><?=translate('session')?> <span class="required">*</span></label>
                    <input type="text" class="form-control" name="session" value="<?=set_value('session')?>" />
                    <span class="error"><?=$this->validation->getError('session')?></span>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-default pull-right" type="submit" name="save" value="1">
                            <i class="fas fa-plus-circle"></i> <?=translate('save')?>
                        </button>
                    </div>  
                </div>
            </div>
            <?php echo form_close();?>
        </section>
    </div>

    <div class="col-md-7">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><?=translate('sessions_list')?></h4>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-condensed mb-md">
                        <thead>
                            <tr>
                                <th><?=translate('session')?></th>
                                <th><?=translate('status')?></th>
                                <th><?=translate('created_at')?></th>
                                <th><?=translate('action')?></th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $result = $this->db->table('schoolyear')->get()->getResultArray();
                        if (count($result)):
                            foreach ($result as $row):
                        ?>
                            <tr>
                                <td><?php echo $row['school_year']; ?></td>
                                <td>
                                    <?php if (get_session_id() == $row['id']) :?>
                                    <span class="label label-primary"> <?=translate('selected_session')?></span>
                                    <?php endif;?>
                                </td>
                                <th><?php echo _d($row['created_at']);?></th>
                                <td>
                                    <!-- update link -->
                                    <a class="btn btn-default btn-circle icon editModal" href="javascript:void(0);" data-id="<?=$row['id']?>" data-session="<?=$row['school_year']?>">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                    
                                    <!-- delete link -->
                                    <?php 
                                    if (get_session_id() != $row['id'])
                                        echo btn_delete('sessions/delete/' . $row['id']);
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
    <section class="panel">
        <?php echo form_open('sessions/edit', array('class' => 'frm-submit')); ?>
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="far fa-edit"></i> <?=translate('edit_session')?>
                </h4>
            </header>
            <div class="panel-body">
                <input type="hidden" name="schoolyear_id" id="schoolyear_id" value="" >
                <div class="form-group mb-md">
                    <label class="control-label"><?=translate('sessions')?> <span class="required">*</span></label>
                    <input type="text" class="form-control" value="" name="session" id="session" />
                    <span class="error"><?=$this->validation->getError('session')?></span>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?=translate('update')?></button>
                        <button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close();?>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.editModal').on('click', function() {
            var id = $(this).data('id');
            var session = $(this).data('session'); 
            $('.error').html("");
            $('#schoolyear_id').val(id);
            $('#session').val(session);
            mfp_modal('#modal');
        });
    });
</script>