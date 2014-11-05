<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h3>{{ $report['issue'] }}</h3>
    <table>
        <tr>
            <td>Reported</td>
            <td>{{ date('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>{{ $_ENV['cloudhrd']->name }}</td>
        </tr>
        <tr>
            <td>Host</td>
            <td>{{ $_ENV['host'] }}.{{  $_ENV['domain'] }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $_ENV['cloudhrd']->email }}</td>
        </tr>
        <tr>
            <td>Logged In User</td>
            <td>{{ $user->email }}</td>
        </tr>
    </table>
    
</body>
</html>




