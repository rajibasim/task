<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JD\Cloudder\Facades\Cloudder;
class function_model extends Model {

        public function img_upload($filename){
            $publicId='';
            $options=array();
            $tags=array();
            Cloudder::upload($filename,$publicId,$options,$tags);
            $getResult = Cloudder::getResult();
            return $getResult;
        }

        public function img_upload_parameterized($filename,$publicId='',$options=array(),$tags=array()){            
            Cloudder::upload($filename,$publicId,$options,$tags);
            $getResult = Cloudder::getResult();
            return $getResult;
        }
        public function upload_via_url($url){
           // $url = "http://www.example.com/sample.jpg";
            Cloudder::upload($url);
            $getResult = Cloudder::getResult();
            return $getResult;
        }
        public function delete_img($id) {
            //Cloudinary::Api.delete_resources(['image1', 'image2'], :keep_original => true)
            // Cloudder::upload($filename,$publicId,$options,$tags);
            \Cloudinary\Uploader::destroy("{$id}", array("invalidate" => TRUE));
        }

}
