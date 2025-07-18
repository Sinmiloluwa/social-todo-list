<!-- resources/views/todo-list-invitation.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>To-Do List Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 30px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee;">
    <tr>
        <td style="padding: 30px;">
            <h2 style="color: #333;">You're Invited!</h2>
            <p style="color: #555;">
                Hello, <br>
                You have been invited by {{$inviter->name}} to join {{$todoList->name}}.
            </p>
            <p style="color: #aaa; font-size: 12px; margin-top: 40px;">
                &copy; {{ date('Y') }} To-Do App
            </p>
        </td>
    </tr>
</table>
</body>
</html>
