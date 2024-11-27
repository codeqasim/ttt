<section class="layout-pt-lg layout-pb-lg bg-blue-2">
    <div class="container">
        <div class="row justify-center">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="px-50 py-50 sm:px-20 sm:py-20 bg-white shadow-4 rounded-4">
                    <form id="signup" action="<?=root?>signup" method="post" class="mb-5">
                        <div class="row y-gap-20">
                            <div class="col-12">
                                <h1 class="text-22 fw-500">Sign in or create an account</h1>
                                <p class="mt-10">Already have an account? 
                                    <a href="<?=root?>login" class="text-blue-1">Log in</a>
                                </p>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <input type="text" id="firstname" name="first_name" required>
                                    <label class="lh-1 text-14 text-light-1">First Name</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <input type="text" id="last_name" name="last_name" required>
                                    <label class="lh-1 text-14 text-light-1">Last Name</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <select name="phone_country_code" class="selectpicker w-100" data-live-search="true" required>
                                        <option value="">Select Country</option>
                                        <?php foreach($meta['countries'] as $c) { ?>
                                            <option value="<?=$c->id?>" data-content="<img class='' src='./admin/assets/img/flags/<?=strtolower($c->iso)?>.svg' style='width: 20px; margin-right: 14px;color:#fff'><span class='text-dark'> <?=$c->nicename?> <strong>+<?=$c->phonecode?></strong></span>"></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <input type="text" id="phone" name="phone" required>
                                    <label class="lh-1 text-14 text-light-1">Phone</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <input type="email" id="user_email" name="user_email" required>
                                    <label class="lh-1 text-14 text-light-1">Email</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-input">
                                    <input type="password" id="password" name="password" required>
                                    <label class="lh-1 text-14 text-light-1">Password</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="g-recaptcha" data-sitekey="6LdX3JoUAAAAAFCG5tm0MFJaCF3LKxUN4pVusJIF" data-callback="correctCaptcha"></div>
                                <script src="https://www.google.com/recaptcha/api.js"></script>
                                <script>
                                    var correctCaptcha = function(response) {
                                        $('#submitBTN').prop('disabled', false);
                                    };
                                </script>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex">
                                    <!-- <div class="form-checkbox mt-5">
                                        <input type="checkbox" class="agree" name="agree">
                                        <div class="form-checkbox__mark">
                                            <div class="form-checkbox__icon icon-check"></div>
                                        </div>
                                    </div> -->
                                    <div class="text-15 lh-15 text-light-1 ml-10">By signup I agree to terms and policy</div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="signup_button">
                                    <button id="submitBTN" disabled class="button py-20 -dark-1 bg-blue-1 text-white w-100">
                                        Sign Up
                                        <div class="icon-arrow-top-right ml-15"></div>
                                    </button>
                                </div>
                                <div class="loading_button" style="display:none">
                                    <button class="loading_button gap-2 w-100 btn btn-primary btn-m rounded-sm font-700 text-uppercase btn-full" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row y-gap-20 pt-30">
                            <div class="col-12">
                                <div class="text-center px-30">Or sign in with</div>
                                <button class="button col-12 -outline-blue-1 text-blue-1 py-15 rounded-8 mt-10">
                                    <i class="icon-apple text-15 mr-10"></i> Facebook
                                </button>
                                <button class="button col-12 -outline-red-1 text-red-1 py-15 rounded-8 mt-15">
                                    <i class="icon-apple text-15 mr-10"></i> Google
                                </button>
                                <button class="button col-12 -outline-dark-2 text-dark-2 py-15 rounded-8 mt-15">
                                    <i class="icon-apple text-15 mr-10"></i> Apple
                                </button>
                            </div>
                        </div> -->
                        <input type="hidden" name="form_token" value="<?=$_SESSION["form_token"]?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>