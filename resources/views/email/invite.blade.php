<!DOCTYPE html>
<html>
<head>
    <title>Friend Invitation </title>
</head>
<body>
    <body>
        <h1>Hi {{$mailData['receiverName']}},</h1>
        <br>
        <p>You have new friend request from {{$mailData['senderName']}}.</p>
        <p>click to confirm. <a href="{{ route('invitation.confirm', $mailData['id']) }}">click here</a></p>

        <br>
        Thank you!
    </body>
</body>
</html>