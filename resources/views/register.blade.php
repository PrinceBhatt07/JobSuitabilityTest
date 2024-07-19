@extends('layout.layout')

@section('content')
    <style>
        .gradient-custom-3 {
            /* fallback for old browsers */
            background: #84fab0;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
        }

        .gradient-custom-4 {
            /* fallback for old browsers */
            background: #84fab0;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
        }

        .modal-dialog {
            min-width: 85vw;
        }
    </style>

    <!-- Trigger the modal with a button -->
    {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> --}}

    <!-- Modal -->
    <div class="modal fade" id="termModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">My Virtual Team Pvt. Ltd.</h4>
                </div>
                <div class="modal-body">

                    <p>
                    <h6>Dear Participant,</h6>
                    Welcome to our in-house Candidate Assessment Test. As we embark on this journey to evaluate skills
                    and fit within our internship program, we want to ensure a transparent, fair, and secure testing
                    process for everyone involved.
                    </p>

                    <p><h5>Monitoring & Integrity:</h5></p>
                    <p>To uphold the integrity of this assessment, we employ certain monitoring techniques. These include
                        detecting significant actions such as tab switching or window changing during the test. This
                        approach helps us maintain a level playing field for all participants.</p>

                    <p><h5>Data Privacy & Storage:</h5></p>
                    <p><h6>- Data Collection:</h6> In addition to monitoring test integrity, we will collect and store
                        certain data related to your test performance and actions during the test. This may include
                        timestamps of actions, test scores, and responses.</p>
                    <p><h6>- Purpose:</h6> The data collected will be used exclusively for assessing your test
                        performance, improving our testing platform, and maintaining the integrity and security of the
                        testing process.</p>
                    <p><h6>- Security:</h6> Your privacy and data security are our top priorities. All personal
                        information and test data are securely stored, adhering to the highest standards of data protection.
                        Rest assured, we do not access or record any personal information or screen content outside the
                        parameters of the test.</p>
                    <p><h6>- Consent:</h6> By proceeding with this assessment, you consent to these monitoring
                        measures and the collection and storage of your data as outlined above.</p>

                    <p><h5>What This Means for You:</h5></p>
                    <p>We encourage you to stay focused on the test window throughout the assessment. Switching tabs or
                        windows may trigger a notification, and repeated instances could influence your test results.</p>

                    <p><h5>Understanding & Agreement:</h5></p>
                    <p>By starting this test, you acknowledge and agree to the terms mentioned regarding monitoring and data
                        handling. We appreciate your cooperation in ensuring a fair and secure testing environment.</p>

                    <p><h5>Technical Requirements & Assistance:</h5></p>
                    <p>Please ensure a stable internet connection and close unnecessary tabs or applications. If you face
                        any issues or have concerns, reach out to us at <a
                            href="mailto:support@myvirtualteams.com">support@myvirtualteams.com</a> or directly approach our
                        HR personnel.</p>

                    <p>Thank you for taking the time to understand these guidelines. We wish you the best in your
                        assessment!</p>

                    <div>
                        <input type="checkbox" name="terms" id="terms" onchange="activateButton(this)"> I Agree Terms
                        &
                        Conditions
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" onclick="termSubmit()" class="btn btn-primary" id="submit"
                        data-dismiss="modal">Submit</button>

                </div>
            </div>

        </div>
    </div>

    <section id="section" style="display: none" class="vh-100 bg-image"
        style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">

        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <d class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Create an accountt</h2>

                                <form action="{{ route('studentRegister') }}" id="registerForm" method="POST">
                                    @csrf

                                    <input type="hidden" id="exam_id" name="exam_id" value="{{ $exam_id }}" />

                                    <div class="mb-4">
                                        <input type="text" id="form3Example1cg" name="name"
                                            placeholder="Enter your Full Name" class="form-control form-control-lg" />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="email" id="form3Example3cg" name="email"
                                            placeholder="Enter your Email " class="form-control form-control-lg" />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="tel" id="form3Example5cg" name="phone"
                                            placeholder="Enter your Phone" class="form-control form-control-lg"
                                            pattern="[0-9]{10}" required />
                                    </div>


                                    <div class="form-outline mb-4">
                                        <select name="qualification[]" id="qualification">
                                            <option disabled selected>Choose your Qualification</option>
                                            <option value="BCA">BCA</option>
                                            <option value="MCA">MCA</option>
                                            <option value="B.Tech">B.Tech</option>
                                            <option value="M.tech">M.tech</option>
                                            <option value="Other">Other</option>
                                        </select>

                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="qualificationInput" name="qualification[]"
                                            placeholder="Enter your qualification" class="form-control form-control-lg"
                                            style="display: none" />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="hidden" id="form3Example4cg" name="password"
                                            placeholder="Enter your Password" class="form-control form-control-lg"
                                            value="1" />
                                    </div>

                                    <div class="form-outline mb-4">
                                        {{-- <input type="password" id="form3Example4cdg" name="password_confirmation"
                                            placeholder="Confirm Your Password"
                                            class="form-control form-control-lg" /> --}}
                                        @foreach ($errors->all() as $error)
                                            <p style="color: red">{{ $error }}</p>
                                        @endforeach
                                    </div>


                                    <div class="d-flex justify-content-center">
                                        <button type="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">
                                            Save and Start Test
                                        </button>
                                    </div>

                                    @if ($errors->any())
                                    @endif
                                    @if (Session::has('success'))
                                        <p style="ctermsolor: green">{{ Session::get('success') }} </p>
                                    @endif
                                    {{-- <p
                                        class="text-center text-muted mt-5 mb-0">Have already an account?
                                        <a href="/login"
                                        class="fw-bold              <input type="checkbox" name="terms" id="terms" onchange="activateButton(this)"> I Agree Terms &
                    Conditions text-body"><u>login here</u>
                                    </a></p> --}}
                                </form>

                            </div>
                        </d iv>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).on('change', '#qualification', function(e) {
            // Does some stuff and logs the event to the console
            if (this.value == 'Other') {
                $('#qualificationInput').show();
            } else {
                $('#qualificationInput').hide();
            }
        });


        $(document).ready(function() {

            $('#terms').prop('checked', false); // Unchecks it
            localStorage.removeItem('myCheckboxValue');
            $('#termModal').modal('show');
            document.getElementById("submit").disabled = true;

        });



        function activateButton(element) {

            if (element.checked) {
                document.getElementById("submit").disabled = false;
            } else {
                document.getElementById("submit").disabled = true;
            }

        }


        function termSubmit(element) {
            var checkbox = document.getElementById('terms');
            localStorage.setItem('myCheckboxValue', checkbox.checked);
            $('#section').show();

        }



        $(document).on('submit', '#registerForm', function(e) {
            // Does some stuff and logs the event to the console
            var checkboxValue = localStorage.getItem('myCheckboxValue');
            console.log(checkboxValue);
            if (checkboxValue != 'true') {
                e.preventDefault();
                alert("Please refresh and agree to the terms and conditions");
            } else {
                localStorage.removeItem('myCheckboxValue');
            }
        });
    </script>
@endsection
