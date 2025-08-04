<div class="modal fade" id="update-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Your Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="updateAccountForm" data-id="">
                @csrf
                <input type="hidden" name="id" id="updateID">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" class="form-control" name="name" id="updateName" placeholder="Enter client name"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="updateEmail" placeholder="Enter client email"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" id="updatePhone" placeholder="Enter client phone"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address" id="updateAddress" placeholder="Enter client address"
                            required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateAccountBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function getSingleData(id) {
        $('#updateAccountForm').data('id', id);
        document.getElementById("updateID").value = id;
        let res = await axios.post('/client-by-id', {
            id: id
        })
        let data = res.data.data;
        document.getElementById("updateName").value = data.name;
        document.getElementById("updateEmail").value = data.email;
        document.getElementById("updatePhone").value = data.phone;
        document.getElementById("updateAddress").value = data.address;
    }
</script>

<script>
    $(document).ready(function() {
        $('#updateAccountBtn').click(function(e) {
            e.preventDefault();

            let id = $('#updateAccountForm').data('id');
            let formData = $('#updateAccountForm').serialize();

            $.ajax({
                url: `/client-update/${id}`,
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
