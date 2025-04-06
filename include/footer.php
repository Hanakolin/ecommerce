<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants</title>
    <style>
        footer {
            background-color: black;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 4px rgba(255, 255, 255, 0.1);
        }

        footer h3 {
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: bold;
            color: white;
        }

        footer .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        footer .social-links a {
            text-decoration: none;
            transition: transform 0.3s ease, background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: black;
            border: 1px solid white;
        }

        footer .social-links a:hover {
            transform: scale(1.1);
            background-color: #686D76;
        }

        footer .social-links svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        footer p {
            margin-top: 15px;
            font-size: 14px;
            color: white;
        }

        footer .logo {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        footer .logo img {
            width: 50px;
            height: 50px;
            filter: invert(1); /* Inverts the logo colors for better visibility */
        }

        footer .logo span {
            font-size: 18px;
            font-weight: bold;
            color: white;
        }
    </style>
</head>

<body>
    <footer>
        <h3>Follow Us</h3>
        <div class="social-links">
            <!-- Facebook -->
            <a href="https://facebook.com" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.124v-3.622h3.124v-2.671c0-3.099 1.892-4.788 4.655-4.788 1.321 0 2.462.099 2.794.143v3.241h-1.918c-1.505 0-1.796.715-1.796 1.763v2.312h3.588l-.467 3.622h-3.121v9.294h6.104c.731 0 1.324-.593 1.324-1.325v-21.35c0-.732-.593-1.325-1.324-1.325z" />
                </svg>
            </a>
            <!-- Instagram -->
            <a href="https://instagram.com" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.343 3.608 1.319.975.975 1.257 2.242 1.319 3.608.058 1.265.07 1.645.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.343 2.633-1.319 3.608-.975.975-2.242 1.257-3.608 1.319-1.265.058-1.645.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.343-3.608-1.319-.975-.975-1.257-2.242-1.319-3.608-.058-1.265-.07-1.645-.07-4.85s.012-3.584.07-4.85c.062-1.366.343-2.633 1.319-3.608.975-.975 2.242-1.257 3.608-1.319 1.265-.058 1.645-.07 4.85-.07zM12 0c-3.257 0-3.667.014-4.947.072-1.257.056-2.12.243-2.911.51-.836.283-1.545.663-2.246 1.364-.701.701-1.081 1.41-1.364 2.246-.267.791-.454 1.654-.51 2.911-.058 1.28-.072 1.69-.072 4.947s.014 3.667.072 4.947c.056 1.257.243 2.12.51 2.911.283.836.663 1.545 1.364 2.246.701.701 1.41 1.081 2.246 1.364.791.267 1.654.454 2.911.51 1.28.058 1.69.072 4.947.072s3.667-.014 4.947-.072c1.257-.056 2.12-.243 2.911-.51.836-.283 1.545-.663 2.246-1.364.701-.701 1.081-1.41 1.364-2.246.267-.791.454-1.654.51-2.911.058-1.28.072-1.69.072-4.947s-.014-3.667-.072-4.947c-.056-1.257-.243-2.12-.51-2.911-.283-.836-.663-1.545-1.364-2.246-.701-.701-1.41-1.081-2.246-1.364-.791-.267-1.654-.454-2.911-.51-1.28-.058-1.69-.072-4.947-.072zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zM12 16.505c-2.485 0-4.505-2.02-4.505-4.505s2.02-4.505 4.505-4.505 4.505 2.02 4.505 4.505-2.02 4.505-4.505 4.505zM18.406 4.594c-.796 0-1.438.641-1.438 1.438s.641 1.438 1.438 1.438 1.438-.641 1.438-1.438-.641-1.438-1.438-1.438z" />
                </svg>
            </a>
            <!-- YouTube -->
            <a href="https://youtube.com" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M23.498 6.186c-.27-1.002-1.059-1.789-2.059-2.07-1.826-.493-9.138-.493-9.138-.493s-7.313 0-9.139.492c-1 .281-1.789 1.068-2.059 2.07-.482 1.828-.482 5.647-.482 5.647s0 3.82.482 5.647c.27 1.002 1.059 1.789 2.059 2.07 1.826.493 9.139.493 9.139.493s7.312 0 9.138-.493c1-.281 1.789-1.068 2.059-2.07.482-1.828.482-5.647.482-5.647s0-3.82-.482-5.647zm-13.498 9.314v-6l5.994 3-5.994 3z" />
                </svg>
            </a>
        </div>
        <p>&copy; 2020 Plants. All Rights Reserved.</p>
        <div class="logo">
            <img src="../assets/backgroung_pic/de.png" alt="logo">
            <span>Plant</span>
        </div>
    </footer>
</body>

</html>
