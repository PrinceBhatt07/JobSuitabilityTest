@extends('layout/admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Exam Review</h2>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Exam</th>
                    <th scope="col">Status</th>
                    <th scope="col">Review</th>
                </tr>
            </thead>
            <tbody id="attemptTable">
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="reviewExamModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Review Exam</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="reviewForm">
                        @csrf
                        <input type="hidden" name="attempt_id" id="attempt_id">
                        <div class="modal-body review-exam">
                            Loading...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary approve-btn">Approved</button>
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
                        url: "{{ route('loadReviewData') }}",
                        type: "GET",
                        success: function(data) {
                            if (data.success == true) {
                                var attemptData = data.data;
                                var html = "";
                                attemptData.map(function(data, index) {
                                    console.log(data)
                                    html += `
                                            <tr>
                                            <td>${index + 1}</td>
                                            <td>${data.user.name}</td>
                                            <td class="user"><a href="#">${data.user.email}</a></td>
                                            <td>${data.exam.exam_name}</td>
                                            ${data.status == 0 ? '<td><span style="color:red">Status Pending</span></td>' : '<td><span style="color:green">Approved</span></td>'}
                                            <td>
                                            ${data.status == 0 ? '<a href="#" data-toggle="modal" class="reviewExam" data-id="' + data.id + '" data-target="#reviewExamModel">Review and Approved</a>' : 'Completed'}
                                            </td>
                                            </tr>
                                            `;

                                })
                                $('#attemptTable').html(html);
                            } else {
                                html = "";
                                html += `<tr><td colspan="2">Student not attempt any exam.</td></tr>`;
                                $('#attemptTable').html(html);
                            }
                        }
                    })
                }
                loadTable();

                $(document).on('click', '.reviewExam', function() {
                    var id = $(this).attr('data-id');
                    $('#attempt_id').val(id);

                    $.ajax({
                        url: "{{ route('reviewQna') }}",
                        type: "GET",
                        data: {
                            attempt_id: id
                        },
                        success: function(data) {
                            var html = '';

                            if (data.success == true) {
                                var data = data.data;
                                for (let i = 0; i < data.length; i++) {
                                    let isCorrect =
                                        '<span style="color:red" class="fa fa-close"></span>';
                                    let answer = data[i]['answers'] ? data[i]['answers']['answer'] :
                                        null;

                                    if (data[i]['answers'] && data[i]['answers']['is_correct'] ==
                                        1) {
                                        isCorrect =
                                            '<span style="color:green" class="fa fa-check"></span>';
                                    }

                                    html += `<div class="row">
                                    <div class="col-sm-12">
                                    <h6>Q(${i + 1}):- ${data[i]['question']['question']}</h6>
                                    <p>Ans(${i + 1}):-${answer == null ? '<span style="color:red">N/A</span>' : answer} ${isCorrect}</p>
                                    </div>
                                    </div>

                                    `;
                                }
                                if (data.length > 0) {} else {
                                    html += `<h6>Student not attempted any questions</h6>
                                            <p>If You Approve this Exam, Student will be Failed.</p>`;
                                }
                            } else {
                                html += `<p>Having some Server Issue</p>`;
                            }
                            $('.review-exam').html(html);
                        }
                    });
                });

                $('.reviewForm').submit(function(e) {
                    e.preventDefault();

                    $('.approve-btn').html('Please wait<i class="fa fa-spinner fa-spin"></i>')

                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('approveQna') }}",
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
                                $('#reviewExamModel').modal('hide')
                                loadTable();
                            } else {
                                alert(data.msg)
                            }
                        }
                    })
                })
            });
        </script>
    @endsection
