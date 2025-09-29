<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BalResplay | Menu</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

<?php
// include file header.php
include 'includes/header.php';
?>

    <!-- Menu Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Our Menu</h1>
            <p>Nikmati kopi, teh, dan camilan favoritmu dalam suasana yang nyaman</p>
        </div>
    </section>

    <!-- Menu Navigation -->
    <section class="menu-nav">
        <div class="container">
            <div class="menu-categories">
                <a href="#coffee" class="category-link">Coffee</a>
                <a href="#tea" class="category-link">Tea Series</a>
                <a href="#non-coffee" class="category-link">Non Coffee</a>
                <a href="#signature" class="category-link">Signature Mocktail</a>
            </div>
        </div>
    </section>

    <!-- Coffee -->
    <section id="coffee" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Coffee</h2>
                <p class="section-subtitle">Begin your culinary journey</p>
            </div>
            <div class="menu-grid">
                <!-- Americano -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Americano" alt="Americano"></div>
                    <div class="item-info">
                        <h3>Americano</h3>
                        <p>Shot espresso yang dituangkan air panas.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="americano" value="hot" data-price="20000" checked> Hot - 20k</label>
                            <label><input type="radio" name="americano" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c1" product-name="Americano" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Americano">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Caramel Pistachio Macchiato -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Pistachio+Macchiato" alt="Caramel Pistachio Macchiato"></div>
                    <div class="item-info">
                        <h3>Caramel Pistachio Macchiato</h3>
                        <p>Macchiato dengan sentuhan karamel dan pistachio.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c2" product-name="Caramel Pistachio Macchiato" product-price="35000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Pistachio+Macchiato">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Cappucino -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Cappucino" alt="Cappucino"></div>
                    <div class="item-info">
                        <h3>Cappucino</h3>
                        <p>Kombinasi espresso, susu panas, dan busa susu.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="cappucino" value="hot" data-price="23000" checked> Hot - 23k</label>
                            <label><input type="radio" name="cappucino" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">23k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c3" product-name="Cappucino" product-price="23000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Cappucino">Tambah</button></div>
                        </div>
                    </div>
                </div>
                 <!-- Cafe Latte -->
                 <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Cafe+Latte" alt="Cafe Latte"></div>
                    <div class="item-info">
                        <h3>Cafe Latte</h3>
                        <p>Espresso dengan porsi susu lebih banyak.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="cafelatte" value="hot" data-price="23000" checked> Hot - 23k</label>
                            <label><input type="radio" name="cafelatte" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">23k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c4" product-name="Cafe Latte" product-price="23000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Cafe+Latte">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Es Kopi Aren -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Es+Kopi+Aren" alt="Es Kopi Aren"></div>
                    <div class="item-info">
                        <h3>Es Kopi Aren</h3>
                        <p>Kopi susu dengan pemanis gula aren asli.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c5" product-name="Es Kopi Aren" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Es+Kopi+Aren">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Mocha Latte -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Mocha+Latte" alt="Mocha Latte"></div>
                    <div class="item-info">
                        <h3>Mocha Latte</h3>
                        <p>Perpaduan espresso, cokelat, dan susu steamed.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="mochalatte" value="hot" data-price="30000" checked> Hot - 30k</label>
                            <label><input type="radio" name="mochalatte" value="ice" data-price="32000"> Ice - 32k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c6" product-name="Mocha Latte" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Mocha+Latte">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tea Series -->
    <section id="tea" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tea Series</h2>
                <p class="section-subtitle">A sip of tranquility</p>
            </div>
            <div class="menu-grid">
                <!-- Green Tea -->
                 <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Green+Tea" alt="Green Tea"></div>
                    <div class="item-info">
                        <h3>Green Tea</h3>
                        <p>Teh hijau klasik yang menyegarkan.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="greentea" value="hot" data-price="20000" checked> Hot - 20k</label>
                            <label><input type="radio" name="greentea" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="t1" product-name="Green Tea" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Green+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Lychee Tea -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Lychee+Tea" alt="Lychee Tea"></div>
                    <div class="item-info">
                        <h3>Lychee Tea</h3>
                        <p>Teh dengan rasa buah leci yang manis dan segar.</p>
                        <div class="item-meta">
                            <span class="item-price">25k</span>
                             <div class="item-actions"><button class="btn-add" product-id="t2" product-name="Lychee Tea" product-price="25000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Lychee+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Peach Tea -->
                 <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Peach+Tea" alt="Peach Tea"></div>
                    <div class="item-info">
                        <h3>Peach Tea</h3>
                        <p>Kesegaran teh dengan aroma dan rasa buah persik.</p>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                             <div class="item-actions"><button class="btn-add" product-id="t3" product-name="Peach Tea" product-price="22000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Peach+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Non Coffee -->
    <section id="non-coffee" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Non Coffee</h2>
                <p class="section-subtitle">Delicious alternatives</p>
            </div>
            <div class="menu-grid">
                <!-- Choco Latte Bal -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Choco+Latte" alt="Choco Latte Bal"></div>
                    <div class="item-info">
                        <h3>Choco Latte Bal</h3>
                        <p>Cokelat premium yang lembut dan kaya rasa.</p>
                         <div class="item-variants">
                            <label><input type="radio" name="chocolatte" value="hot" data-price="28000" checked> Hot - 28k</label>
                            <label><input type="radio" name="chocolatte" value="ice" data-price="30000"> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc1" product-name="Choco Latte Bal" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Choco+Latte">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Bisscoff Caramello -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Bisscoff" alt="Bisscoff Caramello"></div>
                    <div class="item-info">
                        <h3>Bisscoff Caramello</h3>
                        <p>Minuman manis dengan rasa biskuit Biscoff dan karamel.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc2" product-name="Bisscoff Caramello" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Bisscoff">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Matcha Latte Bal -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Matcha" alt="Matcha Latte Bal"></div>
                    <div class="item-info">
                        <h3>Matcha Latte Bal</h3>
                        <p>Bubuk matcha berkualitas dipadukan dengan susu creamy.</p>
                        <div class="item-meta">
                            <span class="item-price">29k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc3" product-name="Matcha Latte Bal" product-price="29000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Matcha">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Signature Mocktail -->
    <section id="signature" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Signature Mocktail</h2>
                <p class="section-subtitle">Creative and refreshing mixes</p>
            </div>
            <div class="menu-grid">
                <!-- Kiwi Mojito -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Kiwi+Mojito" alt="Kiwi Mojito"></div>
                    <div class="item-info">
                        <h3>Kiwi Mojito</h3>
                        <p>Kesegaran kiwi dan mint dalam mocktail soda.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s1" product-name="Kiwi Mojito" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Kiwi+Mojito">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Alyster Sunrise -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Alyster+Sunrise" alt="Alyster Sunrise"></div>
                    <div class="item-info">
                        <h3>Alyster Sunrise</h3>
                        <p>Mocktail cerah dengan gradasi warna yang cantik.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s2" product-name="Alyster Sunrise" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Alyster+Sunrise">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Choco Mint -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Choco+Mint" alt="Choco Mint"></div>
                    <div class="item-info">
                        <h3>Choco Mint</h3>
                        <p>Kombinasi klasik cokelat dan sensasi dingin dari mint.</p>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s3" product-name="Choco Mint" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Choco+Mint">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Modal -->
    <div class="cart-modal">     
        <div class="cart-modal-content">
            <span class="cart-close">&times;</span>
            <h2 class="cart-title">Your Cart</h2>
            <div class="cart-items">
                <!-- Cart items will be populated here -->
            </div>
            <div class="cart-total">
                <span>Total:</span>
                <span class="cart-total-price">0k</span>
            </div>
            <div class="cart-buttons">
                <button class="btn btn-primary" id="place-order-btn">Place Order</button>
                <button class="btn btn-secondary" id="continue-shopping">Continue Shopping</button>
            </div>
        </div>
    </div>

    <!-- Formspree Modal for Cart -->
    <div class="formspree-modal" id="cart-modal">
        <div class="formspree-modal-content">
            <span class="formspree-close">&times;</span>
            <h2>Place Your Order</h2>
            <form action="https://formspree.io/f/mdklbqpl" method="POST" id="cart-order-form">
                <input type="hidden" name="order_details" id="order-details">
                <input type="hidden" name="order_total" id="order-total">
                
                <div class="order-summary">
                    <h4>Order Summary</h4>
                    <div id="cart-order-summary">
                        <!-- Cart items will be populated here -->
                    </div>
                    <div class="order-item" style="font-weight: bold; border-top: 1px solid var(--tertiary-color); padding-top: 5px;">
                        <span>Total:</span>
                        <span id="cart-summary-total">0k</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cart-name">Full Name</label>
                    <input type="text" id="cart-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="cart-email">Email Address</label>
                    <input type="email" id="cart-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="cart-phone">Phone Number</label>
                    <input type="tel" id="cart-phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="cart-address">Delivery Address</label>
                    <textarea id="cart-address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="cart-notes">Special Instructions</label>
                    <textarea id="cart-notes" name="notes" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Complete Order</button>
            </form>
        </div>
    </div>
    
    <!-- <script src="script.js"></script>
    <script src="menu.js"></script> -->

</body>
</html>
