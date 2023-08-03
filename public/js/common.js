let deleteId = null;
function showConfirmDeleteModal(id) {
    deleteId = id;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal._element.addEventListener('hidden.bs.modal', function () {
        deleteId = null;
    });
    modal.show();
}
function deleteUser() {
    if (deleteId !== null) {
        const formId = `deleteForm_${deleteId}`;
        document.getElementById(formId).submit();
    }
}
function removeModalBackdrop() {
    $('.modal-backdrop').remove();
}
function hideSuccessMessage() {
    document.getElementById('success-message').style.display = 'none';
}
