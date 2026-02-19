<?php
include 'db.php';
session_start();

if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user'];
    $notif_query = "SELECT message FROM notifications WHERE user_name = '$user_name' ORDER BY created_at DESC LIMIT 1";
    $notif_result = mysqli_query($conn, $notif_query);
    $notif = mysqli_fetch_assoc($notif_result);

    if ($notif) {
        echo "<script>alert('" . $notif['message'] . "');</script>";
        $delete_query = "DELETE FROM notifications WHERE user_name = '$user_name'";
        mysqli_query($conn, $delete_query);
    }
}

$search = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $query = "SELECT * FROM properties WHERE (location LIKE '%$search%' OR title LIKE '%$search%') 
              AND id NOT IN (SELECT rental_property_id FROM tenants)";
} else {
    $query = "SELECT * FROM properties WHERE id NOT IN (SELECT rental_property_id FROM tenants)";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$properties = [];
while ($row = mysqli_fetch_assoc($result)) {
    preg_match('/\d+\s*[rkbh]+/i', $row['title'], $match);
    $flatType = isset($match[0]) ? strtolower(str_replace(' ', '', $match[0])) : 'all';
    $row['flat_type'] = $flatType;
    $properties[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RentalHub - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/HomePage.css">
</head>
<body>
    <header>
        <?php include 'user_navbar.php'; ?>
    </header>

    <main>
        <section class="hero">
            <h1>Welcome to RentalHub</h1>
            <p>Discover and rent your perfect property with ease</p>
            <div class="search-bar">
                <form method="POST" action="index.php">
                    <input type="text" name="search" placeholder="Search properties by city or type..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
                <?php if (!empty($search)) { ?>
                    <a href="index.php" class="back-btn">🔙 Back to Home</a>
                <?php } ?>
            </div>
        </section>

        <section class="properties">
            <div class="container">
                <h1>Available Rental Properties</h1>

                <div class="filter-container" style="margin-bottom: 25px; display: flex; align-items: center; gap: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e9f2ff; padding: 12px 20px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); max-width: 400px; margin-left: 0; margin-right: auto;">
                    <label for="flatTypeFilter" style="font-weight: 700; font-size: 20px; color: #004085; user-select: none;">Filter by Flat Type:</label>
                    <select id="flatTypeFilter" style="padding: 5px 55px; font-size: 18px; font-weight: 600; border: 2px solid #007bff; border-radius: 10px; background-color: white; color: #007bff; cursor: pointer; transition: all 0.4s ease; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3csvg%20width%3d%2210%22%20height%3d%227%22%20viewBox%3d%220%200%2010%207%22%20xmlns%3d%22http%3a//www.w3.org/2000/svg%22%3e%3cpath%20d%3d%22M0%200l5%207%205-7z%22%20fill%3d%22%23007bff%22/%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 10px 7px;">
                        <option value="all">All</option>
                        <option value="1rk">1RK</option>
                        <option value="1bhk">1BHK</option>
                        <option value="2bhk">2BHK</option>
                        <option value="3bhk">3BHK</option>
                    </select>
                </div>

                <div class="property-grid">
                    <?php if (count($properties) > 0) {
                        foreach ($properties as $row) { ?>
                            <div class="property-card" data-type="<?php echo $row['flat_type']; ?>">
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
                                <h3><?php echo $row['title']; ?></h3>
                                <p>Location: <?php echo $row['location']; ?></p>
                                <p>Price: Rs.<?php echo $row['price']; ?> per month</p>
                                <a href="property_details.php?id=<?php echo $row['id']; ?>" class="btn">Show Details</a>
                            </div>
                        <?php }
                    } else { ?>
                        <p class="no-results">No properties found for "<?php echo htmlspecialchars($search); ?>"</p>
                    <?php } ?>
                </div>

                <!-- Filter-based no result message -->
                <p class="no-results" id="filterNoResults" style="display: none; color: red; font-weight: bold; font-size: 18px; text-align: center; margin-top: 20px;">
                    No properties found for selected flat type.
                </p>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <div class="logo">RentalHub</div>
                <p>Flat No. 303, Bldg No. 6, Atlanta Residency, Anjurphata, Bhiwandi, Dist Thane, 421305</p>
                <p>📞 9960259300 / +917894561230</p>
                <p>📧 rentalhub@gmail.com</p>
            </div>

            <div class="footer-links">
                <div>
                    <h3>About</h3>
                    <ul>
                        <li><a href="about.php">About us</a></li>
                        <li><a href="contact.php">Contact us</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h3>More Information</h3>
                    <ul>
                        <li><a href="#">All properties</a></li>
                        <li><a href="#">Houses for rent</a></li>
                    </ul>
                </div>
                <div>
                    <h3>News</h3>
                    <ul>
                        <li><a href="#">Our Blogs</a></li>
                        <li><a href="#">Why Choose RentalHub?</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 RentalHub. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Filter Script -->
    <script>
        const flatTypeFilter = document.getElementById('flatTypeFilter');
        const propertyCards = document.querySelectorAll('.property-card');
        const noResults = document.getElementById('filterNoResults');

        function applyFilter() {
            const selected = flatTypeFilter.value.toLowerCase();
            let visibleCount = 0;

            propertyCards.forEach(card => {
                const type = card.getAttribute('data-type');
                if (selected === 'all' || type === selected) {
                    card.style.removeProperty('display');
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        flatTypeFilter.addEventListener('change', applyFilter);
        window.addEventListener('pageshow', applyFilter);
    </script>
</body>
</html>
