 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <h1>Account List</h1>
             </div>
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="{{ route('account') }}">Account</a></li>
                 </ol>
             </div>
         </div>
     </div><!-- /.container-fluid -->
 </section>

 <!-- Main content -->
 <section class="content">
     <div class="container-fluid">
         <div class="row">
             <div class="col-12">
                 <div class="card">
                     <div class="card-header d-flex justify-content-end align-items-center">
                         <button data-toggle="modal" data-target="#modal-default" class="btn btn-primary">
                             <i class="fas fa-plus-circle mr-1"></i> Add New Account
                         </button>
                     </div>
                     <!-- /.card-header -->
                     <div class="card-body">
                         <table class="table" id="tableData">
                             <thead class="thead-light">
                                 <tr>
                                     <th>Account Name</th>
                                     <th>Type</th>
                                     <th>Account Number</th>
                                     <th class="text-end">Opening Balance (<strong>&#2547</strong>)</th>
                                     <th class="text-end">Current Balance (<strong>&#2547</strong>)</th>
                                     <th class="text-center">Actions</th>
                                 </tr>
                             </thead>
                             <tbody id="tableList">

                             </tbody>
                         </table>
                     </div>
                     <!-- /.card-body -->
                 </div>
                 <!-- /.card -->
             </div>
             <!-- /.col -->
         </div>
         <!-- /.row -->
     </div>
     <!-- /.container-fluid -->
 </section>



 <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


 <script>
     getList();
     async function getList() {
         let res = await axios.get('/account-list');
         let tableList = $("#tableList");
         let tableData = $("tableData");
         // tableData.DataTable().destroy();
         $('#tableData').DataTable().destroy();
         tableList.empty();
         res.data.data.forEach(function(item, index) {
             let row = ` <tr>
                             <td>${item.name}</td>
                            <td>${item.type}</td>
                            <td>${item.account_number}</td>
                            <td>${item.opening_balance}</td>
                            <td>${item.current_balance}</td>
                            <td class="text-center">
                                <button data-id="${item.id}" class="btn btn-sm btn-warning editBtn" title="Edit"><i class="fas fa-edit"></i></button>
                                <a href="javascript:void(0)" onclick="confirmDelete(${item.id})" class="btn btn-sm btn-danger" title="Delete">
                                 <i class="fas fa-trash-alt"></i>
                                </a>
                           </td>
                            </tr>`
             tableList.append(row);
         })

         $(".editBtn").on("click", async function() {
             let id = $(this).data("id");
             await getSingleData(id);
             $("#update-modal").modal("show");
         })


         let table = new DataTable('#tableData', {
             order: [
                 [0, 'asc']
             ],
             lengthMenu: [
                 [5, 10, 25, 50, -1],
                 ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
             ]
         });

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
                 axios.delete(`/account-delete/${id}`)
                     .then(response => {
                         toastr.success(response.data.message);
                         getList();
                     })
                     .catch(error => {
                         toastr.error('Failed to delete account');
                     });
             }
         });
     }
 </script>
