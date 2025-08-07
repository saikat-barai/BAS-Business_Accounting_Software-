<div class="modal fade" id="updateExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Expense</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="updateExpenseForm" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="id" id="editExpenseId">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" id="editAccountSelect" class="form-control" required>
                            <option value="" disabled selected>Loading account...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="editCategorySelect" class="form-control" required>
                            <option value="" disabled selected>Loading category...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" name="description" id="editDescription" required>
                    </div>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="editAmount"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" id="editDate" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateExpenseBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery & Toastr JS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        loadAccounts();
    });

    async function loadCategories() {
        try {
            const res = await axios.get('/category-list');
            const categorySelect = document.getElementById('editCategorySelect');
            categorySelect.innerHTML = `<option disabled selected>Select Category</option>`;
            res.data.data.forEach(cat => {
                categorySelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        } catch (err) {
            toastr.error('Failed to load categories.');
        }
    }

    async function loadAccounts() {
        try {
            const res = await axios.get('/account-list');
            const accountSelect = document.getElementById('editAccountSelect');
            accountSelect.innerHTML = `<option disabled selected>Select Account</option>`;
            res.data.data.forEach(acc => {
                accountSelect.innerHTML += `<option value="${acc.id}">${acc.name}</option>`;
            });
        } catch (err) {
            toastr.error('Failed to load accounts.');
        }
    }
</script>

<script>
    $(document).on('click', '.editBtn', function() {
        const expenseId = $(this).data('id');
        $.ajax({
            url: `/expense/${expenseId}`,
            type: 'GET',
            success: function(res) {
                const data = res.data;
                $('#editExpenseId').val(data.id);
                $('#editAccountSelect').val(data.account_id);
                $('#editCategorySelect').val(data.category_id);
                $('#editDescription').val(data.description);
                $('#editAmount').val(data.amount);
                $('#editDate').val(data.date);
                $('#updateExpenseModal').modal('show');
            },
            error: function() {
                toastr.error('Failed to load expense data.');
            }
        });
    });
</script>

<script>
    $('#updateExpenseForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        const id = $('#editExpenseId').val();

        $.ajax({
            url: `/expense-update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(response.message || 'Expense updated successfully.');
                $('#updateExpenseModal').modal('hide');
                $('#updateExpenseForm')[0].reset();
                $('#accountTable').DataTable().ajax.reload(null, false);
                getList();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, msg) {
                        toastr.error(msg[0]);
                    });
                } else {
                    toastr.error('Failed to update expense.');
                }
            }
        });
    });
</script>

