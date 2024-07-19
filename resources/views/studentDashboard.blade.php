@extends('layout.layout')

@section('content')
    <div class="container mt-4">

        @if (Session::has('success'))
            <p  class="text-center" style="color: green">{{ Session::get('success') }} </p>
        @endif

        <h1 class="text-center" id="studentName"></h1>
        <table class="table mt-4">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Exam Name</td>
                    <td>Start Exam</td>
                    <td>Total Attempts</td>
                    <td>Remaining Attempts</td>
                </tr>
            </thead>
            <tbody id="studentData">
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            loadTable();
            var currentUrl = window.location.href;
            var extractedString = currentUrl.split('/').pop();
            var myVariable = extractedString;
            console.log(myVariable);

            var url = "{{ route('verifyUserData', ['studentPh' => ':studentPh']) }}"
            url = url.replace(':studentPh', myVariable);

            function loadTable() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    success: function(data) {
                        var html = "";
                        var studentName = data.userInfo.name;
                        $('#studentName').html('Welcome ' + studentName);
                        if (data.success == true) {
                            var studentData = data.userExamData;
                            studentData.map(function(data, index) {
                                html += `
                        <tr>
                            <td>${index+1}</td>
                            <td>${data.exams.exam_name}</td>
                            <td><button class="btn btn-success startBtn" data-link="${data.examlink}">Start Exam</button></td>
                            <td>${data.attempts}</td>
                            <td>${data.remaining_attempts}</td>
                        </tr>
                    `;
                            });
                        } else {
                            html += `<tr><td colspan="2">No Subject Found!</td></tr>`;
                        }
                        $('#studentData').html(html);
                    }
                })
            }

            $(document).on('click', ".copy", function() {
                var code = $(this).attr('data-code');
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

            function startExam(link) {
                window.open(link);
            }

            $(document).on('click', '.startBtn', function() {
                var link = $(this).data('link');
                startExam(link);
                setTimeout(location.reload(), 2000);
            });


        });
    </script>
@endsection
