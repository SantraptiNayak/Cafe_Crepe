<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$prices = [
    "ambur_biryani" => 400, "hyderabadi_biryani" => 450, "egg_biryani" => 300, "goan_fish_biryani" => 500,
    "mutton_biryani" => 550, "kamrupi_biryani" => 420, "kashmiri_biryani" => 460, "memoni_biryani" => 480, "mughlai_biryani" => 500,

    "chicken_roast" => 350, "chicken_curry" => 320, "chicken_do_pyaza" => 340, "chicken_masala" => 360,
    "handi_chicken" => 380, "murgh_musallam" => 400, "chicken_korma" => 420, "chicken_tikka_masala" => 450,
    "tandoori_chicken" => 460, "chicken_65" => 390,

    "matar_paneer" => 280, "palak_paneer" => 300, "paneer_butter_masala" => 350, "paneer_do_pyaza" => 320,
    "hyderabadi_paneer" => 330, "paneer_lababdar" => 360, "shahi_paneer" => 370, "kadai_paneer" => 340,
    "malai_kofta" => 380, "achari_paneer" => 310,

    "navratan_korma" => 280, "veg_jalfrezi" => 290, "veg_biryani" => 310, "veg_curry" => 300, 
    "veg_kolhapuri" => 320, "veg_masala" => 330, "veg_pakora" => 250, "bhindi_masala" => 270, 
    "aloo_gobi" => 260, "mixed_vegetable_curry" => 300,

    "momos" => 150, "chicken_manchurian" => 320, "chili_chicken" => 310, "chowmein" => 180, 
    "spring_roll" => 170, "szechuan_chicken" => 340, "fried_rice" => 220, "hakka_noodles" => 200, 
    "sweet_and_sour_chicken" => 350, "hot_and_sour_soup" => 190,

    "butter_masala_dosa" => 180, "idli" => 120, "masala_dosa" => 160, "mysore_bonda" => 140, 
    "onion_uttapam" => 190, "plain_dosa" => 130, "rava_uttapam" => 180, "sambhar_vada" => 150, 
    "medu_vada" => 140, "upma" => 160,

    "gulab_jamun" => 90, "rasmalai" => 100, "jalebi" => 80, "gajar_ka_halwa" => 120, 
    "malpua" => 110, "kheer" => 100, "rabri" => 130, "chocolate_cake" => 250, 
    "black_forest_cake" => 270, "red_velvet_cake" => 300, "pineapple_pastry" => 180, 
    "mango_ice_cream" => 160, "chocolate_ice_cream" => 170, "strawberry_ice_cream" => 170, 
    "butterscotch_ice_cream" => 180, "kulfi" => 200
];


$categories = [
    "Biryani" => ["Ambur Biryani", "Hyderabadi Biryani", "Egg Biryani", "Goan Fish Biryani", "Mutton Biryani", "Kamrupi Biryani", "Kashmiri Biryani", "Memoni Biryani", "Mughlai Biryani"],
    "Chicken" => ["Chicken Roast", "Chicken Curry", "Chicken Do Pyaza", "Chicken Masala", "Handi Chicken", "Murgh Musallam", "Chicken Korma", "Chicken Tikka Masala", "Tandoori Chicken", "Chicken 65"],
    "Paneer" => ["Matar Paneer", "Palak Paneer", "Paneer Butter Masala", "Paneer Do Pyaza", "Hyderabadi Paneer", "Paneer Lababdar", "Shahi Paneer", "Kadai Paneer", "Malai Kofta", "Achari Paneer"],
    "Vegetable" => ["Navratan Korma", "Veg Jalfrezi", "Veg Biryani", "Veg Curry", "Veg Kolhapuri", "Veg Masala", "Veg Pakora", "Bhindi Masala", "Aloo Gobi", "Mixed Vegetable Curry"],
    "Chinese" => ["Momos", "Chicken Manchurian", "Chili Chicken", "Chowmein", "Spring Roll", "Szechuan Chicken", "Fried Rice", "Hakka Noodles", "Sweet and Sour Chicken", "Hot and Sour Soup"],
    "South Indian" => ["Butter Masala Dosa", "Idli", "Masala Dosa", "Mysore Bonda", "Onion Uttapam", "Plain Dosa", "Rava Uttapam", "Sambhar Vada", "Medu Vada", "Upma"],
    "Desserts" => ["Gulab Jamun", "Rasmalai", "Jalebi", "Gajar Ka Halwa", "Malpua", "Kheer", "Rabri", "Chocolate Cake", "Black Forest Cake", "Red Velvet Cake", "Pineapple Pastry", "Mango Ice Cream", "Chocolate Ice Cream", "Strawberry Ice Cream", "Butterscotch Ice Cream", "Kulfi"]
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="order.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">

    <script>
        let cart = {};
        function updateCart(item, price, action) {
            if (!cart[item]) {
                cart[item] = { price: price, quantity: 0 };
            }
            if (action === 'add') {
                cart[item].quantity++;
            } else if (action === 'remove' && cart[item].quantity > 0) {
                cart[item].quantity--;
            }
            document.getElementById(item + "-qty").innerText = cart[item].quantity;
            updateCartSummary();
        }
        function updateCartSummary() {
            let total = 0;
            let cartDetails = "";
            for (let item in cart) {
                if (cart[item].quantity > 0) {
                    cartDetails += `<li>${item.replace(/_/g, ' ')} - Qty: ${cart[item].quantity}, Price: ₹${cart[item].quantity * cart[item].price}</li>`;
                    total += cart[item].quantity * cart[item].price;
                }
            }
            document.getElementById("cart-items").innerHTML = cartDetails || "<li>No items added.</li>";
            document.getElementById("totalAmount").innerText = "Total: ₹" + total;
        }
        function clearCart() { cart = {}; updateCartSummary(); }
        function checkout() {
            let total = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            if (total === 0) { alert("Please add items to your cart before checking out."); return; }
            window.location.href = "checkout.php?total=" + total;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        
        <h2 class="abc">Food Menu</h2>
        
        <button class="btn btn-info mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#cartSummary">View Cart</button>
        <div class="collapse mt-3" id="cartSummary">
            <div class="card card-body">
                <h5>Cart Summary</h5>
                <ul id="cart-items"></ul>
                <h4 id="totalAmount">Total: ₹0</h4>
                <button class="btn btn-danger" onclick="clearCart()">Clear Cart</button>
                <button class="btn btn-success" onclick="checkout()">Checkout</button>
            </div>
        </div>
        <div class="accordion" id="foodMenu">
            <?php foreach ($categories as $category => $items) { ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo strtolower(str_replace(' ', '', $category)); ?>Section">
                            <?php echo $category; ?> Dishes
                        </button>
                    </h2>
                    <div id="<?php echo strtolower(str_replace(' ', '', $category)); ?>Section" class="accordion-collapse collapse show">
                        <div class="accordion-body row">
                            <?php foreach ($items as $item) { 
                                $itemId = strtolower(str_replace(' ', '_', $item));
                                $price = $prices[$itemId] ?? 350;
                            ?>
                                <div class="col-md-4">
                                    <div class="food-card">
                                        <img src="images/<?php echo $itemId; ?>.jpg" class="food-img" alt="<?php echo $item; ?>">
                                        <h5><?php echo $item; ?></h5>
                                        <p>₹<?php echo $price; ?></p>
                                        <button class="btn btn-danger" onclick="updateCart('<?php echo $itemId; ?>', <?php echo $price; ?>, 'remove')">-</button>
                                        <span id="<?php echo $itemId; ?>-qty">0</span>
                                        <button class="btn btn-success" onclick="updateCart('<?php echo $itemId; ?>', <?php echo $price; ?>, 'add')">+</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
    </div>
</body>
</html>