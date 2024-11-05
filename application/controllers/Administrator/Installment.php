<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Installment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->sbrunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->helper('form');
        $this->load->model('SMS_model', 'sms', true);
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Installment Payment";
        $data['content'] = $this->load->view('Administrator/installment/installment_payment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getInstallment()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";
        $limit = "";

        if (isset($data->today) && $data->today != '') {
            $clauses .= " and ins.due_date = '$data->today'";
            $limit = "limit 20";
        }

        if (isset($data->pastday) && $data->pastday != '') {
            $clauses .= " and ins.due_date < '$data->pastday'";
            $limit = "limit 20";
        }

        if (isset($data->upcomingday) && $data->upcomingday != '') {
            $clauses .= " and ins.due_date between CURRENT_DATE and CURRENT_DATE + INTERVAL 7 DAY";
            $limit = "limit 20";
        }

        $installments = $this->db
            ->query("select 
                ins.*,
                c.Customer_Code,
                c.Customer_Name
                from tbl_installment ins
                left join tbl_customer c on c.Customer_SlNo = ins.customer_id
                where ins.status = 'p'
                $clauses
                order by ins.id asc
                $limit")->result();

        echo json_encode($installments);
    }

    public function checkInstallmentPayment()
    {
        $data = json_decode($this->input->raw_input_stream);
        $res = ['found' => false];
        $returnCount = $this->db->query("select * from tbl_installment where sale_id = ? and customer_id = ? and status = 'a'", [$data->saleId, $data->customerId])->row();

        if (!empty($returnCount)) {
            $res = ['found' => true];
        }

        echo json_encode($res);
    }

    public function updateInstallment()
    {
        $id = $this->input->post('id');
        $due_date = $this->input->post('due_date');
        $installment = array(
            'due_date' => $due_date
        );

        $this->db->where('id', $id)->update('tbl_installment', $installment);
        $res = ['status' => true, 'message' => 'Installment update successfully'];
        echo json_encode($res);
    }
}
