<div class="row login-container column-seperation">
<div class="login-head">  

  <div class="col-md-5 col-md-offset-1">
  </div>

  <div class="col-md-5 card">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><div class="sign-header">Web HRIS PT. Erlangga - Sign In</div></h3>
          </div>
          
          <div class="panel-body">
            <?php echo form_open("auth/login",array("id"=>"login-form","class"=>"login-form"));?>  
              <fieldset>
              <div <?php (! empty($message)) && print('class="alert alert-danger text-center" role="alert"'); ?> id="infoMessage"><?php echo $message;?></div>
              <div class="form-group">
              <div class="input-group">
              <span class="input-group-addon"><i class="icon-user"></i></span>
                  <?php echo bs_form_input($identity);?> 
              </div>
              </div>
              <div class="form-group">
              <div class="input-group">
              <span class="input-group-addon"><i class="icon-lock"></i></span>
                 <?php echo bs_form_input($password);?> 
              </div>
              </div>
              <?php echo bs_form_submit('submit', lang('login_submit_btn'));?>
              </fieldset><br/>
              <u><a href="<?php echo site_url('auth/forgot_password')?>">Lupa Password?, Klik Disini</a></u>
              <br/>
              <br/>
              <u><a href="<?php echo site_url('user_guide')?>" target="_blank">Panduan?, Klik Disini</a></u>
            <div class="col-md-10">
            <?php echo form_close();?>
            </div>
          </div>
        </div>
      </div>
 </div>
</div>


