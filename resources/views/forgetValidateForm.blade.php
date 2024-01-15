@if(isset($user))
<div class="otp-container">
    <h2>Enter OTP Code</h2>
    <p>We have sent an OTP to change password</p>

    <form action="{{route('forgetpasswordotp')}}" method="POST">
        @csrf
        <input type="text" class="otp-input" name="otp" required placeholder="Enter OTP">
        <input type="hidden" name='user_id' value="{{$user->id}}">
        <button type="submit" class="submit-btn">Verify OTP</button>
    </form>
</div>
@else
    <script>
        window.location = "{{ route('login') }}";
    </script>
@endif

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .otp-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 300px;
        text-align: center;
    }

    .otp-input {
        width: 200px;
        height: 40px;
        margin: 0 auto;
        font-size: 18px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        padding: 5px;
        box-sizing: border-box;
    }

    .submit-btn {
        margin-top: 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
    }
</style>