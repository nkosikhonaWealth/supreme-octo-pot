<div class="section dark bg-color my-0">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-4 mt-4">
                <img src="demos/doctors/images/icons/wave.svg" alt="..." class="position-absolute top-0 start-0 translate-middle d-none d-md-block" width="36">
                <h3 class="display-6">Get In Touch</h3>
                <p>Email Us On: zaneled@zaned.com</p>

                <div class="divider divider-xs fw-light font-body text-uppercase mb-5 op-05 ms-0" style="--cnvs-divider-border-color: rgba(var(--cnvs-contrast-rgb), 0.2)"><div class="divider-text">Or</div></div>

                <h3 class="fs-6 fw-light text-uppercase op-07 mb-2">Give Us A Quick Call</h3>
                <h2 class="display-5">+268 7801 3943</h2>
            </div>

            <div class="col-md-6">
                <div class="form-widget">

                    <div class="form-result"></div>

                    <form wire:submit="addMessage" class="row mb-0" id="template-contactform" name="template-contactform">
                        <div class="form-process">
                            <div class="css3-spinner">
                                <div class="css3-spinner-scaler"></div>
                            </div>
                        </div>

                        <div class="col-12 form-group">
                            <input wire:model="name" type="text" id="template-contactform-name" name="template-contactform-name" class="form-control border-form-control border-light border-opacity-50 py-3 required" placeholder="Full Name..">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 form-group">
                            <input wire:model="email" type="email" id="template-contactform-email" name="template-contactform-email" value="" class="email form-control border-form-control border-light border-opacity-50 py-3 required " placeholder="Email Address..">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 form-group">
                            <input wire:model="phone" type="text" id="template-contactform-phone" name="template-contactform-phone" value="" class="form-control border-form-control border-light border-opacity-50 py-3" placeholder="Phone Number..">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 form-group">
                            <textarea wire:model="message" class="required form-control border-form-control border-light border-opacity-50 py-3" id="template-contactform-message" name="template-contactform-message" rows="2" cols="30" placeholder="Type Your Message Here.."></textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 form-group d-none">
                            <input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="form-control border-form-control border-light border-opacity-50 py-4">
                        </div>

                        <div class="col-12 form-group">
                            <button class="button button-large button-light h-text-dark h-bg-color-2 rounded ms-0 mt-3" type="submit" id="template-contactform-submit" name="template-contactform-submit" value="submit">Send Message</button>
                        </div>

                        <input type="hidden" name="prefix" value="template-contactform-">
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
