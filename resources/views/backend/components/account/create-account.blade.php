<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createAccountForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Account Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter account name" required>
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" class="form-control" name="account_number"
                            placeholder="Enter account number">
                    </div>

                    <div class="form-group">
                        <label>Opening Balance</label>
                        <input type="text" class="form-control" name="opening_balance"
                            placeholder="Enter opening balance">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAccountBtn">Save Account</button>
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
        $('#saveAccountBtn').click(function() {
            let formData = $('#createAccountForm').serialize();

            $.ajax({
                url: "{{ route('account.store') }}", 
                type: "POST",
                data: formData,
                success: function(response) {
                    toastr.success('Account created successfully!');
                    $('#createAccountForm')[0].reset();
                    $('#modal-default').modal('hide');
                    $('#accountTable').DataTable().ajax.reload(null, false);
                    getList();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation error
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        });
    });
</script>
