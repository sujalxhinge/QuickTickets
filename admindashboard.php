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
    <link rel="stylesheet" href="admindashboard.css" />
    <link rel="icon" href="images icons/link.png" type="image/icon type" />
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
          <a href="#" class="active">
            <span class="material-symbols-sharp">person_outline </span>
            <h3>custumers</h3>
          </a>
          <a href="#pay">
            <span class="material-symbols-sharp">receipt_long </span>
            <h3>Payments</h3>
          </a>
          <a href="#">
            <span class="material-symbols-sharp">report_gmailerrorred </span>
            <h3>Reports</h3>
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

        <div class="insights">
          <!-- start seling -->
          <div class="sales">
            <span class="material-symbols-sharp">trending_up</span>
            <div class="middle">
              <div class="left">
                <h3>Total Sales</h3>
                <h1>$25,024</h1>
              </div>
              <div class="progress">
                <svg>
                  <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p>80%</p></div>
              </div>
            </div>
            <small>Last 24 Hours</small>
          </div>
          <!-- end seling -->
          <!-- start expenses -->
          <div class="expenses">
            <span class="material-symbols-sharp">local_mall</span>
            <div class="middle">
              <div class="left">
                <h3>Total Sales</h3>
                <h1>$25,024</h1>
              </div>
              <div class="progress">
                <svg>
                  <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p>80%</p></div>
              </div>
            </div>
            <small>Last 24 Hours</small>
          </div>
          <!-- end seling -->
          <!-- start seling -->
          <div class="income">
            <span class="material-symbols-sharp">stacked_line_chart</span>
            <div class="middle">
              <div class="left">
                <h3>Total Sales</h3>
                <h1>$25,024</h1>
              </div>
              <div class="progress">
                <svg>
                  <circle r="30" cy="40" cx="40"></circle>
                </svg>
                <div class="number"><p>80%</p></div>
              </div>
            </div>
            <small>Last 24 Hours</small>
          </div>
          <!-- end seling -->
        </div>
        <!-- end insights and starts recent orders part -->
        <?php
$servername = "localhost"; // Change if needed
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$database = "quicktickets"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the booked_data table
$sql = "SELECT booking_id, username, title, selected_seats, total_price, booking_date FROM booked_data ORDER BY booking_date DESC";
$result = $conn->query($sql);
?>

<div class="recent_order">
    <h2>Recent Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>Seats</th>
                    <th>Price</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['booking_id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['selected_seats']}</td>
                                <td>{$row['total_price']}</td>
                                <td>{$row['booking_date']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No Recent Orders Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
?>

        <!--this section is for adding and deletion of categories like movies and events etc-->
        <h2 id="cat">Add Categories</h2>
        <div class="category-form">
          <form
            action="adddata.php"
            method="POST"
            enctype="multipart/form-data"
          >
            <div class="form-group">
              <select name="category" required>
                <option value="Movies">Movie</option>
                <option value="Events">Event</option>
                <option value="Sports">Sports</option>
                <option value="Concerts">Concerts</option>
                <option value="Shows">Shows</option>
                <option value="Standup-Comedy">Standup Comedy</option>
              </select>
              <input
                type="text"
                name="title"
                placeholder="Enter Title"
                required
              />
            </div>

            <div class="form-group">
              <input
                type="text"
                name="duration"
                placeholder="Duration (e.g., 2h 30m)"
                required
              />
              <input
                type="text"
                name="language"
                placeholder="Language"
                required
              />
            </div>

            <div class="form-group">
              <input type="number" name="price" placeholder="Price" required />
              <input
                type="text"
                name="rating"
                placeholder="Rating (e.g., 4.5)"
                required
              />
            </div>

            <div class="form-group">
              <input
                type="text"
                name="location"
                placeholder="Location"
                required
              />
              <input
                type="text"
                name="theater"
                placeholder="Theater Name"
                required
              />
            </div>

            <div class="form-group">
              <input
                type="text"
                name="time"
                placeholder="Time (e.g., 7:30 PM)"
                required
              />
              <input type="file" name="image" accept="image/*" required />
            </div>
            <div class="form-group">
              <input type="date" name="date" required />
              <input
                type="number"
                name="total_seats"
                placeholder="Total Seats"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="hidden"
                name="id"
                placeholder="Enter ID (for update/delete)"
              />
            </div>

            <div class="buttons">
              <button type="submit" name="add" class="add-btn">Add</button>
              <button type="submit" name="update" class="update-btn">
                Update
              </button>
              <button type="submit" name="delete" class="delete-btn">
                Delete
              </button>
            </div>
          </form>
        </div>

        <!--payments section starts from here-->
        <?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if you have a different username
$password = ""; // Change if you have a password
$database = "quicktickets";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch payment records
$sql = "SELECT title, username, amount, payment_status, payment_date FROM payments";
$result = $conn->query($sql);
?>

<div class="payments">
    <h2 id="pay">Payments</h2>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Username</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Fetch data dynamically
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
                    echo "<td class='" . ($row["payment_status"] == "Pending" ? "warning" : "success") . "'>" . htmlspecialchars($row["payment_status"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["payment_date"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No payments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Close the database connection
$conn->close();
?>

        <!--payments section ends here-->
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
