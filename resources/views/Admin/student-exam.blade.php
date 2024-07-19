@extends('layout/admin-dashboard')

@section('content')
    {{-- @dd($userdata); --}}
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Choose Exam</h2>
        <br><br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Student Email</th>
                    <th scope="col">Exam Name</th>
                    <th scope="col">Exams Details</th>
                </tr>
            </thead>
            <tbody id="studentExamTable">
                {{-- @foreach ($userdata as $key => $data)
                    <tr>
                        <td>{{ $userdata->firstItem() + $loop->index }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td><a href="#" data-id="{{ $data['id'] }}" class="addExam" data-toggle="modal"
                                data-target="#seeExamModal">Select Exams</a></td>
                        <td><button class="btn btn-secondary"><a style="color: white" href="#"
                                    data-id="{{ $data['id'] }}" class="seeExam" data-toggle="modal"
                                    data-target="#seeExamDetailModal">See Details</a></button></td>
                    </tr>
                @endforeach --}}

            </tbody>
        </table>

    </div>

    <!--See Exam Details Modal -->
    <div class="modal fade" id="seeExamDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">See Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="detailId">
                    <table class="table" id="examDetailTable">
                        <thead>
                            <th>Mail Sent Date</th>
                            <th>Time</th>
                            <th>Exam</th>
                            <th>Attempts</th>
                        </thead>
                        <tbody class="addDetails">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Add Exam Modal-->
    <div class="modal fade" id="seeExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Exams</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addExam">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="addExamId">
                        <table class="table" id="questionTable">
                            <thead>
                                <th>Select</th>
                                <th>Attempts</th>
                                <th>Exam</th>
                            </thead>
                            <tbody class="addBody">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"><a style="text-decoration: none">Exam
                                Assign</a></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {


            function loadTable() {
                $.ajax({
                    url: "{{ route('loadStudentExamData') }}",
                    type: "GET",
                    success: function(data) {
                        if (data.success == true) {
                            var studentExamData = data.userdata.data;
                            var html = "";
                            studentExamData.map(function(data, index) {
                                html += `
                                <tr>
                                     <td>${index+1}</td>
                                     <td>${data.name}</td>
                                     <td class="user"><a href="#">${data.email}</a></td>
                                     <td><a href="#" data-id="${data.id}" class="addExam" data-toggle="modal"
                                             data-target="#seeExamModal">Select Exams</a></td>
                                     <td><button class="btn btn-secondary"><a style="color: white" href="#"
                                                 data-id="${data.id}" class="seeExam" data-toggle="modal"
                                                 data-target="#seeExamDetailModal">See Details</a></button></td>
                                </tr>`;
                            })
                            $('#studentExamTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">No Subject Found!</td></tr>`;
                            $('#studentExamTable').html(html);
                        }
                    }
                })
            }
            loadTable();


            //Add Subjects
            $(document).on('click', '.addExam', function() {
                var id = $(this).attr('data-id');
                $('#addExamId').val(id);
                $.ajax({
                    url: "{{ route('getExams') }}",
                    type: "GET",
                    data: {
                        user_id: id
                    },
                    success: function(data) {
                        var exams = data.data;
                        console.log(exams)
                        var html = '';
                        if (exams.length > 0) {
                            for (let i = 0; i < exams.length; i++) {
                                html += `
                            <tr>
                                <td>
                                    <input onchange="checkboxHandler(this)" type="checkbox" class="examsInput" value="${exams[i]['id']}" name="exams_ids[]">
                                </td>
                                <td>
                                    <input oninput="handleInput(this)" type="number" min="1" class="attempts" name="attempts[]" placeholder="Number of Attempts">
                                </td>
                                <td>
                                    ${exams[i]['name']}
                                </td>
                            </tr>`;
                            }
                        } else {
                            html += `
                        <tr>
                            <td colspan="3">Exams not Available!</td>
                        </tr>`;
                        }

                        $('.addBody').html(html);
                    }
                });
            });

            //see Details
            $(document).on('click', '.seeExam', function() {
                var id = $(this).attr('data-id');
                $('#detailId').val(id);

                $.ajax({
                    url: "{{ route('getExamDetails') }}",
                    type: "GET",
                    data: {
                        user_id: id
                    },
                    success: function(data) {
                        var examsInfo = data.data;
                        console.log(examsInfo)
                        var html = '';
                        if (examsInfo.length > 0) {
                            for (let i = 0; i < examsInfo.length; i++) {
                                html += `
                        <tr>
                            <td>
                                ${new Date(examsInfo[i]['mailSent']).toLocaleDateString()}
                            </td>
                            <td>
                                ${new Date(examsInfo[i]['mailSent']).toLocaleTimeString()}
                            </td>
                            <td>
                                ${examsInfo[i]['exam_name']}
                            </td>
                            <td>
                                ${examsInfo[i]['attempt']}
                            </td>

                        </tr>
                    `;
                            }
                        } else {
                            html += `
                    <tr>
                        <td colspan="2  ">Exams Not Sent Yet</td>
                    </tr>
                `;
                        }

                        $('.addDetails').html(html);
                    }
                });
            });

            $('#addExam').submit(function(event) {
                event.preventDefault();
                var count = $('.examsInput:checked').length;
                var isValid = true;
                $('.attempts').each(function() {
                    var inputVal = $(this).val();
                    var checkbox = $(this).closest('tr').find('.examsInput');
                    var isChecked = checkbox.prop('checked');
                    if ((inputVal === '' && isChecked) || (inputVal !== '' && !isChecked) || count <
                        1) {
                        isValid = false;
                    }
                });
                if (!isValid) {
                    alert('Each pair of fields needs to be set!');
                    // Prevent form submission
                } else {
                    submitAddExam(); // Call the submitAddExam function
                }
            });

            function submitAddExam() {
                var formData = $('#addExam').serialize(); // Use the correct form ID
                console.log(formData);
                $.ajax({
                    url: "{{ route('assignExams') }}",
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
                            $('#seeExamModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    }
                });

            }
        })
    </script>


    <script>
        function handleInput(ele) {
            var inputVal = ele.value;
            var checkbox = $(ele).closest('tr').find('.examInput');
            checkbox.prop('disabled', !inputVal);
            if (!inputVal) {
                checkbox.prop('checked', false);
            }
        }

        function checkboxHandler(ele) {
            var isChecked = $(ele).prop('checked');
            var inputField = $(ele).closest('tr').find('.attempts');
            inputField.prop('disabled', !isChecked);
            if (!isChecked) {
                inputField.val('');
            }
        }
    </script>
@endsection
