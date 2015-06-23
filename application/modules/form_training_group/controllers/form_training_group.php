<?php defined('BASEPATH') OR exit('No direct script access allowed');

class form_training_group extends MX_Controller {

  public $data;
    function __construct()
    {
        parent::__construct();
        $this->load->library('authentication', NULL, 'ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        $this->load->database();
    $this->load->model('person/person_model','person_model');
        $this->load->model('form_training_group/form_training_group_model','form_training_group_model');
        
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
        
    }

    function index($ftitle = "fn:",$sort_by = "id", $sort_order = "asc", $offset = 0)
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        else
        {
            $sess_id= $this->data['sess_id'] = $this->session->userdata('user_id');
            $this->data['sess_nik'] = $sess_nik = get_nik($sess_id);


            //set sort order
            $this->data['sort_order'] = $sort_order;
            
            //set sort by
            $this->data['sort_by'] = $sort_by;
           
            //set filter by title
            $this->data['ftitle_param'] = $ftitle; 
            $exp_ftitle = explode(":",$ftitle);
            $ftitle_re = str_replace("_", " ", $exp_ftitle[1]);
            $ftitle_post = (strlen($ftitle_re) > 0) ? array('form_training_group.title'=>$ftitle_re) : array() ;
            
            //set default limit in var $config['list_limit'] at application/config/ion_auth.php 
            $this->data['limit'] = $limit = (strlen($this->input->post('limit')) > 0) ? $this->input->post('limit') : 10 ;

            $this->data['offset'] = 6;

            //list of filterize all form_training_group  
            $this->data['form_training_group_all'] = $this->form_training_group_model->like($ftitle_post)->where('is_deleted',0)->form_training_group()->result();
            
            $this->data['num_rows_all'] = $this->form_training_group_model->like($ftitle_post)->where('is_deleted',0)->form_training_group()->num_rows();

            $form_training_group = $this->data['form_training_group'] = $this->form_training_group_model->like($ftitle_post)->where('is_deleted',0)->limit($limit)->offset($offset)->order_by($sort_by, $sort_order)->form_training_group()->result();
            $this->data['_num_rows'] = $this->form_training_group_model->like($ftitle_post)->where('is_deleted',0)->limit($limit)->offset($offset)->order_by($sort_by, $sort_order)->form_training_group()->num_rows();
            
             //config pagination
             $config['base_url'] = base_url().'form_training_group/index/fn:'.$exp_ftitle[1].'/'.$sort_by.'/'.$sort_order.'/';
             $config['total_rows'] = $this->data['num_rows_all'];
             $config['per_page'] = $limit;
             $config['uri_segment'] = 6;

            //inisialisasi config
             $this->pagination->initialize($config);

            //create pagination
            $this->data['halaman'] = $this->pagination->create_links();

            $this->data['ftitle_search'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('title'),
            );

            $this->_render_page('form_training_group/index', $this->data);
        }
    }

    function detail($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        else
        {
            $user_id= getValue('user_pengaju_id', 'users_training_group', array('id'=>'where/'.$id));
            $this->data['user_nik'] = $sess_nik = get_nik($user_id);
            $this->data['sess_id'] = $this->session->userdata('user_id');

            $form_training_group = $this->data['form_training_group'] = $this->form_training_group_model->form_training_group($id)->result($id);
            $this->data['_num_rows'] = $this->form_training_group_model->form_training_group($id)->num_rows($id);

            $this->data['training_type'] = GetAll('training_type', array('is_deleted' => 'where/0'));
            $this->data['penyelenggara'] = GetAll('penyelenggara', array('is_deleted' => 'where/0'));
            $this->data['pembiayaan'] = GetAll('pembiayaan', array('is_deleted' => 'where/0'));
            $this->data['approval_status'] = GetAll('approval_status', array('is_deleted'=>'where/0'));

            $this->_render_page('form_training_group/detail', $this->data);
        }
    }

    function input()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        $sess_id = $this->data['sess_id'] = $this->session->userdata('user_id');
        $sess_nik = $this->data['sess_nik'] = get_nik($sess_id);
        $form_training_group = $this->data['training'] = $this->form_training_group_model->form_training_group($sess_id);

        $this->data['all_users'] = getAll('users', array('active'=>'where/1'));
        $this->data['subordinate'] = getAll('users', array('superior_id'=>'where/'.get_nik($sess_id)));


        $this->_render_page('form_training_group/input', $this->data);
    }

    function add()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $this->form_validation->set_rules('training_name', 'Nama Program Pelatihan', 'trim|required');
            $this->form_validation->set_rules('tujuan_training', 'Tujuan Pelatihan', 'trim|required');

            if($this->form_validation->run() == FALSE)
            {
            echo json_encode(array('st'=>0, 'errors'=>validation_errors('<div class="alert alert-danger" role="alert">', '</div>')));
            }
            else
            {
                $user_id= $this->input->post('emp');

                $data = array(
                    'user_peserta_id' => implode(',',$this->input->post('peserta')),
                    'id_comp_session' => 1,
                    'training_name' => $this->input->post('training_name'),
                    'tujuan_training' => $this->input->post('tujuan_training'),
                    'user_app_lv1'          => $this->input->post('atasan1'),
                    'user_app_lv2'          => $this->input->post('atasan2'),
                    'user_app_lv3'          => $this->input->post('atasan3'),
                    'created_on'            => date('Y-m-d',strtotime('now')),
                    'created_by'            => $this->session->userdata('user_id'),
                    );

                $num_rows = getAll('users_training_group', array('is_deleted'=>'where/0'))->num_rows();
                $peserta_id = implode(',',$this->input->post('peserta'));
                $peserta_id = explode(',',$peserta_id);
               
                if($num_rows>0){
                    $training_id = $this->db->select('id')->order_by('id', 'asc')->get('users_training_group')->last_row();
                    $training_id = $training_id->id+1;
                }else{
                    $training_id = 1;
                }

                    if ($this->form_validation->run() == true && $this->form_training_group_model->create_($user_id, $data))
                    {
                        $this->send_approval_request($training_id, $user_id);
                        $this->send_peserta_mail($training_id, $user_id, $peserta_id);
                        echo json_encode(array('st' =>1));     
                    }
            }

        }
    }

    function do_approve($id, $type)
    {
        if(!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $user_id = get_nik($this->session->userdata('user_id'));
            $date_now = date('Y-m-d');

            $data = array(
            'is_app_'.$type => 1,
            'approval_status_id_'.$type => $this->input->post('app_status_'.$type),
            'date_app_'.$type => $date_now,
            'note_app_'.$type => $this->input->post('note_'.$type)
            );
                
            $is_app = getValue('is_app_'.$type, 'users_training_group', array('id'=>'where/'.$id));
            $approval_status = $this->input->post('app_status_'.$type);

           if ($this->form_training_group_model->update($id,$data)) {
                return TRUE;
            }

            if($is_app==0){
                $this->approval_mail($id, $approval_status);
            }else{
                $this->update_approval_mail($id, $approval_status);
            }
        }
    }

    function do_approve_hrd($id)
    {
        $user_id = $this->session->userdata('user_id');
        $date_now = date('Y-m-d');

        $data = array(
        'training_type_id' => $this->input->post('training_type'),
        'penyelenggara_id' => $this->input->post('penyelenggara'),
        'pembiayaan_id' => $this->input->post('pembiayaan'),
        'besar_biaya' => $this->input->post('besar_biaya'),
        'tempat' => $this->input->post('tempat'),
        'narasumber' => $this->input->post('narasumber'),
        'vendor' => $this->input->post('vendor'),
        'tanggal_mulai'=> date('Y-m-d',strtotime($this->input->post('tanggal_mulai'))),
        'tanggal_akhir'=> date('Y-m-d',strtotime($this->input->post('tanggal_akhir'))),
        'lama_training_bulan' => $this->input->post('lama_training_bulan'),
        'lama_training_hari' => $this->input->post('lama_training_hari'),
        'jam_mulai'   => $this->input->post('jam_mulai'),
        'jam_akhir'   => $this->input->post('jam_akhir'),
        'is_app_hrd' => 1,
        'approval_status_id_hrd' => $this->input->post('app_status'),
        'note_app_hrd' => $this->input->post('note_hrd'), 
        'user_app_hrd' => $user_id, 
        'date_app_hrd' => $date_now);

        $is_app = getValue('is_app_hrd', 'users_training_group', array('id'=>'where/'.$id));
        $approval_status = $this->input->post('app_status');

        if ($this->form_training_group_model->update($id,$data)) {
           return TRUE;
        }

        if($is_app==0){
            $this->approval_mail($id, $approval_status);
        }else{
            $this->update_approval_mail($id, $approval_status);
        }
            
    }

    function send_approval_request($id, $user_id)
    {
        $url = base_url().'form_training_group/detail/'.$id;
        $user_app_lv1 = getValue('user_app_lv1', 'users_training_group', array('id'=>'where/'.$id));
        $user_app_lv2 = getValue('user_app_lv2', 'users_training_group', array('id'=>'where/'.$id));
        $user_app_lv3 = getValue('user_app_lv3', 'users_training_group', array('id'=>'where/'.$id));
        //approval to LV1
        if(!empty($user_app_lv1)){
            $data1 = array(
                    'sender_id' => get_nik($user_id),
                    'receiver_id' => $user_app_lv1,
                    'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                    'subject' => 'Pengajuan Training Group',
                    'email_body' => get_name($user_id).' mengajukan permohonan pelatihan Group, untuk melihat detail silakan <a class="klikmail" href='.$url.'>Klik Disini</a><br />'.$this->detail_email($id),
                    'is_read' => 0,
                );
            $this->db->insert('email', $data1);
        }

        //approval to LV2
        if(!empty($user_app_lv2)){
            $data2 = array(
                    'sender_id' => get_nik($user_id),
                    'receiver_id' => $user_app_lv2,
                    'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                    'subject' => 'Pengajuan Training Group',
                    'email_body' => get_name($user_id).' mengajukan permohonan pelatihan Group, untuk melihat detail silakan <a class="klikmail" href='.$url.'>Klik Disini</a><br />'.$this->detail_email($id),
                    'is_read' => 0,
                );
            $this->db->insert('email', $data2);
        }

        //approval to LV3
        if(!empty($user_app_lv3)){
            $data3 = array(
                    'sender_id' => get_nik($user_id),
                    'receiver_id' => $user_app_lv3,
                    'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                    'subject' => 'Pengajuan Training',
                    'email_body' => get_name($user_id).' mengajukan permohonan pelatihan Group, untuk melihat detail silakan <a class="klikmail" href='.$url.'>Klik Disini</a><br />'.$this->detail_email($id),
                    'is_read' => 0,
                );
            $this->db->insert('email', $data3);
        }

        //approval to hrd
            $data4 = array(
                    'sender_id' => get_nik($user_id),
                    'receiver_id' => 1,
                    'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                    'subject' => 'Pengajuan Training',
                    'email_body' => get_name($user_id).' mengajukan permohonan pelatihan Group, untuk melihat detail silakan <a class="klikmail" href='.$url.'>Klik Disini</a><br />'.$this->detail_email($id),
                    'is_read' => 0,
                );
            $this->db->insert('email', $data4);

    }

    function send_peserta_mail($id, $sender_id, $peserta_id = array())
    {
        $url = base_url().'form_training_group/detail';
        for($i=0;$i<sizeof($peserta_id);$i++):
        $data = array(
                'sender_id' => get_nik($sender_id),
                'receiver_id' => get_nik($peserta_id[$i]),
                'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                'subject' => 'Pengajuan Training Group',
                'email_body' => get_name($sender_id).' mengajukan permohonan pelatihan group untuk anda, untuk melihat detail silakan <a class="klikmail" href='.$url.'/'.$id.'>Klik Disini</a><br/>'.$this->detail_email($id),
                'is_read' => 0,
            );
        $this->db->insert('email', $data);
        endfor;
    }

    function approval_mail($id, $approval_status)
    {
        $url = base_url().'form_training_group/detail/'.$id;
        $approver = get_name($this->session->userdata('user_id'));
        $pengaju_id = getValue('user_pengaju_id', 'users_training_group', array('id'=>'where/'.$id));
        $approval_status = getValue('title', 'approval_status', array('id'=>'where/'.$approval_status));
        $data = array(
                'sender_id' => get_nik($this->session->userdata('user_id')),
                'receiver_id' => get_nik($pengaju_id),
                'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                'subject' => 'Status Pengajuan Permintaan Training Group dari Atasan',
                'email_body' => "Status pengajuan permohonan training anda $approval_status oleh $approver untuk detail silakan <a class='klikmail' href=$url>Klik disini</a><br/>".$this->detail_email($id),
                'is_read' => 0,
            );
        $this->db->insert('email', $data);
    }

    function update_approval_mail($id, $approval_status)
    {
        $url = base_url().'form_training_group/detail/'.$id;
        $approver = get_name(get_nik($this->session->userdata('user_id')));
        $pengaju_id = getValue('user_pengaju_id', 'users_training_group', array('id'=>'where/'.$id));
        $approval_status = getValue('title', 'approval_status', array('id'=>'where/'.$approval_status));
        $data = array(
                'sender_id' => get_nik($this->session->userdata('user_id')),
                'receiver_id' => get_nik($pengaju_id),
                'sent_on' => date('Y-m-d-H-i-s',strtotime('now')),
                'subject' => 'Perubahan Status Pengajuan Permintaan Training  Groupdari Atasan',
                'email_body' => "$approver melakukan perubahan status permintaan training group anda, Status permintaan anda kini $approval_status, untuk detail silakan <a class='klikmail' href=$url>Klik disini</a><br/>".$this->detail_email($id),
                'is_read' => 0,
            );
        $this->db->insert('email', $data);
    }

    function detail_email($id)
    {
        
        $user_id= getValue('user_pengaju_id', 'users_training_group', array('id'=>'where/'.$id));
        $this->data['user_nik'] = $sess_nik = get_nik($user_id);
        $this->data['sess_id'] = $this->session->userdata('user_id');

        $form_training_group = $this->data['form_training_group'] = $this->form_training_group_model->form_training_group($id)->result($id);
        $this->data['_num_rows'] = $this->form_training_group_model->form_training_group($id)->num_rows($id);
        

        $this->data['training_type'] = GetAll('training_type', array('is_deleted' => 'where/0'));
        $this->data['penyelenggara'] = GetAll('penyelenggara', array('is_deleted' => 'where/0'));
        $this->data['pembiayaan'] = GetAll('pembiayaan', array('is_deleted' => 'where/0'));
        $this->data['approval_status'] = GetAll('approval_status', array('is_deleted'=>'where/0'));

        
        
        

        return $this->load->view('form_training_group/training_mail', $this->data, TRUE);
    }

    function get_subordinate($id)
    {
        $this->data['subordinate'] = getAll('users', array('superior_id'=>'where/'.get_nik($id)));
        $this->load->view('radio_subordinate',$this->data);
    }

    public function get_emp_org()
    {
        $id = $this->input->post('id');

        $url = get_api_key().'users/employement/EMPLID/'.get_nik($id).'/format/json';
            $headers = get_headers($url);
            $response = substr($headers[0], 9, 3);
            if ($response != "404") {
                $getuser_info = file_get_contents($url);
                $user_info = json_decode($getuser_info, true);
                $org_nm = $user_info['ORGANIZATION'];
            } else {
                $org_nm = '';
            }
        
        echo $org_nm;
    }

    public function get_emp_pos()
    {
        $id = $this->input->post('id');

        $url = get_api_key().'users/employement/EMPLID/'.get_nik($id).'/format/json';
            $headers = get_headers($url);
            $response = substr($headers[0], 9, 3);
            if ($response != "404") {
                $getuser_info = file_get_contents($url);
                $user_info = json_decode($getuser_info, true);
                $pos_nm = $user_info['POSITION'];
            } else {
                $pos_nm = '';
            }

        echo $pos_nm;
    }

    public function get_emp_nik()
    {
        $id = $this->input->post('id');

        $url = get_api_key().'users/employement/EMPLID/'.get_nik($id).'/format/json';
            $headers = get_headers($url);
            $response = substr($headers[0], 9, 3);
            if ($response != "404") {
                $getuser_info = file_get_contents($url);
                $user_info = json_decode($getuser_info, true);
                $pos_nm = $user_info['EMPLID'];
            } else {
                $pos_nm = '';
            }

        echo $pos_nm;
    }

     function form_training_group_pdf($id)
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        else
        {
        $user_id= getValue('user_pengaju_id', 'users_training_group', array('id'=>'where/'.$id));
        $this->data['user_nik'] = $sess_nik = get_nik($user_id);
        $this->data['sess_id'] = $this->session->userdata('user_id');

        $form_training_group = $this->data['form_training_group'] = $this->form_training_group_model->form_training_group($id)->result($id);
        $this->data['_num_rows'] = $this->form_training_group_model->form_training_group($id)->num_rows($id);

        $this->data['training_type'] = GetAll('training_type', array('is_deleted' => 'where/0'));
        $this->data['penyelenggara'] = GetAll('penyelenggara', array('is_deleted' => 'where/0'));
        $this->data['pembiayaan'] = GetAll('pembiayaan', array('is_deleted' => 'where/0'));
        $this->data['approval_status'] = GetAll('approval_status', array('is_deleted'=>'where/0'));

        $this->data['id'] = $id;
        $title = $this->data['title'] = 'Form Training-'.get_name($user_id);
        $this->load->library('mpdf60/mpdf');
        $html = $this->load->view('training_pdf', $this->data, true); 
        $mpdf = new mPDF();
        $mpdf = new mPDF('A4');
        $mpdf->WriteHTML($html);
        $mpdf->Output($id.'-'.$title.'.pdf', 'I');
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function _render_page($view, $data=null, $render=false)
    {
        $data = (empty($data)) ? $this->data : $data;
        if ( ! $render)
        {
            $this->load->library('template');

                if(in_array($view, array('form_training_group/index')))
                {
                    $this->template->set_layout('default');

                    $this->template->add_js('jquery.sidr.min.js');
                    $this->template->add_js('breakpoints.js');
                    $this->template->add_js('select2.min.js');

                    $this->template->add_js('core.js');

                    $this->template->add_js('respond.min.js');

                    $this->template->add_js('main.js');

                    
                    $this->template->add_css('jquery-ui-1.10.1.custom.min.css');
                    $this->template->add_css('plugins/select2/select2.css');
                    
                }
                elseif(in_array($view, array('form_training_group/input',
                                             'form_training_group/detail'
                    )))
                {

                    $this->template->set_layout('default');

                    $this->template->add_js('jquery.sidr.min.js');
                    $this->template->add_js('breakpoints.js');
                    $this->template->add_js('select2.min.js');

                    $this->template->add_js('core.js');
                    $this->template->add_js('purl.js');
                    $this->template->add_js('jquery.maskMoney.js');

                    $this->template->add_js('respond.min.js');

                    $this->template->add_js('jquery.bootstrap.wizard.min.js');
                    $this->template->add_js('jquery.validate.min.js');
                    $this->template->add_js('bootstrap-datepicker.js');
                    $this->template->add_js('bootstrap-timepicker.js');
                    $this->template->add_js('form_training_group.js');
                    
                    $this->template->add_css('jquery-ui-1.10.1.custom.min.css');
                    $this->template->add_css('plugins/select2/select2.css');
                    $this->template->add_css('datepicker.css');
                    $this->template->add_css('bootstrap-timepicker.css');
                    $this->template->add_css('approval_img.css');
                     
                }


            if ( ! empty($data['title']))
            {
                $this->template->set_title($data['title']);
            }

            $this->template->load_view($view, $data);
        }
        else
        {
            return $this->load->view($view, $data, TRUE);
        }
    }
}