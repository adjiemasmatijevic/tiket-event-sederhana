<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coming Soon | SPASI Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://spasicreative.space//assets/awe/assets/img/bg_foot.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        #countdown {
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .social-icons a {
            color: #fff;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: 0.3s;
        }

        .social-icons a:hover {
            color: #ff7014;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2);
            z-index: -1;
        }
    </style>
</head>

<body>

    <div class="overlay"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <img src="https://spasicreative.space/assets/awe/assets/img/logo2.png" width="150px" alt="">
                <h1 class="mt-5 mb-3">Coming Soon</h1>
                <p class="mb-4">We're working hard to launch our new Platform. Stay tuned!</p>

                <div id="countdown" class="mb-4 fw-bold"></div>

                <div class="social-icons">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-spotify"></i></a>
                    <!-- <a href="#"><i class="bi bi-twitter-x"></i></a> -->
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown Timer
        const countdown = document.getElementById("countdown");
        const launchDate = new Date("2025-10-26T20:30:00").getTime();

        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = launchDate - now;

            if (distance < 0) {
                clearInterval(timer);
                countdown.innerHTML = "We're Live!";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.innerHTML = `${days}d ${hours}h ${mins}m ${secs}s`;
        }, 1000);
    </script>

</body>

</html>