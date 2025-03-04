<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movies Shows Events</title>
    <link rel="stylesheet" href="dash.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" href="images icons/link.png" type="image/icon type">
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
    />
    <script>
        function searchTitles() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let items = document.getElementsByClassName("event-list-item-title");
            
            for (let i = 0; i < items.length; i++) {
                let title = items[i].textContent.toLowerCase();
                if (title.includes(input)) {
                    items[i].scrollIntoView({ behavior: "smooth", block: "center" });
                    items[i].style.backgroundColor = "black";
                } else {
                    items[i].style.backgroundColor = "";
                }
            }
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("searchButton").addEventListener("click", searchTitles);
        });
    </script>
  </head>
  <body>
    <header
      style="
        position: sticky;
        top: 0px;
        background-color: rgba(255, 255, 255, 0.559);
        z-index: 9999;
      "
    >
      <nav class="navbar">
        <div
          class="logo"
          style="
            height: 50px;
            width: auto;
            max-height: 100%;
            object-fit: contain;
          
          "
        >
          <img src="images icons/favicon.png" alt="Logo" height="50px" width="50px" />

          <span
            class="logotextt"
            style="
              color: rgb(0, 0, 0);
             position: absolute;
             top:15px;
              font-weight: bold;
              font-size: 17px;
            "
            >QuickTickets</span
          >
        </div>
        <ul class="nav-links">
          <li onclick="location.href='#home'"><a href="#home">Home</a></li>
          <li><a href="aboutus.html">About</a></li>
          <li><a href="contactus.php">Contact</a></li>
          <li><a href="testomonials.html">Feedback</a></li>
        </ul>
        <div class="nav-right">
         
        <div class="search">
    <input id="searchInput" placeholder="Search..." type="text" />
    <button type="submit" id="searchButton">Go</button>
</div>

          

          <div class="dropdown-text" onclick="toggleDropdown()">Location</div>
          <div class="profile">
            <a href="userprofile.php">
                <img src="images icons/usericon (2).png" alt="Profile" />
            </a>
        </div>
        
        </div>
      </nav>
    </header>

    <!-- Location Dropdown -->
    <div class="dropdown-box" id="dropdown-box" style="display: none">
      <div class="close-btn">
        <span onclick="toggleDropdown()">Close</span>
      </div>
      <div class="grid">
        <div class="city"><i class="fas fa-city"></i>Pune</div>
        <div class="city"><i class="fas fa-landmark"></i>Hydrabad</div>
        <div class="city"><i class="fas fa-building"></i>Kolkata</div>
        <div class="city"><i class="fas fa-tree"></i>Delhi</div>
        <div class="city"><i class="fas fa-university"></i>Ajmer</div>
        <div class="city"><i class="fas fa-hotel"></i>Chennai</div>
        <div class="city"><i class="fas fa-monument"></i>Ahmadabad</div>
        <div class="city"><i class="fas fa-water"></i>Banglore</div>
        <div class="city"><i class="fas fa-home"></i>Chandigarh</div>
        <div class="city"><i class="fas fa-cogs"></i>Mumbai</div>
      </div>
    </div>

    <script>
      function toggleDropdown() {
        const dropdown = document.getElementById("dropdown-box");
        dropdown.style.display =
          dropdown.style.display === "block" ? "none" : "block";
      }
    </script>
    <main>
      <!--This section is for categories-->
      <div class="categories" id="home">
        <a href="#movies">Movies</a>
        <a href="#events">Events</a>
        <a href="#shows">Shows</a>
        <a href="#sports">Sports</a>
        <a href="#standupcomedy">Stand-Up Comedy</a>
        <a href="#concerts">Concerts</a>
      </div>
     <!-- Movies Section -->
<div class="container">
    <div class="featured-content" style="
          background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
            url('movie\ img/imgtop4');
          background-position: center;
          background-size: cover;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
        ">
        <img class="featured-title" src="img/f-t-1.png" alt="" />
    </div>
    
    <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch movies
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<h1 id="movies" class="event-list-title" style="margin-left:20px; margin-top:5px;">Movies</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Construct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                // Check if file exists, otherwise use default
                if (!file_exists($imagePath) || empty($row["image"])) {
                    $imagePath = "assets/default.jpg";
                }

                // Securely encode movie title for URL
                $safeTitle = urlencode($row["title"]);
                
                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?movie_id=" . (int)$row["movie_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";        
                echo '</div>';
            }
        } else {
            echo "<p>No movies available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </div>
</div>
<!-- Movies Section Ends -->


      <!--this section is for events-->
      <div class="container">
        <div class="content-container">
          <div
            class="featured-content"
            style="
              background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
                url('movie\ img/imgtop1');
              background-position: center;
              background-size: cover;
              box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            "
          >
            <img class="featured-title" src="img/f-t-1.png" alt="" />
            <p class="featured-desc">
              Avengers: Endgame is a 2019 American superhero film directed by
              Anthony and Joe Russo and written by Christopher Markus and
              Stephen McFeely. It is the direct sequel to Avengers: Infinity War
              (2018) and the 22nd film in the Marvel Cinematic Universe
            </p>
          </div>
          <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only events with category_id = 2
$sql = "SELECT * FROM events WHERE category_id = 2";
$result = $conn->query($sql);
?>

<h1 id="events" class="event-list-title" style="margin-left:20px;margin-top:5px;">Events</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ensure correct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?event_id=" . (int)$row["event_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";
        
                echo '</div>';
            }
        } else {
            echo "<p>No events available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
        </div>
      </div>
      <!--this section of events ends here -->

      <!--this section is for shows-->
      <div class="container">
        <div class="content-container">
          <div
            class="featured-content"
            style="
              background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
                url('movie\ img/imgtop2');
              background-position: center;
              background-size: cover;
              box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            "
          >
            <img class="featured-title" src="img/f-t-1.png" alt="" />
            <p class="featured-desc">
              Eternals is a 2021 American superhero film directed by Chloé Zhao
              and written by Zhao, Patrick Burleigh, Ryan Firpo, and Kaz Firpo.
              It is the 26th film in the Marvel Cinematic Universe (MCU) and is
              based on the Marvel Comics race of the same name.
            </p>
          </div>
          <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only events with category_id = 6
$sql = "SELECT * FROM events WHERE category_id = 6";
$result = $conn->query($sql);
?>

<h1 id="shows" class="event-list-title" style="margin-left:20px;margin-top:5px;">Shows</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ensure correct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?event_id=" . (int)$row["event_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";
        
                echo '</div>';
            }
        } else {
            echo "<p>No events available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </div>
</div>
        </div>
      </div>
      <!--this section of shows ends here -->

      <!--this section is for sports-->
      <div class="container">
        <div class="content-container">
          <div
            class="featured-content"
            style="
              background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
                url('movie\ img/imgtop3');
              background-position: center;
              background-size: cover;
              box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            "
          >
            <img class="featured-title" src="img/f-t-1.png" alt="" />
            <p class="featured-desc">
              The story revolves around the battle between the Jedi and the
              Sith, centered on characters like Luke Skywalker, Darth Vader,
              Princess Leia, Han Solo, and Yoda. The franchise explores themes
              of the Force, an energy field that gives Jedi and Sith their
              powers.
            </p>
          </div>
          <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only events with category_id = 2
$sql = "SELECT * FROM events WHERE category_id = 4";
$result = $conn->query($sql);
?>

<h1 id="sports" class="event-list-title" style="margin-left:20px;margin-top:5px;">Sports</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ensure correct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?event_id=" . (int)$row["event_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";
        
                echo '</div>';
            }
        } else {
            echo "<p>No events available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </div>
</div>
        </div>
      </div>
      <!--this section of sports ends here -->

      <!--this section is for Stand up Comedy-->
      <div class="container">
        <div class="content-container">
          <div
            class="featured-content"
            style="
              background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
                url('movie\ img/imgtop5');
              background-position: center;
              background-size: cover;
              box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            "
          >
            <img class="featured-title" src="img/f-t-1.png" alt="" />
            <p class="featured-desc">
              War is a Hollywood action-thriller directed by Philip G. Atwell
              and starring Jet Li and Jason Statham. The film follows FBI agent
              Jack Crawford (Statham) as he seeks revenge against a mysterious
              and ruthless assassin named Rogue (Jet Li), who is responsible for
              his partner’s death.
            </p>
          </div>
          <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only events with category_id = 2
$sql = "SELECT * FROM events WHERE category_id = 5";
$result = $conn->query($sql);
?>

<h1 id="standupcomedy" class="event-list-title" style="margin-left:20px;margin-top:5px;">Stand-up Comedy</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ensure correct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?event_id=" . (int)$row["event_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";
        
                echo '</div>';
            }
        } else {
            echo "<p>No events available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </div>
</div>
        
      <!--this section of standupcomedy ends here -->

      <!--this section is for Concerts-->
      <div class="container">
        <div class="content-container">
          <div
            class="featured-content"
            style="
              background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
                url('movie\ img/upper.jpg');
              background-position: center;
              background-size: cover;
              box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            "
          >
            <img class="featured-title" src="img/f-t-1.png" alt="" />
            <p class="featured-desc">
              The original 1933 film directed by Merian C. Cooper and Ernest B.
              Schoedsack became a cinematic milestone. The franchise has seen
              several remakes, including Peter Jackson’s King Kong (2005) and
              the MonsterVerse reboot Kong: Skull Island (2017), which later led
              to Godzilla vs. Kong (2021).
            </p>
          </div>
          <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only events with category_id = 2
$sql = "SELECT * FROM events WHERE category_id = 3";
$result = $conn->query($sql);
?>

<h1 id="concerts" class="event-list-title" style="margin-left:20px;margin-top:5px;">Concerts</h1>
<div class="event-list-wrapper">
    <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ensure correct image path
                $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";

                echo '<div class="event-list-item">';
                echo '<img class="event-list-item-img" src="' . $imagePath . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<span class="event-list-item-title">' . htmlspecialchars($row["title"]) . '</span>';
                echo "<a href='booking.php?event_id=" . (int)$row["event_id"] . "'>
                <button class='event-list-item-button'>Book Ticket</button>
              </a>";
        
                echo '</div>';
            }
        } else {
            echo "<p>No events available.</p>";
        }
        $conn->close();
        ?>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </div>
</div>
        </div>
      </div>
      <!--This section is for banner-->
      <div class="banner">
        <img src="images icons/banner1.jpg." alt="Banner" />
      </div>
      <!--banner section is end here-->
      <!--this section of Concerts ends here -->
     
      <form class="form" action="subscribe.php" method="POST">
        <span class="title">Subscribe to our newsletter</span>
        <p class="description">
          Subscribe to QuickTickets and never miss out on the hottest events!
          Get exclusive early access & discounts.
        </p>
        <div>
          <input
            placeholder="Enter your email"
            type="email"
            name="email"
            id="email-address"
            required
          />
          <button type="submit">Subscribe</button>
        </div>
      </form>
    </main>
    <footer class="copyright">
      <div class="footer-content">
        <div class="footer-section about">
          <h3>QuickTickets</h3>
          <p>&copy; 2025 QuickTickets pvt ltd. All rights reserved.</p>
          <p>
            We are committed to delivering the best services. Stay connected
            with us for exciting updates and offers!
          </p>
        </div>

        <div class="footer-section services">
          <h4>Our Services</h4>
          <ul>
            <li><a href="bookticket.html">Ticket Booking</a></li>
            <li><a href="listproduct.html">List Product</a></li>
          </ul>
        </div>

        <div class="footer-section links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="/privacy-policy">Privacy Policy</a></li>
            <li><a href="/terms-of-service">Terms of Service</a></li>
            <li><a href="contactus.php">Contact Us</a></li>
            <li><a href="aboutus.html"> About Us</a></li>
            <a href="adminlogin.php" class="login-link" style="color: white"
              >Admin Login</a
            >
          </ul>
        </div>

        <div class="footer-section contact">
          <h4>Contact Us</h4>
          <p>Email: sujalhinge079@gmail.com</p>
          <p>Phone: (+91) 8329583876</p>
          <div class="socials">
            <a
              href="https://www.linkedin.com/in/sujal-hinge-08a500256"
              target="_blank"
              >LinkedIn</a
            >
            <a href="https://www.twitter.com" target="_blank">Twitter</a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>
          Designed and developed by QuickTickets pvt ltd. All information is
          subject to terms and conditions.
        </p>
      </div>
    </footer>

    <!-- JavaScript -->
    <script>
      document.addEventListener("click", function (event) {
        // Close all dropdowns if clicking outside
        document.querySelectorAll(".dropdown-content").forEach((dropdown) => {
          if (!dropdown.parentElement.contains(event.target)) {
            dropdown.style.display = "none";
          }
        });

        // Toggle the clicked dropdown
        if (event.target.classList.contains("dropdown-button")) {
          const dropdownContent = event.target.nextElementSibling;
          if (dropdownContent) {
            dropdownContent.style.display =
              dropdownContent.style.display === "block" ? "none" : "block";
          }
        }
      });
    </script>
    <!--this is the another script tag for the location-->
    <script>
      function toggleDropdown() {
        const dropdown = document.getElementById("dropdown-box");
        // Toggle visibility
        if (dropdown.style.display === "block") {
          dropdown.style.display = "none";
        } else {
          dropdown.style.display = "block";
        }
      }

      // Close the dropdown if clicked outside
      window.onclick = function (event) {
        if (
          !event.target.matches(".dropdown-text") &&
          !event.target.matches(".city") &&
          !event.target.matches(".close-btn span")
        ) {
          const dropdown = document.getElementById("dropdown-box");
          if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
          }
        }
      };
    </script>
    <script src="app.js"></script>
    <script src="eventarrow.js"></script>
  </body>
</html>
