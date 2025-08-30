<div>
    <section id="page-title" class="text-center">
        <div class="container">
            <div class="d-flex">
                <div class="pe-4">
                    <div class="fancy-title title-border mb-3 text-center"><h5 class="fw-normal color font-body text-uppercase ls1">Programme Sign Up</h5></div>
                    <h2 class="nott display-4 fw-semibold">Be A Part Of The ENYC TVET Support Programme</h2>
                    <span>
                        <p style="text-align: justify;">Take the right action for your better future by following the steps to complete your application for the programme.</p>
                    </span>
                </div>
            </div>
        </div>
    </section><!-- #page-title end -->


    <section id="content">
        <div class="content-wrap pb-0">
            <div class="section section-yogas mb-0" style="background-color: rgba(255, 255, 255, 0.8); padding: 10px 0">
                <div class="container">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center bottommargin-lg">
                            <div class="heading-block border-bottom-0 mb-0" style="max-width: 900px">
                                <div class="fancy-title title-border mb-3"><h5 class="fw-normal color font-body text-uppercase ls1">How To Be A Part?</h5></div>
                                <h2 class="fw-bold mb-2 nott" style="font-size: 42px; letter-spacing: -1px">Your <span>ENYC TVET Support Programme</span> Journey</h2>
                                <p class="lead mb-0">It is time for you to take action for a better future. Apply for a TVET startup toolkit.</p>
                            </div>
                            <img src="{{ asset('assets/images/youth4.jpeg') }}" alt="ENYC TVET Support Programme Image" class="d-none d-sm-flex rounded" width="300">
                        </div>

                    <div class="row justify-content-center">
                    <div class="col-md-9 mt-4 mb-5">
                        <div class="card pricing border-0 shadow text-center">
                            <div class="card-body rounded pb-0">
                                <h3>TVET Pathway</h3>
                                <p class="text-black-50" style="text-align: justify;">This pathway is open to any young person who has a vocational qualification, or has attained a vocational skill through practical work in the field with a person / people who are qualified in vocational skills. This pathway is focused on assisting with further developing the vocational knowledge and skills of the youth into a business.</p>
                                <a href="#" class="btn button-link rounded bg-danger text-white text-uppercase fw-semibold ls1 py-2 px-4">Apply Today</a>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="clear bottommargin"></div>

                    <div class="center"><h3>Additional Criteria For The Project</h3></div>
                    <div class="price-features">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="iconlist mb-0">
                                    <li><i class="bi-check-circle-fill color"></i> Complete Time Commitment</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="iconlist mb-0">
                                    <li><i class="bi-check-circle-fill color"></i> Willingness To Learn</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="iconlist mb-0">
                                    <li><i class="bi-check-circle-fill color"></i> Ability To Cooperate</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="section section-schedule" style="background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.8) 70%) left top; padding: 10px 0; background-size: 100% 100%">
                <div class="container">
                    <div class="heading-block border-bottom-0 bottommargin-lg" style="max-width: 900px">
                        <h2 class="fw-bold mb-2 nott" style="font-size: 42px; letter-spacing: -1px">Participation In <span>ENYC TVET Support Programme</span> </h2>
                        <p class="lead">Check how many young people have taken advantage of ENYC TVET Support Programme in your region</p>
                    </div>
                    <div class="row mb-4">
                        <div class="card border-f5 shadow-sm">
                            <div class="card-body py-2 px-2">
                                <div class="row col-mb-50 align-items-center text-xl text-center">
                                    <div class="col-6 col-lg-6">
                                        <div class="counter"><span data-from="0" data-to="{{$participants}}" data-refresh-interval="1" data-speed="500"></span></div>
                                        <h5 class="mb-0 text-smaller text-black-50">Applications Already Recieved</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                    <div class="col-lg-4 col-md-5 mt-4 mt-md-0 sticky-sidebar-wrap">
                        <div class="sticky-sidebar">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="sc-hho-tab" data-bs-toggle="pill" href="#sc-hho" role="tab" aria-controls="sc-hho" aria-selected="true">Hhohho</a>
                                <a class="nav-link" id="sc-mnz-tab" data-bs-toggle="pill" href="#sc-mnz" role="tab" aria-controls="sc-mnz" aria-selected="false">Manzini</a>
                                <a class="nav-link" id="sc-shi-tab" data-bs-toggle="pill" href="#sc-shi" role="tab" aria-controls="sc-shi" aria-selected="false">Shiselweni</a>
                                <a class="nav-link" id="sc-lub-tab" data-bs-toggle="pill" href="#sc-lub" role="tab" aria-controls="sc-lub" aria-selected="false">Lubombo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 mt-5 mt-md-0">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane schedule-wrap fade show active" id="sc-hho" role="tabpanel" aria-labelledby="sc-hho-tab">
                                <dl class="row g-0 table mb-0">
                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Females</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$hhohhoF}} Participants</div>
                                    </dd>

                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Males</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$hhohhoM}} Participants</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane schedule-wrap fade" id="sc-mnz" role="tabpanel" aria-labelledby="sc-mnz-tab">
                                <dl class="row g-0 table mb-0">
                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Females</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$manziniF}} Participants</div>
                                    </dd>

                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Males</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$manziniM}} Participants</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane schedule-wrap fade" id="sc-shi" role="tabpanel" aria-labelledby="sc-shi-tab">
                                <dl class="row g-0 table mb-0">
                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Females</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$shiselweniF}} Participants</div>
                                    </dd>

                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Males</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$shiselweniM}} Participants</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane schedule-wrap fade" id="sc-lub" role="tabpanel" aria-labelledby="sc-lub-tab">
                                <dl class="row g-0 table mb-0">
                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Females</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$lubomboF}} Participants</div>
                                    </dd>

                                    <dt class="col-sm-6"><div class="schedule-time font-primary">Males</div></dt>
                                    <dd class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <div class="schedule-desc font-primary">{{$lubomboM}} Participants</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>                    </div>
            </div>
        </div>

    </section><!-- #content end -->
</div>
@push('scripts')
<script>
    window.addEventListener('registered', function(e) {
        Swal.fire({
            title : e.detail.title
            , icon : e.detail.icon
            , iconColor : e.detail.iconColor
            , timer: 5000
            , toast : true
            , position : 'top-right'
            , width : '500px'
            , timeProgressBar : true
            , showConfirmationButton : false 
        , })
    });
</script>
@endpush
