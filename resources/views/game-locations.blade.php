@extends('layouts.adminLayout')

@section('title', 'Manage Game Locations')

@section('content')
<div class="container">
    <div class="card">
        <h2 class="title">إدارة أماكن توفر الألعاب <i class="fa-solid fa-gamepad"></i></h2>
        <button onclick="openModal()" class="add-button">
            <i class="fa-solid fa-plus"></i> أضف منصة جديدة
        </button>

        <div class="table-container">
            <table class="game-locations-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المنصة</th>
                        <th>الأيقونة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @if($gameLocations->isEmpty())
                    <tr>
                        <td colspan="4" class="no-record">لا يوجد منصات</td>
                    </tr>
                    @endif
                    @foreach($gameLocations as $gameLocation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $gameLocation->name }}</td>
                        <td class="text-center">
                            @if($gameLocation->icon)
                            <img src="{{ asset('storage/' . $gameLocation->icon) }}" alt="{{ $gameLocation->name }}" class="icon">
                            @else
                            <span class="no-icon">لا يوجد</span>
                            @endif
                        </td>
                        <td class="actions">
                            <button onclick="editGameLocation({{ $gameLocation->id }}, '{{ $gameLocation->name }}', '{{ $gameLocation->icon }}')" class="edit-button">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="confirmDelete({{ $gameLocation->id }})" class="delete-button">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="gameLocationModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">أضف منصة جديدة</h3>
            <form id="gameLocationForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="gameLocationId" name="gameLocationId">
                <div id="methodField"></div>

                <label for="gameLocationName" class="label">اسم المنصة</label>
                <input type="text" id="gameLocationName" name="name" class="input" placeholder="مثال: Steam">

                <label for="gameLocationIcon" class="label">أيقونة المنصة</label>
                <input type="file" id="gameLocationIcon" name="icon" class="input-file">

                <div class="modal-actions">
                    <button type="button" onclick="closeModal()" class="cancel-button">إلغاء</button>
                    <button type="submit" class="save-button">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
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
        document.getElementById("modalTitle").innerText = "أضف منصة جديدة";
        document.getElementById("gameLocationForm").setAttribute("action", "{{ route('game-locations.store') }}");
        document.getElementById("methodField").innerHTML = "";
        document.getElementById("gameLocationForm").reset();
        document.getElementById("gameLocationModal").classList.remove("hidden");
    }

    function editGameLocation(id, name, icon) {
        document.getElementById("modalTitle").innerText = "تعديل المنصة";
        document.getElementById("gameLocationForm").setAttribute("action", "/admin/game-locations/" + id);
        document.getElementById("methodField").innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById("gameLocationName").value = name;

        let iconPreview = document.getElementById("iconPreview");
        if (!iconPreview) {
            iconPreview = document.createElement("img");
            iconPreview.id = "iconPreview";
            iconPreview.classList.add("icon-preview");
            document.getElementById("gameLocationIcon").insertAdjacentElement('afterend', iconPreview);
        }

        if (icon) {
            iconPreview.src = "/storage/" + icon;
            iconPreview.style.display = "block";
        } else {
            iconPreview.style.display = "none";
        }

        document.getElementById("gameLocationModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("gameLocationModal").classList.add("hidden");
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟'
            , text: "لن تتمكن من استرجاع هذه المنصة!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'نعم، احذف!'
            , cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = "/admin/game-locations/" + id;
                form.submit();
            }
        });
    }

</script>
@endsection

<style>
    /* Container for the entire content */
    .container {
    padding: 2rem;
    height: 100vh;
    padding-top: 5rem;
    }

    /* Card styling for the content box */
    .card {
    background-color: #2c2c38;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
    max-width: 900px;
    margin: 0 auto;
    }

    /* Title styling */
    .title {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 1rem;
    text-align: center;
    }

    /* Add button */
    .add-button {
    background-color: #8f333a;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }

    .add-button:hover {
    background-color: #ad3741;
    }

    /* Table container */
    .table-container {
    margin-top: 1.5rem;
    overflow-x: auto;
    }

    /* Table styling */
    .game-locations-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.875rem;
    }

    .game-locations-table th, .game-locations-table td {
    padding: 0.5rem 1rem;
    border-bottom: 1px solid #e2e2e2;
    }

    .game-locations-table th {
    background-color: #f1f1f1;
    color: #333;
    }

    .game-locations-table td {
    color: #555;
    }

    /* Empty record message */
    .no-record {
    text-align: center;
    font-size: 1.25rem;
    color: white;
    }

    /* Icon in the table */
    .icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.25rem;
    display: inline-block;
    }

    /* No icon text */
    .no-icon {
    color: #bbb;
    }

    /* Actions buttons */
    .actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    }

    .edit-button, .delete-button {
    font-size: 1.25rem;
    cursor: pointer;
    }

    .edit-button {
    color: #fbbf24;
    }

    .edit-button:hover {
    color: #f59e0b;
    }

    .delete-button {
    color: #ef4444;
    }

    .delete-button:hover {
    color: #dc2626;
    }

    /* Modal styling */
    .modal {
    display: none;
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    }

    .modal-content {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    width: 24rem;
    }

    .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    }

    .label {
    font-size: 0.875rem;
    color: #333;
    text-align: right;
    margin-bottom: 0.25rem;
    }

    .input, .input-file {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    }

    .cancel-button {
    background-color: #e2e8f0;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    }

    .save-button {
    background-color: #2563eb;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    }

    .save-button:hover {
    background-color: #1d4ed8;
    }


</style>