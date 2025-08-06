<div class="modal fade" id="updateInvoiceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Update Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="updateInvoiceForm">
                @csrf
                @method('POST')
                <input type="hidden" name="id" id="updateInvoiceId">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Client</label>
                                <select name="client_id" id="updateClientSelect" class="form-control" required>
                                    <option value="" selected disabled>Loading clients...</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-control"
                                    id="updateInvoiceNumber" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="invoice_date" class="form-control" id="updateInvoiceDate"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description (Product / Service)</label>
                        <input type="text" class="form-control" name="description" id="updateDescription"
                            placeholder="Description" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit Price <strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="unit_price" id="updateUnitPrice"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control" name="quantity" id="updateQuantity" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subtotal <strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="subtotal" id="updateSubtotal" readonly
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tax (%)</label>
                                <input type="text" class="form-control" name="tax" id="updateTax" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Discount <strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="discount" id="updateDiscount" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total <strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="total" id="updateTotal" readonly
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateInvoiceBtn">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- jQuery & Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    loadClients();

    function loadClients(selectedClientId = null) {
        $.ajax({
            url: "{{ route('client.list') }}", // update this to your route
            method: "GET",
            success: function(clients) {
                const select = $('#updateClientSelect');
                select.empty().append('<option value="" disabled>Select Client</option>');

                $.each(clients.data, function(_, client) {
                    select.append(`<option value="${client.id}">${client.name}</option>`);
                });

                if (selectedClientId) {
                    select.val(selectedClientId);
                }
            },
            error: function() {
                toastr.error('Failed to load clients');
            }
        });
    }

    // Load data into modal
    $(document).on('click', '.editInvoiceBtn', function() {
        const invoiceId = $(this).data('id');

        $.ajax({
            url: `/invoice/${invoiceId}`, // Adjust route if needed
            method: 'GET',
            success: function(res) {
                $('#updateInvoiceId').val(res.id);
                $('#updateClientSelect').val(res.client_id);
                $('#updateInvoiceNumber').val(res.invoice_number);
                $('#updateInvoiceDate').val(res.invoice_date);
                $('#updateDescription').val(res.items[0]?.description || '');
                $('#updateUnitPrice').val(res.items[0]?.unit_price || '');
                $('#updateQuantity').val(res.items[0]?.quantity || '');
                $('#updateSubtotal').val(res.subtotal);
                $('#updateTax').val(res.tax);
                $('#updateDiscount').val(res.discount);
                $('#updateTotal').val(res.total);

                $('#updateInvoiceModal').modal('show');
            },
            error: function() {
                toastr.error('Failed to load invoice data.');
            }
        });
    });

    // Submit update form
</script>

{{-- script for calculate subtotal and total of invoice --}}
<script>
    $(document).ready(function() {
        function calculateSubtotal() {
            let unitPrice = parseFloat($('#updateUnitPrice').val()) || 0;
            let quantity = parseFloat($('#updateQuantity').val()) || 0;
            let subtotal = unitPrice * quantity;
            $('#updateSubtotal').val(subtotal.toFixed(2));
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = parseFloat($('#updateSubtotal').val()) || 0;
            let taxPercent = parseFloat($('#updateTax').val()) || 0;
            let discount = parseFloat($('#updateDiscount').val()) || 0;

            let taxAmount = (subtotal * taxPercent) / 100;
            let total = subtotal + taxAmount - discount;
            $('#updateTotal').val(total.toFixed(2));
        }

        $('#updateUnitPrice, #updateQuantity').on('input', calculateSubtotal);
        $('#updateTax, #updateDiscount').on('input', calculateTotal);
    });
</script>

{{-- script for update invoice --}}
<script>
    $(document).ready(function() {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#updateInvoiceBtn').on('click', function() {
            let form = $('#updateInvoiceForm');
            let formData = form.serialize();

            $.ajax({
                url: "{{ route('invoice.update') }}",
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $('#updateInvoiceBtn').prop('disabled', true).text('Updating...');
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message || 'Invoice updated successfully.');

                        // Reset and close modal
                        $('#updateInvoiceForm')[0].reset();
                        $('#updateInvoiceForm').closest('.modal').modal('hide');

                        // Refresh list or table
                        getList();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            messages.forEach(function(msg) {
                                toastr.error(msg);
                            });
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    $('#updateInvoiceBtn').prop('disabled', false).text('Update');
                }
            });
        });
    });
</script>
