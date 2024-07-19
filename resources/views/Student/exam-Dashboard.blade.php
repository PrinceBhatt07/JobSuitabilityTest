@extends('layout/layout')

@section('content')

    @php
        $time = explode(':', $exam[0]['time']);
    @endphp


    <div class="container">
        <div class="sticky-top mb-4">
            <h1 class="text-center">{{ $exam[0]['exam_name'] }}</h1>

        </div>

        <div class="mt-16">

            @php  $qCount = 1; @endphp
            @if ($success == true)

                @if (count($questions) > 0)
                    <h4 class="sticky-top mb-4" style="float:right" id="timer">{{ $exam[0]['time'] }}</h4>

                    <form action="{{ route('examSubmit') }}" method="POST" id="exam_form" class="mb-5">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student_id }}">
                        <input type="hidden" name="exam_id" value="{{ $exam[0]['id'] }}">
                        @foreach ($questions as $data)
                            <div>
                                <h5>Q-{{ $qCount++ }} . {!! $data['question'] !!}</h5>
                                <input type="hidden" name="q[]" value="{{ $data['id'] }}">
                                <input type="hidden" name="ans_{{ $qCount - 1 }}" id="ans_{{ $qCount - 1 }}">
                                @php  $aCount = 1; @endphp
                                @foreach ($data['answers'] as $answer)
                                    <p>
                                        <input type="radio" class='select_ans' data-id="{{ $qCount - 1 }}"
                                            name="radio_{{ $qCount - 1 }}" value="{{ $answer['id'] }}">
                                        {{ $answer['answer'] }}
                                    </p>
                                @endforeach
                            </div>
                        @endforeach
                        <div class=" text-center">
                            <input type="submit" class="btn btn-info" value="Submit">
                        </div>
                    </form>
                @else
                    <h2 class="text-center" style="color:red;">Questions and Answers not Available!</h2>
                @endif
            @else
                <h2 class="text-center" style="color:red;">{{ $msg }}</h2>
            @endif

        </div>

    </div>
    <script>
        $(document).ready(function() {
            $('.select_ans').click(function() {
                var idNum = $(this).attr('data-id');
                $('#ans_' + idNum).val($(this).val());
            });

            var time = @json($time);
            $('.time').text(formatTime(time[0], time[1]) + ' Time Left');

            let storedTime = sessionStorage.getItem('countdownTime');

            let countdownTime;

            if (storedTime && !isNaN(storedTime)) {
                countdownTime = parseInt(storedTime, 10);
            } else {
                // Set initial countdown time (in seconds)
                countdownTime = time[0] * 3600 + time[1] * 60;
            }

            // Start the countdown
            startCountdown(countdownTime);

            function startCountdown(seconds) {
                const timerElement = document.getElementById('timer');

                function updateTimer() {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const remainingSeconds = seconds % 60;

                    timerElement.textContent =
                        `${formatTime(hours, 2)}:${formatTime(minutes, 2)}:${formatTime(remainingSeconds, 2)} Time Left`;
                }

                function countdown() {
                    if (seconds > 0) {
                        seconds--;
                        updateTimer();
                    } else {
                        // Countdown has finished, you can add any specific actions here
                        $('#exam_form').submit();
                        clearInterval(countdownInterval);
                    }
                }

                // Initial display
                updateTimer();

                // Start the countdown interval
                const countdownInterval = setInterval(countdown, 1000);

                // Save the countdown time to session storage on page unload
                window.addEventListener('beforeunload', function() {
                    sessionStorage.setItem('countdownTime', seconds);
                });
            }

            // Function to format time with leading zeros
            function formatTime(value, length) {
                return value.toString().padStart(length, '0');
            }
        });

        function isValid() {
            var result = true;
            var qlength = parseInt('{{ $qCount }}') - 1;
            $('.error_msg').remove();

            for (let i = 1; i <= qlength; i++) {
                if ($('#ans_' + i).val() == "") {
                    result = false;
                    $('#ans_' + i).parent().append(
                        "<span class='error_msg' style='color:red'>Please Select any option</span>");
                    setTimeout(() => {
                        $('.error_msg').remove();
                    }, 2000);
                }
            }

            return result;
        }
    </script>
@endsection
