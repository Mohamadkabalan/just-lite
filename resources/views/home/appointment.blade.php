<!-- Appointment Area -->
<div id="appointment-area" class="appointment-area pt-100 pb-70">
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
                            <input type="text" id="datetimepicker" class="form-control date" required data-error="Please Enter Date" placeholder="{{ __('translation.date') }}" name="appointment_date">
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <select class="form-select form-control" name="type_of_service" required>
                                <option selected>{{ __('translation.type-of-service') }}*</option>
                                <option value="1">{{ __('translation.weight-lifting') }}</option>
                                <option value="2">{{ __('translation.organic-yoga') }}</option>
                                <option value="3">{{ __('translation.power-yoga') }}</option>
                            </select>
                        </div>
                    </div>

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
<!-- Appointment Area End -->
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
