<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Category List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('category') }}">Category</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Category List Table -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="categoryTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Category Name</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- JS will populate rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create New Category Form -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4 id="categoryFormTitle">Create New Category</h4>
                    </div>
                    <div class="card-body">
                        <form id="createCategoryForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter category name" required>
                                <input type="text" class="form-control d-none" name="id" id="updateID">
                            </div>
                            <button type="submit" class="btn btn-primary" id="createCategoryBtn">
                                <i class="fas fa-plus-circle mr-1"></i> Add Category
                            </button>
                            <a onclick="Update()" class="btn btn-primary d-none" id="updateCategoryBtn">
                                <i class="fas fa-plus-circle mr-1"></i> Update Category
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
{{-- <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" /> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<script>
    let dataTableInstance;

    $(document).ready(function() {
        loadCategoryList();

        $('#createCategoryForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('category.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    toastr.success(response.message || 'Category added successfully!');
                    $('#createCategoryForm')[0].reset();
                    loadCategoryList(true); // reload table
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        });
    });

    async function loadCategoryList(reload = false) {
        try {
            let res = await axios.get('/category-list');

            // If reloading, destroy previous instance
            if (dataTableInstance && $.fn.DataTable.isDataTable('#categoryTable')) {
                dataTableInstance.clear().destroy();
            }

            let rows = '';
            res.data.data.forEach(function(item) {
                rows += `
                    <tr>
                        <td>${item.name}</td>
                        <td class="text-center">
                            <button data-id="${item.id}" class="btn btn-sm btn-warning editBtn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="javascript:void(0)" onclick="confirmDelete(${item.id})" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>`;
            });

            $('#categoryTable tbody').html(rows);

            $(".editBtn").on("click", async function() {
                let id = $(this).data("id");
                let res = await axios.post('/category-by-id', {
                    id: id
                })
                document.getElementById('categoryFormTitle').innerText = 'Update Category';
                document.getElementById('createCategoryBtn').style.display = 'none';
                document.getElementById('updateCategoryBtn').classList.remove('d-none');
                document.getElementById("name").value = res.data.data.name;
                document.getElementById("updateID").value = res.data.data.id;
            })
            // Initialize or re-initialize DataTable
            dataTableInstance = $('#categoryTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                ]
            });

        } catch (err) {
            toastr.error('Failed to load category list.');
        }
    }
</script>

<script>
    async function Update() {
        try {
            let name = document.getElementById('name').value.trim();
            let id = document.getElementById('updateID').value;

            if (!name) {
                toastr.warning('Category name is required.');
                return;
            }

            const res = await axios.post('/category-update', {
                id: id,
                name: name,
            });

            // Show success message
            toastr.success(res.data.message);

            // Reset UI to Create mode
            document.getElementById('categoryFormTitle').innerText = 'Create New Category';
            document.getElementById('createCategoryBtn').style.display = 'block';
            document.getElementById('updateCategoryBtn').classList.add('d-none');
            document.getElementById('updateID').value = ''; // Clear ID
            $('#createCategoryForm')[0].reset();

            // Reload category list
            loadCategoryList(true);

        } catch (error) {
            if (error.response && error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            } else {
                toastr.error('Something went wrong.');
            }
            console.error(error);
        }
    }
</script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Once deleted, you won't be able to recover!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/category-delete/${id}`)
                    .then(response => {
                        toastr.success(response.data.message);
                        loadCategoryList();
                    })
                    .catch(error => {
                        toastr.error('Failed to delete account');
                    });
            }
        });
    }
</script>
