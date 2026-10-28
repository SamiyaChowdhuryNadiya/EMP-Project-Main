<?php
include 'connection.php';

$messageStatus = "";
$messageColor = "green";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {

        $sql = "INSERT INTO contact (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $message);

            if ($stmt->execute()) {
                $messageStatus = "Message sent successfully!";
                $messageColor = "#22c55e";
            } else {
                $messageStatus = "Failed to send message!";
                $messageColor = "#ef4444";
            }

            $stmt->close();
        } else {
            $messageStatus = "Database error!";
            $messageColor = "#ef4444";
        }

    } else {
        $messageStatus = "All fields are required!";
        $messageColor = "#ef4444";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact - EFFITRACK</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f4f7fb;
}

/* HEADER */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 60px;
    background: #0f172a;
    color: white;
}

/* NAVIGATION */
.navigation a {
    color: white;
    margin: 0 12px;
    text-decoration: none;
    font-weight: 500;
}

/* LOGIN BUTTON */
.cta-button {
    background: linear-gradient(45deg, #38bdf8, #6366f1);
    padding: 8px 16px;
    border-radius: 25px;
    color: white;
}

/* BACK BUTTON */
.back-button {
    margin-left: 12px;
    padding: 8px 14px;
    border-radius: 20px;
    background: #ef4444;
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.back-button:hover {
    background: #dc2626;
    transform: scale(1.05);
}

/* CONTACT SECTION */
.contact {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 20px;
}

.container {
    width: 100%;
    max-width: 1000px;
    text-align: center;
}

.container h2 {
    font-size: 32px;
    margin-bottom: 20px;
}

/* STATUS MESSAGE */
.status {
    margin-bottom: 15px;
    font-weight: 600;
}

/* WRAPPER */
.contact-wrapper {
    display: flex;
    gap: 30px;
    justify-content: center;
    flex-wrap: wrap;
}

/* FORM BOX */
.contact-form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    width: 400px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: left;
}

.contact-form h3 {
    margin-bottom: 15px;
}

/* INPUT */
.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

/* BUTTON */
.contact-form button {
    margin-top: 15px;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 25px;
    background: linear-gradient(45deg, #38bdf8, #6366f1);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.contact-form button:hover {
    transform: scale(1.05);
}

/* INFO BOX */
.contact-info {
    background: #2d3444;
    color: white;
    padding: 30px;
    border-radius: 15px;
    width: 300px;
    text-align: left;
}

/* RESPONSIVE */
@media(max-width: 768px) {
    header {
        flex-direction: column;
        gap: 10px;
    }

    .contact-wrapper {
        flex-direction: column;
        align-items: center;
    }
}

</style>
</head>

<body>

<!-- HEADER -->
<header>
    <div class="logo">EFFITRACK</div>

    <div class="navigation">
        <a href="Home.html">Home</a>
        <a href="About.html">About</a>
        <a href="contact.php">Contact</a>
        <a href="Login.html" class="cta-button">Login</a>

        <!-- BACK BUTTON -->
        <a href="javascript:history.back()" class="back-button">← Back</a>
    </div>
</header>

<!-- CONTACT -->
<div class="contact">

    <div class="container">

        <h2>Contact Us</h2>

        <?php if ($messageStatus != "") { ?>
            <p class="status" style="color: <?php echo $messageColor; ?>;">
                <?php echo $messageStatus; ?>
            </p>
        <?php } ?>

        <div class="contact-wrapper">

            <!-- FORM -->
            <div class="contact-form">
                <h3>Send Message</h3>

                <form action="contact.php" method="post">

                    <input type="text" name="name" placeholder="Your Name" required>

                    <input type="email" name="email" placeholder="Your Email" required>

                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            </div>

            <!-- INFO -->
            <div class="contact-info">
                <h3>Contact Info</h3>
                <p>📞 +880 13 987 12</p>
                <p>📧 example@gmail.com</p>
                <p>📍 Chattogram, Bangladesh</p>
            </div>

        </div>
    </div>

</div>

</body>
</html>
