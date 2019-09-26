<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>{{env('APP_NAME')}} Admin</title><meta charset="UTF-8" />
        
        
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="{{asset('public/backend/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{asset('public/backend/js/jquery-migrate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/backend/js/jarallax.js')}}"></script>
<script type="text/javascript" src="{{asset('public/backend/js/jquery.mCustomScrollbar.min.js')}}"></script>
<script src="{{asset('public/backend/js/jquery.mousewheel.min.js')}}"></script>
<!--[if lt IE 8]>
<script type='text/javascript' src='http://demo.accesspressthemes.com/wordpress-plugins/wp-admin-white-label-login/template-1/wp-includes/js/json2.min.js?ver=2015-05-03'></script>
<![endif]-->
<script type="text/javascript" src="{{asset('public/backend/js/underscore.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/backend/js/wpawll-style-login.js')}}"></script>

<link href="{{asset('public/backend/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" id="dashicons-css" href="{{asset('public/backend/css/dashicons.min.css')}}" type="text/css" media="all">
<link rel="stylesheet" id="buttons-css" href="{{asset('public/backend/css/buttons.min.css')}}" type="text/css" media="all">
<link rel="stylesheet" id="login-css" href="{{asset('public/backend/css/login.min.css')}}" type="text/css" media="all">
<link rel="stylesheet" id="wpawll-custom-login-css" href="{{asset('public/backend/css/wpawll-style-login.css')}}" type="text/css" media="all">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
    <body>
        
        
        
        
<div class="login login-action-login wp-core-ui  locale-en-us wpawll-login-active">
<div class="wpawll-wrapper wpawll-template-1 wpawll-jarallax-image" data-imgsrc="http://runmobileapps.com/koi/public/backend/img/login-bg.jpg" data-imgsize="cover" data-imgrepeat="no-repeat" data-imgposition="center center" style="z-index: 0;">
  <div class="wpawll-content-wrapper">
    <div class="wpawll-content-1">
      <div class="wpawll-additional-content">
        <div class="wpawll-additional-content-template-1">
          <div class="wpawll-headline">Login to Your Account </div>
          <!--<div class="wpawll-tagline">Login and access to your account. Build meaningful relationships between</div>-->
        </div>
      </div>
    </div>
    <div class="wpawll-content-2">
      <div class="wpawll-login-form-wrapper">
        <div class="wpawll-logo"> <a href="#"><img src="http://runmobileapps.com/koi/public/backend/img/logo.png" alt="" style="height:70px;margin-bottom:20px;"/></a> </div>
        <div class="wpawll-login">
        
          <div id="loginbox"> 
            
        @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif


        @if(session('message'))
        <div class="alert alert-{{session('message.type')}}">
            {{session('message.text')}}
        </div>
        @endif
        </div>
        </div>
      </div>
    </div>
  </div>
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
</div>
</div>
<script src="{{asset('public/backend/js/jquery.min.js')}}"></script>
</body>
</html>
