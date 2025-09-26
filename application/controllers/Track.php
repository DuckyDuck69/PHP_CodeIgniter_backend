<?php
/**
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Loader $load
 * @property Track_model $track
 */
class Track extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("Track_model", 'track');
    }

    //1x1 image tracker
    public function user_opened(){
        $id = $this->input->get('id', TRUE);

        log_message('info', "Tracking pixel requested for ID: " . ($id ?: 'null'));

        if($id){
            // GET /index.php/track/user_opened?id=<mongoId>
            $updated = $this->track->update_open_tracking($id);
            log_message('info', "Tracking update result: " . ($updated ? 'success' : 'failed'));
        }

        //1x1 transparent PNG (base64)
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=');
        
        //ouput: CodeIgniter output library: handle the final response to the browser
        $this->output  
            ->set_header('Content-Type: image/png') //add HTTP img header 
            ->set_header('Cache-Control: no-cache, no-store, must-revalidate') 
            ->set_header('Pragma: no-cache')
            ->set_header('Expires: 0')
            ->set_header('Content-Length: ' . strlen($png))
            ->set_output($png);
        exit(); 
    }

    //PDF tracker
    public function pdf(){
        $id = $this->input->get('id', TRUE);
        if ($id){ 
            $this->track->record_pdf_click($id); 
        }

        $pdfPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'secret_file.pdf';
        if (!is_file($pdfPath)) { show_404(); return; }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/pdf')
            ->set_header('Content-Disposition: inline; filename="secret_file.pdf"')
            ->set_header('Content-Length: ' . filesize($pdfPath))
            ->set_header('Cache-Control: private, max-age=0, must-revalidate')
            ->set_output(file_get_contents($pdfPath));
        return;

    }
     
}