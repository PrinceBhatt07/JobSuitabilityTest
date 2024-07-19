@extends('layout/admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Tags </h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubjectModal">
            Add Tag
        </button>
        <br><br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tag</th>
                    <th style="cursor : pointer" scope="col" title="Use This id in csv file to import q&a">Tag Id
                        &nbsp <span><i class="fa fa-question-circle"
                                style="font-size:18px;color:rgb(71, 216, 245)"></i></span></th>
                    <th scope="col">Add/Update Questions</th>
                    {{-- <th scope="col">Question</th> --}}
                    <th scope="col">Number of Questions</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody id="subjectTable">
            </tbody>
        </table>

        <!--Add Tag Modal -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addSubject" action="{{ redirect('addsubject') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label>Tag:-</label>
                            <input type="text" class="w-100" name="subject" placeholder="Enter any Tag name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                </div>
                </form>
            </div>
        </div>


        <!-- Edit Tag Modal -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Tag</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editSubject" action="{{ redirect('editsubject') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label>Tag:-</label>
                            <input type="text" id="edit_subject" name="subject" placeholder="Enter any Tag name"
                                required>
                            <input type="hidden" name="id" id="edit_subject_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Tag Modal -->
    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteSubject" action="{{ redirect('deleteSubject') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are You Sure to Delete?</p>
                        <input type="hidden" name="id" id="delete_subject_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>

    <!--Add Question Modal -->
    <div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Q&A</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addQna">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="subject_id" id="addExamId">
                        <input type="search" name="search" id="search" name="search" onkeyup="searchTable()"
                            class="w-100" placeholder="Search Here">
                        <br><br>
                        <table class="table" id="questionTable">
                            <thead>
                                <th>Select</th>
                                <th>Question</th>
                            </thead>
                            <tbody class="addBody">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Q&A</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--See Question Modal -->
    {{-- <div class="modal fade" id="seeQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Questions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <table class="table" id="seeQuestionTable">
                        <thead>
                            <th>Sr.no</th>
                            <th>Question</th>
                            <th>Delete</th>
                        </thead>
                        <tbody class="seeQuestionTableBody">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div> --}}
    </div>



    {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            function loadTable() {
                $.ajax({
                    url: "{{ route('adminDashboardData') }}",
                    type: "GET",
                    success: function(data) {
                        console.log('====', data);
                        if (data.success == true) {
                            var subjectData = data.data;
                            var html = "";
                            subjectData.map(function(data, index) {
                                console.log(data.id)
                                html += ` <tr>
                            <td>${index + 1} </td>
                            <td>${data.subject}</td>
                            <td><em>id:</em> ${data.id}</td>
                            <td><a href="#" ${data.questions_count > 0 ? '' : 'style="color:red"'} data-id="${data.id}" class="addQuestion"
                                    data-toggle="modal" data-target="#addQnaModal">${data.questions_count > 0 ? 'Update ' : 'Add '}Question</a></td>

                            <td>${data.questions_count}</td>
                            <td><button class="btn btn-info editButton" data-subject="${data.subject}"
                                    data-id="${data.id}" data-toggle="modal"
                                    data-target="#editSubjectModal">Edit</button></td>
                            <td><button class="btn btn-danger deleteButton" data-subject="${data.subject}"
                                    data-id="${data.id}" data-toggle="modal"
                                    data-target="#deleteSubjectModal">Delete</button></td>
                        </tr>`;
                            })
                            $('#subjectTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">No Tag Found!</td></tr>`;
                            $('#subjectTable').html(html);
                        }
                    }
                })
            }
            loadTable();

            $("#addSubject").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addSubject') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        console.log("Data received:", data);
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $("#addSubject")[0].reset();
                            $('#addSubjectModal').modal('hide');
                            loadTable();
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                });
            })

            // Edit
            $(document).on('click', '.editButton', function() {
                let subject_id = $(this).attr('data-id');
                let subject = $(this).attr('data-subject');
                $('#edit_subject').val(subject);
                $('#edit_subject_id').val(subject_id);
            });

            $("#editSubject").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('editSubject') }}",
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
                            $('#editSubjectModal').modal('hide');
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            // Delete
            $(document).on('click', '.deleteButton', function() {
                var subject_id = $(this).attr('data-id');
                $("#delete_subject_id").val(subject_id);
            });

            $("#deleteSubject").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('deleteSubject') }}",
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
                            $('#deleteSubjectModal').modal('hide');
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //Get the QUESTIONS
            $(document).on('click', '.addQuestion', function() {
                var id = $(this).attr('data-id');
                $('#addExamId').val(id);

                $.ajax({
                    url: "{{ route('getQuestionsForSubject') }}",
                    type: "GET",
                    data: {
                        subject_id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var questions = data.data;
                            console.log(questions);
                            var html = '';
                            if (questions.length > 0) {
                                console.log('-----====---', questions[0].subject_id);

                                for (let i = 0; i < questions.length; i++) {
                                    console.log('--------------', questions[i].subject_id, id);
                                    var prop =
                                        questions[i].subject_id.includes(parseInt(id)) ?
                                        'checked' : '';

                                    html += `<tr>
                                                    <td>
                                                        <input ${prop} type="checkbox" value="${questions[i].id}" name="questions_ids[]">
                                                    </td>
                                                    <td>
                                                        ${questions[i].question}
                                                    </td>
                                                </tr>`;
                                }

                                if (html == '') {
                                    html += `<tr>
                                    <td colspan="2">Question without any subject not Available!</td>
                                    </tr>`;
                                }


                            } else {
                                html += `<tr>
                            <td colspan="2">Question not Available!</td>
                        </tr>`;
                            }
                            $('.addBody').html(html);
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //addQuestions
            $("#addQna").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addQuestionsInSubject') }}",
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
                            loadTable();
                            $('#addQnaModal').modal('hide')
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });
            $(document).on('click', ".seeQuestion", function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('getSubjectQuestions') }}",
                    type: "GET",
                    data: {
                        subject_id: id
                    },
                    success: function(data) {
                        var html = '';
                        var questions = data.data;
                        if (questions.length > 0) {
                            console.log(questions.length)
                            for (let i = 0; i < questions.length; i++) {

                                html += `
                                <tr>
                                <td>` + (i + 1) + `</td>
                                <td>
                                ` + questions[i]['question'] + `
                                </td>
                                <td>
                                <button class="btn btn-danger deleteQuestion"
                                data-id ="` +
                                    questions[i]['id'] + `">Delete</button>
                                </td>
                                </tr>
                                `
                            }
                        } else {
                            html += `
                                <tr>
                                <td colspan="1" >Questions are not Available!</td>
                                </tr>
                                `
                        }

                        $(".seeQuestionTableBody").html(html);
                    }
                })
            });

            $(document).on('click', '.deleteQuestion', function() {
                var id = $(this).attr('data-id');
                var obj = $(this);
                $.ajax({
                    url: "{{ route('deleteSubjectQuestions') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            obj.parent().parent().remove();
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.error("", data.msg);
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

        });

        function searchTable() {
            var filter, input, td, tr, i, txtValue;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            table = document.getElementById('questionTable');
            tr = table.getElementsByTagName('tr');
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td')[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";

                    }
                }
            }
        }
    </script>
@endsection
