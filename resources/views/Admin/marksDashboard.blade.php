@extends('layout/admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Marks</h2>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Exam Name</th>
                    <th scope="col">Total number of Questions</th>
                    <th scope="col">Marks Per Question</th>
                    <th scope="col">Total Marks</th>
                    <th scope="col">Passing Marks</th>
                    <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody id="marksTable">
            </tbody>
        </table>

        <div class="modal fade" id="editMarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editMarks" action="{{ redirect('editMarks') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col sm-4">
                                    <label>Marks Per Question</label>
                                </div>
                                <div class="col sm-6">
                                    <input type="hidden" id="exam_id" name="exam_id">
                                    <input type="text"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46"
                                        name="marks" id="marks" paceholder="Enter Marks per Question" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col sm-4">
                                    <label>Total Marks</label>
                                </div>
                                <div class="col sm-6">
                                    <input type="number" id="tmarks" disabled placeholder="Total Marks" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col sm-4">
                                    <label>Passing Percentage %</label>
                                </div>
                                <div class="col sm-6">
                                    <input type="number"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46"
                                        name="pass_marks" id="pass_marks" paceholder="Enter Passing Percentage " required>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
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

            function loadTable() {
                $.ajax({
                    url: "{{ route('loadMarksData') }}",
                    type: "GET",
                    success: function(data) {
                        if (data.success == true) {
                            var html = "";
                            var examData = data.data;
                            examData.map(function(data, index) {
                                var marks = data.marks;
                                var getQnaCount = data.questions_count;
                                var passingMarks = data.passing_marks;
                                var pc = passingMarks;
                                var marksPerQuestion = getQnaCount !== 0 ? marks / getQnaCount :
                                    0;
                                var passMPercentage = marks !== 0 ? Math.ceil((pc / marks) *
                                    100) : 0;
                                console.log(examData)
                                html += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${data.exam_name}</td>
                                    <td>${data.questions_count}</td>
                                    <td>${marksPerQuestion}</td>
                                    <td>${marks}</td>
                                    <td>${Math.ceil(passingMarks)}</td>
                                    <td>
                                        <button class="btn btn-primary editMarks" data-id="${data.id}"
                                            data-toggle="modal" data-passM="${passMPercentage}" data-target="#editMarksModal"
                                            data-marks="${marksPerQuestion}"
                                            data-totalq="${data.questions_count}">Edit</button>
                                    </td>
                                </tr>
                                        `;
                            })
                            $('#marksTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">Exam Not Added!</td></tr>`;
                            $('#marksTable').html(html);
                        }
                    }
                })
            }
            loadTable();

            var totalQna = 0;
            $(document).on('click', '.editMarks', function() {
                var exam_id = $(this).data('id');
                var marks = $(this).data('marks');
                var totalq = $(this).data('totalq');

                $('#marks').val(marks);
                $('#exam_id').val(exam_id);
                $('#tmarks').val((totalq * marks).toFixed(1));
                totalQna = totalq;

                $('#pass_marks').val($(this).attr('data-passM'));
            });

            $(document).on('keyup', '#marks', function() {
                $('#tmarks').val(($(this).val() * totalQna).toFixed(1));
            });


            $(document).on('keyup', '#pass_marks', function() {
                $('.pass-error').remove();
                var tmarks = $('#tmarks').val();
                var pmarks = $('#pass_marks').val();
                const pmarksValue = parseFloat(pmarks);
                const tmarksValue = parseFloat(tmarks);
                const data = (pmarksValue / 100) * tmarksValue;

                if (isNaN(pmarksValue) || isNaN(tmarksValue)) {
                    console.error('Invalid marks. Please provide valid numerical values.');
                } else {
                    if (pmarksValue < 33) {
                        const errorMessage = $('<p>', {
                            text: 'Passing percentage should be minimum 33%',
                            style: 'color: red',
                            class: 'pass-error'
                        });

                        $('#pass_marks').parent().append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 5000);

                        return false;
                    } else if (data >= tmarksValue) {

                        console.log((pmarksValue / 100) * tmarksValue, tmarksValue);
                        const errorMessage = $('<p>', {
                            text: 'Passing marks will be less than total marks',
                            style: 'color: red',
                            class: 'pass-error'
                        });

                        $('#pass_marks').parent().append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 5000);

                        return false;
                    }
                }
            });

            $('#editMarks').submit(function(e) {
                e.preventDefault();

                $('.pass-error').remove();
                var tmarks = $('#tmarks').val();
                var pmarks = $('#pass_marks').val();


                const pmarksValue = parseFloat(pmarks);
                const tmarksValue = parseFloat(tmarks);
                const data = (pmarksValue / 100) * tmarksValue;

                if (isNaN(pmarksValue) || isNaN(tmarksValue)) {
                    console.error('Invalid marks. Please provide valid numerical values.');
                } else {
                    if (pmarksValue < 33) {
                        const errorMessage = $('<p>', {
                            text: 'Passing marks should be minimum 33%',
                            style: 'color: red',
                            class: 'pass-error'
                        });

                        $('#pass_marks').parent().append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 5000);

                        return false;
                    } else if (data >= tmarksValue) {
                        const errorMessage = $('<p>', {
                            text: 'Passing marks will be less than total marks',
                            style: 'color: red',
                            class: 'pass-error'
                        });

                        $('#pass_marks').parent().append(errorMessage);

                        setTimeout(() => {
                            errorMessage.remove();
                        }, 5000);
                        return false;
                    }
                }

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('editMarks') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $('#editMarksModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error: " + textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
@endsection
