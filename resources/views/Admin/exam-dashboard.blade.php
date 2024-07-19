@extends('layout.admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Exams </h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExamModal">
            Add Exam
        </button>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Exam Name</th>
                    <th>Add/Update Tags</th>
                    <th>Questions</th>
                    <th>Exam Date</th>
                    <th>Link valid From</th>
                    <th>Link valid To</th>
                    <th>Exam Duration</th>
                    {{-- <th>Subjects</th> --}}
                    <th>Copy Exam Link</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="examTable">
            </tbody>
        </table>

        <!--Add Subject Modal -->
        <div class="modal fade" id="addSubModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add/Update Question</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addSub">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="exam_id" id="addSubjectId">
                            <input type="search" name="search" id="search" name="search" onkeyup="searchTable()"
                                class="w-100" placeholder="Search Here">
                            <table class="table mt-4" id="subjectTable">
                                <colgroup>
                                    <col style="width: 10%;">
                                    <col style="width: 60%;">
                                    <col style="width: 30%;">
                                </colgroup>
                                <thead>
                                    <th>Select</th>
                                    <th>Tag</th>
                                    <th>Questions Selected</th>
                                </thead>
                                <tbody class="addSubject">

                                </tbody>
                            </table>

                            <table class="table mt-4" id="questionTable">
                                <colgroup>
                                    <col style="width: 10%;">
                                    <col style="width: 60%;">
                                    <col style="width: 30%;">
                                </colgroup>
                                <thead>
                                    <th>Select</th>
                                    <th>Question</th>
                                    <th>Tags</th>
                                </thead>
                                <tbody class="addQuestion">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="addSubjectButton">Add Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!--Add Exam Modal -->
        <div class="modal fade" id="addExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addExam" action="{{ redirect('addsubject') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="exam_id" id="exam_id">
                            <label>
                                <h5>Exam Name:-</h5>
                            </label>
                            <input type="text" class="w-100" name="exam_name" placeholder="Enter any Exam name"
                                required>
                            <br><br>
                            <label>
                                <h5>Exam Date:-</h5>
                            </label>
                            <input type="date" name="date" min="@php echo date('Y-m-d') @endphp" class="w-100">

                            <div class="row mt-4">



                                <br><br>
                                <div class="col-md-6">
                                    <label>
                                        <h5>Exam Active From</h5>
                                    </label>
                                    <input type="text" name="from_time" value="<?php echo date('h:i A'); ?>" class="w-50 bs-time">
                                </div>
                                <div class="col-md-6">
                                    <label>
                                        <h5>Exam Active To</h5>
                                    </label>
                                    <input type="text" name="to_time" class="w-50 bs-time">
                                </div>
                            </div>

                            <br><br>
                            <label>
                                <h5>Exam Duration Time:-</h5>
                            </label>
                            <input type="text" class="bs-time-24" value="<?php echo date('00:30'); ?>" name="time"
                                class="w-100 bs-time" required>
                            <br><br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Exam Modal -->
    <div class="modal fade" id="editExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateExam" action="{{ redirect('updateExam') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="exam_id" class="exam_id">
                        <label>
                            <h5>Exam Name:-</h5>
                        </label>
                        <input type="text" class="w-100" name="exam_name" id="exam_name"
                            placeholder="Enter any Exam name" required>
                        <br><br>
                        <label>
                            <h5>Exam Date:-</h5>
                        </label>
                        <input type="date" id="date" name="date" min="@php echo date('Y-m-d') @endphp"
                            class="w-100">


                        <div class="row mt-4">
                            <br><br>
                            <div class="col-md-6">
                                <label>
                                    <h5>Exam Active From</h5>
                                </label>
                                <input type="text" id="from_time" name="from_time" class="w-50 bs-time">
                            </div>
                            <div class="col-md-6">
                                <label>
                                    <h5>Exam Active To</h5>
                                </label>
                                <input type="text" id="to_time" name="to_time" class="w-50 bs-time">
                            </div>
                        </div>


                        <br><br>
                        <label>
                            <h5>Exam Duration Time:-</h5>
                        </label>
                        <input type="text" id="time" class="bs-time-24" placeholder="Hour:minute:second"
                            name="time" class="w-100 bs-time" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Delete Exam Modal -->
    <div class="modal fade" id="deleteExamModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteExam" action="{{ redirect('deleteExam') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="exam_id" class="DeleteExamId">
                        <p>Are You Sure to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--See Subject Modal -->
    <div class="modal fade" id="seeSubModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Subjects</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>Sr.no</th>
                            <th>Subjects</th>
                            <th>Qustions Count</th>
                            <th>Delete</th>
                        </thead>
                        <tbody class="seeSubjectTable">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>



    <script>
        $(document).ready(function() {
            function loadTable() {
                $.ajax({
                    url: "{{ route('examDashboardData') }}",
                    type: "GET",
                    success: function(data) {
                        console.log("-------", data);
                        if (data.success == true) {
                            var exams = data.exams;
                            var idSubjectArray = data.idSubjectArray;
                            var subjects = data.subjects;
                            html = "";
                            exams.map(function(data, index) {
                                html += `
                                    <tr>
                                        <td>${index+1}</td>
                                        <td>${data.exam_name}</td>
                                        <td><a href="#" data-id="${data.id}"
                                        ${data.questions_count > 0 ? '' : 'style="color:red"'} class="addSubjects" data-toggle="modal" data-target="#addSubModal">${data.questions_count > 0 ? 'Update ' : 'Add '}Questions</a></td>
                                        <td>${data.questions_count}</td>
                                        <td>${data.date}</td>


                                        <td>${moment(data.from_time, ["HH:mm:ss"]).format("hh:mm A")}</td>

                                        <td>${moment(data.to_time, ["HH:mm:ss"]).format("hh:mm A")}</td>

                                        <td>${data.time}</td>

                                        <td>${data.questions_count > 0 && data.marks > 0 ? `<i class="fa fa-copy copy ml-2" style="font-size:28px;color:#0056b3;cursor:pointer;" data-code="{{ url('/register/${data.enterance_id}') }}"></i>` : 'missing: marks or ques'}
                                        </td>
                                        <td><button class="btn btn-info editButton" data-toggle="modal" data-id="${data.id}"
                                        data-target="#editExamModal">Edit</button>
                                        </td>
                                        <td><button class="btn btn-danger deleteButton" data-toggle="modal"
                                        data-id="${data.id}" data-target="#deleteExamModal">Delete</button>
                                        </td>
                                    </tr>
                                `;
                            });
                            $('#examTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">No Exam Found!</td></tr>`;
                            $('#examTable').html(html);
                        }
                    }
                });
            }
            loadTable();


            //Add Exam
            $('#addExam').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('addExam') }}",
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
                            $('#addExam')[0].reset();
                            $('#addExamModal').modal('hide');
                            loadTable();
                        } else {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.error("", data.msg);
                        }
                    }
                });
            });

            //Edit Exam
            $(document).on('click', '.editButton', function() {
                var id = $(this).attr('data-id');
                $('.exam_id').val(id);
                var url = '{{ route('getExamDetail', 'id') }}';
                url = url.replace('id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(data) {
                        if (data.success == true) {
                            var exam = data.data;
                            $("#exam_name").val(exam.exam_name);
                            $("#subject_id").val(exam.subject_id);
                            $("#date").val(exam.date);
                            $("#from_time").val(moment(exam.from_time, ["HH:mm:ss"]).format(
                                "hh:mm A"));
                            $("#to_time").val(moment(exam.to_time, ["HH:mm:ss"]).format(
                                "hh:mm A"));
                            $("#time").val(exam.time);
                        } else {
                            alert(data.msg)
                        }
                    }
                })
            });

            //Update Exam
            $('#updateExam').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('updateExam') }}",
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
                            $('#editExamModal').modal('hide');
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //Delete Exam
            $(document).on('click', ".deleteButton", function() {
                var exam_id = $(this).attr('data-id');
                $(".DeleteExamId").val(exam_id);
            })

            $("#deleteExam").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('deleteExam') }}",
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
                            $('#seeSubModal').modal('hide');
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //Add Subjects
            $(document).on('click', '.addSubjects', function() {


                var id = $(this).attr('data-id');
                $('#addSubjectId').val(id);

                $.ajax({
                    url: "{{ route('getSubjects') }}",
                    type: "GET",
                    data: {
                        exam_id: id
                    },
                    success: function(data) {
                        console.log(data.data);
                        var html = data.result;
                        var questionHtml = data.questions;
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $('#addSubModal').modal('hide');
                            loadTable();
                        }

                        $('.addSubject').html(html);
                        $('.addQuestion').html(questionHtml);
                        let checkedOnes = $('.addQuestion .checkbox-question:checked');
                        if (checkedOnes.length > 0) {
                            $('#addSubjectButton').text('Update Question');
                        } else {
                            $('#addSubjectButton').text('Add Question');
                        }

                        let array = data.data;
                        setTimeout(() => {
                            array.forEach((element, index) => {
                                console.log(element.subject);
                                let checkedOnes = $('.' + element.subject +
                                    ':checked');
                                console.log(checkedOnes);
                                $('#' + element.subject).text(checkedOnes
                                    .length);

                                const className = element.subject;
                                const backgroundColor = backgroundColors[
                                    index] !== undefined ? backgroundColors[
                                    index] : 'black';

                                $('.' + className).parent().parent().find(
                                    'td:eq(2)').find('span').css(
                                    'background-color',
                                    backgroundColor);
                            });
                        }, 300);


                    }
                });



            });

            $("#addSub").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addSubjects') }}",
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
                            $('#addSubModal').modal('hide');
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });


            $(document).on('click', ".seeQuestion", function() {

                var id = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('getExamQuestions') }}",
                    type: "GET",
                    data: {
                        exam_id: id
                    },
                    success: function(data) {

                        var html = '';
                        var questions = data.data;
                        console.log(questions)
                        if (questions.length > 0) {
                            for (let i = 0; i < questions.length; i++) {
                                html += `
                            <tr>
                            <td>` + (i + 1) + `</td>
                            <td>
                            ` + questions[i]['subject'] + `
                            </td>
                            <td>
                            ` + questions[i]['questionsCount'] + `
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
                            <td colspan="1" >Subjects are not Available!</td>
                            </tr>
                            `
                        }

                        $(".seeSubjectTable").html(html);
                    }
                })
            });

            $(document).on('click', '.deleteQuestion', function() {

                var id = $(this).attr('data-id');
                var obj = $(this);
                $.ajax({
                    url: "{{ route('deleteExamQuestions') }}",
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
                            $('deleteExamModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }

                });

            });


            $(document).on('change', ".checkbox-sub", function() {
                var className = $(this).parent().parent().find('td:eq(2)').attr('id');
                console.log(className);
                if (className != '') {
                    console.log('inside if ', className);
                    let classes = $('.' + className + '').parent().parent();
                    if ($(this).prop('checked')) {
                        classes.show();
                    } else {
                        classes.hide();
                    }
                } else {
                    console.log('not getting subject when checking or unchecking');
                }

            })





            $(document).on('change', ".checkbox-question", function() {
                var className = $(this).attr('class').split(' ')[1];
                if (className != '') {
                    console.log(className);
                    let checkedOnes = $('.' + className + ':checked');
                    if (checkedOnes.length > 0) {
                        $('#' + className + '').text(checkedOnes.length);
                    } else {
                        $('#' + className + '').text(0);
                    }
                }
            })

            $(document).on('click', ".copy", function() {
                const randomString = Math.random().toString(36).substr(2, 3).toUpperCase();
                // alert($(this).attr('data-code'));
                var code = $(this).attr('data-code') + randomString;
                $(this).parent().prepend('<span class="copied_text">Copied</span>');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(code).select();
                document.execCommand("copy");
                $temp.remove();
                setTimeout(() => {
                    $('.copied_text').remove();
                }, 1000);
            });
        });
    </script>
    <script>
        function searchTable() {
            var filter, input, td, tr, i, txtValue;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            table = document.getElementById('subjectTable');
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
