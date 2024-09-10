<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <!-- Modal for button -->
    <div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="errorMessage" class="alert alert-warning d-none">
                </div>
                <form id="saveStudent" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="">Name</label>
                            <input type="text" name="userName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Email</label>
                            <input type="text" name="userEmail" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Phone</label>
                            <input type="text" name="userPhone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Course</label>
                            <input type="text" name="userCourse" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Php Ajax tut
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                                Add Student
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="successMessage" class="alert alert-success d-none"></div>
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>PHONE</th>
                                    <th>COURSE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="studentData">
                            </tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {

            function fetchStudents() {
                $.ajax({
                    type: "GET",
                    url: "student-action.php",
                    
                    success: function(response) {
                        var students = $.parseJSON(response);
                        console.log(students);
                        var studentHtml = '';
                        $.each(students, function(key, student) {
                            studentHtml += `
                                <tr>
                                    <td>${student.id}</td>
                                    <td>${student.name}</td>
                                    <td>${student.email}</td>
                                    <td>${student.phone}</td>
                                    <td>${student.course}</td>
                                    <td>
                                        <a href="" class="btn btn-info">View</a>
                                        <button value="${student.id}" class="editStudentBtn btn btn-success">Edit</button>
                                        <button value="${student.id}" class="btn btn-danger deleteBtn">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#studentData').html(studentHtml);
                    }
                });
            }

            fetchStudents();


            $(document).on("submit", "#saveStudent", function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('save_student', true);
                $.ajax({
                    type: "POST",
                    url: "student-action.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var res = $.parseJSON(response);
                        if (res.status == 422) {
                            $('#errorMessage').removeClass('d-none');
                            $('#errorMessage').text(res.message);
                        } else if (res.status == 200) {
                            $('#errorMessage').addClass('d-none');
                            $('#studentAddModal').modal('hide');
                            $('#saveStudent')[0].reset();
                            fetchStudents();

                        }
                    }
                });
            });
            $(document).on('click', '.deleteBtn', function(e) {
                e.preventDefault();
                var studentId = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "student-action.php",
                    data: {
                        'delete_student': true,
                        'studentId': studentId,
                    },

                    success: function(response) {
                        var res = $.parseJSON(response);
                        if (res.status == 200) {
                            $('#successMessage').removeClass('d-none');
                            $('#successMessage').text(res.message);
                            fetchStudents();
                        }
                    }
                });
            });
            $(document).on('click', '.editStudentBtn', function(e) {
                e.preventDefault();
                var studentId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "student-action.php?student_id=" + studentId,
                    contentType:'application/json',
                    success: function(response) {
                        var res = $.parseJSON(response);
                        if (res.status == 200) {
                            console.log('confirmed')
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>