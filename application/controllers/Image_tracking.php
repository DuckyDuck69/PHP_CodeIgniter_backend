<?php

class Image_tracking extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("track_model", "track");
    }
    public function track_open(){

        $id = $this->input->get("id", true);
        if($id){
            log_message('debug', "Image tracking endpoint accessed");
            try{
                $ok = $this->track->record_verify_email_opened($id);
                log_message('debug', 'track_open: id='.$id.' result=' . ($ok ? 'modified' : 'not-modified'));
                log_message('debug', "Successfully update email opened");
            }catch(Exception $e){
                log_message("error","Can't load image: ".$e->getMessage());
            }
        }else {
            log_message('error', 'track_open: missing/invalid id');
        }
        header( 'Content-Type: image/gif');
        header('Content-Disposition: inline; filename="pixel.gif"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $im = imagecreatetruecolor(1, 1);
        imagesavealpha($im, true);
        $trans_colour = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $trans_colour);
        imagegif($im);
        imagedestroy($im);
    }

}