<div>
    <!-- Page Title
        ============================================= -->
        <section id="page-title" class="text-center">
            <div class="container">
                <div class="d-flex">
                    <div class="pe-4">
                        <div class="fancy-title title-border mb-3 text-center"><h5 class="fw-normal color font-body text-uppercase ls1">Contact Us</h5></div>
                        <h2 class="nott display-4 fw-semibold">Interact With The ENYC TVET Support Programme Team</h2>
                        <span>
                            <p style="text-align: justify;">The ENYC TVET Support Programme has warm, vibrant, and young people who are ready to assist you with any aspect of the programme, so that you can also benefit.</p>
                        </span>
                    </div>
                </div>
            </div>
        </section><!-- #page-title end -->

        <section id="content">
            <div class="content-wrap py-0">
                <div class="container">
                    <div id="section-contact" class="page-section">
                        <h2 class="bottommargin">Get in Touch.</h2>

                        <div class="row clearfix">

                            <div class="col-lg-12">
                                <div class="form-widget">

                                    @if (session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                
                                    <form wire:submit.prevent="sendEmail" class="mb-0">
                                        <div class="row">
                                            <div class="col-6 form-group">
                                                <label for="name">Name <small>*</small></label>
                                                <input type="text" id="name" wire:model="name" class="form-control required">
                                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                
                                            <div class="col-6 form-group">
                                                <label for="email">Email <small>*</small></label>
                                                <input type="email" id="email" wire:model="email" class="required email form-control">
                                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                
                                            <div class="col-12 form-group">
                                                <label for="message">Message <small>*</small></label>
                                                <textarea class="required form-control" id="contact_message" wire:model="contact_message" rows="4"></textarea>
                                                @error('message') <span class="text-danger">{{ $contact_message }}</span> @enderror
                                            </div>
                                
                                            <div class="col-12 form-group text-end">
                                                <button class="button button-rounded button-small m-0" type="submit">Send Message</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
