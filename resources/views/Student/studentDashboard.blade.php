@extends('layout/student-layout')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Exams </h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Exam Name</th>
                    <th>Subject Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Total Attempt</th>
                    <th>Available Attempt</th>
                    <th>Copy Link</th>
                </tr>
            </thead>
            <tbody>
                @if (count($exams) > 0)
                    @foreach ($exams as $key => $exam)
                        <tr>
                            <td style="display:none">{{ $exam->id }}</td>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $exam->exam_name }}</td>
                            <td>{{ $exam->subjects[0]['subject']}}</td>
                            <td>{{ $exam->date }}</td>
                            <td>{{ $exam->time }} Hrs</td>
                            <td>{{ $exam->attempt }} Time</td>
                            <td>{{  $exam->attempt - $exam->attempt_counter  }}</td>
                            <td><a href="#" class="copy" data-code="{{ $exam->enterance_id }}"><i
                                        class="fa fa-copy pl-4"></i></a></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">There is no Exam to be Held Yet</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $(".copy").click(function() {
                var code = $(this).attr('data-code');
                var url = "{{ URL::to('/') }}/exam/" + code;


                $(this).parent().prepend('<span class="copied_text">Copied</span>');


                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(url).select();
                document.execCommand("copy");
                $temp.remove();


                setTimeout(() => {
                    $('.copied_text').remove();
                }, 1000);
            });
        });
    </script>
@endsection
