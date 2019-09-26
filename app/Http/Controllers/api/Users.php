<?php 
namespace App\Http\Controllers\api;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\model\common_model;
use App\model\custom_model;
use App\model\push;
use JWTAuth;
use App\User;
use JWTAuthException;
use Session;



//use App\model\function_model;
class Users extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(User $user)
	{
		//$this->middleware('auth');
		$this->user = $user;
		$this->common_model = new common_model();
		$this->custom_model = new custom_model();
	}
	/**
	 * Show the application dashboard to the users.
	 *
	 * @return Response
	 */
	public function index()
	{
		return "Welcome to ".env('APP_NAME')." api";
	}
	function decode_validator_error($input)
	{
		$error_array = json_decode($input,true);
		$msg= "";
		foreach($error_array as $k=>$v){
			$msg .= $v[0];
		}
		return $msg;
	}

	function randomWord($limit = null) 
	{

        $limt = $limit != null ? $limit : 6;
        $word = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $random = str_shuffle($word);
        $random = substr($random, rand(0, 8), $limit);
        return $random;
	}

	/***********************Custom function start******************************** */

	/* User Registration Start */
	public function registration(Request $input)
	{
		$validator = Validator::make($input->all(), [
					'name' => 'required|max:255',
					'email' => 'required|email|max:255',
					'phone' => 'required|numeric',
					'password' => 'required|min:6|max:12',
					
			]
		);
	   if ($validator->fails()) {
		   return Response::make([
					   'result' => false,
					   'message' => $this->decode_validator_error($validator->errors())					
		   ]);
	   }
	   else
	   {
			$check_if_exists = $this->common_model->get_all('users',array(),array(), $join = array(), $left = array(), $right = array(), $order = array(), $group = "", $limit = array(), $raw = "", $paging = "", $o_where = "", $having = array(), $raw_where = "(users.email = '".$input->input('email')."') OR (users.phone = '".$input->input('phone')."')");
			if(!empty($check_if_exists) && is_array($check_if_exists)){
				return Response::make([
					'result' => false
					,'message' => 'Email/Phone already registered.'
				]);
			}
			else
			{

				$new_user_data = $input->only('name','email','phone');
				$new_user_data['password'] = bcrypt($input->input('password'));
				$new_user_data['status'] = 0;  
				$new_user_data['created_at'] = date('Y-m-d H:i:s');
				//$new_user_data['updated_at'] = date('Y-m-d H:i:s');
				$new_user_id = $this->common_model->insert_data_get_id('users',$new_user_data);

				if($new_user_id)
				{
					//Send verification email
					$email = $new_user_data['email']; 
					$url = Crypt::encrypt($new_user_id);
	                $redirect_url = url('user-verification/'.$url); 
	                $emailData['redirect_url'] = $redirect_url;
					try{
	                    Mail::send('email.vrification_email_trmplate',$emailData,function($message) use($email)
	                    {
	                        $message->from('info@taskmanager.com', 'Task Manager');
	                        $message->subject('Verification Link');
	                        $message->to($email);
	                    });
	                }
	                catch(\Exception $e){
	                    return Response::make([
							'result' => false,
							'message' => 'Email sending faild.'					
						]);
	                }

					return Response::make([
						'result' => true,
						'id'=> $new_user_id,
						'message' => 'Verification mail send to your email.Please verify for continue login.'					
					]);
				}
				else
				{
					return Response::make([
						'result' => false,
						'message' => 'Unable to register users.'					
					]);
				}
			}
	   }
	}
	/* User Registration End */
	/*User/User email Verification*/
    public function userVerification(Request $request, $user_id)
    {
        $user_id = Crypt::decrypt($user_id);
        $check_email_verification = $this->common_model->get_all($table = "users", $select = array('*'), $where = array(array('id','=',$user_id), array('is_email_verified','=', 1)), $join = array(), $left = array(), $right = array(), $order = array(), $group = "", $limit = array(), $raw = "", $paging = "");
        if($check_email_verification)
        {
            $response_data = ['type'=>'danger','text'=>'Already verified.'];
        }
        else
        {
            $save_data['is_email_verified'] = 1;
            $save_data['status'] = 1;
            $save_data['email_verified_time'] = date('Y-m-d H:i:s');
            $update = $this->common_model->update_data($table = 'users', array(array('id', '=', $user_id)), $data = $save_data);
            $response_data = ['type'=>'success','text'=>'Successfully verified.'];
        }

        return view('admin_view.login.verification_status', ['response_data' =>  $response_data]);
    }
    /*User/User email Verification*/
	/* User Forgot Password Start */
	public function forgot_password(Request $input)
	{
		$validator = Validator::make($input->all(), [
					'email' => 'required'
				]
			);
		if ($validator->fails()) {
			return Response::make([
							'result' => false,
							'message' => $this->decode_validator_error($validator->errors())					
				]);
		}
		else
		{
			$check_if_exists = $this->common_model->get_all('users',array(),array(
					array('users.email','=',$input->input('email'))
				));
			if($check_if_exists)
			{
				$email = $input->input('email');
				$url = time().'-'.$email;
                $url = Crypt::encrypt($url);
                $redirect_url = url('reset-password/'.$url);  
                $emailData['redirect_url'] = $redirect_url;
                try{
                	//print_r($emailData); die();
                    Mail::send('email.reset_password_template',$emailData,function($message) use($email)
                    {
                        $message->from('info@taskmanager.com', 'Task Manager');
                        $message->subject('Reset Password Link');
                        $message->to($email);
                    });

                    return Response::make([
						'result' => true
						,'message' => 'An email has been sent to your registered email. Please check.'
					]);
                }
                catch(\Exception $e){
                    $response_data=['type'=>'error','text'=>'Email sending faild'];
                }
			}
			else
			{
				return Response::make([
					'result' => false
					,'message' => 'Email not registered.'
				]);
			}
		}
	}
	/* User Forgot Password End */
	/* User Reset Start */
	public function reset_password($token,Request $input)
	{
		$token_array = explode('||',base64_decode($token));
		$check_if_exists = $this->common_model->get_all('users',array(),array(
			array('users.email','=',$token_array[0])
		));
		if($check_if_exists && is_array($check_if_exists)){
			if ($input->isMethod('post'))
			{
				$validator = Validator::make($input->all(), [
				'password' => 'required'
						]
					);
				if ($validator->fails()) {
					Session::flash('flash_errmessage','Data missing.');
					return redirect(url('api/reset-password/'.$token)); 
				}else{
					
					$new_user_data=array();
					$new_user_data['password'] = bcrypt($input->input('password'));
					$new_user_data['updated_at'] = date('Y-m-d H:i:s');
					$update = $this->common_model->update_data('users',array(array('id','=',$user_id)),$new_user_data);
					if($update){
						Session::flash('flash_message','Password updated successfully.');
						return redirect(url('api/reset-password/'.$token)); 
					}else{
						Session::flash('flash_errmessage','Unable to update password.');
						return redirect(url('api/reset-password/'.$token)); 
					}
				}
			}else{
				return view('reset',['token'=>$token]);
			}
		}else{
			return Response::make([
				'result' => false
				,'message' => 'Request not valid.'
			]);
		}
	}
	/* User Reset Password End */
	/* User Login Start */
	public function login(Request $request)
	{
        $validator = Validator::make($request->all(), [
        	//'social_type' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            //'device_type'=>'required',
            //'push_key'=>'required',
            ]
        );
        if ($validator->fails())
        {
            $errors = $this->decode_validator_error($validator->messages());
            return response()->json(['result'=>false,'message'=>$errors]);
        }
        else
        {
            $credentials = $request->only('email','password');
            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    //return response()->json(['invalid_email_or_password'], 422);
                    $msg = 'Invalid login credential';
                    return response()->json(['result'=>false,'message'=>$msg]);
                }
                else
                {
                    $userDetails = JWTAuth::toUser($token);
                    if($userDetails->status == 1)
                    {
                    	$update_data['push_key'] = $request->input('push_key');
						$update_data['device_type'] = $request->input('device_type');
						$update_data['updated_at'] = date('Y-m-d H:i:s');

						$update = $this->common_model->update_data('users',array(array('id','=',$userDetails->id)),$update_data);

                    	unset($userDetails['password']);

                    	$data = array(
                    			'token' => $token,
								'id' => $userDetails->id,
								'name' => $userDetails->name,
								'email' => $userDetails->email,
								'phone' => $userDetails->phone,
								'occupation' => $userDetails->occupation,
								'profile_image' => $userDetails->profile_image ? url('public/upload/profile_image').'/'.$userDetails->profile_image : '',
								'occupation' => $userDetails->occupation,
								'occupation' => $userDetails->occupation,
						);
                    	
                    	$msg = 'Logged in successfully';
                        return response()->json(['result'=>true,'message'=>$msg,'data'=>$data]);
                    }
                    else
                    {
                        $msg = 'Your account is not active';
                        return response()->json(['result'=>false,'message'=>$msg]);
                    }
                    
                }
            } catch (JWTAuthException $e) {
                $msg = 'Failed to generate token';
                return response()->json(['result'=>false,'message'=>$msg]);
            }
        }
    }
	/* User Login End */
	/* User Details Using Token Autentication Start */
	public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->header('token'));
        return response()->json(['result' => $user]);
    }
    /* User Details Using Token Autentication End */
    /* User Logout Start */
    public function logout(Request $request){
        JWTAuth::invalidate($request->header('token'));
        return response()->json(['result'=>true,'message'=>'Logged out Successfully.']);
    }
    /* User Logout End */
    
	/* User Password Update */
	public function update_password(Request $input)
	{
		$userDetails = JWTAuth::toUser($input->header('token'));
		$user_id = $userDetails->id;
		
		$validator = Validator::make($input->all(), [
				 'password' => 'required'
				,'new_password' => 'required'
			]
		);
	   if ($validator->fails()) {
		   return Response::make([
					   'result' => false,
					   'message' => $this->decode_validator_error($validator->errors())					
		   ]);
	   }else{
			$get_user = $this->common_model->get_all('users',array(),array(
				array('users.id', '=', $user_id)
			));


			$new_user_data=array();
			$new_user_data['password'] = bcrypt($input->input('new_password'));
			$new_user_data['updated_at'] = date('Y-m-d H:i:s');
			$update = $this->common_model->update_data('users',array(array('id','=',$user_id)),$new_user_data);
			if($update){
				return Response::make([
					'result' => true,
					'message' => 'Password updated Successfully.'					
				]);
			}else{
				return Response::make([
					'result' => false,
					'message' => 'Password to update profile.'					
				]);
			}
	   }
	}
	/* User Password Update End */

	/*********User Password Reset***********/
    public function userResetPassword(Request $request, $user_data)
    {
        $user_data = $user_data;
        return view('admin_view.login.user-reset-password', ['user_data' =>  $user_data]);
    }
    
    /*********User Password Reset***********/

    /*********User password reset process*****/
    public function userResetPasswordProcess(Request $input)
    {
        $password = $input->input('password');
        $confirm_password = $input->input('confirm_password');
        if($password && $confirm_password)
        {
            if($password==$confirm_password)
            {
                $user_data = $input->input('user_data'); 
                $user_data = Crypt::decrypt($user_data);
                $user_data = explode('-', $user_data);
                $time = $user_data[0];
                $email = $user_data[1]; 
                //validate time
                $endTime = strtotime("+30 minutes", strtotime($time));
                $vali_till = $time+$endTime; 
                if($vali_till > time())
                {
                    $admin_data['password'] = bcrypt($password);
                    $update = $this->common_model->update_data('users', array(array('email','=',$email)), $admin_data);
                    if($update)
                    {
                        $response_data=['type'=>'success','text'=>'Successfully updated.'];
                        return redirect('/reset-password/'.$input->input('user_data'))->with('message',$response_data);
                    }
                    else
                    {
                        $response_data=['type'=>'danger','text'=>'Invalid reset link.'];
                        return redirect('/reset-password/'.$input->input('user_data'))->with('message',$response_data);
                    }
                }
                else
                {
                    $response_data=['type'=>'danger','text'=>'Invalid reset link.'];
                    return redirect('/reset-password/'.$input->input('user_data'))->with('message',$response_data);
                }
            }
            else
            {
                $response_data=['type'=>'danger','text'=>'Password & confirm password not match.'];
                return redirect('/reset-password/'.$input->input('user_data'))->with('message',$response_data);
            }
        }
        else
        {
            $response_data=['type'=>'danger','text'=>'Password & confirm password required.'];
            return redirect('/reset-password/'.$input->input('user_data'))->with('message',$response_data);
        }
    }
    
    /*********User password reset process*****/

    /* User Profile Update Start */
	public function updateProfile(Request $input)
	{
		$userDetails = JWTAuth::toUser($input->header('token'));
		$user_id = $userDetails->id;
		$new_user_data = array();
		if($input->input('name') && $input->input('name')!=""){
			$new_user_data['name'] = $input->input('name');
		}
		if($input->input('address') && $input->input('address')!=""){
			$new_user_data['address'] = $input->input('address');
		}
		if($input->input('occupation') && $input->input('occupation')!=""){
			$new_user_data['occupation'] = $input->input('occupation');
		}
		if($input->input('about') && $input->input('about')!=""){
			$new_user_data['about'] = $input->input('about');
		}
		
		/***********************File upload code********************** */
        if ($input->hasFile('profile_image'))
        {
            $file = $input->file('profile_image');
            $image = time().$file->getClientOriginalName();
            $destinationPath = public_path() . '/upload/profile_image/';
            $file->move($destinationPath, $image);	

            $new_user_data['profile_image'] = $image;				
        }
        /*********************File upload code**************************** */

		$new_user_data['updated_at'] = date('Y-m-d H:i:s');
		$update = $this->common_model->update_data('users',array(array('id','=',$user_id)),$new_user_data);
		$updated_user_data = $this->common_model->get_all('users',array(),array(
			array('users.id','=',$user_id)
		));

		$updated_user_data = $updated_user_data[0];

		$user_data = array(
				'id' => $updated_user_data->id,
				'name' => $updated_user_data->name,
				'email' => $updated_user_data->email,
				'phone' => $updated_user_data->phone,
				'occupation' => $updated_user_data->occupation,
				'profile_image' => $updated_user_data->profile_image ? url('public/upload/profile_image').'/'.$updated_user_data->profile_image : '',
				'occupation' => $updated_user_data->occupation,
				'occupation' => $updated_user_data->occupation,
		);

		if($update){
			return Response::make([
				'result' => true,
				'user_data'=> $user_data,
				'message' => 'profile updated Successfully.'					
			]);
		}else{
			return Response::make([
				'result' => false,
				'message' => 'Unable to update profile.'					
			]);
		}
	}
	/* User Profile Update End */

	/* User Conttact list */
	public function contactList(Request $input)
	{
		$userDetails = JWTAuth::toUser($input->header('token'));
		$user_id = $userDetails->id;

		$validator = Validator::make($input->all(), [
				 'contacts' => 'required',
			]
		);
	    if ($validator->fails())
	    {
		   return Response::make([
					   'result' => false,
					   'message' => $this->decode_validator_error($validator->errors())	
		   ]);
		}	

		$contacts = $input->input('contacts');
		$contacts = explode(',', $contacts);
		

		$contact_array = array();
		if($contacts)
		{
			foreach ($contacts as $key => $value)
			{
				$exist = $this->common_model->get_all("users", $select = array('*'), $where = array(array('phone', '=', $value)), $join = array(), $left = array(), $right = array(), $order = array(), $group = "", $limit = array(), $raw = "", $paging = "");
				if($exist)
				{
					$contact_array[] = array(
							'id' => $exist[0]->id,
							'name' => $exist[0]->name,
							'phone' => $exist[0]->phone,
					);
				}
			}
		}
		
		if($contact_array)
		{
			return Response::make([
				'result' => true,
				'contact_list' => $contact_array,
				'message' => ''					
			]);
		}
		else
		{
			return Response::make([
				'result' => false,
				'message' => 'No user found.'					
			]);
		}		
	}
	/* User Conttact list */

	/* User Conttact list */
	/*public function addTask(Request $input)
	{
		$userDetails = JWTAuth::toUser($input->header('token'));
		$user_id = $userDetails->id;

		$validator = Validator::make($input->all(), [
				 'description' => 'required',
				 'assing_to' => 'required',
				 'end_date' => 'required',
				 'task_status' => 'required',
			]
		);
	    if ($validator->fails())
	    {
		   return Response::make([
					   'result' => false,
					   'message' => $this->decode_validator_error($validator->errors())	
		   ]);
		}	

		$contacts = $input->input('contacts');
		$contacts = explode(',', $contacts);		
	}*/
	/* User Conttact list */

}
