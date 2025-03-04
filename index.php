<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css" rel="stylesheet">
    <style>
        table.dataTable th.dt-type-numeric, 
        table.dataTable th.dt-type-date, 
        table.dataTable td.dt-type-numeric, 
        table.dataTable td.dt-type-date{
            text-align: left;
        }
        #main_container{
            width: 100%;
            height: auto;
            display: flex;
            padding: 50px;
            justify-content: center;
        }
        #container{
            width: 90%;
            height: auto;
            padding: 50px;
        }
    </style>
</head>
<body>
    <div id="main_container" >
        <div id="container" class="col-10 shadow border rounded-2">
            <div class="mb-3">
                <?php include '1-add.php'?>
                <button type="button" id="updateBtn" class="btn btn-primary margin " data-bs-toggle="modal" data-bs-target="#UpdateModal" disabled>Edit</button>     
                
                <div class="modal fade" id="UpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form action="2-update.php" method="POST" id="updateForm">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel">Edit Employee</h4>
                                    </div><br>
                                    
                                    <input type="hidden" name="id" id="employee_id">
    
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" name="salary" id="salary" class="form-control" placeholder="Salary" required max="10000">
                                    </div>
                                    <div class="mb-3">
                                        <input type="date" name="hire_date" id="hire_date" class="form-control" required>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="deleteBtn" class="btn btn-primary" disabled >Delete</button>
            </div>
            <div id="main">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Salary</th>
                            <th>Hire Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include '4-show.php'?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
    <script>
        $(document).ready(function () {
            let table = new DataTable('#example');
            let selectedRow = null;
            let selectedRowData = null;

            // Handle row selection
            $('#example tbody').on('click', 'tr', function () {
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');
                    selectedRow = null;
                    selectedRowData = null;
                } else {
                    $('#example tbody tr').removeClass('table-primary');
                    $(this).addClass('table-primary');
                    selectedRow = this;
                    selectedRowData = table.row(this).data();
                }
                
                $('#updateBtn').prop('disabled', !selectedRow);
                $('#deleteBtn').prop('disabled', !selectedRow);
            });

            $('#updateBtn').click(function () {
                if (selectedRowData) {
                    $('#employee_id').val(selectedRowData[0]);
                    $('#first_name').val(selectedRowData[1]);
                    $('#last_name').val(selectedRowData[2]);
                    $('#salary').val(selectedRowData[3]);
                    
                    let rawDate = selectedRowData[4];
                    let formattedDate = new Date(rawDate).toISOString().split('T')[0];

                    $('#hire_date').val(formattedDate);
                }
            });

            $('#deleteBtn').click(function () {
                if (selectedRowData) {
                    let employeeId = selectedRowData[0];
                    
                    $.ajax({
                        url: '3-delete.php',
                        type: 'POST',
                        data: { id: employeeId },
                        success: function (response) {
                            if (response === 'success') {
                                table.row(selectedRow).remove().draw();
                                selectedRow = null;
                                selectedRowData = null;
                                $('#deleteBtn').prop('disabled', true);
                                $('#updateBtn').prop('disabled', true);
                            } else {
                                alert('Failed to delete employee.qwertyuio');
                            }
                        }
                    });
                }
            });
        });
    </script>
</html>