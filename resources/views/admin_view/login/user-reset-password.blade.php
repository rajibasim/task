<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{{env('APP_NAME')}} Reset Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
<meta name="description" content="">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('public/backend/css/font-awesome.min.css')}}">
<link href="{{asset('public/backend/css/login.css')}}" rel="stylesheet"></head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
                            <h4 class="mb-0">
                                <span class="d-block">Task Management</span>
                                </h4>
                            <div class="divider row"></div>
                           @if(session('message'))
                            <div class="alert alert-{{session('message.type')}} alert-dismissible">
                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{session('message.text')}}
                            </div>
                            @endif
                            <div <?=session('message.type')=="success" ? 'style="display: none;"' : ''; ?>>
                                <form name="loginform" id="loginform" class="" action="{{url('user-reset-password-process')}}" method="post" >
                                    <div class="form-row">
                                        
                                        <input type="hidden" name="user_data" value="<?=$user_data;?>">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Password</label>
                                              <input type="password" placeholder="Password" id="password" name="password" class="form-control" value="" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Confirmm Password</label>
                                              <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" class="form-control" value="" required="" />
                                            </div>
                                        </div>
                                

                                    </div>
                                    <div class="position-relative form-check">
                                    <div class="divider row"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="ml-auto">
                                          <input type="submit" class="btn btn-primary btn-lg" id="admin_login_submit" value="Send"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" src="{{asset('public/backend/js/login.js')}}"></script>
<script src="{{asset('public/backend/js/jquery.min.js')}}"></script>

</body>
</html>
