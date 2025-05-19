<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CTS Parcel Office</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="main-wrapper">
    <nav>
      <div class="heading-logo">
        <div class="logo">
          <img class="img-logo" src="includes/img/logo.png" alt="CTS logo" />
        </div>
        <div class="heading">
          <h1>CTS Courier Service</h1>
        </div>
      </div>

      <div class="nav-links">
        <a href="#" data-section="home">Home</a>
        <a href="#" data-section="track">Track Parcel</a>
        <a href="#" data-section="payment">Payment</a>
        <a href="#" data-section="contact">Contact</a>

        <a class="login-link" href="auth/login.php"><button class="login">Login</button></a>
        <a class="login-link" href="auth/register.php"><button class="login">Register</button></a>
      </div>
    </nav>

    <!-- HOME SECTION -->
    <section id="home" class="content-section home-section active">
      <div class="home-overlay">
        <div class="home-content">
          <h1>Fast & Reliable Parcel Delivery</h1>
          <p>CTS delivers your packages quickly and securely across Malawi and beyond.</p>
          <a href="#track"><button class="cta-button">Explore Services</button></a>
        </div>
      </div>
    </section>

    <!-- OTHER SECTIONS -->
    <section id="track" class="content-section">
      <h2>Track Your Parcel</h2>
      <form>
        <label for="tracking-number">Enter Tracking Number:</label>
        <input type="text" id="tracking-number" name="tracking-number" />
        <button type="submit">Track</button>
      </form>
    </section>

    <section id="payment" class="content-section">
      <h2>Payment Portal</h2>
      <p>Securely pay for your parcel services using mobile money or bank cards.</p>
    </section>

    <section id="about" class="content-section">
      <h2>About CTS</h2>
      <p>CTS is committed to providing fast, secure, and affordable parcel delivery services across Malawi.</p>
    </section>

    <section id="contact" class="content-section">
      <h2>Contact Us</h2>
      <p>Email: info@cts.mw</p>
      <p>Phone: +265 999 999 999</p>
      <p>Address: CTS Head Office, Lilongwe, Malawi</p>
    </section>

    <footer>
      <p>© 2025 CTS Parcel Office. All rights reserved.</p>
    </footer>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const navLinks = document.querySelectorAll('.nav-links a[data-section]');
      const sections = document.querySelectorAll('.content-section');

      navLinks.forEach(link => {
        link.addEventListener('click', event => {
          event.preventDefault();
          const targetId = link.getAttribute('data-section');

          sections.forEach(section => {
            section.classList.remove('active');
          });

          const targetSection = document.getElementById(targetId);
          if (targetSection) {
            targetSection.classList.add('active');
          }
        });
      });
    });
  </script>
</body>
</html>
