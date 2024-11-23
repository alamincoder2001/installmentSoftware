<style>
    .v-select {
        float: right;
        min-width: 200px;
        background: #fff;
        margin-left: 5px;
        border-radius: 4px !important;
        margin-top: -2px;
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
</style>

<div id="customerDueList">
    <div class="row" style="display: none" v-bind:style="{display: installments.length > 0 ? '' : 'none'}">
        <div class="col-md-12">
            <div class="text-right">
                <a href="" v-on:click.prevent="print">
                    <i class="fa fa-print"></i> Print
                </a>
            </div>
            <div class="table-responsive" id="reportTable">
                <table class="table table-bordered">
                    <tbody>
                        <template v-for="data in installments">
                            <tr style="background: #7260a7;">
                                <td colspan="5" style="padding: 5px;color:white;">
                                    <span style="font-weight: 700;">Customer ID:</span> {{data.Customer_Code}} | <span style="font-weight: 700;"> Name: {{data.Customer_Name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Sl</th>
                                <th>Due Date</th>
                                <th>Month</th>
                                <th>Action</th>
                                <th>Installment Due Amount</th>
                            </tr>
                            <tr v-for="(item, sl) in data.months">
                                <td>{{ sl + 1 }}</td>
                                <td>{{moment(item.due_date).format('DD-MM-YYYY')}}</td>
                                <td>{{moment(item.due_date).format('MMMM-YYYY')}}</td>
                                <td>
                                    <i @click="updateInstallment(type, item.id, item.due_date, item.Customer_Name)" class="fa fa-edit" style="font-size:13px;cursor:pointer;"></i>
                                </td>
                                <td style="vertical-align: middle;font-weight:700;" :rowspan="data.months.length" v-if="sl == 0" v-html="data.months.reduce((pr, cu) => {return pr + parseFloat(cu.payment_amount)}, 0).toFixed(2)"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="updateInstallmentPayment" class="modal fade" tabindex="-1" role="dialog">
        <form @submit.prevent="updateInstallmentPayment($event)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title">Update Installment (<span class="customerName"></span>)</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" id="id" />
                        <input type="hidden" name="type" class="form-control" id="type" />
                        <input type="date" name="due_date" class="form-control" id="due_date" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#customerDueList',
        data() {
            return {
                type: "<?= $type; ?>",
                installments: [],
            }
        },
        created() {
            this.getInstallment();
        },
        methods: {
            getInstallment() {
                let filter = {
                    customerId: "<?= $customerId; ?>",
                    groupBy: 'yes'
                }
                if (this.type == 'today') {
                    filter.today = moment().format('YYYY-MM-DD');
                }
                if (this.type == 'past') {
                    filter.pastday = moment().format('YYYY-MM-DD');
                }
                axios.post('/get_installment', filter).then(res => {
                    this.installments = res.data;
                })
            },
            updateInstallment(type, id, due_date, customer) {
                $('#updateInstallmentPayment').modal('show');
                $('#updateInstallmentPayment').find('#id').val(id);
                $('#updateInstallmentPayment').find('#type').val(type);
                $('#updateInstallmentPayment').find('#due_date').val(due_date);
                $('#updateInstallmentPayment').find('.customerName').text(customer);
            },
            updateInstallmentPayment(event) {
                event.preventDefault();
                let type = $('#updateInstallmentPayment').find('#type').val();
                let formdata = new FormData(event.target);
                $.ajax({
                    url: '/update_installment',
                    method: 'POST',
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    data: formdata,
                    success: res => {
                        this.getInstallment();
                        $('#updateInstallmentPayment').modal('hide');
                        alert(res.message);
                    }
                });
            },
            async print() {
                let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Customer Installment Due</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

                var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
                mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                mywindow.document.body.innerHTML += reportContent;

                mywindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                mywindow.print();
                mywindow.close();
            }
        }
    })
</script>