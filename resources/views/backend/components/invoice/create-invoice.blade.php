<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createAccountForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Client</label>
                                <select name="client_id" id="clientSelect" class="form-control" required>
                                    <option value="" selected disabled>Loading invoice...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-control"
                                    value="{{ 'INV-' . time() }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description (Product / Service)</label>
                        <input type="email" class="form-control" name="description" placeholder="Description"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit Price<strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="unit_price" placeholder="Unit Price"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control" name="quantity" placeholder="Enter quantity"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subtotal<strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="subtotal" placeholder="Subtotal"
                                    required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tax (%)</label>
                                <input type="text" class="form-control" name="tax" placeholder="Tax" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Discount <strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="discount" placeholder="Discount"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total<strong>(&#2547;)</strong></label>
                                <input type="text" class="form-control" name="total" placeholder="Total" required
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAccountBtn">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- jQuery & Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


{{-- script for load client list --}}
<script>
    async function loadClientList() {
        try {
            const res = await axios.get('/client-list');
            const data = res.data;

            if (data.status === 'success' && Array.isArray(data.data)) {
                const clientSelect = document.getElementById('clientSelect');
                clientSelect.innerHTML = '<option value="" disabled selected>Select Your Client</option>';

                data.data.forEach(client => {
                    const option = document.createElement('option');
                    option.value = client.id;
                    option.textContent = client.name;
                    clientSelect.appendChild(option);
                });
            } else {
                toastr.error(data.message || 'Failed to load clients.');
                console.error('Failed to load clients:', data.message);
            }
        } catch (error) {
            toastr.error('Error fetching clients.');
            console.error('Error fetching clients:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadClientList);
</script>


{{-- script for calculate subtotal and total  --}}
<script>
    $(document).ready(function() {
        // Calculate Subtotal
        function calculateSubtotal() {
            let unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
            let quantity = parseFloat($('input[name="quantity"]').val()) || 0;
            let subtotal = unitPrice * quantity;
            $('input[name="subtotal"]').val(subtotal.toFixed(2));

            // Also trigger total calculation when subtotal updates
            calculateTotal();
        }

        // Calculate Total
        function calculateTotal() {
            let subtotal = parseFloat($('input[name="subtotal"]').val()) || 0;
            let taxPercent = parseFloat($('input[name="tax"]').val()) || 0;
            let discount = parseFloat($('input[name="discount"]').val()) || 0;

            let taxAmount = (subtotal * taxPercent) / 100;
            let total = subtotal + taxAmount - discount;
            $('input[name="total"]').val(total.toFixed(2));
        }

        // Bind events
        $('input[name="unit_price"], input[name="quantity"]').on('input', calculateSubtotal);
        $('input[name="subtotal"], input[name="tax"], input[name="discount"]').on('input', calculateTotal);
    });
</script>

<script>
    $(document).ready(function() {
        $('#saveAccountBtn').click(function(e) {
            e.preventDefault();

            let $btn = $(this);
            let $form = $('#createAccountForm');
            let formData = $form.serialize();

            // Disable button to prevent multiple clicks
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('invoice.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    toastr.success(response.message || 'Client created successfully!');

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
