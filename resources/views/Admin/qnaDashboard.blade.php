@extends('layout.admin-dashboard')

@section('content')
    @php
        // $ids = [];
        // foreach ($subjects as $item) {
        // $ids[] = $item['id'];
        // }
    @endphp

    <style>
        .dropdown-menu {
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
        }

        .form-check-input {
            margin-right: 8px;
        }

        /* Optional: Add some custom styles if needed */
        .tagList li {
            list-style-type: none;
            margin: 5px;
            padding: 10px;
            background-color: #f0f0f0bb;
            border-radius: 5px;
            max-height: 42px;
        }
    </style>

    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Q&A </h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuesModal">
            Add Q&A
        </button>
        <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#importQnaModal">
            Import Q&A
        </button>


        <span class="dropdown">
            <button class="btn btn-secondary dropdown-toggle ml-2" type="button" id="checkboxDropdown"
                data-toggle="dropdown" aria-expanded="false">
                Filter Tag
            </button>
            <ul class="dropdown-menu" aria-labelledby="checkboxDropdown" id="dropdown-menu">

            </ul>

        </span>



        <span style="display: none" id="mutipleSpan">
            <button type="button" class="btn btn-danger float-right ml-2" id="deleteAll">
                Remove
            </button>

            <button type="button" class="btn btn-secondary ml-2 float-right" data-toggle="modal"
                data-target="#setSubjectModal">
                Set Tag
            </button>
        </span>


        <br><br>

        <table class="table" aria-describedby="tableDescription">
            <thead>
                <th><input type="checkbox" id="markAll"></th>
                <th>#</th>
                <th>Question</th>
                <th>Tags</th>
                {{-- <th>Tag</th> --}}
                <th>Answers</th>
                <th>Edit</th>
                <th>Delete</th>
            </thead>
            <tbody class="qnaTable">
            </tbody>
        </table>
    </div>

    <!--Add Subject Modal -->
    {{-- <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addSub">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="question_id" id="addSubId">
                        <table class="table" id="subjectTable">
                            <thead>
                                <th>Select</th>
                                <th>Tag</th>
                            </thead>
                            <tbody class="addBody">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!--Add Question Modal -->
    <div class="modal fade" id="addQuesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Q&A</h5>

                    <button class="btn btn-info ml-3" id="addAnswer">Add Answer</button>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addQna" action="{{ redirect('addQna') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body addQnaModalBody">
                        <ul class="tagList d-flex flex-wrap">
                        </ul>
                        <div class="row">

                            <div class="col">
                                <textarea id="question" name="question"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <span class="error" style="color:red"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Q&A</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--View Answer Modal -->
    <div class="modal fade" id="showAnsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Answers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Answers</td>
                                <td>Is Correct</td>
                            </tr>
                        </thead>
                        <tbody class="showAnswers">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <span class="error" style="color:red"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


    {{-- Edit Question Modal --}}
    <div class="modal fade" id="editQnaModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Q&A</h5>

                    <button class="btn btn-info ml-3" id="addEditAnswer">Add Answer</button>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editQna">
                    @csrf
                    <div class="modal-body editModalAnswers">
                        <ul class="tagList d-flex flex-wrap">
                        </ul>
                        <div class="row">
                            <div class="col">
                                <input type="hidden" id="question_id" name="question_id">
                                {{-- <input type="text" name="question" id="question" value="" class="w-100"
                                placeholder="Enter the questions" required> --}}
                                <textarea id="editQuestion" class="w-100" name="editQuestion" required></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="editError" style="color:red"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Q&A</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Delete Qna Modal -->
    <div class="modal fade" id="deleteQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteQna" action="{{ redirect('deleteQna') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" class="DeleteQnaId">
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

    <!--Import Qna Modal -->
    <div class="modal fade" id="importQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Import Q&A</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="text-center p-4">
                    Download sample file: <a href="{{ asset('qna-sample.xlsx') }}">click here</a>
                </div>
                <form id="importQna" enctype="multipart/form-data">
                    @csrf
                    <h5 class="text-center">Always make sure you have entered correct and existing Tag ids in csv file
                        before uploading one</h5>
                    <div class="modal-body">
                        <input type="file" name="file" id="fileupload" required
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Import Qna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--set subject Modal -->
    <div class="modal fade" id="setSubjectModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Set Tag for Marked</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="setSubject">
                    @csrf
                    <div class="modal-body">
                        <select multiple name="subjects_ids[]" id="subject_id" class="form-control" required>
                            <option value="">Select Tag</option>

                        </select>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Set Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>



    <script>
        $(document).ready(function() {

            // Handle checkbox changes
            $(document).on("change", "#dropdown-menu input[type='checkbox']", function() {
                let checkedDropdown = $("#dropdown-menu input[type='checkbox']:checked").map(function() {
                    return this.value;
                }).get();
                console.log('checkedDropdown', checkedDropdown);
                let secondArg = false
                loadTable(checkedDropdown, secondArg);
            });


            $("#markAll").on("change", function() {
                let status = $(this).prop("checked");
                $(".checkbox").prop("checked", status);
                status ? $("#mutipleSpan").show() : $("#mutipleSpan").hide();
            });

            $(document).on("change", ".checkbox", function() {
                if (!$(this).prop("checked")) {
                    $("#markAll").prop("checked", false);
                }
                if ($(".checkbox:checked").length === $(".checkbox").length) {
                    $("#markAll").prop("checked", true);
                }

                if ($(".checkbox:checked").length > 0) {
                    $("#mutipleSpan").show();
                } else {
                    $("#mutipleSpan").hide();
                }
            })




            $(document).on("submit", "#setSubject", function(e) {
                e.preventDefault();
                var checkedIds = $("input[name='qna[]']:checked").map(function() {
                    return this.value;
                }).get();


                $.ajax({
                    url: "{{ route('addSub') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        subjects_ids: $(this).find('#subject_id').val(),
                        question_id: checkedIds
                    },

                    success: function(data) {
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $('#setSubjectModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
                $("#markAll").prop("checked", false);
            })




            var allQuestions;
            var allSubjects;
            var flag = true;
            var flag_tags = true;
            var tagsData;

            function loadTable(params = 'all', seconArg = true) {
                console.log('loadtable');
                $("#markAll").prop("checked", false);
                $("#mutipleSpan").hide();

                $.ajax({
                    url: "{{ route('qnaDashboardData') }}?params=" + params,
                    type: "GET",
                    success: function(data) {
                        console.log(data);
                        var qna = data.data;
                        var html = "";
                        allQuestions = data.data;
                        allSubjects = data.subjects;
                        if (data.success == true && data.subjects.length > 0) {
                            var optionsData = data.subjects;
                            var selectBox = $('#subject_id');
                            selectBox.empty();
                            $.each(optionsData, function(index, option) {
                                selectBox.append(
                                    `<option value="${option.id}">${option.subject}</option>`
                                );



                                const className = option.subject;
                                const backgroundColor = backgroundColors[index] != undefined ?
                                    backgroundColors[index] : 'black';

                                setTimeout(() => {
                                    $('.' + className).css('background-color',
                                        backgroundColor);

                                }, 200);


                            });

                            if (seconArg) {
                                loadDropdown();
                            }
                        }

                        if (data.success == true && flag_tags && allSubjects.length > 0) {
                            tagsData = allSubjects;
                            var tagUl = $('.tagList');
                            tagUl.empty();
                            $.each(tagsData, function(index, option) {
                                tagUl.append(
                                    `<li class="list-group-item"><input class="tags" type="checkbox" name="tags[]" value="${option.id}">&nbsp;${option.subject}</li>`
                                );
                            });
                            flag_tags = false;
                        }


                        if (data.success == true && data.data.length > 0) {
                            qna.map(function(data, index) {
                                console.log('======', data.subjects);
                                html += `
                                        <tr>
                                        <td><input type="checkbox" class="checkbox" name="qna[]" value="${data.id}"></td>
                                        <td>${index+1}</td>
                                        <td>${data.question}</td>
                                        <td>
                                        ${data.subjects.map(function(data){return '<span class="'+data.subject+' d-inline badge badge-info mr-2 ">'+data.subject+'</span>'}).join('')}
                                        </td>
                                        <td>
                                        <a href="#" class="ansButton" data-id = "${data.id}" data-toggle="modal"
                                        data-target="#showAnsModal">See Answers</a>
                                        </td>
                                        <td><button class="btn btn-info editButton" data-id = "${data.id}" data-toggle="modal"
                                        data-target="#editQnaModal">Edit</button></td>
                                        <td><button class="btn btn-danger deleteButton" data-id = "${data.id}"
                                        data-toggle="modal" data-target="#deleteQnaModal">Delete</button></td>
                                        </tr>
                                        `
                                $('.qnaTable').html(html);
                                $("#markAll").show();

                            });
                        } else {
                            $("#markAll").hide();
                            html = "";
                            html += `<tr><td colspan="2">No Question & Answer Found!</td></tr>`;
                            $('.qnaTable').html(html);
                        }
                    }
                })
            }
            loadTable();



            function loadDropdown() {
                var dropdown = $('#dropdown-menu');
                dropdown.empty();


                $.each(allSubjects, function(index, option) {
                    dropdown.append(
                        `<li>
                    <div class="form-check">
                    <input checked class="form-check-input" type="checkbox" value="${option.id}" id="check${option.id}">
                    <label class="form-check-label" for="check${option.id}">
                    ${option.subject}
                    </label>
                    </div>
                    </li>`
                    );
                })
                dropdown.append(
                    `<button onclick="window.location.reload();" class="btn btn-danger mb-2">Reset</button>`);

            }





            // Form Submition
            $('#addQna').submit(function(e) {
                e.preventDefault();
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                if ($('.answerOption').length < 2) {
                    $('.error').text('Add minimum 2 Answers');
                    setTimeout(() => {
                        $('.error').text('');
                    }, 2000);
                } else {
                    var checkIsCorrect = false;
                    for (let i = 0; i < $(".is_correct").length; i++) {
                        if ($(".is_correct:eq(" + i + ")").prop('checked') == true) {
                            checkIsCorrect = true;
                            $(".is_correct:eq(" + i + ")").val($(".is_correct:eq(" + i + ")").next().find(
                                'input').val());
                        }
                    }
                    if (checkIsCorrect) {
                        var formData = $(this).serialize();
                        $.ajax({
                            url: "{{ route('addQna') }}",
                            type: "POST",
                            data: formData,
                            dataType: "json",
                            success: function(data) {
                                if (data.success == true) {
                                    toastr.options = {
                                        'timeOut': 900,
                                        'closeButton': true,
                                        'progressBar': true,
                                        "positionClass": "toast-top-center",
                                    };
                                    toastr.success("", data.msg);
                                    $("#addQna")[0].reset();
                                    $('#addQuesModal').modal('hide')
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
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.error("Error: " + errorThrown);
                            }
                        });
                    } else {
                        $('.error').text('Please select any one correct option');
                        setTimeout(() => {
                            $('.error').text('');
                        }, 2000);
                    }

                }
            });


            //Add Answer
            $(document).on('click', '#addAnswer', function() {

                if ($('.answerOption').length >= 6) {
                    $('.error').text('You can Add maximum only 6 Options');
                    setTimeout(() => {
                        $('.error').text('');
                    }, 2000);
                } else {
                    var html = `
                            <div class="row mt-2 answerOption">
                                <input type="radio"  name="is_correct" class="is_correct" class="ml-2">
                                <div class="col">
                                    <input type="text" name="answer[]" class="w-100" placeholder="Enter the answer"
                                        required>
                                </div>
                                <button class="btn btn-danger removeButton">Remove</button>
                            </div>
                            `
                }
                $('.addQnaModalBody').append(html);
                $(document).on('click', '.removeButton', function() {
                    $(this).parent().remove();
                });
            });


            //Show Answers
            $(document).on('click', '.ansButton', function(data) {

                // var questions = @json($questions);
                var questions = allQuestions;
                var qid = $(this).attr('data-id');
                var showAns = '';

                for (let i = 0; i < questions.length; i++) {

                    if (questions[i]['id'] == qid) {

                        var answerLength = questions[i]['answers'].length;
                        for (let j = 0; j < answerLength; j++) {
                            var is_correct = "No";
                            if (questions[i]['answers'][j]['is_correct'] == 1) {
                                is_correct = "Yes";
                            }
                            showAns += `
                                        <tr>
                                            <td> ${j + 1} </td>
                                            <td> ${questions[i]['answers'][j]['answer']} </td>
                                            <td> ${is_correct} </td>
                                        </tr>
                                    `;

                        }
                        break;
                    }
                }
                $('.showAnswers').html(showAns);
            });


            //Edit or update Answer
            $(document).on('click', '#addEditAnswer', function() {

                if ($('.editOption').length >= 6) {
                    $('.editError').text('You can Add maximum only 6 Options');
                    setTimeout(() => {
                        $('.editError').text('');
                    }, 2000);
                } else {
                    var html = `
                            <div class="row mt-2 editOption">
                                <input type="radio" name="is_correct" class="edit_is_correct ml-4">
                                <div class="col-9">
                                    <input type="text" name="new_answer[]" class="w-100" placeholder="Enter the answer"
                                        required>
                                </div>
                                <button class="btn btn-danger removeButton">Remove</button>
                            </div>
                            `;
                    $('.editModalAnswers').append(html);
                }
            });

            $(document).on('click', ".editButton", function() {



                $('.editOption').remove();

                var qid = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('getQnaDetails') }}",
                    type: "GET",
                    data: {
                        qid: qid
                    },
                    success: function(data) {


                        $('.editModalAnswers .tags').each(function() {
                            var tagValue = $(this).val();
                            var dbTags = data.tagIds.map(
                                String); // Convert to strings if necessary

                            if (dbTags.includes(String(tagValue))) {
                                $(this).prop('checked', true);
                            } else {
                                $(this).prop('checked', false);
                            }
                        });




                        $('textarea#editQuestion').val('');
                        var qna = data.data[0];
                        $('#question_id').val(qna['id']);
                        $('textarea#editQuestion').val(qna['question']);
                        $('.editAnswers').remove();

                        var html = '';

                        for (let i = 0; i < qna['answers'].length; i++) {

                            var checked = '';
                            if (qna['answers'][i]['is_correct'] == 1) {
                                checked = 'checked';
                            }



                            html +=
                                `
                                <div class="row mt-2 editAnswers">
                                <input type="radio" name="is_correct" class="edit_is_correct ml-4"` +
                                checked + `>
                                <div class="col-9">
                                    <input type="text" name="answers[` + qna['answers'][i]['id'] + `]" class="w-100" placeholder="Enter the answer"
                                    value="${qna['answers'][i]['answer']}"  required>
                                </div>
                                <button class="btn btn-danger removeButton removeAnswer" data-id="` + qna[
                                    'answers'][i]
                                ['id'] + `">Remove</button>
                            </div>
                            `;




                        }
                        $('.editModalAnswers').append(html);

                        $(document).on('click', '.removeButton', function() {
                            $(this).parent().remove();
                        });

                    }
                });
            });

            // Update Qna Submition
            $('#editQna').submit(function(e) {
                e.preventDefault();


                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }


                if (($('.editAnswers').length + $('.editOption').length) < 2) {
                    $('.editError').text('Add minimum 2 Answers');
                    setTimeout(() => {
                        $('.editError').text('');
                    }, 2000);
                } else {

                    var checkIsCorrect = false;


                    for (let i = 0; i < $(".edit_is_correct").length; i++) {
                        if ($(".edit_is_correct:eq(" + i + ")").prop('checked') == true) {
                            checkIsCorrect = true;
                            $(".edit_is_correct:eq(" + i + ")").val($(".edit_is_correct:eq(" + i + ")")
                                .next()
                                .find('input').val());
                        }
                    }


                    if (checkIsCorrect) {
                        var formData = $(this).serialize();
                        $.ajax({
                            url: "{{ route('updateQna') }}",
                            type: "POST",
                            data: formData,
                            success: function(data) {

                                if (data.success == true) {
                                    toastr.options = {
                                        'closeButton': true,
                                        'progressBar': true,
                                        "positionClass": "toast-top-center",
                                        'timeOut': 900,
                                    };
                                    toastr.success("", data.msg);
                                    // document.getElementById('editQnaModal').style.display = 'none';
                                    // jQuery('#editQnaModal').hide();

                                    $('#editQnaModal').modal('hide');
                                    console.log('hide');
                                    loadTable();
                                } else {
                                    alert(data.msg);
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.error("Error: " + errorThrown);
                            }
                        });
                    } else {
                        $('.editError').text('Please select any one correct option');
                        setTimeout(() => {
                            $('.editError').text('');
                        }, 2000);
                    }

                }
            });

            //Remove Answer
            $(document).on('click', '.removeAnswer', function() {
                var ansId = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('deleteAnswer') }}",
                    type: "GET",
                    data: {
                        id: ansId
                    },
                    success: function(data) {
                        if (data.success === true) {
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
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Request Failed: " + textStatus, errorThrown);
                    }
                });
            });

            // Delete multiple Question
            $(document).on('click', "#deleteAll", function() {
                var checkedIds = $("input[name='qna[]']:checked").map(function() {
                    return this.value;
                }).get();

                $.ajax({
                    url: "{{ route('deleteQna') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: checkedIds
                    },
                    success: function(data) {
                        if (data.success == true) {
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
                $("#markAll").prop("checked", false);
            })

            // Delete Question
            $(document).on('click', ".deleteButton", function() {
                var question_id = $(this).attr('data-id');
                $(".DeleteQnaId").val(question_id);
            })

            $("#deleteQna").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('deleteQna') }}",
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
                            $('#deleteQnaModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            // Import Qna

            $("#importQna").submit(function(e) {
                e.preventDefault();

                let formData = new FormData();

                formData.append("file", fileupload.files[0]);

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })

                $.ajax({
                    url: "{{ route('importQna') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data)
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $('#importQnaModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //get Subjects
            $(document).on('click', '.addSubjectButton', function() {
                var id = $(this).attr('data-id');
                $('#addSubId').val(id);

                $.ajax({
                    url: "{{ route('getSub') }}",
                    type: "GET",
                    data: {
                        question_id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var subjects = data.data;
                            var html = '';

                            if (subjects.length > 0) {
                                for (let i = 0; i < subjects.length; i++) {
                                    if (subjects[i].subject_id == null) {
                                        var subject_id = data.subject_id;
                                        html += `
                                                <tr>
                                                    <td>
                                                        ${subject_id.subject_id == subjects[i].id ?
                                                            `<input type="radio" checked value="${subjects[i].id}" name="subjects_ids">` :
                                                            `<input type="radio" value="${subjects[i].id}" name="subjects_ids">`}
                                                    </td>
                                                    <td>
                                                        ${subjects[i].subject}
                                                    </td>
                                                </tr>`;

                                    }
                                }
                            } else {
                                html += `<tr>
                            <td colspan="2">Tags not Available!</td>
                        </tr>`;
                            }

                            $('.addBody').html(html);
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            //addSubject
            $("#addSub").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addSub') }}",
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
                            $('#addSubjectModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });




            $('#addQuesModal').on('shown.bs.modal', function(e) {
                for (const instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].destroy(true);
                }
                CKEDITOR.replace('question');
            })


            $('#editQnaModal').on('shown.bs.modal', function(e) {
                e.preventDefault()
                for (const instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].destroy(true);
                }
                CKEDITOR.replace('editQuestion');
            })
        });
    </script>
@endsection
