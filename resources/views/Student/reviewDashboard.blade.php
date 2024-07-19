@extends('layout/student-layout')

@section('content')

    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Marks</h2>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Exam Name</th>
                    <th scope="col">Result</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>

                @if (count($attempts) > 0)
                    @foreach ($attempts as $key => $attempt)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $attempt->exam->exam_name }}</td>
                        <td>
                            @if ($attempt->status == 0)
                                Not Declared
                            @else
                                @if ($attempt->marks >= $attempt->exam->passing_marks)
                                    <span style="color:green">Passed</span>
                                @else
                                    <span style="color:red">Failed</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($attempt->status == 0)
                            <span style="color:red">Pending</span>
                            @else
                            <span style="color:green">Done</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                @else
                    <tr>
                        <td colspan="4">You have not attempted any exam</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>

    {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
@endsection
