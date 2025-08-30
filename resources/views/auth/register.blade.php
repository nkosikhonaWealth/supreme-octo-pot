<x-guest-layout>
    <section id="page-title" class="text-center">
        <div class="container ">
            <div class="d-flex">
                <div class="pe-4">
                    <div class="fancy-title title-border mb-3 text-center"><h5 class="fw-normal color font-body text-uppercase ls1">Project Registration</h5></div>
                    <h2 class="nott display-4 fw-semibold">Register For An IYLY Project Account</h2>
                    <span>
                        <p style="text-align: justify;">Begin your journey to financial independence by registering an account.</p>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">

                <div class="accordion accordion-lg mx-auto mb-0 clearfix" style="max-width: 550px;">

                    <div class="accordion-header">
                        <div class="accordion-icon">
                            <i class="accordion-closed icon-lock3"></i>
                            <i class="accordion-open icon-unlock"></i>
                        </div>
                        <div class="accordion-title">
                            Register For An IYLY Project Account
                        </div>
                    </div>
                    <div class="accordion-content clearfix">
                        <x-validation-errors class="mb-4" />
                        <form id="register-form" name="register-form" class="row mb-0" action="{{route('register')}}" method="POST">
                            @csrf
                            <div class="col-12 form-group">
                                <label class="text-black" for="register-form-name">Name:</label>
                                <input type="text" id="register-form-name" name="name" placeholder="Enter Your Name" autofocus autocomplete="name" :value="name" required class="form-control" />
                            </div>

                            <div class="col-12 form-group">
                                <label class="text-black" for="register-form-email">Email Address:</label>
                                <input type="text" id="register-form-email" name="email" placeholder="Enter Your Email" autocomplete="email" required :value="email" class="form-control" />
                            </div>

                            <div class="col-12 form-group">
                                <label class="text-black" for="register-form-password">Choose Password:</label>
                                <input type="password" id="register-form-password" name="password" placeholder="Password" required autocomplete="new-password" class="form-control" />
                            </div>

                            <div class="col-12 form-group">
                                <label class="text-black" for="register-form-repassword">Re-enter Password:</label>
                                <input type="password" id="register-form-repassword" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" class="form-control" />
                            </div>

                            <div class="col-12 form-group d-flex justify-content-center mt-4">
                                <button class="button button-rounded button-red m-0" id="register-form-submit" name="register" value="Register">Register Now</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

