

<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div class="sidebar-menu">
                    <ul>
                        <li>
                            <a href="<?= base_url(); ?>"><i class="fa fa-home back-icon"></i> <span>Back to Home</span></a>
                        </li>

                        <?php

                        $schoolSettings = false;
                        if (get_permission('school_settings', 'is_view') ||
                        get_permission('live_class_config', 'is_view') ||
                        get_permission('payment_settings', 'is_view') ||
                        get_permission('sms_settings', 'is_view') ||
                        get_permission('email_settings', 'is_view') ||
                        get_permission('accounting_links', 'is_view')) {
                            $schoolSettings = true;
                        }
                        if (get_permission('global_settings', 'is_view') ||
                        ($schoolSettings == true) ||
                        get_permission('translations', 'is_view') ||
                        get_permission('cron_job', 'is_view') ||
                        get_permission('custom_field', 'is_view') ||
                        get_permission('backup', 'is_view')) {
                        ?>
                        <!-- setting -->
                        <li class="menu-title"><?=translate('settings')?></li>

                        <?php if(get_permission('global_settings', 'is_view')){ ?>
                        <li class="<?php if($sub_page == 'settings/universal') echo 'active';?>">
                            <a href="<?= base_url(); ?>settings/universal"><i class="fa fa-building"></i> <span><?= translate('global_settings');?></span></a>
                        </li>
                        <?php } if($schoolSettings == true){ ?>
                        <li class="<?php if($sub_page == 'settings') echo 'active';?>">
                            <a href="<?= base_url(); ?>school_settings"><i class="fa fa-clock-o"></i> <span><?= translate('school_settings') ?></span></a>
                        </li>
                        <?php } if (is_superadmin_loggedin()) { ?>
                        <li class="<?php if ($sub_page == 'role/index' || $sub_page == 'role/permission') echo 'active';?>">
                            <a href="<?=base_url('role')?>"><i class="fa fa-picture-o"></i> <span><?=translate('role_permission')?></span></a>
                        </li>
                        <?php } if (is_superadmin_loggedin()) { ?>
                        <li class="<?php if ($sub_page == 'sessions/index') echo 'active';?>">
                            <a href="<?=base_url('sessions')?>"><i class="fa fa-key"></i> <span><?=translate('session_settings')?></span></a>
                        </li>
                        <?php } if(get_permission('translations', 'is_view')){ ?>
                        <li class="<?php if ($sub_page == 'language/index') echo 'active';?>">
                            <a href="<?=base_url('translations')?>"><i class="fa fa-envelope-o"></i> <span><?=translate('translations')?></span></a>
                        </li>
                        <?php } if(get_permission('cron_job', 'is_view')){ ?>
                        <li class="<?php if ($sub_page == 'cron_api/index') echo 'active';?>">
                            <a href="<?=base_url('cron_api')?>"><i class="fa fa-pencil-square-o"></i> <span><?=translate('cron_job')?></span></a>
                        </li>
                        <?php } if(get_permission('custom_field', 'is_view')){ ?>
                        <li class="<?php if ($sub_page == 'custom_field/index') echo 'active';?>">
                            <a href="<?=base_url('custom_field')?>"><i class="fa fa-money"></i> <span><?=translate('custom_field')?></span></a>
                        </li>
                        <?php } if(get_permission('backup', 'is_view')){ ?>
                        <li class="<?php if ($sub_page == 'database_backup/index') echo 'active';?>">
                            <a href="<?=base_url('backup')?>"><i class="fa fa-globe"></i> <span><?=translate('database_backup')?></span></a>
                        </li>
                        <?php } ?>
                        <!-- <li>
                            <a href="change-password.html"><i class="fa fa-lock"></i> <span>Change Password</span></a>
                        </li>
                        <li>
                            <a href="leave-type.html"><i class="fa fa-cogs"></i> <span>Leave Type</span></a>
                        </li> -->

                    </ul>
                    <?php } ?>
                </div>
            </div>
        </div>