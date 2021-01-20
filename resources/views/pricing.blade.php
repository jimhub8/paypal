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
                            <td>
                                <form action="{{ route('create-agreement', $subscription->subscription_id) }}" method="POST">
                                    @csrf
                                {{-- <a href="plan/{{ $subscription->subscription_id }}/activate" target="_blank">{{ $subscription->subscription_id }}</a> --}}
                                <input type="submit"  value="Subscribe" class="btn btn-primary">
                            </form>
                            </td>
                            <td>{{ $subscription->created_at }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
