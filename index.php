<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Car Rental CRUD</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2>🚗 Elite Car Rental Management</h2>
            <p class="text-white-50">Manage your fleet with ease.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#addModal">
                + Add New Car
            </button>
        </div>
    </div>

    <div class="glass-panel">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Daily Rate</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="carData">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Car</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                <div class="modal-body text-white">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" required placeholder="e.g. Toyota">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="model" class="form-control" required placeholder="e.g. Camry">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" required min="1990" max="2026">
                        </div>
                        <div class="col">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Daily Rate ($)</label>
                            <input type="number" step="0.01" name="daily_rate" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Rented">Rented</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Car</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Car Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body text-white">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" id="edit_brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="model" id="edit_model" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" id="edit_year" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" id="edit_color" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Daily Rate ($)</label>
                            <input type="number" step="0.01" name="daily_rate" id="edit_daily_rate" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Rented">Rented</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Car</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function(){
        
        // Fetch all cars
        function fetchCars(){
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: { action: 'fetch' },
                success: function(response){
                    $('#carData').html(response);
                }
            });
        }
        
        // Initial fetch
        fetchCars();

        // Custom SweetAlert Mixin for Dark Theme
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#1e293b',
            color: '#fff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Add Car
        $('#addForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response){
                    if(response.status === 'success'){
                        $('#addModal').modal('hide');
                        $('#addForm')[0].reset();
                        fetchCars();
                        Toast.fire({ icon: 'success', title: response.message });
                    } else {
                        Toast.fire({ icon: 'error', title: response.message });
                    }
                }
            });
        });

        // Edit Car - Fetch Data into Modal
        $('body').on('click', '.editBtn', function(){
            let id = $(this).data('id');
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: { action: 'edit_fetch', id: id },
                dataType: 'json',
                success: function(response){
                    $('#edit_id').val(response.id);
                    $('#edit_brand').val(response.brand);
                    $('#edit_model').val(response.model);
                    $('#edit_year').val(response.year);
                    $('#edit_color').val(response.color);
                    $('#edit_daily_rate').val(response.daily_rate);
                    $('#edit_status').val(response.status);
                }
            });
        });

        // Update Car
        $('#editForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response){
                    if(response.status === 'success'){
                        $('#editModal').modal('hide');
                        fetchCars();
                        Toast.fire({ icon: 'success', title: response.message });
                    } else {
                        Toast.fire({ icon: 'error', title: response.message });
                    }
                }
            });
        });

        // Delete Car
        $('body').on('click', '.deleteBtn', function(){
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                background: '#1e293b',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: { action: 'delete', id: id },
                        dataType: 'json',
                        success: function(response){
                            if(response.status === 'success'){
                                fetchCars();
                                Toast.fire({ icon: 'success', title: response.message });
                            } else {
                                Toast.fire({ icon: 'error', title: response.message });
                            }
                        }
                    });
                }
            });
        });

    });
</script>

</body>
</html>
