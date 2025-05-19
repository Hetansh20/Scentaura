<?php
session_start();
include 'db.php';

$query = "SELECT p.*, b.name AS brand_name 
          FROM products p 
          LEFT JOIN brands b ON p.brand_id = b.id";

$whereClauses = [];
$params = [];

$filterFields = [
    'category', 'scent', 'brand_id', 'gender', 
    'occasion', 'concentration', 'size', 'pack_size'
];

foreach ($filterFields as $field) {
    if (isset($_GET[$field]) && $_GET[$field] !== '') {
        $whereClauses[] = "$field = ?";
        $params[] = $_GET[$field];
    }
}

// Price range filter
if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
    $whereClauses[] = "price >= ?";
    $params[] = $_GET['min_price'];
}

if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
    $whereClauses[] = "price <= ?";
    $params[] = $_GET['max_price'];
}

// Build the complete query
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

// Apply sorting if specified
if (isset($_GET['sort_by'])) {
    $sortAllowed = ['price_asc', 'price_desc', 'name_asc', 'name_desc'];
    $sortBy = $_GET['sort_by'];
    
    if (in_array($sortBy, $sortAllowed)) {
        switch ($sortBy) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'name_asc':
                $query .= " ORDER BY p.name ASC";
                break;
            case 'name_desc':
                $query .= " ORDER BY p.name DESC";
                break;
        }
    }
} else {
    // Default sorting
    $query .= " ORDER BY p.id DESC";
}

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

// Bind parameters if any
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get distinct values for filters
function getDistinctValues($conn, $field) {
    $query = "SELECT DISTINCT $field FROM products WHERE $field IS NOT NULL AND $field != '' ORDER BY $field";
    $result = mysqli_query($conn, $query);
    $values = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $values[] = $row[$field];
    }
    
    return $values;
}

// Get distinct values for filter dropdowns
$categories = getDistinctValues($conn, 'category');
$scents = getDistinctValues($conn, 'scent');
$genders = getDistinctValues($conn, 'gender');
$occasions = getDistinctValues($conn, 'occasion');
$concentrations = getDistinctValues($conn, 'concentration');
$sizes = getDistinctValues($conn, 'size');
$packSizes = getDistinctValues($conn, 'pack_size');

// Get brands
$brandsQuery = "SELECT id, name FROM brands ORDER BY name";
$brandsResult = mysqli_query($conn, $brandsQuery);
$brands = [];

while ($row = mysqli_fetch_assoc($brandsResult)) {
    $brands[$row['id']] = $row['name'];
}

// Get price range
$priceQuery = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products";
$priceResult = mysqli_query($conn, $priceQuery);
$priceRange = mysqli_fetch_assoc($priceResult);

$user_id = "Select id from users where full_name = '$_SESSION[username]'";
$user_id_res = mysqli_query($conn, $user_id);
$user_id_row = mysqli_fetch_assoc($user_id_res);
// echo $user_id_row['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity'] ?? 1));

    // Check if item already exists in cart
    $check = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND product_id = ?");
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        // Update quantity
        $row = $check_result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND product_id = ?");
        $update->bind_param("iii", $new_quantity, $user_id_row['id'], $product_id);
        $update->execute();
    } else {
        // Insert new item
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id_row['id'], $product_id, $quantity);
        $insert->execute();
    }

    // Redirect back to the page user came from
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ScentAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Components/CSS/products.css">
    <link rel="stylesheet" href="Components/CSS/index.css">
    <style>
        /* Card animation delay cascade */
        <?php
            $maxProducts = mysqli_num_rows($result);
            for ($i = 0; $i < $maxProducts && $i < 20; $i++) {
            echo ".product-card:nth-child(".($i+1).") { animation-delay: ".($i * 0.1)."s; }\n";
            }
        ?>
    </style>
</head>
<body>

    <nav>
        <div class="logo">
            <a href="index.php"><img src="Components/Logo/logo.png" alt="ScentAura"></a>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li>
                <input type="text" placeholder="Search products...">
                <input type="button" value="Search">
            </li>
            <li class="profile-dropdown">
                <div class="dropdown">
                    <button class="user-icon">
                        <i class="fa-solid fa-circle-user"></i>
                    </button>
                    <div class="dropdown-menu">
                        <?php if (isset($_SESSION['username'])): ?>
                            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span><br>
                            <a href="profile.php">My Profile</a><br>
                            <a href="logout.php">Logout</a>
                        <?php else: ?>
                            <a href="login.php">LOGIN</a><br>
                            <a href="login.php#show-signup">SIGNUP</a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <li class="cart">
                <a href="cart.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cart-count">
    <?php
        $cart_count = 0;
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];
            $user_id_query = "SELECT id FROM users WHERE email = '$username'";
            $user_id_res = mysqli_query($conn, $user_id_query);

            if ($user_id_res && mysqli_num_rows($user_id_res) > 0) {
                $user_id_row = mysqli_fetch_assoc($user_id_res);
                $user_id_val = $user_id_row['id'];

                $query = "SELECT COUNT(*) as total FROM cart WHERE user_id = $user_id_val";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                echo $row['total'];
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
    ?>
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <header>
            <h1>ScentAura Collection</h1>
            <p>Discover our exquisite range of luxury fragrances and products</p>
        </header>
        
        <!-- Filter Section -->
        <div class="filter-wrapper">
            <button class="filter-toggle" id="filterToggle">
                <i class="fas fa-sliders-h"></i> Filter Products
            </button>
            
            <div class="filter-panel" id="filterPanel">
                <form action="products.php" method="GET" id="filterForm">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="">All Categories</option>
                                <option value="perfume">Perfume</option>
                                <option value="deodorant">Deodorant</option>
                                <option value="body spray">Body Spray</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="scent">Scent</label>
                            <select name="scent" id="scent">
                                <option value="">All Scents</option>
                                <option value="floral">Floral</option>
                                <option value="woody">Woody</option>
                                <option value="citrus">Citrus</option>
                                <option value="oriental">Oriental</option>
                                <option value="fresh">Fresh</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="brand_id">Brand</label>
                            <select name="brand_id" id="brand_id">
                                <option value="">All Brands</option>
                                <option value="1">Chanel</option>
                                <option value="2">Dior</option>
                                <option value="3">Tom Ford</option>
                                <option value="4">Versace</option>
                                <option value="5">Gucci</option>
                                <option value="6">Armani</option>
                                <option value="7">Yves Saint Laurent</option>
                                <option value="8">Burberry</option>
                                <option value="9">Calvin Klein</option>
                                <option value="10">Jo Malone</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unisex">Unisex</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="occasion">Occasion</label>
                            <select name="occasion" id="occasion">
                                <option value="">All Occasions</option>
                                <option value="casual">Casual</option>
                                <option value="daily">Daily</option>
                                <option value="formal">Formal</option>
                                <option value="special">Special</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="concentration">Concentration</label>
                            <select name="concentration" id="concentration">
                                <option value="">All Concentrations</option>
                                <option value="EDP">EDP</option>
                                <option value="EDT">EDT</option>
                                <option value="body spray">Body Spray</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="size">Size</label>
                            <select name="size" id="size">
                                <option value="">All Sizes</option>
                                <option value="30ml">30ml</option>
                                <option value="50ml">50ml</option>
                                <option value="75ml">75ml</option>
                                <option value="100ml">100ml</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="pack_size">Pack Size</label>
                            <select name="pack_size" id="pack_size">
                                <option value="">All Pack Sizes</option>
                                <option value="single">Single</option>
                                <option value="gift pack">Gift Pack</option>
                                <option value="couple pack">Couple Pack</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Price Range</label>
                            <div class="price-range">
                                <input type="number" name="min_price" id="min_price" placeholder="Min">
                                <span>to</span>
                                <input type="number" name="max_price" id="max_price" placeholder="Max">
                            </div>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="apply-filters">Apply Filters</button>
                        <button type="button" id="resetFilters" class="reset-filters">Reset Filters</button>
                    </div>
                </form>
            </div>
        </div>

        
        <!-- Results Bar -->
        <div class="results-bar">
            <div class="results-count">
                <?php 
                $count = mysqli_num_rows($result);
                echo "<strong>{$count}</strong> " . ($count == 1 ? "product" : "products") . " found";
                ?>
            </div>
            
            <div class="sort-group">
                <label for="sort_by">Sort by:</label>
                <select name="sort_by" id="sort_by" onchange="updateSort(this.value)">
                    <option value="price_asc" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_asc') echo 'selected'; ?>>Price (Low to High)</option>
                    <option value="price_desc" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_desc') echo 'selected'; ?>>Price (High to Low)</option>
                    <option value="name_asc" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc') echo 'selected'; ?>>Name (A to Z)</option>
                    <option value="name_desc" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc') echo 'selected'; ?>>Name (Z to A)</option>
                </select>
            </div>
        </div>
        
        <!-- Products Grid -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <a href="product_des.php?product_id=<?php echo urlencode($row['id']); ?>">
                                <img src="admin/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </a>
                            <?php if (!empty($row['category'])): ?>
                                <div class="product-category"><?php echo htmlspecialchars($row['category']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="product-details">
                            <h3 class="product-name">
                                <a href="product_des.php?product_id=<?php echo urlencode($row['id']); ?>">
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </a>
                            </h3>
                            
                            <?php if (!empty($row['brand_name'])): ?>
                                <div class="product-brand"><?php echo htmlspecialchars($row['brand_name']); ?></div>
                            <?php endif; ?>
                            
                            <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            
                            <div class="product-meta">
                                <?php if (!empty($row['scent'])): ?>
                                    <span class="meta-item"><i class="fas fa-magic"></i> <?php echo htmlspecialchars($row['scent']); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($row['gender'])): ?>
                                    <span class="meta-item"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['gender']); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($row['concentration'])): ?>
                                    <span class="meta-item"><i class="fas fa-tint"></i> <?php echo htmlspecialchars($row['concentration']); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($row['size'])): ?>
                                    <span class="meta-item"><i class="fas fa-ruler"></i> <?php echo htmlspecialchars($row['size']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-footer">
                                <div>
                                    <div class="product-price">$<?php echo number_format($row['price'], 2); ?></div>
                                    <?php
                                    $stockClass = 'in-stock';
                                    $stockText = 'In Stock';
                                    
                                    if ($row['stock'] <= 0) {
                                        $stockClass = 'out-of-stock';
                                        $stockText = 'Out of Stock';
                                    } elseif ($row['stock'] <= 5) {
                                        $stockClass = 'low-stock';
                                        $stockText = 'Low Stock: ' . $row['stock'] . ' left';
                                    }
                                    ?>
                                    <div class="stock-status <?php echo $stockClass; ?>">
                                        <i class="fas <?php echo ($row['stock'] > 0) ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i> 
                                        <?php echo $stockText; ?>
                                    </div>
                                </div>
                                
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-to-cart" <?php if ($row['stock'] <= 0) echo 'disabled'; ?>>
                                        <i class="fas fa-shopping-cart"></i> Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <h3>No Products Found</h3>  
                <p>Try adjusting your filters or search criteria</p>
                <button id="clearAllFilters" class="reset-filters">Clear All Filters</button>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section about">
                <h3>About ScentAura</h3>
                <p>
                    ScentAura brings you premium fragrances that blend luxury and sophistication.
                    Elevate your senses with our exclusive collection of perfumes.
                </p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://x.com/" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 <span>ScentAura</span>. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Toggle filter panel
        const filterToggle = document.getElementById('filterToggle');
        const filterPanel = document.getElementById('filterPanel');
        
        filterToggle.addEventListener('click', () => {
            filterPanel.classList.toggle('active');
        });
        
        // Reset filters
        document.getElementById('resetFilters').addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = 'products.php';
        });
        
        // Clear all filters from no results panel
        const clearAllFiltersBtn = document.getElementById('clearAllFilters');
        if (clearAllFiltersBtn) {
            clearAllFiltersBtn.addEventListener('click', () => {
                window.location.href = 'products.php';
            });
        }
        
        // Update sort and refresh
        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort_by', value);
            window.location.href = url.toString();
        }
        
        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Animation effect
                this.innerHTML = '<i class="fas fa-check"></i> Added';
                this.style.backgroundColor = '#2e7d32';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart"></i> Add';
                    this.style.backgroundColor = '';
                }, 2000);
                
                // Here you would typically add AJAX code to update cart
                // For example: fetch('/add-to-cart.php', { method: 'POST', body: productData })
            });
        });
        
        // Animate products on scroll
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.product-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => {
                card.style.animationPlayState = 'paused';
                observer.observe(card);
            });
        });
    </script>
    <script src="components/js/index.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>