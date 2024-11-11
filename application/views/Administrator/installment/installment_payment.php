<style>
    .v-select {
        margin-bottom: 5px;
        background: #fff;
        border-radius: 3px;
    }

    .v-select.open .dropdown-toggle {
        border-bottom: 1px solid #ccc;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
        height: 25px;
        border: none;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .vs__selected-options {
        overflow: hidden;
        flex-wrap: nowrap;
    }

    .v-select .selected-tag {
        margin: 2px 0px;
        white-space: nowrap;
        position: absolute;
        left: 0px;
    }

    .v-select .vs__actions {
        margin-top: -5px;
    }

    .v-select .dropdown-menu {
        width: auto;
        overflow-y: auto;
    }

    #customerPayment label {
        font-size: 13px;
    }

    #customerPayment select {
        border-radius: 3px;
        padding: 0;
    }

    .add-button {
        padding: 2.8px;
        width: 100%;
        background-color: #d15b47;
        display: block;
        text-align: center;
        color: white;
        cursor: pointer;
        border-radius: 3px;
    }

    .add-button:hover {
        color: white;
    }
</style>
<div id="customerPayment">
    <div class="row">
        <div class="col-md-12" style="margin:0;">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Installment Payment Form</legend>
                <div class="control-group">
                    <form @submit.prevent="saveInstallmentPayment">
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Payment Type</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <select class="form-control" v-model="payment.payment_type" required>
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank</option>
                                            <option value="discount">Discount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;" v-bind:style="{display: payment.payment_type == 'bank' ? '' : 'none'}">
                                    <label class="col-md-4 control-label">Bank Account</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <v-select v-bind:options="filteredAccounts" v-model="selectedAccount" label="display_text" placeholder="Select account"></v-select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"> Customer </label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7" style="display: flex;align-items:center;margin-bottom:5px;">
                                        <div style="width: 86%;">
                                            <v-select v-bind:options="customers" style="margin: 0;" label="display_name" v-model="selectedCustomer" v-on:input="getCustomerDue" @search="onSearchCustomer"></v-select>
                                        </div>
                                        <div style="width: 13%;margin-left:2px;">
                                            <a href="<?= base_url('customer') ?>" class="add-button" target="_blank" title="Add New Customer"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"> Invoice </label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7" style="margin-bottom: 5px;">
                                        <v-select v-bind:options="invoices" style="margin: 0;" label="SaleMaster_InvoiceNo" v-model="selectedInvoice" v-on:input="getInstallment"></v-select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"> Installment </label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <div style="border: 1px solid #818181;min-height:30px;padding-left:3px;border-radius: 4px;">
                                            <label :for="item.display_name+`${item.id}`" v-for="item in installments" style="cursor: pointer;margin-right: 5px;display:none;" :style="{display: installments.length > 0 ? '' : 'none'}">
                                                <input type="checkbox" :id="item.display_name+`${item.id}`" :value="item.id" v-model="paymentId" @change="calculateTotal">
                                                <p style="margin:0;margin-top: -1px;display:inline-block;">{{ item.display_name }}</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Due Amount</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <input type="number" style="margin-bottom: 4px;" class="form-control" :value="payment.dueAmount" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Payment Date</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <input type="date" style="margin-bottom: 4px;" class="form-control" v-model="payment.payment_date" required @change="getInstallmentPayments" v-bind:disabled="userType == 'u' ? true : false">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Description</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="payment.note">
                                    </div>
                                </div>
                                <div class="form-group" v-if="payment.payment_type == 'discount'" style="display: none;" :style="{display: payment.payment_type == 'discount' ? '' : 'none'}">
                                    <label class="col-md-4 control-label">Discount</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <input type="number" min="0" step="any" class="form-control" v-model="payment.discount">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Amount</label>
                                    <label class="col-md-1">:</label>
                                    <div class="col-md-7">
                                        <input type="number" min="0" step="any" class="form-control" v-model="payment.paid_amount" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-5 text-right">
                                        <input type="button" @click="resetForm" class="btnReset" value="Reset">
                                        <input type="submit" :disabled="paymentProgress" class="btnSave" value="Save">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="payments" :filter-by="filter" style="margin-bottom: 5px;">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.sl }}</td>
                            <td>{{ row.payment_date }}</td>
                            <td>{{ row.display_name }}</td>
                            <td>{{ row.payment_type }}</td>
                            <td>{{ row.Customer_Name }}</td>
                            <td>{{ row.payment_amount }}</td>
                            <td>{{ row.discount }}</td>
                            <td>{{ row.paid_amount }}</td>
                            <td>{{ row.note }}</td>
                            <td>
                                <!-- <i class="fa fa-file" style="margin-right: 5px;font-size: 14px;cursor: pointer;" @click="window.location = `/paymentAndReport/${row.CPayment_id}`"></i> -->
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <i class="btnEdit fa fa-pencil" @click="editPayment(row)"></i>
                                    <i class="btnDelete fa fa-trash" @click="deletePayment(row.id)"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#customerPayment',
        data() {
            return {
                payment: {
                    id: 0,
                    customer_id: null,
                    payment_type: 'cash',
                    account_id: null,
                    payment_date: moment().format('YYYY-MM-DD'),
                    note: '',
                    dueAmount: 0,
                    discount: 0,
                    paid_amount: '',
                },
                paymentId: [],
                payments: [],
                customers: [],
                selectedCustomer: {
                    display_name: 'Select Customer',
                    Customer_Name: ''
                },
                accounts: [],
                selectedAccount: null,
                invoices: [],
                selectedInvoice: null,
                installments: [],
                installPayments: [],
                paymentProgress: false,
                userType: '<?php echo $this->session->userdata("accountType"); ?>',

                columns: [{
                        label: 'Sl',
                        field: 'sl',
                        align: 'center'
                    },
                    {
                        label: 'Date',
                        field: 'payment_date',
                        align: 'center'
                    },
                    {
                        label: 'Payment Month',
                        field: 'display_name',
                        align: 'center'
                    },
                    {
                        label: 'Payment Type',
                        field: 'payment_type',
                        align: 'center'
                    },
                    {
                        label: 'Customer',
                        field: 'Customer_Name',
                        align: 'center'
                    },
                    {
                        label: 'Installment',
                        field: 'payment_amount',
                        align: 'center'
                    },
                    {
                        label: 'Discount',
                        field: 'discount',
                        align: 'center'
                    },
                    {
                        label: 'Paid Amount',
                        field: 'paid_amount',
                        align: 'center'
                    },
                    {
                        label: 'Description',
                        field: 'note',
                        align: 'center'
                    },
                    {
                        label: 'Action',
                        align: 'center',
                        filterable: false
                    }
                ],
                page: 1,
                per_page: 100,
                filter: '',
                isEdit: ''
            }
        },
        computed: {
            filteredAccounts() {
                let accounts = this.accounts.filter(account => account.status == '1');
                return accounts.map(account => {
                    account.display_text = `${account.account_name} - ${account.account_number} (${account.bank_name})`;
                    return account;
                })
            },
        },
        created() {
            this.getCustomers();
            this.getAccounts();
            this.getInstallmentPayments();
        },
        methods: {
            getInstallmentPayments() {
                axios.post('/get_installment', {
                    status: 'a'
                }).then(res => {
                    this.payments = res.data.map((item, index) => {
                        item.sl = index + 1;
                        return item;
                    });
                })
            },
            getCustomers() {
                axios.post('/get_customers', {
                    forSearch: 'yes'
                }).then(res => {
                    this.customers = res.data;
                })
            },
            getSaleInvoice() {
                let filter = {
                    customerId: this.selectedCustomer.Customer_SlNo,
                    isEdit: this.isEdit
                }
                axios.post('/get_installment_sale_invoice', filter).then(res => {
                    this.invoices = res.data;
                })
            },
            getInstallment() {
                if (this.isEdit == '') {
                    if (this.selectedInvoice == null) {
                        this.installments = [];
                        this.payment.dueAmount = 0;
                        this.paymentId = [];
                        return;
                    }
                }
                let filter = {
                    customerId: this.selectedCustomer.Customer_SlNo,
                    saleId: this.selectedInvoice ? this.selectedInvoice.SaleMaster_SlNo : '',
                    isEdit: this.isEdit
                }
                axios.post('/get_installment', filter).then(res => {
                    this.installments = res.data.map(item => {
                        item.amount = item.status == 'a' ? (+item.paid_amount + +item.discount) : item.payment_amount;
                        return item;
                    })
                    if (this.isEdit != '') {
                        this.installments = this.installments.filter(item => item.status == 'p' || item.id == Array.from(this.paymentId)[0]);
                    }
                })
            },
            getCustomerDue() {
                this.selectedInvoice = null;
                this.invoices = [];
                if (this.selectedCustomer == null || this.selectedCustomer.Customer_SlNo == undefined) {
                    return;
                }

                if (this.selectedCustomer.Customer_SlNo != '') {
                    axios.post('/get_customer_due', {
                        customerId: this.selectedCustomer.Customer_SlNo
                    }).then(res => {
                        this.payment.CPayment_previous_due = res.data[0].dueAmount;
                    })
                    this.getSaleInvoice();
                }

            },
            async calculateTotal() {
                if (this.paymentId.length == 0) {
                    this.payment.dueAmount = 0
                    return;
                }
                var dues = [];
                let datas = Array.from(this.paymentId);
                datas.forEach(item => {
                    var due = this.installments.find(install => install.id == item);
                    dues.push(due);
                })

                this.installPayments = dues;
                this.payment.dueAmount = dues.reduce((prev, curr) => {
                    return prev + parseFloat(curr.amount)
                }, 0).toFixed(2);
            },
            async onSearchCustomer(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get_customers", {
                            name: val,
                        })
                        .then(res => {
                            let r = res.data;
                            this.customers = r.filter(item => item.status == 'a')
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getCustomers();
                }
            },
            getAccounts() {
                axios.get('/get_bank_accounts')
                    .then(res => {
                        this.accounts = res.data;
                    })
            },
            saveInstallmentPayment() {
                if (this.payment.payment_type == 'bank') {
                    if (this.selectedAccount == null) {
                        Swal.fire({
                            icon: "error",
                            text: "Select an account",
                        });
                        return;
                    } else {
                        this.payment.accountId = this.selectedAccount.account_id;
                    }
                } else {
                    this.payment.accountId = null;
                }
                if (this.selectedCustomer == null || this.selectedCustomer.Customer_SlNo == undefined) {
                    Swal.fire({
                        icon: "error",
                        text: "Select Customer",
                    });
                    return;
                }

                if (parseFloat(this.payment.dueAmount) < parseFloat(+this.payment.paid_amount + +this.payment.discount)) {
                    Swal.fire({
                        icon: "error",
                        text: "You can not paid grater than due amount",
                    });
                    return;
                }

                this.payment.customer_id = this.selectedCustomer.Customer_SlNo;
                this.payment.paymentId = this.paymentId;
                this.payment.installments = this.installPayments;
                let url = '/add_installment_payment';
                if (this.payment.id != 0) {
                    url = '/update_installment_payment';
                }
                this.paymentProgress = true;
                axios.post(url, this.payment).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.paymentProgress = false;
                        this.getInstallmentPayments();
                        // let invoiceConfirm = confirm('Do you want to view invoice?');
                        // if (invoiceConfirm == true) {
                        //     window.open('/paymentAndReport/' + r.paymentId, '_blank');
                        // }
                    }
                })
            },
            async editPayment(payment) {
                this.isEdit = 'yes';
                this.paymentId = [];
                this.paymentId.push(payment.id);
                let keys = Object.keys(this.payment);
                this.payment.dueAmount = 0;
                keys.forEach(key => {
                    this.payment[key] = payment[key];
                })
                if (payment.payment_type == 'bank') {
                    this.selectedAccount = this.accounts.find(item => item.account_id == payment.accountId)
                }
                this.selectedCustomer = this.customers.find(item => item.Customer_SlNo == payment.customer_id);
                setTimeout(() => {
                    this.selectedInvoice = {
                        SaleMaster_SlNo: payment.sale_id,
                        SaleMaster_InvoiceNo: payment.SaleMaster_InvoiceNo
                    }
                    this.getInstallment();
                }, 500);
                setTimeout(() => {
                    this.calculateTotal();
                }, 1000);
            },
            deletePayment(paymentId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_installment_payment', {
                    paymentId: paymentId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getInstallmentPayments();
                    }
                })
            },
            resetForm() {
                this.isEdit = '';
                this.payment.id = 0;
                this.payment.customer_id = '';
                this.payment.paid_amount = '';
                this.payment.note = '';

                this.selectedCustomer = {
                    display_name: 'Select Customer',
                    Customer_Name: ''
                }

                this.invoices = [];
                this.installments = [];
                this.payment.dueAmount = 0;
            }
        }
    })
</script>