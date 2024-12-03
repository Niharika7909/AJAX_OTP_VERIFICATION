<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OTP Verification System</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    /* Form Styles */
    #registrationForm {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: 0 auto;
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    input:focus {
        border-color: #6c63ff;
        outline: none;
    }

    /* Button Styles */
    button {
        width: 100%;
        padding: 12px;
        background-color: #6c63ff;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        margin: 10px 0;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #5a54e2;
    }

    /* Hidden Elements */
    #otpRow {
        display: none;
    }

    /* Message Styles */
    #message {
        text-align: center;
        margin-top: 20px;
        font-size: 16px;
        font-weight: bold;
    }

    .success {
        color: green;
    }

    .error {
        color: red;
    }
</style>
</head>
<body>
    <h2>Register with OTP Verification</h2>
    <form id="registrationForm">
        <div>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>
        <div>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div id="otpRow" style="display:none;">
            <input type="text" id="otp" name="otp" placeholder="Enter OTP">
        </div>
        <button type="button" id="sendOtpBtn">Send OTP</button>
        <button type="button" id="verifyOtpBtn" style="display:none;">Verify OTP</button>
    </form>
    <div id="message"></div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Function to handle XHR requests
        function xhrRequest(action, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            callback(response);
                        } catch (e) {
                            document.getElementById("message").innerText = "Invalid server response.";
                        }
                    } else {
                        document.getElementById("message").innerText = `An error occurred while processing ${action}.`;
                    }
                }
            };

            const params = Object.keys(data)
                .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(data[key])}`)
                .join("&");
            xhr.send(params);
        }

        // Send OTP Button Click
        document.getElementById("sendOtpBtn").addEventListener("click", function () {
            const formData = {
                action: "send_otp",
                name: document.getElementById("name").value,
                email: document.getElementById("email").value,
                password: document.getElementById("password").value
            };

            xhrRequest("send_otp", formData, function (response) {
                document.getElementById("message").innerText = response.message;
                document.getElementById("message").className = response.status === "success" ? "success" : "error";
                if (response.status === "success") {
                    document.getElementById("otpRow").style.display = "block";
                    document.getElementById("verifyOtpBtn").style.display = "block";
                    document.getElementById("sendOtpBtn").style.display = "none";
                }
            });
        });

        // Verify OTP Button Click
        document.getElementById("verifyOtpBtn").addEventListener("click", function () {
            const formData = {
                action: "verify_otp",
                otp: document.getElementById("otp").value,
                email: document.getElementById("email").value
            };

            xhrRequest("verify_otp", formData, function (response) {
                document.getElementById("message").innerText = response.message;
                document.getElementById("message").className = response.status === "success" ? "success" : "error";
                if (response.status === "success") {
                    document.getElementById("verifyOtpBtn").style.display = "none";
                }
            });
        });
    });
    </script>
</body>
</html>
