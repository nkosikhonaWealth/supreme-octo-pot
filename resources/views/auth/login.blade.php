<x-guest-layout>
    <section id="page-title" class="text-center">
        <div class="container ">
            <div class="d-flex">
                <div class="pe-4">
                    <div class="fancy-title title-border mb-3 text-center"><h5 class="fw-normal color font-body text-uppercase ls1">Project Log In</h5></div>
                    <h2 class="nott display-4 fw-semibold">Log Into Your IYLY Project Account</h2>
                    <span>
                        <p style="text-align: justify;">Continue your journey to financial independence by loggin into your account.</p>
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
                            Log In To Your IYLY Project Account
                        </div>
                    </div>
                    <div class="accordion-content clearfix">
                        <x-validation-errors class="mb-4" />
                        <form id="login-form" name="login-form" class="row mb-0" action="{{route('login')}}" method="POST">
                            @csrf
                            <div class="col-12 form-group">
                                <label for="login-form-username">Email:</label>
                                <input type="text" id="login-form-username" name="email" value="" class="form-control" />
                            </div>

                            <div class="col-12 form-group">
                                <label for="login-form-password">Password:</label>
                                <input type="password" id="login-form-password" name="password" value="" class="form-control" />
                            </div>

                            <div class="col-12 form-group">
                                <button class="button button-3d button-black m-0" id="login-form-submit" name="login-form-submit" value="login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

