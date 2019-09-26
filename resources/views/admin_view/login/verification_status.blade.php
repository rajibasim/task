<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{{env('APP_NAME')}} Verification Status</title>
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
                                <span class="d-block">Task Manager APP </span>
                                <span>Verification Status</span></h4>
                            <div class="divider row"></div>
                            <div class="alert alert-<?=$response_data['type'];?> alert-dismissible">
                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?=$response_data['text'];?>
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
