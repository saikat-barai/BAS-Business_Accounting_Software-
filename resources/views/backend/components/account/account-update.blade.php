<div class="modal fade" id="update-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Your Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="updateAccountForm" data-id="">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Account Name</label>
                        <input type="text" class="form-control" name="name" id="updateName"
                            placeholder="Enter account name" required>
                        <input type="text" class="form-control d-none" name="id" id="updateID">
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" id="updateType" class="form-control" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" class="form-control" name="account_number" id="updateAccountNumber"
                            placeholder="Enter account number">
                    </div>

                    <div class="form-group">
                        <label>Opening Balance</label>
                        <input type="text" class="form-control" name="opening_balance" id="updateOpeningBalance"
                            placeholder="Enter opening balance">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateAccountBtn">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function getSingleData(id) {
        $('#updateAccountForm').data('id', id);
        document.getElementById("updateID").value = id;
        let res = await axios.post('/account-by-id', {
            id: id
        })
        let data = res.data.data;
        document.getElementById("updateName").value = data.name;
        document.getElementById("updateType").value = data.type;
        document.getElementById("updateAccountNumber").value = data.account_number;
        document.getElementById("updateOpeningBalance").value = data.opening_balance;
    }
</script>

<script>
    $(document).ready(function() {
        $('#updateAccountBtn').click(function(e) {
            e.preventDefault();

            let id = $('#updateAccountForm').data('id'); 
            let formData = $('#updateAccountForm').serialize();

            $.ajax({
                url: `/account-update/${id}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    toastr.success(response.message);
                    $('#updateAccountForm')[0].reset();
                    $('#update-modal').modal('hide');
                    $('#accountTable').DataTable().ajax.reload(null, false);
                    getList();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    });
</script>
