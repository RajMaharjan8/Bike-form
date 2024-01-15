

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        .password-toggle {
            position: relative;
            margin-bottom: 16px;
        }

        input {
            width: calc(100% - 24px);
            padding: 8px;
            box-sizing: border-box;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
{{-- @if(isset($user)) --}}
<div class="container">
    <h2>Change Password</h2>
    <form action="{{ route('changingpassword') }}" method="post">
        @csrf
        <label for="newPassword">New Password</label>
        <div class="password-toggle">
            <input type="password" id="newPassword" name="password">
            <span class="eye-icon" onclick="togglePassword('newPassword')">&#x1F441;</span>
        </div>
        @error('password')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <label for="confirmPassword">Confirm Password</label>
        <div class="password-toggle">
            <input type="password" id="confirmPassword" name="password_confirmation">
            <span class="eye-icon" onclick="togglePassword('confirmPassword')">&#x1F441;</span>
        </div>
        @error('password_confirmation')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        @if(isset($error))
        <p class="text-danger">{{ $error }}</p>
        @endif
    
        <input type="hidden" name="user_id" value="@if(isset($user)){{ $user->id }}@endif">
        {{-- <input type="hidden" name="user_id" value="{{ $user->id }}"> --}}
        <button type="submit">Change Password</button>
    </form>
</div>
{{-- @else
    <script>
        window.location = "{{ route('login') }}";
    </script>
@endif --}}

<script>
    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>

