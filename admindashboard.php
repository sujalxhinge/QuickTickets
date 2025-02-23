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
        <!-- end insights -->
        <div class="recent_order">
          <h2>Recent Orders</h2>
          <table>
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Product Number</th>
                <th>Payments</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
            </tbody>
          </table>
          <a href="#">Show All</a>
        </div>
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


        <!--payments section starts from here-->
        <div class="payments">
          <h2 id="pay">Payments</h2>
          <table>
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Product Id</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
              <tr>
                <td>Mini USB</td>
                <td>4563</td>
                <td>Due</td>
                <td class="warning">Pending</td>
                <td class="primary">Details</td>
              </tr>
            </tbody>
          </table>
          <a href="#">Show All</a>
        </div>
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
