<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Edit <span id="user_name"></span></h1>
                    <p>Fill the form below to edit user.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <div id="editUserModalContent">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" style="width: 100px;height: 100px;margin-top: 50px;margin-bottom: 100px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>