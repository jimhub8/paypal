@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subscription</th>
                            <th scope="col">Subscription amount</th>
                            <th scope="col">Subscription id</th>
                            <th scope="col">Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $key => $subscription)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $subscription->subscription }}</td>
                            <td>{{ $subscription->subscription_amount }}</td>
                            <td><a href="plan/{{ $subscription->subscription_id }}/activate" target="_blank">{{ $subscription->subscription_id }}</a></td>
                            <td>{{ $subscription->created_at }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <br>
                <form action="{{ route('create_plan') }}" method="POST">
                    @csrf
                    <div>
                        <label for="">Subscription Name</label>
                        <input placeholder="2000" name="subscription" class="form-control">

                    </div>
                    <div>
                        <label for="">Subscription amount</label>
                        <input placeholder="2000" name="subscription_amount" class="form-control">
                    </div>

                    <div>
                        <label for="">Subscription Description</label>

                        <textarea name="subscription_description" id="" cols="30" rows="10"
                            class="form-control"></textarea>
                    </div>

                    <div>
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>

                {{-- <div id="paypal-button-container"></div> --}}

            </div>
        </div>
    </div>
</div>
@endsection
