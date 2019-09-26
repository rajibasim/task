<?php namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;	
class push extends Model {
	
	public function insert_notification_log($data){
            $result = DB::table('notification_log')->insertGetId(
                [
                'customer_id' => "{$data['customer_id']}",
                'message' => "{$data['message']}",
                'message_type' => "{$data['message_type']}",
                ]);
            return $result;
        }

    public function insert_company_notification_log($data){
        $result = DB::table('company_notification_log')->insertGetId(
            [
            'company_id' => "{$data['company_id']}",
            'message' => "{$data['message']}",
            'message_type' => "{$data['message_type']}",
            'created_at' => date('Y-m-d H:i:s')
            ]);
        return $result;
    }

	public function view_notification_log($data){
            $log = DB::table('notification_log as log');
            $log->select('log.id as notification_log_id','log.customer_id','log.message','log.message_type'
                        ,'log.is_read','log.created_at','log.status');
            $log->where('log.status', '=', 0);
            $log->where('log.customer_id', '=',$data['company_id']);
            $result = $log->get();
            return $result;
        }

    public function view_company_notification_log($data){
        $log = DB::table('company_notification_log as log');
        $log->select('log.id as company_notification_log','log.company_id','log.message','log.message_type'
                    ,'log.is_read','log.created_at','log.status');
       // $log->where('log.is_read', '=', 0);
        $log->where('log.status', '=', 1);
        $log->where('log.company_id', '=',$data['company_id']);
        $log->where('status','=',1)->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at)=MONTH(NOW())')->count();
        $log->orderBy('id','DESC');
        $result = $log->get();
        return $result;
    }
	public function get_user_data($id){
        $result = DB::table('customer')
                ->where('id', $id)
                ->first();
        return $result;
        }
    public function get_user_data_2($id){
        $result = DB::table('customer')
                ->where('id', $id)
                ->first();
        return $result;
        }    
        
    public function sendPushAndroid($device_token, $title, $tag, $message,$data = array()) {
         // define('API_ACCESS_KEY', 'AAAAAqBUmM8:APA91bFhea3Kfe2-9V_ahrMtqKlGDS_o9wYnVvOx0adA1U1I7TaNKi8KwaGOBAzdRJ5fulmLY9hJQ-PZ2ZptfhWAWcDGPF6i_IJP9NUAufWXkVS7cydc2Y_OOWU8MV_SaqnvEOn_4G2i');
         defined('API_ACCESS_KEY') or define('API_ACCESS_KEY', 'AAAACKnW5gQ:APA91bH0rycZBA_GuHK7ZBNR1Q2CcaHWZUhNTjR_a4CRHCduXveD6SYyz_cwJ64D3h3nYXo1q7pJ_Di_NysWpE5_EBBiwGgvPCtzJeuQvD7IDRPpNF2cgD-OKK1nV7QcUYZj8ILcwV7Z');
         
         $registrationIds = $device_token;
        #prep the bundle
                $msg = array
                    (
                    'body' => $message,
                    'title' => $title,
                    //'icon'	=> 'myicon',/*Default Icon*/
                    'sound' => 'mySound', /* Default sound */
                    'tag' => $tag
                );

                $fields = array
                    (
                    'to' => $registrationIds,
                    'notification' => $msg,
                    'data'=>$msg

                );


                $headers = array
                    (
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );

        #Send Reponse To FireBase Server	
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
    }
    public function sendPushIos($Token, $message, $additional_data = array()) {
        $arrDeviceToken=array($Token);
        
        $result = '';
        $passphrase = '';

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', 'LiveShop_Distribution.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
            'content-available' => '1'
        );
       
        /*         * Append additional data* */
        if (!empty($additional_data)) {

            foreach ($additional_data as $key => $value) {

                $body[$key] = $value;
            }
        }

        // Encode the payload as JSON
        $payload = json_encode($body);

        for ($i = 0; $i < count($arrDeviceToken); $i++) {
            $deviceToken = $arrDeviceToken[$i];
            if (strpos($deviceToken, '-') !== false) {
                $deviceToken = str_replace("-", "", $deviceToken);
            }
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server

            $result = fwrite($fp, $msg, strlen($msg));
        }
        if (!$result){
            PHP_EOL;
			$msg_pdg=FALSE;
		} else{
			
            PHP_EOL;
		}	$msg_pdg=TRUE;

        fclose($fp);
		return $msg_pdg;
    }


}
