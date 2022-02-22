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