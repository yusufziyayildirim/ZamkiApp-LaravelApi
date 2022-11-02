<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form  action="{{ route("reset.password", ["token" => $token]) }}" method="POST">
        @csrf
        <label for="password" >Password</label>
        <input type="password" name="password">
        <br><br>
        <label for="password" >Password Confrim</label>
        <input type="password" name="password_confirmation">
        <br><br>
        @error('password')
            <div style="color: red">{{ $message }}</div>
        @enderror
        <br>
        <button type="submit">Change password</button>
    </form>
</body>
</html>