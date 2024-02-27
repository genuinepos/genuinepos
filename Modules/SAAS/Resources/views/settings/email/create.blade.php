<x-saas::admin-layout title="Create Coupon">
    @push('css')
    <style>

.switch .btn-toggle {
  top: 50%;
  transform: translateY(-50%);
}
.btn-toggle {
  margin: 0 4rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 3rem;
  border-radius: 1.5rem;
  color: #6b7381;
  background: #bdc1c8;
}
.btn-toggle:focus,
.btn-toggle.focus,
.btn-toggle:focus.active,
.btn-toggle.focus.active {
  outline: none;
}
.btn-toggle:before,
.btn-toggle:after {
  line-height: 1.5rem;
  width: 4rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}
.btn-toggle:before {
  content: 'Off';
  left: -4rem;
}
.btn-toggle:after {
  content: 'On';
  right: -4rem;
  opacity: 0.5;
}
.btn-toggle > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left 0.25s;
}
.btn-toggle.active {
  transition: background-color 0.25s;
}
.btn-toggle.active > .handle {
  left: 1.6875rem;
  transition: left 0.25s;
}
.btn-toggle.active:before {
  opacity: 0.5;
}
.btn-toggle.active:after {
  opacity: 1;
}
.btn-toggle.btn-sm:before,
.btn-toggle.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.4125rem;
  width: 2.325rem;
}
.btn-toggle.btn-sm:before {
  text-align: right;
}
.btn-toggle.btn-sm:after {
  text-align: left;
  opacity: 0;
}
.btn-toggle.btn-sm.active:before {
  opacity: 0;
}
.btn-toggle.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-xs:before,
.btn-toggle.btn-xs:after {
  display: none;
}
.btn-toggle:before,
.btn-toggle:after {
  color: #6b7381;
}
.btn-toggle.active {
  background-color: #29b5a8;
}
.btn-toggle.btn-lg {
  margin: 0 5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 2.5rem;
  width: 5rem;
  border-radius: 2.5rem;
}
.btn-toggle.btn-lg:focus,
.btn-toggle.btn-lg.focus,
.btn-toggle.btn-lg:focus.active,
.btn-toggle.btn-lg.focus.active {
  outline: none;
}
.btn-toggle.btn-lg:before,
.btn-toggle.btn-lg:after {
  line-height: 2.5rem;
  width: 5rem;
  text-align: center;
  font-weight: 600;
  font-size: 1rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}
.btn-toggle.btn-lg:before {
  content: 'Off';
  left: -5rem;
}
.btn-toggle.btn-lg:after {
  content: 'On';
  right: -5rem;
  opacity: 0.5;
}
.btn-toggle.btn-lg > .handle {
  position: absolute;
  top: 0.3125rem;
  left: 0.3125rem;
  width: 1.875rem;
  height: 1.875rem;
  border-radius: 1.875rem;
  background: #fff;
  transition: left 0.25s;
}
.btn-toggle.btn-lg.active {
  transition: background-color 0.25s;
}
.btn-toggle.btn-lg.active > .handle {
  left: 2.8125rem;
  transition: left 0.25s;
}
.btn-toggle.btn-lg.active:before {
  opacity: 0.5;
}
.btn-toggle.btn-lg.active:after {
  opacity: 1;
}
.btn-toggle.btn-lg.btn-sm:before,
.btn-toggle.btn-lg.btn-sm:after {
  line-height: 0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.6875rem;
  width: 3.875rem;
}
.btn-toggle.btn-lg.btn-sm:before {
  text-align: right;
}
.btn-toggle.btn-lg.btn-sm:after {
  text-align: left;
  opacity: 0;
}
.btn-toggle.btn-lg.btn-sm.active:before {
  opacity: 0;
}
.btn-toggle.btn-lg.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-lg.btn-xs:before,
.btn-toggle.btn-lg.btn-xs:after {
  display: none;
}
.btn-toggle.btn-sm {
  margin: 0 0.5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 3rem;
  border-radius: 1.5rem;
}
.btn-toggle.btn-sm:focus,
.btn-toggle.btn-sm.focus,
.btn-toggle.btn-sm:focus.active,
.btn-toggle.btn-sm.focus.active {
  outline: none;
}
.btn-toggle.btn-sm:before,
.btn-toggle.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}
.btn-toggle.btn-sm:before {
  content: 'Off';
  left: -0.5rem;
}
.btn-toggle.btn-sm:after {
  content: 'On';
  right: -0.5rem;
  opacity: 0.5;
}
.btn-toggle.btn-sm > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left 0.25s;
}
.btn-toggle.btn-sm.active {
  transition: background-color 0.25s;
}
.btn-toggle.btn-sm.active > .handle {
  left: 1.6875rem;
  transition: left 0.25s;
}
.btn-toggle.btn-sm.active:before {
  opacity: 0.5;
}
.btn-toggle.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-sm.btn-sm:before,
.btn-toggle.btn-sm.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.4125rem;
  width: 2.325rem;
}
.btn-toggle.btn-sm.btn-sm:before {
  text-align: right;
}
.btn-toggle.btn-sm.btn-sm:after {
  text-align: left;
  opacity: 0;
}
.btn-toggle.btn-sm.btn-sm.active:before {
  opacity: 0;
}
.btn-toggle.btn-sm.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-sm.btn-xs:before,
.btn-toggle.btn-sm.btn-xs:after {
  display: none;
}
.btn-toggle.btn-xs {
  margin: 0 0;
  padding: 0;
  position: relative;
  border: none;
  height: 1rem;
  width: 2rem;
  border-radius: 1rem;
}
.btn-toggle.btn-xs:focus,
.btn-toggle.btn-xs.focus,
.btn-toggle.btn-xs:focus.active,
.btn-toggle.btn-xs.focus.active {
  outline: none;
}
.btn-toggle.btn-xs:before,
.btn-toggle.btn-xs:after {
  line-height: 1rem;
  width: 0;
  text-align: center;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}
.btn-toggle.btn-xs:before {
  content: 'Off';
  left: 0;
}
.btn-toggle.btn-xs:after {
  content: 'On';
  right: 0;
  opacity: 0.5;
}
.btn-toggle.btn-xs.active {
  transition: background-color 0.25s;
}
.btn-toggle.btn-xs.active > .handle {
  left: 1.125rem;
  transition: left 0.25s;
}
.btn-toggle.btn-xs.active:before {
  opacity: 0.5;
}
.btn-toggle.btn-xs.active:after {
  opacity: 1;
}
.btn-toggle.btn-xs.btn-sm:before,
.btn-toggle.btn-xs.btn-sm:after {
  line-height: -1rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.275rem;
  width: 1.55rem;
}
.btn-toggle.btn-xs.btn-sm:before {
  text-align: right;
}
.btn-toggle.btn-xs.btn-sm:after {
  text-align: left;
  opacity: 0;
}
.btn-toggle.btn-xs.btn-sm.active:before {
  opacity: 0;
}
.btn-toggle.btn-xs.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-xs.btn-xs:before,
.btn-toggle.btn-xs.btn-xs:after {
  display: none;
}
.btn-toggle.btn-secondary {
  color: #6b7381;
  background: #bdc1c8;
}
.btn-toggle.btn-secondary:before,
.btn-toggle.btn-secondary:after {
  color: #6b7381;
}
.btn-toggle.btn-secondary.active {
  background-color: #ff8300;
}

.btn-toggle.btn-sm:hover{
  background:#0D99FF;
}

</style>
@endpush
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create Email Settings') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.email-settings.index') }}" class="btn btn-sm btn-primary">{{ __('All Settings') }}</a>
                    </div>
                </div>
                <div class="panel-body">

                    <form method="POST" action="{{ route('saas.email-settings.store') }}" id="couponstoreForm">
                        @csrf
                        <div class="row g-3">

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="code" class="form-label"><strong>{{ __('Provider Name') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="provider_name" value="{{old('provider_name')}}" class="form-control" autocomplete="off" id="provider_name" placeholder="{{ __('Enter Provider Name') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="start_date" class="form-label"><strong>{{ __('Mail Mailer') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_mailer"  value="{{old('mail_mailer')}}" autocomplete="off" class="form-control date-picker hasDatepicker" id="dp1709016555514"  placeholder="{{ __('Enter Mail Mailer') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="end_date" class="form-label"><strong>{{ __('Mail Host') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_host" value="{{old('mail_host')}}" class="form-control" autocomplete="off" id="mail_host" placeholder="{{ __('Enter Mail Host') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail Port') }}</strong><span class="text-danger">*</span></label>
                                <input type="number" name="mail_port" value="{{old('mail_port')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Enter Mail Port') }}" required>
                            </div>

                             <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail Username') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_username" value="{{old('mail_username')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Enter Mail Username') }}" required>
                            </div>

                             <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail Password') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_password" value="{{old('mail_password')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Enter Mail Password') }}" required>
                             </div>

                             <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail Encryption') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_encryption" value="{{old('mail_encryption')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Enter Mail Encryption') }}" required>
                             </div>

                             <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail From Address') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_from_address" value="{{old('mail_from_address')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Mail From Address') }}" required>
                             </div>

                              <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Mail From Name') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="mail_from_name" value="{{old('mail_from_name')}}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Mail From Name') }}" required>
                             </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Active') }}</strong></label>
                                <button type="button" class="btn btn-sm btn-toggle minimum_purchase_class" autocomplete="off" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="minimum_purchase_input" name="mail_active" value="1">
                             </div>

                            <div class="mt-3">
                                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Save') }}" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){

                $('.minimum_purchase_class').on('click', function(){
                    var toggleValue = $(this).hasClass('active') ? 0 : 1;
                    $('#minimum_purchase_input').val(toggleValue);
                });

            });
        </script>
    @endpush
</x-saas::admin-layout>
