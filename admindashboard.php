<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0"
    />
    <link rel="stylesheet" href="admindash.css"/>
    <link rel="icon" href="images icons/link.png" type="image/icon type" />
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  </head>
  <body>
    <div class="container">
      <aside>
        <div class="top">
          <div class="logo">
            <h2><span class="danger">QuickTickets</span></h2>
          </div>
          <div class="close" id="close_btn">
            <span class="material-symbols-sharp"> close </span>
          </div>
        </div>
        <!-- end top -->
        <div class="sidebar">
          <a href="#">
            <span class="material-symbols-sharp">grid_view </span>
            <h3>Dashbord</h3>
          </a>
          <a href="#users" class="active">
            <span class="material-symbols-sharp">person_outline </span>
            <h3>Users Reports</h3>
          </a>
          <a href="#recent_events">
            <span class="material-symbols-sharp">receipt_long </span>
            <h3>Events Reports</h3>
          </a>
          <a href="#book">
            <span class="material-symbols-sharp">report_gmailerrorred </span>
            <h3>Bookings Reports</h3>
          </a>

          <a href="#cat">
            <span class="material-symbols-sharp">add </span>
            <h3 id="recent">Add Product</h3>
          </a>

          <a href="dashboard.php">
            <span class="material-symbols-sharp">logout </span>
            <h3>logout</h3>
          </a>
        </div>
      </aside>
      <!-- --------------
        end asid
      -------------------- -->

      <!-- --------------
        start main part
      --------------- -->

      <main>
        <h1>Dashbord</h1>

        <div class="date">
          <input type="date" />
        </div>

        <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch sales data
$last_24_hours_sales = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE booking_date >= NOW() - INTERVAL 1 DAY")->fetch_assoc()['total'] ?? 0;
$last_month_sales = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE booking_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)")->fetch_assoc()['total'] ?? 0;
$total_revenue = $conn->query("SELECT SUM(total_price) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;

// Define target values (adjust these based on business goals)
$target_24_hours = 50000; // Example: Target $50,000 sales per day
$target_month = 500000; // Example: Target $500,000 per month
$target_revenue = 5000000; // Example: Target $5,000,000 total revenue

// Calculate percentages
$percent_24h = ($last_24_hours_sales / $target_24_hours) * 100;
$percent_month = ($last_month_sales / $target_month) * 100;
$percent_revenue = ($total_revenue / $target_revenue) * 100;

// Ensure percentages don't exceed 100%
$percent_24h = min($percent_24h, 100);
$percent_month = min($percent_month, 100);
$percent_revenue = min($percent_revenue, 100);
?>

<div class="insights">
    <!-- Last 24 Hours Sales -->
    <div class="sales">
        <span class="material-symbols-sharp">trending_up</span>
        <div class="middle">
            <div class="left">
                <h3>24 Hours Sales</h3>
                <h1>₹<?php echo number_format($last_24_hours_sales, 2); ?></h1>
            </div>
            <div class="progress">
                <svg>
                    <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p><?php echo round($percent_24h, 2); ?>%</p></div>
            </div>
        </div>
        <small>Last 24 Hours</small>
    </div>

    <!-- Last Month Sales -->
    <div class="expenses">
        <span class="material-symbols-sharp">local_mall</span>
        <div class="middle">
            <div class="left">
                <h3>Last Month Sales</h3>
                <h1>₹<?php echo number_format($last_month_sales, 2); ?></h1>
            </div>
            <div class="progress">
                <svg>
                    <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p><?php echo round($percent_month, 2); ?>%</p></div>
            </div>
        </div>
        <small>Last Month</small>
    </div>

    <!-- Total Revenue -->
    <div class="income">
        <span class="material-symbols-sharp">stacked_line_chart</span>
        <div class="middle">
            <div class="left">
                <h3>Total Revenue</h3>
                <h1>₹<?php echo number_format($total_revenue, 2); ?></h1>
            </div>
            <div class="progress">
                <svg>
                    <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p><?php echo round($percent_revenue, 2); ?>%</p></div>
            </div>
        </div>
        <small>Total Revenue</small>
    </div>
</div>

<?php $conn->close(); ?>

        <!-- end insights -->
        <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest 20 records
$sql = "SELECT 
            b.booking_id, 
            b.selected_seats, 
            b.total_price, 
            b.booking_date,
            e.title AS event_name,
            m.title AS movie_name
        FROM bookings b
        LEFT JOIN events e ON b.showtime_id = e.event_id
        LEFT JOIN movies m ON b.showtime_id = m.movie_id
        ORDER BY b.booking_date DESC
        LIMIT 20"; // Restrict to 20 records

$result = $conn->query($sql);
?>

<div class="recent_order">
    <h2 id="book">Recent Bookings</h2>
    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th>Movie/Event</th>
                    <th>Booking ID</th>
                    <th>Seats</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Determine if it's a movie or an event
                        $title = !empty($row["movie_name"]) ? $row["movie_name"] : $row["event_name"];

                        echo "<tr>
                            <td>" . $title . "</td>
                            <td>" . $row["booking_id"] . "</td>
                            <td>" . $row["selected_seats"] . "</td>
                            <td>₹" . $row["total_price"] . "</td>
                            <td>" . date("d M Y", strtotime($row["booking_date"])) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No recent bookings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Close connection
$conn->close();
?>
<!-- <div style="text-align: right; margin-bottom: 10px;">
    <a href="generate_report.php" class="btn btn-primary" style="background: #007bff; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
        Download PDF
    </a>
</div> -->
<a href="generate_report.php" target="_blank" class="download-btn">
  <button class="button">
    <span class="one">Download</span>
    <span class="two">100%</span>
  </button>
</a>


        <!--this section is for adding and deletion of categories like movies and events etc-->
  <!-- Admin Dashboard Forms -->
<main>
    <h2 id="cat" class="form-heading">Add Categories</h2>
    <form action="admin_handler.php" method="POST" class="category-form" id="category-form">
        <input type="hidden" name="action" value="add_category">
        <div class="form-group" style="display: flex; justify-content: center; align-items: center;">
            <input type="text" name="category_name" placeholder="Enter Category Name" required class="full-width">
        </div>
        <div class="buttons">
            <button type="submit" class="add-btn">Add Category</button>
        </div>
    </form>

    <h2 class="form-heading">Add Venues</h2>
<form action="admin_handler.php" method="POST" class="category-form" id="venue-form">
    <input type="hidden" name="action" value="add_venue">
    <div class="form-group">
        <input type="text" name="venue_name" placeholder="Enter Venue Name" required class="full-width">
        <input type="text" name="location" placeholder="Enter Location" required class="full-width">
    </div>
    <div class="buttons">
        <button type="submit" class="add-btn">Add Venue</button>
    </div>
</form>


    <h2 class="form-heading">Add Theaters</h2>
<form action="admin_handler.php" method="POST" class="category-form" id="theater-form">
    <input type="hidden" name="action" value="add_theater">
    <div class="form-group">
        <input type="text" name="theater_name" placeholder="Enter Theater Name" required class="full-width">
        <input type="number" name="venue_id" placeholder="Enter Venue ID" required class="full-width">
        <input type="text" name="location" placeholder="Enter Location" required class="full-width">
    </div>
    <div class="buttons">
        <button type="submit" class="add-btn">Add Theater</button>
    </div>
</form>


    <h2 class="form-heading">Add Movies</h2>
    <form action="admin_handler.php" method="POST" enctype="multipart/form-data" class="category-form" id="movie-form">
        <input type="hidden" name="action" value="add_movie">
        <div class="form-group">
            <input type="text" name="title" placeholder="Movie Title" required>
            <input type="text" name="duration" placeholder="Duration (e.g., 2h 30m)" required>
        </div>
        <div class="form-group">
            <input type="text" name="language" placeholder="Language" required>
            <input type="number" name="price" placeholder="Price" required>
        </div>
        <div class="form-group">
            <input type="text" name="rating" placeholder="Rating (e.g., 4.5)" required>
            <input type="file" name="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <input type="number" name="category_id" placeholder="Category ID" required>
        </div>
        <div class="buttons">
            <button type="submit" class="add-btn">Add Movie</button>
        </div>
    </form>

    <h2 class="form-heading">Add Events</h2>
    <form action="admin_handler.php" method="POST" enctype="multipart/form-data" class="category-form" id="event-form">
        <input type="hidden" name="action" value="add_event">
        <div class="form-group">
            <input type="text" name="title" placeholder="Event Title" required>
            <input type="date" name="date" required>
        </div>
        <div class="form-group">
            <input type="time" name="time" required>
            <input type="text" name="location" placeholder="Location" required>
        </div>
        <div class="form-group">
            <input type="number" name="price" placeholder="Price" required>
            <input type="text" name="rating" placeholder="Rating (e.g., 4.5)" required>
        </div>
        <div class="form-group">
            <input type="file" name="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <input type="number" name="category_id" placeholder="Category ID" required>
        </div>
        <div class="buttons">
            <button type="submit" class="add-btn">Add Event</button>
        </div>
    </form>

    <h2 class="form-heading">Add Showtimes</h2>
<form action="admin_handler.php" method="POST" class="category-form" id="showtime-form">
    <input type="hidden" name="action" value="add_showtime">
    
    <div class="form-group">
        <label for="type">Select Type:</label>
        <select id="type" name="type" required onchange="toggleInputFields()">
            <option value="">-- Select Type --</option>
            <option value="movie">Movie</option>
            <option value="event">Event</option>
        </select>
    </div>
    
    <div class="form-group">
        <input type="number" id="movie_id" name="movie_id" placeholder="Movie ID" style="display: none;">
        <input type="number" id="event_id" name="event_id" placeholder="Event ID" style="display: none;">
        
        <!-- Theater ID (For Movies) -->
        <input type="number" id="theater_id" name="theater_id" placeholder="Theater ID" style="display: none;">

        <!-- Venue ID (For Movies & Events) -->
        <input type="number" id="venue_id" name="venue_id" placeholder="Venue ID" style="display: none;">
    </div>
    
    <div class="form-group">
        <input type="date" name="date" required>
        <input type="time" name="time" required>
    </div>
    
    <div class="form-group">
        <input type="number" name="total_seats" placeholder="Total Seats" required>
    </div>
    
    <div class="buttons">
        <button type="submit" class="add-btn">Add Showtime</button>
    </div>
</form>

<script>
function toggleInputFields() {
    var type = document.getElementById("type").value;
    
    document.getElementById("movie_id").style.display = (type === "movie") ? "block" : "none";
    document.getElementById("event_id").style.display = (type === "event") ? "block" : "none";
    
    // Show theater_id for movies
    document.getElementById("theater_id").style.display = (type === "movie") ? "block" : "none";

    // Show venue_id for both movies and events (if required)
    document.getElementById("venue_id").style.display = (type !== "") ? "block" : "none";
}
</script>

    <h2 class="form-heading">Update Seat Availability</h2>
    <form action="admin_handler.php" method="POST" class="category-form" id="update-seats-form">
        <input type="hidden" name="action" value="update_seats">
        <div class="form-group">
            <input type="number" name="showtime_id" placeholder="Showtime ID" required>
            <input type="number" name="available_seats" placeholder="Available Seats" required>
        </div>
        <div class="buttons">
            <button type="submit" class="update-btn">Update Seats</button>
        </div>
    </form>
</main>


        <!--Events section starts from here-->
        <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest 20 event bookings with event details
$sql = "SELECT 
            b.selected_seats, 
            b.total_price, 
            e.title AS event_name,
            e.location,
            e.price,
            e.available_seats,
            e.rating
        FROM bookings b
        INNER JOIN events e ON b.showtime_id = e.event_id
        ORDER BY b.booking_date DESC
        LIMIT 20"; // Restrict to 20 records

$result = $conn->query($sql);
?>

<div class="recent_order">
    <h2 id="recent_events">Recent Events</h2>
    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Location</th>
                    <th>Price (₹)</th>
                    <th>Available Seats</th>
                    <th>Rating</th>
                    <th>Seats</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row["event_name"] . "</td>
                            <td>" . $row["location"] . "</td>
                            <td>" . $row["price"] . "</td>
                            <td>" . $row["available_seats"] . "</td>
                            <td>" . $row["rating"] . "⭐</td>
                            <td>" . $row["selected_seats"] . "</td>
                            <td>₹" . $row["total_price"] . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No recent event bookings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Close connection
$conn->close();
?>
<!-- Date Filter Form -->
<form method="GET" action="generate_events_pdf.php">
  <div style=" margin-left:40%;margin-top:-10px;margin-bottom:10px;">
        <label>From Date:</label>
        <input type="date" name="from_date" required>
        
        <label>To Date:</label>
        <input type="date" name="to_date" required>
              </div>
        
        <!-- <button type="submit">Download PDF</button> -->
        <a href="generate_events_pdf.php" target="_blank" class="download-btn">
  <button class="button">
    <span class="one">Download</span>
    <span class="two">100%</span>
  </button>
</a>
    </form>

        <!--events section ends here-->
        <!--usersprofile section starts  here-->
        <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest 20 user profiles
$sql = "SELECT first_name, last_name, address, phone_number, email FROM usersprofile ORDER BY id DESC LIMIT 20";

$result = $conn->query($sql);
?>

<div class="recent_order">
    <h2 id="users">Recent Users</h2>
    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                            <td>" . $row["address"] . "</td>
                            <td>" . $row["phone_number"] . "</td>
                            <td>" . $row["email"] . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No recent users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Close connection
$conn->close();
?>
<a href="generate_users_pdf.php" target="_blank" class="download-btn">
  <button class="button">
    <span class="one">Download</span>
    <span class="two">100%</span>
  </button>
</a>


      </main>
      <!------------------
         end main
        ------------------->

      <!----------------
        start right main 
      ---------------------->
      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>

          <div class="theme-toggler">
            <span class="material-symbols-sharp active">light_mode</span>
            <span class="material-symbols-sharp">dark_mode</span>
          </div>
          <div class="profile">
            <div class="info">
              <p><b>Sujal</b></p>
              <p>Admin</p>
              <small class="text-muted"></small>
            </div>
            <div class="profile-photo">
              <img src="images icons/profile.jpeg" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="admindashboard.js"></script>
  </body>
</html>
