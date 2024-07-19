<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Template</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
    <p>{{ $mailData['body'] }}</p>

    @foreach ($mailData['urls'] as $urlData)
        <p>{{ $urlData['name'] }}</p>
        <a href="{{ $urlData['url'] }}">{{ $urlData['url'] }}</a>
        <br>
    @endforeach

    <p>Thanks</p>
</body>
</html>
