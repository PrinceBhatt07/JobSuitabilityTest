<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $data['title'] }}</title>
</head>

<body>
    <table>
        <tr>
            <th> Name </th>
            <th> {{ $data['name'] }} </th>
        </tr>
        <tr>
            <th> Email </th>
            <th> {{ $data['email'] }} </th>
        </tr>
    </table>
    <p><b>Note:-</b>You can use your old Password</p>
    <a href="{{ $data['url'] }}"> Click Here to login Your Account</a>
    <p>Thank You</p>
</body>

</html>
