 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <h1>Invoice List</h1>
             </div>
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="{{ route('invoice') }}">Invoice</a></li>
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
                             <i class="fas fa-plus-circle mr-1"></i> Add New Invoice
                         </button>
                     </div>
                     <!-- /.card-header -->
                     <div class="card-body">
                         <table class="table" id="tableData">
                             <thead class="thead-light">
                                 <tr>
                                     <th>Date</th>
                                     <th>Invoice</th>
                                     <th>Client Name</th>
                                     <th>Product/Service</th>
                                     <th>Total Amount (<strong>&#2547</strong>)</th>
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


 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

 <!-- DataTables JS -->
 <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

 {{-- script for payment status  --}}
 <script>
     getList();
     async function getList() {
         let res = await axios.get('/invoice-list');
         console.log(res.data.data);
         let tableList = $("#tableList");
         let tableData = $("tableData");
         // tableData.DataTable().destroy();
         if ($.fn.DataTable.isDataTable('#tableData')) {
             $('#tableData').DataTable().destroy();
         }
         //  $('#tableData').DataTable().destroy();
         tableList.empty();
         res.data.data.forEach(function(item, index) {
             let invoiceItem = item.items[0];
             let row = ` <tr>
                             <td>${item.invoice_date}</td>
                             <td>${item.invoice_number}</td>
                            <td>${item.client.name}</td>
                            <td>${invoiceItem ? invoiceItem.description : 'N/A'}</td>
                            <td>${item.total}</td>
                            <td>${getStatusBadge(item.status)}</td>
                            <td class="text-center">
                                <button data-id="${item.id}" class="btn btn-sm btn-primary downloadInvoiceBtn" title="Download"><i class="fas fa-download"></i></button>
                                <button data-id="${item.id}" class="btn btn-sm btn-success viewInvoiceBtn" title="View"><i class="fas fa-eye"></i></button>
                                <button data-id="${item.id}" class="btn btn-sm btn-warning editInvoiceBtn" title="Edit"><i class="fas fa-edit"></i></button>
                                <a href="javascript:void(0)" onclick="confirmDelete(${item.id})" class="btn btn-sm btn-danger" title="Delete">
                                 <i class="fas fa-trash-alt"></i>
                                </a>
                           </td>
                            </tr>`
             tableList.append(row);
         })

         let table = new DataTable('#tableData', {
             order: [
                 [1, 'desc']
             ],
             lengthMenu: [
                 [5, 10, 25, 50, -1],
                 ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
             ]
         });

         function getStatusBadge(status) {
             switch (status) {
                 case 'paid':
                     return `<span class="badge badge-success">Paid</span>`;
                 case 'partially_paid':
                     return `<span class="badge badge-warning">Partially Paid</span>`;
                 case 'unpaid':
                     return `<span class="badge badge-danger">Unpaid</span>`;
                 default:
                     return `<span class="badge badge-secondary">${status}</span>`;
             }
         }

     }
 </script>

 {{-- script for download invoice --}}
 <script>
     $(document).on('click', '.downloadInvoiceBtn', function() {
         let invoiceId = $(this).data('id');
         window.open(`/invoice-download/${invoiceId}`, '_blank');
     });
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
                 axios.delete(`/invoice-delete/${id}`)
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
