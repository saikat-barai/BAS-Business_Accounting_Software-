<!-- Pretty Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-danger text-white rounded-top">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-3 fs-5 text-dark">
                    Are you sure you want to delete this record?<br>
                    <small class="text-muted">This action cannot be undone.</small>
                </p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 me-2" data-dismiss="modal">
                        Cancel
                    </button>
                    <button id="confirmDeleteBtn" type="button" class="btn btn-danger px-4">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
