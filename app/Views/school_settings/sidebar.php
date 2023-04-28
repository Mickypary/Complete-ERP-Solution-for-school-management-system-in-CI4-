<?php

$this->request = \Config\Services::request();


$url = '';
if ($this->request->getGet('branch_id')) {
   $url = '?branch_id=' . $this->request->getGet('branch_id');
}
?>
<div class="panel mailbox">
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
        <?php if(get_permission('school_settings', 'is_view')){ ?>
            <li <?=$sub_page == 'school_settings/school' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings' . $url)?>"><i class="fas fa-school"></i> <?=translate('school') . " " . translate('details')?></a></li>
        <?php } if(get_permission('live_class_config', 'is_view')){ ?>
            <li <?=$sub_page == 'school_settings/live_class_config' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings/live_class_config' . $url)?>"><i class="fas fa-headset"></i> <?=translate('live_class') . " " . translate('settings')?></a></li>
        <?php } if(get_permission('payment_settings', 'is_view')){ ?>
            <li <?=$sub_page == 'school_settings/payment_gateway' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings/payment' . $url)?>"><i class="fas fa-dollar-sign"></i> <?=translate('payment_settings')?></a></li>
        <?php } if(get_permission('sms_settings', 'is_view')){ ?> 
            <li <?=$sub_page == 'school_settings/smsconfig' || $sub_page == 'school_settings/smstemplate' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings/smsconfig' . $url)?>"><i class="far fa-comment-alt"></i> <?=translate('sms_settings')?></a></li>
        <?php } if(get_permission('email_settings', 'is_view')){  ?>
            <li <?=$sub_page == 'school_settings/emailconfig' || $sub_page == 'school_settings/emailtemplate' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings/emailconfig' . $url)?>"><i class="far fa-envelope"></i> <?=translate('email_settings')?></a></li>
        <?php } if (get_permission('accounting_links', 'is_view')) {?>
            <li <?=$sub_page == 'school_settings/accounting_links' ? 'class="active"' : '';?>><a href="<?=base_url('school_settings/accounting_links' . $url)?>"><i class="fas fa-random"></i> <?=translate('accounting_links')?></a></li>
        <?php } ?>
        </ul>
    </div>
</div>