 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <h1>Payment History</h1>
             </div>
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="{{ route('payment') }}">Payment</a></li>
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
                             <i class="fas fa-plus-circle mr-1"></i> Add New Payment
                         </button>
                     </div>
                     <!-- /.card-header -->
                     <div class="card-body">
                         <table class="table" id="tableData">
                             <thead class="thead-light">
                                 <tr>
                                     <th>Invoice</th>
                                     <th class="text-center">Account Name</th>
                                     <th class="text-end">Paybale</th>
                                     <th class="text-end">Due</th>
                                     <th class="text-end">Date</th>
                                     <th class="text-end">Notes</th>
                                     <th class="text-end">Status</th>
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
         let res = await axios.get('/payment-list');
         console.log(res.data.data);
         let tableList = $("#tableList");
         let tableData = $("tableData");
         // tableData.DataTable().destroy();
         $('#tableData').DataTable().destroy();
         tableList.empty();
         res.data.data.forEach(function(item, index) {
             let row = ` <tr>
                             <td>${item.invoice.invoice_number}</td>
                            <td>${item.account.name}</td>
                            <td>${item.invoice.total}</td>
                            <td>${item.invoice.total - item.invoice.paid_amount}</td>
                            <td>${item.date}</td>
                            <td>${item.notes}</td>
                            <td>${item.invoice.status}</td>
                            <td class="text-center">
                                <a href="javascript:void(0)" onclick="confirmDelete(${item.id})" class="btn btn-sm btn-danger" title="Delete">
                                 <i class="fas fa-trash-alt"></i>
                                </a>
                           </td>
                            </tr>`
             tableList.append(row);
         })

        //  $(".editBtn").on("click", async function() {
        //      let id = $(this).data("id");
        //      await getSingleData(id);
        //      $("#update-modal").modal("show");
        //  })


         let table = new DataTable('#tableData', {
            order: [[4, 'desc']],
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
                 axios.delete(`/payment-delete/${id}`)
                     .then(response => {
                         toastr.success(response.data.message);
                         getList();
                     })
                     .catch(error => {
                         toastr.error('Failed to delete client');
                     });
             }
         });
     }
 </script>
