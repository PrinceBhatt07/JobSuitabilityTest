@extends('layout.admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Remarks </h2>
        <br>

        <table class="table">
            <thead>
                <th>#</th>
                <th>Student Name</th>
                <th>Student Email</th>
                <th>Exam Attempted</th>
                <th>Date of Exam Attempt</th>
                <th>Marks Obtained</th>
                <th>Result</th>
                <th>See Remarks</th>
                <th>Add Remarks</th>
            </thead>
            <tbody id="remarksTable">
                {{-- @if (count($studentRemarks) > 0)
                    @foreach ($studentRemarks as $key => $name)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $name['user']['name'] }}</td>
                            <td>{{ $name['exam']['exam_name'] }}</td>
                            <td>{{ $name['exam']['date'] }}</td>
                            @if (!filled($name['marks']))
                                <td style="color:red">Marks not Calculated yet.</td>
                            @else
                                <td>{{ $name['marks'] }}/{{ $studentRemarks[0]['total_marks'] }}</td>
                            @endif
                            @if ($name['marks'] === null)
                                <td><span style="color:Black">Result Not Declared</span></td>
                            @elseif ($name['marks'] >= $name['exam']['passing_marks'])
                                <td><span style="color:green">Pass</span></td>
                            @else
                                <td><span style="color:red">Fail</span></td>
                            @endif
                            @if ($name['remarks'] === null)
                            <td><a href="#" style="color: grey">See Remarks</a></td></td>
                            @else
                            <td><a href="#" class="seeRemarks" data-id="{{$name['id']}}"
                                    data-toggle="modal" data-target="#seeRemarksModal">See Remarks</a></td>
                            @endif
                            <td><button class="btn btn-primary remarksButton" data-id="{{ $name['id'] }}"
                                    data-toggle="modal" data-target="#addRemarksModal">Add Remarks</button></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Student not attempted any exam</td>
                    </tr>
                @endif --}}
            </tbody>
        </table>
    </div>


    <!--Add Remarks Modal -->
    <div class="modal fade" id="addRemarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addRemarks">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="remarksId" id="remarksId">
                        {{-- <label>Review:-</label> --}}
                        {{-- <input type="text" class="w-100" name="remarks" placeholder="Add the Remarks" required> --}}
                        <textarea name="remarks" id="" cols="82"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--See Remarks Modal -->
    <div class="modal fade" id="seeRemarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="remarksId" id="remarksId">
                        <p class="remarksBody"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>



    </div>


    {{-- @include('Admin.user-info-modal') --}}


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



            function loadTable() {
                $.ajax({
                    url: "{{ route('loadRemarksData') }}",
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
                                             <td>${new Date(data.exam.created_at).toLocaleString('en-US', options)}</td>
                                             <td>
                                                 ${data.marks == null ? '<span style="color:red">Marks not Calculated yet.</span>' : `${data.marks}/${data.total_marks}`}
                                             </td>
                                             <td>
                                                 ${data.marks === null ? '<span style="color:Black">Result Not Declared</span>' : data.marks >= data.exam.passing_marks ? '<span style="color:green">Pass</span>' : '<span style="color:red">Fail</span>'}
                                             </td>
                                             <td>
                                                 ${data.remarks === null ? '<a href="#" style="color: grey">See Remarks</a>' : `<a href="#" class="seeRemarks" data-id="${data.id}" data-toggle="modal" data-target="#seeRemarksModal">See Remarks</a>`}
                                             </td>
                                             <td>
                                                 <button class="btn btn-primary remarksButton" data-id="${data.id}" data-toggle="modal" data-target="#addRemarksModal">Add Remarks</button>
                                             </td>
                                         </tr>
                                        `;
                            })
                            $('#remarksTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">Student not attempt any exam.</td></tr>`;
                            $('#remarksTable').html(html);
                        }
                    }
                })
            }
            loadTable();


            $(document).on('click', '.remarksButton', function() {
                var id = $(this).attr('data-id')
                $('#remarksId').val(id);
            });

            $("#addRemarks").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addRemarks') }}",
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
                            $("#addRemarks")[0].reset();
                            $('#addRemarksModal').modal('hide');
                            loadTable();
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                });
            });

            $(document).on('click', '.seeRemarks', function() {
                var id = $(this).attr('data-id')
                $.ajax({
                    url: "{{ route('getRemarks') }}",
                    type: "GET",
                    data: {
                        attempt_id: id
                    },
                    success: function(data) {
                        var html = '';
                        var remarks = data.data;
                        html = `
                        <textarea name="" id="" cols="83" readonly>${remarks}</textarea>
                        `
                        $('.remarksBody').html(html)
                    }
                })
            })
        });
    </script>
@endsection
