<!-- Bootstrap Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Invoice Details</h5>
                <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>

            <div class="modal-body">
                <p><strong>Invoice #:</strong> <span id="modalInvoiceNumber"></span></p>
                <p><strong>Date:</strong> <span id="modalInvoiceDate"></span></p>
                <p><strong>Client:</strong> <span id="modalClientName"></span></p>
                <p><strong>Status:</strong> <span id="modalInvoiceStatus"></span></p>

                <hr>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price (<strong>&#2547</strong>)</th>
                            <th>Total <strong>(&#2547)</strong></th>
                        </tr>
                    </thead>
                    <tbody id="modalInvoiceItems">
                        <!-- JS will inject items here -->
                    </tbody>
                </table>

                <hr>

                <p><strong>Subtotal: </strong><span id="modalSubtotal"> </span>(&#2547;)</p>
                <p><strong>Tax: </strong> <span id="modalTax"></span>(%)</p>
                <p><strong>Discount: </strong> <span id="modalDiscount"></span>(&#2547;)</p>
                <h5><strong>Total: </strong> <span id="modalTotal"></span>(&#2547;)</h5>

            </div>

        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation to catch click on dynamically generated buttons
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.viewInvoiceBtn')) {
                const button = e.target.closest('.viewInvoiceBtn');
                const invoiceId = button.getAttribute('data-id');

                fetch(`/invoice/${invoiceId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Populate modal fields
                        document.getElementById('modalInvoiceNumber').textContent = data
                            .invoice_number;
                        document.getElementById('modalInvoiceDate').textContent = data.invoice_date;
                        document.getElementById('modalClientName').textContent = data.client.name;
                        document.getElementById('modalInvoiceStatus').textContent = data.status
                            .charAt(0).toUpperCase() + data.status.slice(1);

                        // Fill items
                        const tbody = document.getElementById('modalInvoiceItems');
                        tbody.innerHTML = '';
                        data.items.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td>${item.description}</td>
                            <td>${item.quantity}</td>
                            <td>${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td>${parseFloat(item.total).toFixed(2)}</td>
                        `;
                            tbody.appendChild(row);
                        });

                        // Totals
                        document.getElementById('modalSubtotal').textContent = parseFloat(data
                            .subtotal).toFixed(2);
                        document.getElementById('modalTax').textContent = parseFloat(data.tax)
                            .toFixed(2);
                        document.getElementById('modalDiscount').textContent = parseFloat(data
                            .discount).toFixed(2);
                        document.getElementById('modalTotal').textContent = parseFloat(data.total)
                            .toFixed(2);

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                        modal.show();
                    })
                    .catch(error => {
                        alert('Failed to fetch invoice details.');
                        console.error('Fetch error:', error);
                    });
            }
        });
    });
</script>
