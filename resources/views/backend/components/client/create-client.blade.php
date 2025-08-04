<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createAccountForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter client name"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter client email"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Enter client phone"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter client address"
                            required>
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
                url: "{{ route('client.store') }}",
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
