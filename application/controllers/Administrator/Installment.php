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
        $data['title'] = "Installment Receive";
        $data['content'] = $this->load->view('Administrator/installment/installment_payment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getInstallmentSaleInvoice()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";
        if (isset($data->customerId) && $data->customerId != '') {
            $clauses .= " and ins.customer_id = '$data->customerId'";
        }
        if (isset($data->isEdit) && $data->isEdit != '') {
        } else {
            if (isset($data->status) && $data->status != '') {
                $clauses .= " and ins.status = '$data->status'";
            } else {
                $clauses .= " and ins.status = 'p'";
            }
        }
        $query = $this->db->query("select
                            sm.SaleMaster_SlNo,
                            sm.SaleMaster_InvoiceNo,
                            sm.SaleMaster_SaleDate,
                            sm.SalseCustomer_IDNo
                            from tbl_installment ins
                            left join tbl_salesmaster sm on sm.SaleMaster_SlNo = ins.sale_id
                            where ins.branch_id = '$this->sbrunch'
                            $clauses
                            group by ins.sale_id")->result();

        echo json_encode($query);
    }

    public function getInstallment()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";
        $groupBy = "";
        $limit = "";

        if (isset($data->customerId) && $data->customerId != '') {
            $clauses .= " and ins.customer_id = '$data->customerId'";
        }
        if (isset($data->saleId) && $data->saleId != '') {
            $clauses .= " and ins.sale_id = '$data->saleId'";
        }
        if (isset($data->isEdit) && $data->isEdit != '') {
        } else {
            if (isset($data->status) && $data->status != '') {
                $clauses .= " and ins.status = '$data->status'";
            } else {
                $clauses .= " and ins.status = 'p'";
            }
        }
        if (isset($data->today) && $data->today != '') {
            $clauses .= " and ins.due_date = '$data->today'";
            $limit = "limit 15";
        }

        if (isset($data->pastday) && $data->pastday != '') {
            $clauses .= " and ins.due_date < '$data->pastday'";
            $limit = "limit 15";
        }

        if (isset($data->groupBy) && $data->groupBy != '') {
            $groupBy .= " group by c.Customer_Name";
        }


        if (isset($data->upcomingday) && $data->upcomingday != '') {
            $clauses .= " and ins.due_date between CURRENT_DATE + INTERVAL 1 DAY and CURRENT_DATE + INTERVAL 8 DAY";
            $limit = "limit 15";
        }

        if ((isset($data->dateFrom) && $data->dateFrom != '') && (isset($data->dateFrom) && $data->dateFrom != '')) {
            $clauses .= " and ins.payment_date between '$data->dateFrom' and '$data->dateTo'";
        }
        $installments = $this->db
            ->query("select 
                ins.*,
                c.Customer_Code,
                c.Customer_Name,
                concat(DATE_FORMAT(ins.due_date, '%M-%Y')) as display_name,
                sm.SaleMaster_InvoiceNo
                from tbl_installment ins
                left join tbl_customer c on c.Customer_SlNo = ins.customer_id
                left join tbl_salesmaster sm on sm.SaleMaster_SlNo = ins.sale_id
                where ins.branch_id = '$this->sbrunch'
                $clauses
                $groupBy                
                order by ins.id asc
                $limit")->result();

        if (isset($data->groupBy) && $data->groupBy != '') {
            foreach ($installments as $key => $item) {
                $item->months = $this->db
                    ->query("select 
                    ins.*,
                    c.Customer_Code,
                    c.Customer_Name,
                    concat(DATE_FORMAT(ins.due_date, '%M-%Y')) as display_name,
                    sm.SaleMaster_InvoiceNo
                    from tbl_installment ins
                    left join tbl_customer c on c.Customer_SlNo = ins.customer_id
                    left join tbl_salesmaster sm on sm.SaleMaster_SlNo = ins.sale_id
                    where ins.branch_id = '$this->sbrunch'
                    and ins.customer_id = ?
                    $clauses               
                    order by ins.id asc", $item->customer_id)->result();
            }
        }

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

    public function addInstallmentPayment()
    {
        $data = json_decode($this->input->raw_input_stream);

        $due_amount = $data->dueAmount - ($data->paid_amount + $data->discount);
        $installments = $data->installments;
        sort($installments);
        $installment_payments = array();
        foreach ($installments as $key => $item) {
            $installment_payment = $this->db->query("select * from tbl_installment where id = ?", $item->id)->row();
            $payment = array(
                'id'           => $item->id,
                'customer_id'  => $data->customer_id,
                'payment_date' => $data->payment_date,
                'payment_type' => $data->payment_type,
                'accountId'    => $data->accountId,
                'discount'     => $key == count($installments) - 1 && $data->discount > 0 ? $data->discount : 0,
                'paid_amount'  => $key == count($installments) - 1 && $due_amount > 0 ? $installment_payment->payment_amount - $due_amount : ($key == count($installments) - 1 ? $item->amount - $data->discount : $item->amount),
                'note'         => $data->note,
                'status'       => 'a'
            );
            array_push($installment_payments, $payment);
        }

        foreach ($installment_payments as $key => $item) {
            $this->db->where('id', $item['id']);
            $this->db->update('tbl_installment', $item);
            if (count($installments) - 1 == $key && $due_amount > 0) {
                $payment = $this->db->where('id', $item['id'])->get('tbl_installment')->row();
                $installment = array(
                    'sale_id'        => $payment->sale_id,
                    'customer_id'    => $item['customer_id'],
                    'due_date'       => date("Y-m-d", strtotime("+1 month", strtotime($payment->due_date))),
                    'payment_amount' => $due_amount,
                    'status'         => 'p',
                    'AddBy'          => $this->session->userdata("userId"),
                    'AddTime'        => date('Y-m-d H:i:s'),
                    'last_update_ip' => get_client_ip(),
                    'branch_id'      => $this->session->userdata('BRANCHid')
                );
                $this->db->insert('tbl_installment', $installment);
            }
        }

        $res = ['success' => true, 'message' => 'Installment payment successful'];
        echo json_encode($res);
    }

    public function updateInstallmentPayment()
    {
        $data = json_decode($this->input->raw_input_stream);

        $due_amount = $data->dueAmount - ($data->paid_amount + $data->discount);
        $installments = $data->installments;
        sort($installments);
        $installment_payments = array();
        foreach ($installments as $key => $item) {
            $installment_payment = $this->db->query("select * from tbl_installment where id = ?", $item->id)->row();
            $payment = array(
                'id'           => $item->id,
                'customer_id'  => $data->customer_id,
                'payment_date' => $data->payment_date,
                'payment_type' => $data->payment_type,
                'accountId'    => $data->accountId,
                'discount'     => $key == count($installments) - 1 && $data->discount > 0 ? $data->discount : 0,
                'paid_amount'  => $key == count($installments) - 1 && $due_amount > 0 ? $installment_payment->payment_amount - $due_amount : ($key == count($installments) - 1 ? $item->amount - $data->discount : $item->amount),
                'note'         => $data->note,
                'status'       => 'a'
            );
            array_push($installment_payments, $payment);
        }

        foreach ($installment_payments as $key => $item) {
            $this->db->where('id', $item['id']);
            $this->db->update('tbl_installment', $item);
            if (count($installments) - 1 == $key && $due_amount > 0) {
                $payment = $this->db->where('id', $item['id'])->get('tbl_installment')->row();
                $installment = array(
                    'sale_id'        => $payment->sale_id,
                    'customer_id'    => $item['customer_id'],
                    'due_date'       => date("Y-m-d", strtotime("+1 month", strtotime($payment->due_date))),
                    'payment_amount' => $due_amount,
                    'status'         => 'p',
                    'AddBy'          => $this->session->userdata("userId"),
                    'AddTime'        => date('Y-m-d H:i:s'),
                    'last_update_ip' => get_client_ip(),
                    'branch_id'      => $this->session->userdata('BRANCHid')
                );
                $this->db->insert('tbl_installment', $installment);
            }
        }

        $res = ['success' => true, 'message' => 'Installment update successful'];
        echo json_encode($res);
    }

    public function deleteInstallmentPayment()
    {
        $data = json_decode($this->input->raw_input_stream);
        $getRow =  $this->db->where('id', $data->paymentId)->get('tbl_installment')->row();
        $installment = array(
            'payment_amount' => $getRow->paid_amount,
            'paid_amount'    => 0,
            'status'         => 'p'
        );

        $this->db->where('id', $data->paymentId)->update('tbl_installment', $installment);
        $res = ['success' => true, 'message' => 'Installment delete successfully'];
        echo json_encode($res);
    }

    public function customerWiseInstallment($customerId, $type = null)
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title']      = 'Customer Installment List';
        $data['type']       = $type;
        $data['customerId'] = $customerId;
        $data['content']    = $this->load->view('Administrator/installment/customer_wise_installment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}
