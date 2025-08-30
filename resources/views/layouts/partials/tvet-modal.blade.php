<div class="modal1 mfp-hide" id="modal-tvet">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white p-4 p-md-5 rounded">
            <div class="d-flex justify-content-between mb-2">
                <h3 class="mb-0 text-uppercase ls1">Apply For TVET Pathway</h3>
                <a href="#" onClick="$.magnificPopup.close();return false;" class="text-muted h4 mb-0 h-text-danger"><i class="icon-line-circle-cross"></i></a>
            </div>
            <div class="line line-sm mt-2 mb-4"></div>

            <div class="form-widget" data-alert-type="inline">
                <div class="form-result"></div>

                <form id="step-form" class="mb-0" wire:submit.prevent="addTVET">
                    @csrf

                    <div class="form-process"></div>

                    <div id="tab-reservation" class="nav nav-pills flex-column flex-lg-row" role="tablist">
                        <a class="nav-link active ms-0" data-bs-toggle="list" href="#tab-reservation-details" role="tab">1. Your Personal Details</a>
                        <a class="nav-link ms-0 mt-2 mt-lg-0 ms-lg-3" data-bs-toggle="list" href="#tab-reservation-select-address" role="tab">2. Your Address</a>
                        <a class="nav-link ms-0 mt-2 mt-lg-0 ms-lg-3" data-bs-toggle="list" href="#tab-reservation-msg" role="tab">3. Your Application Details</a>
                    </div>

                    <div class="line line-sm"></div>

                    <div class="tab-content mt-5" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="tab-reservation-details" role="tabpanel" aria-labelledby="tab-reservation-details">
                            <span class="op-05 text-smaller ls1">STEP - 1/3</span>
                            <h2 class="mb-5 text-uppercase ls1 fw-bold">Your Personal Details</h2>
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-first-name">Name:</label>
                                    <input type="text" class="form-control required" placeholder="Nkosikhona Dlamini" wire:model="name">
                                </div>
                                <div class="col-6 col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-age">Date of Birth:</label>
                                    <input type="text" class="form-control text-start component-datepicker past-enabled required" onchange="this.dispatchEvent(new InputEvent('input'))" wire:model="D_O_B">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-gender">Gender</label>
                                    <select class="form-select required" wire:model="gender">
                                        <option selected value="">-- Select Gender --</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-marital">Marital Status</label>
                                    <select class="form-select required" wire:model="marital_status">
                                        <option selected value="">-- Select Marital Status --</option>
                                        <option value="Single">Single</option>
                                        <option value="Engaged">Engaged</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-email">Email Address:</label>
                                    <input type="email"  class="form-control required" placeholder="nkosikhona@bizgrowsz.com" wire:model="email">
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="step-form-phone">Phone:</label><br>
                                    <input type="text"  class="form-control border-form-control required" placeholder="761234567" wire:model="phone">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-idupload">Upload ID<small class="text-muted">(Maximum file size allowed is 2048 KB.)</small></label>
                                    <div class="form-file">
                                        <input type="file" class="form-control" wire:model="id_upload">
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-password">Choose A Password:</label>
                                    <input type="password" class="form-control required"  wire:model="password">
                                </div>

                                <div class="w-100 clear"></div>

                                <div class="col-12">
                                    <a href="#" class="btn px-5 py-3 tab-action-btn-next float-end text-white" style="background-color: #3D1144;">Next Step</a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-reservation-select-address" role="tabpanel" aria-labelledby="tab-reservation-select-address">
                            <span class="op-05 text-smaller ls1">STEP - 2/3</span>
                            <h2 class="mb-5 text-uppercase ls1 fw-bold">Your Address</h2>
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-phone">Residential Address:</label><br>
                                    <input type="text"  class="form-control border-form-control required" placeholder="Nyamane, next to Methodist Church" wire:model="residential_address">
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-phone">Nearest Town:</label><br>
                                    <input type="text"  class="form-control border-form-control required" placeholder="Nhlangano" wire:model="nearest_town">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-marital">Inkhundla</label>
                                    <select class="form-select required" wire:model="inkhundla">
                                        <option selected value="">-- Select Inkhundla --</option>
                                        <option value="Lobamba">Lobamba</option>
                                        <option value="Madlangemphisi">Madlangemphisi</option>
                                        <option value="Ndzingeni">Ndzingeni</option>
                                        <option value="Mayiwane">Mayiwane</option>
                                        <option value="Ntfonjeni">Ntfonjeni</option>
                                        <option value="Pigg's Peak">Pigg's Peak</option>
                                        <option value="Motjane">Motjane</option>
                                        <option value="Nkhaba">Nkhaba</option>
                                        <option value="Hhukwini">Hhukwini</option>
                                        <option value="Maphalaleni">Maphalaleni</option>
                                        <option value="Mhlangatane">Mhlangatane</option>
                                        <option value="Timphisini">Timphisini</option>
                                        <option value="Mbabane West">Mbabane West</option>
                                        <option value="Mbabane East">Mbabane East</option>
                                        <option value="Siphocosini">Siphocosini</option>
                                        <option value="Ludzeludze">Ludzeludze</option>
                                        <option value="Ekukhanyeni">Ekukhanyeni</option>
                                        <option value="Mkhiweni">Mkhiweni</option>
                                        <option value="Mtfongwaneni">Mtfongwaneni</option>
                                        <option value="Mafutseni">Mafutseni</option>
                                        <option value="LaMgabhi">LaMgabhi</option>
                                        <option value="Motjane">Motjane</option>
                                        <option value="Mhlambanyatsi">Mhlambanyatsi</option>
                                        <option value="Mangcongco">Mangcongco</option>
                                        <option value="Ngwemphisi">Ngwemphisi</option>
                                        <option value="Mahlangatsha">Mahlangatsha</option>
                                        <option value="Manzini North">Manzini North</option>
                                        <option value="Manzini South">Manzini South</option>
                                        <option value="Nhlambeni">Nhlambeni</option>
                                        <option value="Kwaluseni">Kwaluseni</option>
                                        <option value="Lobamba Lomdzala">Lobamba Lomdzala</option>
                                        <option value="Ntondozi">Ntondozi</option>
                                        <option value="Phondo">Phondo</option>
                                        <option value="Nkomiyahlaba">Nkomiyahlaba</option>
                                        <option value="Sandleni">Sandleni</option>
                                        <option value="Zombodze Emuva">Zombodze Emuva</option>
                                        <option value="Somntongo">Somntongo</option>
                                        <option value="Matsanjeni">Matsanjeni</option>
                                        <option value="Sigwe">Sigwe</option>
                                        <option value="Shiselweni I">Shiselweni I</option>
                                        <option value="Gege">Gege</option>
                                        <option value="Maseyisini">Maseyisini</option>
                                        <option value="Kubuta">Kubuta</option>
                                        <option value="Mtsambama">Mtsambama</option>
                                        <option value="Nkwene">Nkwene</option>
                                        <option value="Shiselweni II">Shiselweni II</option>
                                        <option value="Hosea">Hosea</option>
                                        <option value="Ngudzeni">Ngudzeni</option>
                                        <option value="KuMethula">KuMethula</option>
                                        <option value="Matsanjeni">Matsanjeni</option>
                                        <option value="Mpolonjeni">Mpolonjeni</option>
                                        <option value="Siphofaneni">Siphofaneni</option>
                                        <option value="Dvokodvweni">Dvokodvweni</option>
                                        <option value="Lugongolweni">Lugongolweni</option>
                                        <option value="Lomahasha">Lomahasha</option>
                                        <option value="Lubuli">Lubuli</option>
                                        <option value="Sithobelweni">Sithobelweni</option>
                                        <option value="Nkilongo">Nkilongo</option>
                                        <option value="Sithobelweni">Mhlume</option>
                                        <option value="Nkilongo">Gilgal</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-region">Region</label>
                                    <select class="form-select required" wire:model="region">
                                        <option selected value="">-- Select Region --</option>
                                        <option value="Hhohho">Hhohho</option>
                                        <option value="Manzini">Manzini</option>
                                        <option value="Shiselweni">Shiselweni</option>
                                        <option value="Lubombo">Lubombo</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-family-situation">What is your family situation?</label>
                                    <select class="form-select required" wire:model="family_situation">
                                        <option selected value="">-- Select Family Situation --</option>
                                        <option value="Nuclear Family">Nuclear Family</option>
                                        <option value="Extended Family">Extended Family</option>
                                        <option value="Child Headed Family">Child Headed Family</option>
                                        <option value="Orphaned">Orphaned</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-family_role">What is your role in your family?</label>
                                    <select class="form-select required" wire:model="family_role">
                                        <option selected value="">-- Select Family Role --</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-living-situation">Living Situation</label>
                                    <select class="form-select required" wire:model="living_situation">
                                        <option selected value="">-- Select Living Situation --</option>
                                        <option value="Parental Home">Parental Home</option>
                                        <option value="Rental / Work Quarters">Rental / Work Quarters</option>
                                        <option value="School Accomodation">School Accomodation</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-inkhundla">Number of beneficiaries you have:</label>
                                    <input type="text" class="form-control required" placeholder="1" wire:model="beneficiaries">
                                </div>
                                <div class="col-12">
                                    <a href="#" class="btn px-5 py-3 tab-action-btn-next float-end text-white" style="background-color: #3D1144;">Next Step</a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-reservation-msg" role="tabpanel" aria-labelledby="tab-reservation-msg">
                            <span class="op-05 text-smaller ls1">STEP - 3/3</span>
                            <h2 class="mb-5 text-uppercase ls1 fw-bold">Application Details</h2>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-current-status">Which vocational skill are you qualified in?</label>
                                    <select class="form-select required" wire:model="vocational_skill">
                                        <option selected value="">-- Select Vocational Skill --</option>
                                        <option value="Carpentry">Carpentry</option>
                                        <option value="Electrician">Electrician</option>
                                        <option value="Motor Mechanic">Motor Mechanic</option>
                                        <option value="Plumbing">Plumbing</option>
                                        <option value="Sewing">Sewing</option>
                                        <option value="Wielding">Wielding</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-academicupload">Upload your academic certificate / letter of reference</label>
                                    <div class="form-file">
                                        <input type="file" class="form-control" wire:model="academic_upload"  multiple>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-current-status">What are you currently doing?</label>
                                    <select class="form-select required" wire:model="current_status">
                                        <option selected value="">-- Select Current Activity --</option>
                                        <option value="Unemployed">Unemployed</option>
                                        <option value="Employed">Employed</option>
                                        <option value="Vocational Skill Business">Vocational Skill Business</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-current-status">How long have you been doing it?</label>
                                    <select class="form-select required" wire:model="duration">
                                        <option selected value="">-- Select Duration Of Activity --</option>
                                        <option value="1">1 year or less</option>
                                        <option value="2">2 years</option>
                                        <option value="3">3 years</option>
                                        <option value="4">4 years or more</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-5">
                                    <label class="nott ls0 fw-medium" for="step-form-message">If you were awarded a toolkit for your vocational skill, what would you use it for?</label>
                                    <textarea class="required form-control" rows="5" placeholder="Write your message here..." wire:model="toolkit_use"></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-current-status">In the past 6 months, have you recieved any assistance in the form of a grant / award?</label>
                                    <select class="form-select required" wire:model="recent_assistance">
                                        <option selected value="">-- Select your answer --</option>
                                        <option value="Yes">Yes I have</option>
                                        <option value="No">No I have not</option>
                                    </select>
                                </div>
                                 <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-financeupload">Upload any document(s) to support your request for assistance <small class="text-muted">e.g profile, business proposal, etc.</small></label>
                                    <div class="form-file">
                                        <input type="file" class="form-control" wire:model="finance_upload"  multiple>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-current-status">Do you have a bank / MoMo account for business purposes?</label>
                                    <select class="form-select required" wire:model="account">
                                        <option selected value="">-- Select your answer --</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-account-number">If yes, please write the account / phone number:</label>
                                    <input type="text" class="form-control" placeholder="Standard Bank - 90000 / Momo - 7600 0000" wire:model="account_number">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="nott ls0 fw-medium" for="step-form-disability-status">Do you have a disability?</label>
                                    <select class="form-select required" wire:model="disability">
                                        <option selected value="">-- Select your answer --</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-4">
                                    <label class="nott ls0 fw-medium" for="step-form-disability-name">If yes, please state the disability:</label>
                                    <input type="text" class="form-control" placeholder="Impaired Vision" wire:model="disability_name">
                                </div>
                                <div class="col-12 mb-5">
                                    <label class="nott ls0 fw-medium" for="step-form-message">Please tell us why you have applied and what you hope to gain from this program:</label>
                                    <textarea class="required form-control" rows="5" placeholder="Write your message here..." wire:model="motivation"></textarea>
                                </div>
                                <div class="col-12 d-none">
                                    <input type="text" id="step-form-botcheck" name="step-form-botcheck" value="" />
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="step-form-submit" class="btn px-5 py-3 float-end text-white" style="background-color: #3D1144;">Submit Now</button>
                                </div>
                                <input type="hidden" name="prefix" value="step-form-">
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
