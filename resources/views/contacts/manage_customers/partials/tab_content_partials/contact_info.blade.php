<div class="tab_contant contract_info_area d-hide">
    <div class="row">
        <div class="col-md-6">
            <table class="display table table-sm">
                <tbody>
                    <tr>
                        <td class="text-start">{{ __('Customer ID') }}</td>
                        <td class="text-start">: {{ $contact->contact_id }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Name') }}</td>
                        <td class="text-start">: {{ $contact->name }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Phone') }}</td>
                        <td class="text-start">: {{ $contact->phone }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Business') }}</td>
                        <td class="text-start">: {{ $contact->business_name }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Alternative Phone Number') }}</td>
                        <td class="text-start">: {{ $contact->alternative_phone }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Landline') }}</td>
                        <td class="text-start">: {{ $contact->landline }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Email') }}</td>
                        <td class="text-start">: {{ $contact->email }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Date Of Birth') }}</td>
                        <td class="text-start">: {{ $contact->date_of_birth }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Tax Number') }}</td>
                        <td class="text-start">: {{ $contact->tax_number }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Address') }}</td>
                        <td class="text-start">: {{ $contact->address }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('City') }}</td>
                        <td class="text-start">: {{ $contact->city }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('State') }}</td>
                        <td class="text-start">: {{ $contact->state }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Zip-code') }}</td>
                        <td class="text-start">: {{ $contact->zip_code }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Country') }}</td>
                        <td class="text-start">: {{ $contact->country }}</td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Pay-Term') }}</td>
                        <td class="text-start">:
                            {{ ($contact->pay_term_number ? $contact->pay_term_number : 0) . '/' . ($contact->pay_term == 1 ? __('Days') : __('Months')) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-start">{{ __('Credit Limit') }}</td>
                        <td class="text-start">:
                            {{ App\Utils\Converter::format_in_bdt($contact->credit_limit ? $contact->credit_limit : 0) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
