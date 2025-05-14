@extends('layouts.adminLayout')

@section('title', 'Manage Age Ratings')

@section('content')
<div class="content-container">
    <div class="container">
        <h2 class="title">إدارة التصنيفات العمرية <i class="fa-solid fa-user-shield"></i></h2>
        <button onclick="openModal()" class="add-button">
            <i class="fa-solid fa-plus"></i> أضف تصنيف عمري جديد
        </button>

        <div class="table-container">
            <table class="age-ratings-table">
                <thead class="table-header">
                    <tr>
                        <th class="table-cell">#</th>
                        <th class="table-cell center-text">التصنيف العمري</th>
                        <th class="table-cell center-text">الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @if($ageRatings->isEmpty())
                    <tr>
                        <td colspan="3" class="no-data">لا يوجد تصنيفات عمريّة</td>
                    </tr>
                    @endif
                    @foreach($ageRatings as $ageRating)
                    <tr class="table-row">
                        <td class="table-cell">{{ $loop->iteration }}</td>
                        <td class="table-cell center-text">{{ $ageRating->rating }}</td>
                        <td class="table-cell actions">
                            <button onclick="editAgeRating({{ $ageRating->id }}, '{{ $ageRating->rating }}')" class="edit-button">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="confirmDelete({{ $ageRating->id }})" class="delete-button">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Adding/Editing Age Rating -->
    <div id="ageRatingModal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title" id="modalTitle">أضف تصنيف عمري</h3>
            <form id="ageRatingForm" method="POST">
                @csrf
                <input type="hidden" id="ageRatingId" name="ageRatingId">
                <div id="methodField"></div>
                <label class="form-label">التصنيف العمري</label>
                <input type="text" id="ageRatingValue" name="rating" class="form-input" placeholder="مثال: +18">

                <div class="modal-actions">
                    <button type="button" onclick="closeModal()" class="cancel-button">إلغاء</button>
                    <button type="submit" class="submit-button">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
    // Display SweetAlert for Success Message
    @if(session('success'))
    Swal.fire({
        title: 'Success!'
        , text: "{{ session('success') }}"
        , icon: 'success'
        , confirmButtonText: 'OK'
        , confirmButtonColor: '#3085d6'
    });
    @endif

    function openModal() {
        document.getElementById("modalTitle").innerText = "أضف تصنيف عمري";
        document.getElementById("ageRatingForm").setAttribute("action", "{{ route('age-ratings.store') }}");
        document.getElementById("methodField").innerHTML = "";
        document.getElementById("ageRatingForm").reset();
        document.getElementById("ageRatingModal").classList.remove("hidden");
    }

    function editAgeRating(id, rating) {
        document.getElementById("modalTitle").innerText = "تعديل التصنيف العمري";
        document.getElementById("ageRatingForm").setAttribute("action", "/admin/age-ratings/" + id);
        document.getElementById("methodField").innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById("ageRatingValue").value = rating;
        document.getElementById("ageRatingModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("ageRatingModal").classList.add("hidden");
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟'
            , text: "لن تتمكن من استرجاع هذا التصنيف!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'نعم، احذف!'
            , cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = "/admin/age-ratings/" + id;
                form.submit();
            }
        });
    }

    document.getElementById('ageRatingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const rating = document.getElementById('ageRatingValue').value;

        if (!rating.trim()) {
            Swal.fire({
                title: 'خطأ!'
                , text: 'التصنيف العمري مطلوب'
                , icon: 'error'
                , confirmButtonText: 'حسنًا'
            });
            return;
        }

        // Show loading indicator during data submission
        Swal.fire({
            title: 'جاري الحفظ...'
            , text: 'يرجى الانتظار أثناء حفظ البيانات'
            , allowOutsideClick: false
            , didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit the form
        this.submit();
    });

</script>

@endsection

@section('styles')
<style>
    .content-container {
        padding: 1rem;
        height: 100vh;
        padding-top: 5rem;
    }

    .container {
        max-width: 48rem;
        margin-left: auto;
        margin-right: auto;
        padding: 1.5rem;
        background-color: #2c2c38;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    .title {
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
        color: white;
        margin-bottom: 1rem;
    }

    .add-button {
        padding-left: 1rem;
        padding-right: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        background-color: #8f333a;
        color: white;
        border-radius: 0.5rem;
        margin-top: 1rem;
        transition: background-color 0.3s;
    }

    .add-button:hover {
        background-color: #ad3741;
    }

    .table-container {
        margin-top: 1rem;
        overflow-x: auto;
    }

    .age-ratings-table {
        width: 100%;
        font-size: 0.875rem;
        text-align: left;
        color: #6B7280;
    }

    .table-header {
        background-color: #e5e7eb;
        color: #374151;
    }

    .table-cell {
        padding: 0.5rem;
    }

    .center-text {
        text-align: center;
    }

    .table-row {
        border-bottom: 1px solid #e5e7eb;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .edit-button,
    .delete-button {
        font-size: 1.125rem;
        cursor: pointer;
    }

    .edit-button {
        color: #f59e0b;
    }

    .edit-button:hover {
        color: #d97706;
    }

    .delete-button {
        color: #ef4444;
    }

    .delete-button:hover {
        color: #dc2626;
    }

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 50;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        width: 24rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .form-label {
        font-size: 0.875rem;
        color: #4B5563;
        text-align: right;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.5rem;
        margin-top: 0.25rem;
        border-radius: 0.375rem;
        border: 1px solid #D1D5DB;
        text-align: right;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .cancel-button,
    .submit-button {
        padding-left: 1rem;
        padding-right: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        border-radius: 0.375rem;
    }

    .cancel-button {
        background-color: #D1D5DB;
        margin-right: 1rem;
    }

    .cancel-button:hover {
        background-color: #9CA3AF;
    }

    .submit-button {
        background-color: #2563EB;
        color: white;
    }

    .submit-button:hover {
        background-color: #1D4ED8;
    }

</style>
@endsection

