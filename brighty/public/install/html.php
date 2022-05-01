<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo $this->page_title; ?></title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" integrity="sha512-NmLkDIU1C/C88wi324HBc+S2kLhi08PN5GDeUVVVC/BVt/9Izdsc9SVeVfA1UZbY3sHUlDSyRXhCzHfr6hmPPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
      <link href='/css/style.css' rel='stylesheet' type='text/css'>

   </head>
   <body style="background:#fffffc">
      <div class="container" style="max-width:750px">
         <div class="row">
            <div class="col-md-12 ">
               <div class="logo">
                  <img src="logo.png">
               </div>
               <div class="row" style="padding: 20px;padding-bottom: 0;
">
                  <div class="col-3 text-center <?php if($passed_steps[1] == true || $step == 1){echo 'bg-default';} ?> padding-10">
                     <h5>Requirements</h5>
                  </div>
                  <div class="col-2 text-center <?php if($passed_steps[2] || $step == 2){echo 'bg-default';} else {echo 'bg-not-passed';} ?> padding-10">
                     <h5>Permissions</h5>
                  </div>
                  <div class="col-3 text-center <?php if($passed_steps[3] || $step == 3){echo 'bg-default';} else {echo 'bg-not-passed';} ?> padding-10">
                     <h5> Database setup</h5>
                  </div>
                  <div class="col-2 text-center <?php if($passed_steps[4] || $step == 4){echo 'bg-default';} else {echo 'bg-not-passed';} ?> padding-10">
                     <h5> Install</h5>
                  </div>
                  <div class="finish col-2 text-center <?php if($step == 5){echo 'bg-success';}else {echo 'bg-not-passed';} ?> padding-10">
                     <h5> Finish</h5>
                  </div>
               </div>
               <div class="install-row">
                  <p style="padding:20px 10px">This is a list of all system requirements to install <?php echo $this->software_name; ?>. Based on the test results, current status is listed below. Please fix any unfulfilled requirement to be able to proceed further. </p>
                  <?php if($debug != ''){ ?>
                  <p class="sql-debug-alert text-success" style="margin-bottom:20px;">
                     <b><?php echo $debug; ?></b>
                  </p>
                  <?php } ?>
                  <?php if(isset($error) && $error != ''){ ?>
                  <div class="alert alert-danger text-center">
                     <?php echo $error; ?>
                  </div>
                  <?php } ?>
                  <?php if($step == 1){
                     include_once('requirements.php');
                     } else if($step == 2){
                     include_once('file_permissions.php');
                     } else if($step == 3){ ?>
                  <?php echo '<form action="" method="post" accept-charset="utf-8">'; ?>
                  <?php echo '<input type="hidden" name="step" value="'.$step.'">'; ?>
                  <div class="form-group">
                     <label for="hostname" class="control-label">Hostname</label>
                     <input type="text" class="form-control" name="hostname" value="localhost">
                  </div>
                  <div class="form-group">
                     <label for="database" class="control-label">Database Name</label>
                     <input type="text" class="form-control" name="database">
                  </div>
                  <div class="form-group">
                     <label for="username" class="control-label">Username</label>
                     <input type="text" class="form-control" name="username">
                  </div>
                  <div class="form-group">
                     <label for="password" class="control-label"><i class="glyphicon glyphicon-info-sign" title='Avoid use of single(&lsquo;) and double(&ldquo;) quotes in your password'></i> Password</label>
                     <input type="text" class="form-control" name="password">
                  </div>
                  <hr />
                  <div class="text-left">
                     <button type="submit" class="btn btn-success">Check Database</button>
                  </div>
                  </form>
                  <?php } else if($step == 4){ ?>
                  <?php echo '<form action="" method="post" accept-charset="utf-8" id="installForm">'; ?>
                  <?php echo '<input type="hidden" name="step" value="'.$step.'">'; ?>
                  <?php echo '<input type="hidden" name="hostname" value="'.$_POST['hostname'].'">'; ?>
                  <?php echo '<input type="hidden" name="username" value="'.$_POST['username'].'">'; ?>
                  <?php echo '<input type="hidden" name="password" value="'.$_POST['password'].'">'; ?>
                  <?php echo '<input type="hidden" name="database" value="'.$_POST['database'].'">'; ?>
                  <div class="form-group">
                     <div class="form-group">
                        <label for="base_url" class="control-label">Base URL <a href="https://help.perfexcrm.com/faq/what-is-base-url/" target="_blank">Read more...</a></label>
                        <input type="url" class="form-control" value="<?php echo $this->guess_base_url(); ?>" name="base_url" id="base_url" required>
                     </div>
                  </div>
                  <hr />
                  <h5>Admin login</h5>
                  <hr />
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="firstname" class="control-label">Firstname</label>
                           <input type="text" class="form-control" name="firstname" id="firstname" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="lastname" class="control-label">Lastname</label>
                           <input type="text" class="form-control" name="lastname" id="lastname" required>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="admin_email" class="control-label">Email</label>
                     <input type="email" class="form-control" name="admin_email" id="admin_email" required>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="admin_password" class="control-label">Password</label>
                           <input type="password" class="form-control" name="admin_password" id="admin_password" required>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="admin_passwordr" class="control-label">Repeat Password</label>
                           <input type="password" class="form-control" name="admin_passwordr" id="admin_passwordr" required>
                        </div>
                     </div>
                  </div>
                  <h5>Other Settings</h5>
                  <hr />
                  <div class="form-group">
                     <label for="timezone" class="control-label">Timezone</label>
                     <select name="timezone" data-live-search="true" id="timezone" class="form-control" required data-none-selected-text="Select system timezone">
                        <option value=""></option>
                        <?php foreach($this->get_timezones_list() as $key => $timezones){ ?>
                        <optgroup label="<?php echo $key; ?>">
                           <?php foreach($timezones as $timezone){ ?>
                           <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                           <?php } ?>
                        </optgroup>
                        <?php } ?>
                     </select>
                  </div>
                  <hr />
                  <div class="text-left">
                     <button type="submit" class="btn btn-success" id="installBtn">Install</button>
                  </div>
                  </form>
                  <?php } else if($step == 5){ ?>
                  <h4 class="bold">Installation successful!</h4>
                  <?php if(isset($config_copy_failed)){ ?>
                  <p class="text-danger">
                     Failed to copy application/config/app-config-sample.php. Please navigate to application/config/ and copy the file app-config-sample.php and rename it to app-config.php
                  </p>
                  <?php } ?>
                  <p>Please <b>delete the install directory</b> and login as administrator at <a href="<?php echo $_POST['base_url']; ?>admin" target="_blank"><?php echo $_POST['base_url']; ?>admin</a></p>
                  <hr />
                  <p><b style="color:red;">Remember:</b></p>
                  <ul class="list-unstyled">
                     <li>Administrators/staff members must login at <a href="<?php echo $_POST['base_url']; ?>admin" target="_blank"><?php echo $_POST['base_url']; ?>admin</a></li>
                     <li>Customers contacts must login at <a href="<?php echo $_POST['base_url']; ?>clients" target="_blank"><?php echo $_POST['base_url']; ?>clients</a></li>
                  </ul>
                  <hr />
                  <h4>
                     <b>404 Not Found After Installation? - <a href="https://help.perfexcrm.com/404-not-found-after-installation/" target="_blank">Read more</a></b>
                  </h4>
                  <hr />
                  <h4>
                     <b>Getting Started Guide - <a href="https://help.perfexcrm.com/quick-installation-getting-started-tutorial/" target="_blank">Read more</a></b>
                  </h4>
                  <hr />
                  <h4>
                     <b>Looking For Help? - <a href="https://support.perfexcrm.com/" target="_blank">Open Support Ticket</a></b>
                  </h4>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <script src='../assets/plugins/jquery/jquery.min.js'></script>
      <script src='../assets/plugins/bootstrap/js/bootstrap.min.js'></script>
      <script src='../assets/plugins/bootstrap-select/js/bootstrap-select.min.js'></script>
      <script>
         $(function(){
           $('select').selectpicker();
           $('#installForm').on('submit',function(e){
               $('#installBtn').prop('disabled',true);
               $('#installBtn').text('Please wait...');
           });
           setTimeout(function(){
             $('.sql-debug-alert').slideUp();
           },4000);
         });
      </script>
   </body>
</html>
