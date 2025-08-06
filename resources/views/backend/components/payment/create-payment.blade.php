<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Payment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createPaymentForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Invoice Number</label>
                        <select name="invoice_id" id="invoiceSelect" class="form-control" required>
                            <option value="" selected disabled>Loading invoice...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" id="accountSelect" class="form-control" required>
                            <option value="" selected disabled>Loading account...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ammount</label>
                        <input type="text" class="form-control" name="amount" placeholder="Enter ammount" required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" name="notes" placeholder="Short note" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePaymentBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- jQuery & Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


{{-- script for load invoice list --}}
<script>
    loadInvoiceList();
    async function loadInvoiceList() {
        try {
            const res = await axios.get('/invoice-list');
            const data = res.data;

            if (data.status === 'success' && Array.isArray(data.data)) {
                const clientSelect = document.getElementById('invoiceSelect');
                clientSelect.innerHTML = '<option value="" disabled selected>Select Your Invoice</option>';

                data.data.forEach(invoice => {
                    const option = document.createElement('option');
                    option.value = invoice.id;
                    option.textContent = invoice.invoice_number;
                    clientSelect.appendChild(option);
                });
            } else {
                toastr.error(data.message || 'Failed to load invoice.');
                console.error('Failed to load invoice:', data.message);
            }
        } catch (error) {
            toastr.error('Error fetching invoice.');
            console.error('Error fetching invoice:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadClientList);
</script>

{{-- script for load account list --}}
<script>
    loadInvoiceList();
    async function loadInvoiceList() {
        try {
            const res = await axios.get('/account-list');
            const data = res.data;

            if (data.status === 'success' && Array.isArray(data.data)) {
                const clientSelect = document.getElementById('accountSelect');
                clientSelect.innerHTML = '<option value="" disabled selected>Select Your Account</option>';

                data.data.forEach(account => {
                    const option = document.createElement('option');
                    option.value = account.id;
                    option.textContent = account.name;
                    clientSelect.appendChild(option);
                });
            } else {
                toastr.error(data.message || 'Failed to load invoice.');
                console.error('Failed to load invoice:', data.message);
            }
        } catch (error) {
            toastr.error('Error fetching invoice.');
            console.error('Error fetching invoice:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadClientList);
</script>


<script>
    $(document).ready(function() {
        $('#savePaymentBtn').click(function(e) {
            e.preventDefault();

            let $btn = $(this);
            let $form = $('#createPaymentForm');
            let formData = $form.serialize();

            // Disable button to prevent multiple clicks
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('payment.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    toastr.success(response.message || 'Payment created successfully!');

                    // Reset form and close modal
                    $form[0].reset();
                    $('#modal-default').modal('hide');

                    // Reload data table and list
                    $('#accountTable').DataTable().ajax.reload(null, false);
                    getList();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            toastr.error(messages[0]);
                        });
                    } else if (xhr.status === 500) {
                        toastr.error(xhr.responseJSON.message || 'Server error occurred.');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    // Re-enable the button after request completes
                    $btn.prop('disabled', false).text('Save');
                }
            });
        });
    });
</script>
