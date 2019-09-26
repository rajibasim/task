<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
class common_model extends Model {
    /* ===============================Dynamic Model================================== */

    function get_all($table, $select = array(), $where = array(), $join = array(), $left = array(), $right = array(), $order = array(), $group = "", $limit = array(), $raw = "", $paging = "", $o_where = "", $having = array(), $raw_where = "") {
        $base = DB::table($table);
        if (!empty($select)) {
            $base->select($select);
        } else {
            $base->select('*');
        }
        if ($raw != "") {
            $base->select(DB::raw($raw));
        }
        if (!empty($where)) {
            foreach ($where as $wh) {
                $base->where($wh[0], $wh[1], $wh[2]);
            }
        }
        if (!empty($o_where)) {
            foreach ($o_where as $owh) {
                $base->orWhere($owh[0], $owh[1], $owh[2]);
            }
        }
        if (!empty($order)) {
            foreach ($order as $od) {
                foreach ($od as $key => $val) {
                    $base->orderby($key, $val);
                }
            }
        }
        if (!empty($left)) {
            foreach ($left as $lf) {
                $base->leftJoin($lf[0], $lf[1], $lf[2], $lf[3]);
            }
        }
        if (!empty($right)) {
            foreach ($right as $rt) {
                $base->rightjoin($rt[0], $rt[1], $rt[2], $rt[3]);
            }
        }
        if (!empty($join)) {
            foreach ($join as $jn) {
                $base->join($jn[0], $jn[1], $jn[2], $jn[3]);
            }
        }
        if ($group != "") {
            $base->groupby($group);
        }
        if (!empty($having)) {
            foreach ($having as $hv) {
                $base->having($hv[0], $hv[1], $hv[2]);
            }
        }
        // return $having;
        //return $raw;
        if (!empty($limit)) {
            foreach ($limit as $of => $lim) {
                $base->offset($of);
                $base->limit($lim);
            }
        }

        if ($raw_where != "") {

            $base->whereRaw(DB::raw($raw_where));
        }

        if ($paging != "") {
            $result = $base->paginate($paging);
        } else {

            $result = $base->get();
        }
        //print_r($result);
        //echo count($result);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
        //return $data=array('head'=>"This is laravel",'body'=>"Welcome to my laravel application");
    }

    public function insert_data($table, $data) {
        $insert_data = DB::table($table)->insert($data);
        return $insert_data;
    }

    public function insert_data_get_id($table, $data) {
        $insert_data = DB::table($table)->insertGetId($data);
        return $insert_data;
    }

    public function update_data($table, $where, $data) {
        $base = DB::table($table);
        if (!empty($where)) {
            foreach ($where as $wh) {
                $base->where($wh[0], $wh[1], $wh[2]);
            }
        }
        $result = $base->update($data);
        return $result;
    }

    public function delete_data($table, $where) {
        $base = DB::table($table);
        if (!empty($where)) {
            foreach ($where as $wh) {
                $base->where($wh[0], $wh[1], $wh[2]);
            }
        }
        $result = $base->delete();
        return $result;
    }

    /* ===============================Dynamic Model================================== */

    /*     * *************twilio**************** */

    function twilio_send_sms($to, $body) {
        // resource url & authentication

        $sid = 'AC2a07f72d1ce57ea6273091490022e789';
        $token = 'd02559e94552e0e31a2c85480dda5897';
        $from = '+1 415-651-4374';
        $uri = 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/SMS/Messages';
        $auth = $sid . ':' . $token;

        // post string (phone number format= +15554443333 ), case matters
       // $to = str_replace(['+44','+1','(',')','-',' '],"",$to);
    //    $to = "+91".$to;
        $fields = '&To=' . urlencode($to) .
                '&From=' . urlencode($from) .
                '&Body=' . urlencode($body);

        // start cURL
        $res = curl_init();

        // set cURL options
        curl_setopt($res, CURLOPT_URL, $uri);
        curl_setopt($res, CURLOPT_POST, 3); // number of fields
        curl_setopt($res, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($res, CURLOPT_USERPWD, $auth); // authenticate
        curl_setopt($res, CURLOPT_RETURNTRANSFER, true); // don't echo
        // send cURL
        $result = curl_exec($res);
        return $result;
    }

    /*     * *********************twilio*************************** */
    public function find_details_table_by_field($table_name,$field_name,$field_value,$get_result_type) {
        $result = DB::table($table_name);
                $result->where($field_name, '=', $field_value);
                if($get_result_type == 1) {
                    $value = $result->first();
                }elseif($get_result_type == 2){
                    $value = $result->get();
                }               
        return $value; 
 }


 public function convertTimeToUSERzone($str, $userTimezone, $format = 'Y-m-d H:i:s',$from=null){
    if(empty($str)){
        return "0000-00-00 00:00:00";
    }else if($str== "" || (strtoupper($str) == "N/A") || ($str== "0000-00-00 00:00:00") ||  ($str== "0000-00-00") || (strtotime($str) <= 0 )   ){
        return $str;
    }

    if($from==null){
        $new_str = new DateTime($str, new DateTimeZone(/*'America/Los_Angeles'*/env('APP_TIMEZONE')) );
    }else{
        $new_str = new DateTime($str, new DateTimeZone($from) );
    }   
    $new_str->setTimeZone(new DateTimeZone( $userTimezone ));
    return $new_str->format( $format);
}
 
}
