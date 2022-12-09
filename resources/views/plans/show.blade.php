@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="">
                <p>You will be charged ${{ number_format($plan->cost, 2) }} for {{ $plan->name }} Plan</p>
            </div>
            <div class="card cell example example1">
                <form action="{{ route('subscription.create') }}" method="post" id="payment-form">
                    @csrf
                    <div class="form-group">
                        <div class="card-header">
                            <label for="card-element">
                                Enter your credit card information
                            </label>
                        </div>
                        <div class="card-body">

                                <fieldset>
                                    <div class="row">
                                        <label for="example1-name" data-tid="elements_examples.form.name_label">Name</label>
                                        <input id="example1-name" data-tid="elements_examples.form.name_placeholder" type="text" placeholder="Jane Doe" required="" autocomplete="name">
                                    </div>
                                    <div class="row">
                                        <label for="example1-email" data-tid="elements_examples.form.email_label">Email</label>
                                        <input id="example1-email" data-tid="elements_examples.form.email_placeholder" type="email" placeholder="janedoe@gmail.com" required="" autocomplete="email">
                                    </div>
                                    <div class="row">
                                        <label for="example1-phone" data-tid="elements_examples.form.phone_label">Phone</label>
                                        <input id="example1-phone" data-tid="elements_examples.form.phone_placeholder" type="tel" placeholder="(941) 555-0123" required="" autocomplete="tel">
                                    </div>
                                </fieldset>

                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                            <input type="hidden" name="plan" value="{{ $plan->id }}" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="card-button" class="btn btn-dark" type="submit" data-secret="{{ $intent->client_secret }}"> Pay </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
