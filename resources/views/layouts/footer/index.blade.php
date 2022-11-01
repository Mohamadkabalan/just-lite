<!-- Modal Start -->
<div class="modal fade fade-scale searchmodal" id="bookModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="appointment-form">
                        <h2>{{ __('translation.make-an-appointment') }}</h2>
                        <form id="appointment-form" method="POST" action="">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" required data-error="Please Enter Your Name" placeholder="{{ __('translation.name') }}*" >
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" required data-error="Please Enter Your Email" placeholder="{{ __('translation.email') }}*">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" required data-error="Please Enter Your Phone Number" placeholder="{{ __('translation.phone') }}*">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <i class="flaticon-weekly-calendar-page-symbol"></i>
                                        <input type="date" id="datetimepicker" class="form-control date" required data-error="Please Enter Date" placeholder="{{ __('translation.date') }}" name="appointment_date">
                                    </div>
                                </div>

                                <input type="hidden" id="type_of_service" name="type_of_service" >

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" id="message" cols="30" rows="5" required data-error="Write your message" placeholder="{{ __('translation.your-message') }}"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <button type="submit" class="default-btn">
                                        {{ __('translation.submit-now') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
<!-- FOOTER -->
<!-- Footer Area -->
<footer class="footer-area footer-area-bg">
    <div class="container">
        <div class="footer-top pt-100 pb-70">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="index.html">
                                <img style="max-width: 160px;" src="{{ asset('images/logo.png') }}" alt="Images">
                            </a>
                        </div>
                        <p>
                            {{ __('translation.footer-text') }}
                        </p>
                        <ul class="social-link">
                            <li>
                                <a href="https://www.facebook.com/bylaylahanna/" target="_blank">
                                    <i class='bx bxl-facebook'></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/in/layla-hanna-313892204/?originalSubdomain=lb" target="_blank">
                                    <i class='bx bxl-linkedin' ></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/just_lite/?hl=en" target="_blank">
                                    <i class='bx bxl-instagram'></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="footer-widget ps-5">
                        <h3>{{ __('translation.useful-links') }}</h3>
                        <ul class="footer-list">
                            <li>
                                <a href="/" target="_blank">
                                    {{ __('translation.home') }}
                                </a>
                            </li>
                            <li>
                                <a href="/about" target="_blank">
                                    {{ __('translation.about-us') }}
                                </a>
                            </li>
                            <li>
                                <a href="/testimonials" target="_blank">
                                    {{ __('translation.testimonials') }}
                                </a>
                            </li>
                            <li>
                                <a href="/shop" target="_blank">
                                    {{ __('translation.shop') }}
                                </a>
                            </li>
                            <li>
                                <a href="/contact-us" target="_blank">
                                    {{ __('translation.contact-us') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-5 col-sm-6">
                    <div class="footer-widget ps-5">
                        <h3>{{ __('translation.informations') }}</h3>
                        <ul class="footer-contact">
                            <li>
                                {{ __('translation.phone') }}:
                                <span>
                                            <a href="tel:+8245678924">{{ __('translation.phone-info') }}</a>
                                        </span>
                            </li>
                            <li>
                                {{ __('translation.email') }}:
                                <span>
                                    <a href="mailto:Layla.G.Hanna@hotmail.com"> Layla.G.Hanna@hotmail.com </a>
                                </span>
                            </li>
                            <li>
                                {{ __('translation.address') }}:
                                <span>{{ __('translation.address-info') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

{{--                <div class="col-lg-4 col-sm-6">
                    <div class="footer-widget ps-5">
                        <h3>Newsletter</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                        <div class="newsletter-area">
                            <form class="newsletter-form" data-toggle="validator" method="POST">
                                <input type="email" class="form-control" placeholder="Enter Your Email" name="EMAIL" required autocomplete="off">
                                <button class="subscribe-btn" type="submit">
                                    Subscribe
                                </button>
                                <div id="validator-newsletter" class="form-result"></div>
                            </form>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>

    <div class="copyright-area">
        <div class="container">
            <div class="copy-right-text text-center">
                <p>
                    {{ __('translation.copy-right') }}
                    <a href="http://itvision.me" target="_blank">ItVision</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Area End -->
<!-- END FOOTER -->
<a href="https://api.whatsapp.com/send?phone=96179779100" target="_blank" class="scroll-whatsapp">
    <svg  xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor" class="bi bi-whatsapp p-2" viewBox="0 0 16 16">
        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
    </svg>
</a>



@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit','#appointment-form',function(e){
                e.preventDefault();
                $.ajax({
                    url: "/appointment/create",
                    method: "POST",
                    data : $('#appointment-form').serialize(),
                    success: function (data) {
                        console.log(data);
                        if(data.status==200){
                            alert(data.message);
                            $('#appointment-form')[0].reset();
                        }
                    }
                });
            });
        });
    </script>
@endpush
