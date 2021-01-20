<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script
    src="https://www.paypal.com/sdk/js?client-id=AdqfQ_xn0HyksbTB2lY_BZSTfgIxnsVZ2O6ZhItIt_S3el1B23iWyOagUFS4Ikrd4Maj-GYDGJvKYRju&vault=true&intent=subscription"
    data-sdk-integration-source="button-factory"></script>
<script>
    paypal.Buttons({
style: {
shape: 'rect',
color: 'gold',
layout: 'vertical',
label: 'subscribe'
},
createSubscription: function(data, actions) {
return actions.subscription.create({
'plan_id': 'P-4X0593074X9260716L7XWMWI'
});
},
onApprove: function(data, actions) {
alert(data.subscriptionID);
}
}).render('#paypal-button-container');
</script>
</head>
<body class="antialiased">
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
