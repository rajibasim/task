
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <title>Forgot Password</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="{{asset('public/assets/css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('public/assets/css/bootstrap-responsive.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/assets/css/matrix-login.css')}}" />
        <link href="{{asset('public/assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="loginbox"> 
            
        
		
		<?php if (Session::has('flash_errmessage')) { ?>
                   <div class="alert alert-danger alert-dismissible margin-t-10" style="margin-bottom:15px;">
                       <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                       <p><i class="icon fa fa-warning"></i><strong>Sorry!</strong> {{Session::get('flash_errmessage')}}</p>
                   </div>
               <?php } ?><!-- / Error Message -->
               <?php if (Session::has('flash_message')) { 
			   ?>
                   <div class="alert alert-success alert-dismissible margin-t-10" style="margin-bottom:15px;">
                       <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                       <p><i class="icon fa fa-check"></i><strong>Success!</strong> {{Session::get('flash_message')}}</p>
                   </div>
               <?php } ?>
		
		
		
		<?php if (Session::has('flash_message')) {
	 ?>
	                 <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
							Go back to app to login.
                        </div>
                    </div>
                </div>
	 <?php
		}else{
			   ?>
			<form id="forgot_pass_form" class="form-vertical" action="{{url('api/reset-password/'.$token)}}" method="post">
            <input type="hidden" name="id" value="{{$token}}">
            <div class="control-group">
            <div class="controls">
                <center><h2><b>KOI</b></h2>
                <h6>*Reset password</h6></center>
        </div>
            <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                            <input type="password" placeholder="Password" id="password_1" name="password_1" />
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                            <input type="password" placeholder="Retype Password" id="password_2" name="password_2" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                   <center> <span><input type="submit" class="btn btn-success" id="forgot_password" value="Reset Password"/> </span> </center>

                </div>
            </form>
		<?php } ?>
        </div>
        
<script src="{{asset('public/assets/js/jquery.min.js')}}"></script> 
<script src="{{asset('public/assets/js/jquery.ui.custom.js')}}"></script>
<script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script> 
<script src="{{asset('public/assets/js/jquery.uniform.js')}}"></script>
<script src="{{asset('public/assets/js/select2.min.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.validate.js')}}"></script>
<script src="{{asset('public/assets/js/matrix.js')}}"></script> 
<script src="{{asset('public/assets/js/matrix.popover.js')}}"></script> 
<script src="{{asset('public/assets/js/matrix.form_validation.js')}}"></script>

<script src="{{asset('public/assets/js/jquery.flot.min.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.flot.resize.min.js')}}"></script>
<script src="{{asset('public/assets/js/jquery.peity.min.js')}}"></script>


        <script>
            
          $("#forgot_pass_form").validate({
                rules:{
			password_1:{ required:true },
                        password_2:{ required:true,equalTo: '#password_1' },
                       },
                errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
        });

        </script>
    </body>

</html>
