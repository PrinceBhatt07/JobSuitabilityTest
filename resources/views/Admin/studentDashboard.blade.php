@extends('layout/admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Students</h2>
        <!-- Button trigger modal -->
        <div class="d-flex justify-content-between">
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">
                    Add Student
                </button>
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#importStudentModal">
                    Import Students
                </button>
                <a class="btn btn-warning float-end" href="{{ route('users.export') }}">Export Students</a>

            </div>
            <div>
                <form>
                    @csrf
                    <input type="search" placeholder="Search students" id="search" name="search" /> <i
                        class="fa fa-search"></i>
                </form>
            </div>
        </div>
        <br><br>
        <table class="table" id="studentTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email ID</th>
                    <th scope="col">Mobile no.</th>
                    <th scope="col">Qualification</th>
                    <th scope="col">Self Registered</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Updated at</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody id="studenttable">
            </tbody>
        </table>

        <div class="pagination">
            {{ $students->links('pagination::bootstrap-4') }}
        </div>

        <!--ADD Student Model-->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addStudent">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="user_id" id="user_id">
                                    <input type="text" name="name" class="w-100" placeholder="Enter the Student Name"
                                        required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input type="email" name="email" class="w-100"
                                        placeholder="Enter the Student Email Id" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input class="w-100" type="text" name="contact" placeholder="Enter contact number"
                                        required>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer">
                            <span class="error" style="color:red"></span>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Edit Student Model-->
        <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editStudent">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="id" id="id">
                                    <input type="text" name="name" id="name" class="w-100"
                                        placeholder="Enter the Student Name" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input type="email" id="email" name="email" class="w-100"
                                        placeholder="Enter the Student Email Id" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <input class="w-100" type="text" pattern="[0-9]{10}" name="contact"
                                        id="contact" placeholder="Enter contact number" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <span class="error" style="color:red"></span>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary updateButton">Update Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Delete Student Model-->
        <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="deleteStudent">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="id" id="student_id">
                                    <p>Are You sure to Delete?</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <span class="error" style="color:red"></span>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!--Import Student Modal -->
        <div class="modal fade" id="importStudentModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Import Students</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-info">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>

        <script>
            $(document).ready(function() {
                const options = {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                };

                function loadTable(item = '') {
                    console.log(item);
                    $.ajax({
                        url: "{{ route('studentDashboardData') }}?search=" + item,
                        type: "GET",
                        success: function(data) {
                            if (data.students.data.length > 0) {
                                var studentData = data.students.data;
                                var html = "";
                                studentData.map(function(data, index) {
                                    html += `
                                            <tr>
                                            <td>${index+1}</td>
                                            <td>${data.name}</td>
                                            <td>${data.email}</td>
                                            <td>${data.contact}</td>
                                            <td>${data.qualification ? data.qualification : 'N/A'}</td>
                                            <td>${data.password ? 'Yes' : 'No'}</td>
                                            <td>${new Date(data.created_at).toLocaleString('en-US', options)}</td>
                                            <td>${new Date(data.updated_at).toLocaleString('en-US', options)}</td>
                                            <td>
                                            <button type="button" class="btn btn-info editButton" data-toggle="modal"
                                            data-id="${data.id}" data-name="${data.name}"
                                            data-email="${data.email}" data-contact="${data.contact}"
                                            data-target="#editStudentModal">
                                            Edit
                                            </button>
                                            </td>
                                            <td>
                                            <button type="button" class="btn btn-danger deleteButton" data-toggle="modal"
                                            data-id="${data.id}" data-target="#deleteStudentModal">
                                            Delete
                                            </button>
                                            </td>
                                            </tr>
                                            `;
                                })
                                $('#studenttable').html(html);
                            } else {
                                html = "";
                                html += `<tr><td colspan="2">No Students Found!</td></tr>`;
                                $('#studenttable').html(html);
                            }
                        }
                    })
                }
                loadTable();

                $(document).on('input', "#search", function(e) {
                    loadTable(e.target.value);
                });

                //Add Student
                $("#addStudent").submit(function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('addStudent') }}",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            if (data.success == true) {
                                toastr.options = {
                                    'timeOut': 900,
                                    'closeButton': true,
                                    'progressBar': true,
                                    "positionClass": "toast-top-center",
                                };
                                toastr.success("", data.msg);
                                $("#addStudent")[0].reset();
                                $('#addStudentModal').modal('hide')
                                loadTable();
                            } else {
                                toastr.options = {
                                    'timeOut': 900,
                                    'closeButton': true,
                                    'progressBar': true,
                                    "positionClass": "toast-top-center",
                                };

                                if (data && data.msg && data.msg.email && data.msg.email[0]) {
                                    toastr.error(data.msg.email[0]);
                                }

                                if (data && data.msg && data.msg.name && data.msg.name[0]) {
                                    toastr.error(data.msg.name[0]);
                                }

                                if (data && data.msg && data.msg.contact && data.msg.contact[0]) {
                                    toastr.error(data.msg.contact[0]);
                                }

                            }
                        }
                    });
                });

                //Edit
                $(document).on('click', ".editButton", function() {

                    $("#id").val($(this).attr('data-id'));
                    $("#name").val($(this).attr('data-name'));
                    $("#email").val($(this).attr('data-email'));
                    $("#contact").val($(this).attr('data-contact'));

                });


                //Update
                $("#editStudent").submit(function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('editStudent') }}",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            if (data.success == true) {
                                toastr.options = {
                                    'timeOut': 900,
                                    'closeButton': true,
                                    'progressBar': true,
                                    "positionClass": "toast-top-center",
                                };
                                toastr.success("", data.msg);
                                $('#editStudentModal').modal('hide')
                                loadTable();
                            } else {
                                alert(data.msg)
                            }
                        }
                    });
                });


                //Delete
                $(document).on('click', ".deleteButton", function() {
                    var id = $(this).attr("data-id");
                    $("#student_id").val(id);
                });

                $("#deleteStudent").submit(function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('deleteStudent') }}",
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            if (data.success == true) {
                                toastr.options = {
                                    'timeOut': 900,
                                    'closeButton': true,
                                    'progressBar': true,
                                    "positionClass": "toast-top-center",
                                };
                                toastr.error("", data.msg);
                                $('#deleteStudentModal').modal('hide')
                                loadTable();
                            } else {
                                alert(data.msg)
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
