<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Expense</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="createExpenseForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" id="accountSelect" class="form-control" required>
                            <option value="" selected disabled>Loading account...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="categorySelect" class="form-control" required>
                            <option value="" selected disabled>Loading account...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" name="description" placeholder="Short note" required>
                    </div>
                    <div class="form-group">
                        <label>Ammount</label>
                        <input type="text" class="form-control" name="amount" placeholder="Enter ammount" required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveExpenseBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- jQuery & Toastr JS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



{{-- script for load category list --}}
<script>
    document.addEventListener('DOMContentLoaded', loadCategoryList);

    async function loadCategoryList() {
        try {
            const res = await axios.get('/category-list');
            const data = res.data;

            if (data.success && Array.isArray(data.data)) {
                const categorySelect = document.getElementById('categorySelect');
                categorySelect.innerHTML = '<option value="" disabled selected>Select Your Category</option>';

                data.data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            } else {
                toastr.error(data.message || 'Failed to load categories.');
                console.error('Failed to load categories:', data.message);
            }
        } catch (error) {
            toastr.error('Error fetching categories.');
            console.error('Error fetching categories:', error);
        }
    }
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
        $('#saveExpenseBtn').click(function(e) {
            e.preventDefault();

            let $btn = $(this);
            let $form = $('#createExpenseForm');
            let formData = new FormData($form[0]); // Use FormData to include files

            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('expense.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // Important for FormData
                processData: false, // Important for FormData
                success: function(response) {
                    toastr.success(response.message || 'Expense saved successfully!');
                    $form[0].reset();
                    $('#modal-default').modal('hide');
                    $('#accountTable').DataTable().ajax.reload(null, false);
                    getList();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
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
                    $btn.prop('disabled', false).text('Save');
                }
            });
        });

    });
</script>

{{-- <script>
    // Set CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Submit form
    $(document).ready(function() {
        loadCategoryList();
        loadAccountList();

        $('#saveExpenseBtn').click(function(e) {
            e.preventDefault();

            let $btn = $(this);
            let $form = $('#createExpenseForm');
            let formData = $form.serialize();

            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('expense.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    toastr.success(response.message || 'Expense created successfully');
                    $form[0].reset();
                    $('#modal-default').modal('hide');
                    $('#accountTable').DataTable().ajax.reload(null, false);
                    getList();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message || 'An error occurred.');
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Save');
                }
            });
        });
    });
</script> --}}
