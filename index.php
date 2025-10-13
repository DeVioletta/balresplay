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

    <section class="page-hero">
        <div class="container">
            <h1>Our Menu</h1>
            <p>Nikmati kopi, teh, dan camilan favoritmu dalam suasana yang nyaman</p>
        </div>
    </section>

    <section class="menu-nav">
        <div class="container">
            <div class="menu-categories">
                <a href="#rice" class="category-link">Rice</a>
                <a href="#noodles" class="category-link">Noodles</a>
                <a href="#lite-easy" class="category-link">Lite & Easy</a>
                <a href="#coffee" class="category-link">Coffee</a>
                <a href="#tea" class="category-link">Tea Series</a>
                <a href="#non-coffee" class="category-link">Non Coffee</a>
                <a href="#signature" class="category-link">Signature Mocktail</a>
            </div>
        </div>
    </section>

    <!-- Rice Dishes -->
    <section id="rice" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Rice Dishes</h2>
                <p class="section-subtitle">Hearty and satisfying meals</p>
            </div>
            <div class="menu-grid">
                <!-- Fried Rice Chicken Grill -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fried+Rice+Grill" alt="Fried Rice Chicken Grill"></div>
                    <div class="item-info">
                        <h3>Fried Rice Chicken Grill</h3>
                        <p>Nasi goreng spesial disajikan dengan ayam panggang.</p>
                        <div class="item-meta">
                            <span class="item-price">40k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f1" product-name="Fried Rice Chicken Grill" product-price="40000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fried+Rice+Grill">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Fried Rice Seafood -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fried+Rice+Seafood" alt="Fried Rice Seafood"></div>
                    <div class="item-info">
                        <h3>Fried Rice Seafood</h3>
                        <p>Nasi goreng dengan aneka hidangan laut segar.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f2" product-name="Fried Rice Seafood" product-price="35000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fried+Rice+Seafood">Tambah</button></div>
                        </div>
                    </div>
                </div>
                 <!-- Ayam Bakar -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ayam+Bakar" alt="Ayam Bakar"></div>
                    <div class="item-info">
                        <h3>Ayam Bakar</h3>
                        <p>Ayam yang dibumbui dan dibakar dengan sempurna.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f3" product-name="Ayam Bakar" product-price="35000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ayam+Bakar">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Ayam Geprek -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ayam+Geprek" alt="Ayam Geprek"></div>
                    <div class="item-info">
                        <h3>Ayam Geprek</h3>
                        <p>Ayam goreng renyah yang ditumbuk dengan sambal.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f4" product-name="Ayam Geprek" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ayam+Geprek">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Noodles -->
    <section id="noodles" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Noodles</h2>
                <p class="section-subtitle">A slurp of happiness</p>
            </div>
            <div class="menu-grid">
                <!-- Spaghetti Aglio Olio -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Aglio+Olio" alt="Spaghetti Aglio Olio"></div>
                    <div class="item-info">
                        <h3>Spaghetti Aglio Olio</h3>
                        <p>Spaghetti dengan bumbu bawang putih dan minyak zaitun.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f5" product-name="Spaghetti Aglio Olio" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Aglio+Olio">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Spaghetti Bolognese -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Bolognese" alt="Spaghetti Bolognese"></div>
                    <div class="item-info">
                        <h3>Spaghetti Bolognese</h3>
                        <p>Spaghetti dengan saus daging cincang klasik.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f6" product-name="Spaghetti Bolognese" product-price="35000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Bolognese">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Chicken Char Kwetiau -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Kwetiau" alt="Chicken Char Kwetiau"></div>
                    <div class="item-info">
                        <h3>Chicken Char Kwetiau</h3>
                        <p>Kwetiau goreng dengan potongan ayam dan bumbu khas.</p>
                        <div class="item-meta">
                            <span class="item-price">39k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f7" product-name="Chicken Char Kwetiau" product-price="39000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Kwetiau">Tambah</button></div>
                        </div>
                    </div>
                </div>
                 <!-- Seafood Noodle Fried -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Mie+Seafood" alt="Seafood Noodle Fried"></div>
                    <div class="item-info">
                        <h3>Seafood Noodle Fried</h3>
                        <p>Mie goreng dengan aneka hidangan laut segar.</p>
                        <div class="item-meta">
                            <span class="item-price">38k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f8" product-name="Seafood Noodle Fried" product-price="38000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Mie+Seafood">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lite & Easy -->
    <section id="lite-easy" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Lite & Easy</h2>
                <p class="section-subtitle">Perfect for snacking</p>
            </div>
            <div class="menu-grid">
                <!-- Crinkle Fries -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fries" alt="Crinkle Fries"></div>
                    <div class="item-info">
                        <h3>Crinkle Fries</h3>
                        <p>Kentang goreng renyah dengan potongan berkerut.</p>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f9" product-name="Crinkle Fries" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fries">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Pisang Crispy -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Pisang+Crispy" alt="Pisang Crispy"></div>
                    <div class="item-info">
                        <h3>Pisang Crispy</h3>
                        <p>Pisang goreng dengan balutan adonan renyah.</p>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f10" product-name="Pisang Crispy" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Pisang+Crispy">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Roti Bakar Mix -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Roti+Bakar" alt="Roti Bakar Mix"></div>
                    <div class="item-info">
                        <h3>Roti Bakar Mix</h3>
                        <p>Roti panggang dengan isian cokelat dan keju.</p>
                        <div class="item-meta">
                            <span class="item-price">25k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f11" product-name="Roti Bakar Mix" product-price="25000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Roti+Bakar">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <!-- Burger Bal -->
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Burger" alt="Burger Bal"></div>
                    <div class="item-info">
                        <h3>Burger Bal</h3>
                        <p>Burger spesial dengan patty dan saus khas.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="f12" product-name="Burger Bal" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Burger">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="coffee" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Coffee</h2>
                <p class="section-subtitle">Begin your culinary journey</p>
            </div>
            <div class="menu-grid">
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Americano" alt="Americano"></div>
                    <div class="item-info">
                        <h3>Americano</h3>
                        <p>Shot espresso yang disajikan dengan tambahan air.</p>
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
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Pistachio+Macchiato" alt="Caramel Pistachio Macchiato"></div>
                    <div class="item-info">
                        <h3>Caramel Pistachio Macchiato</h3>
                        <p>Macchiato dengan sentuhan karamel dan pistachio.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="pistachio_macchiato" value="ice" data-price="35000" checked> Ice - 35k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c2" product-name="Caramel Pistachio Macchiato" product-price="35000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Pistachio+Macchiato">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Caramel+Macchiato" alt="Caramel Macchiato"></div>
                    <div class="item-info">
                        <h3>Caramel Macchiato</h3>
                        <p>Sajian kopi dengan susu dan saus karamel.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="caramel_macchiato" value="ice" data-price="28000" checked> Ice - 28k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c11" product-name="Caramel Macchiato" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Caramel+Macchiato">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Cappucino" alt="Cappucino"></div>
                    <div class="item-info">
                        <h3>Cappucino</h3>
                        <p>Kombinasi espresso, susu, dan busa susu.</p>
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
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Coffee+Milk" alt="Coffee Milk"></div>
                    <div class="item-info">
                        <h3>Coffee Milk</h3>
                        <p>Perpaduan kopi dan susu yang klasik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="coffeemilk" value="hot" data-price="22000" checked> Hot - 22k</label>
                            <label><input type="radio" name="coffeemilk" value="ice" data-price="23000"> Ice - 23k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c12" product-name="Coffee Milk" product-price="22000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Coffee+Milk">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Espresso" alt="Espresso Single"></div>
                    <div class="item-info">
                        <h3>Espresso Single</h3>
                        <p>Satu shot ekstrak kopi murni.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="espresso_single" value="hot" data-price="20000" checked> Hot - 20k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c13" product-name="Espresso Single" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Espresso">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Espresso+Double" alt="Espresso Double"></div>
                    <div class="item-info">
                        <h3>Espresso Double</h3>
                        <p>Dua shot ekstrak kopi untuk rasa lebih intens.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="espresso_double" value="hot" data-price="22000" checked> Hot - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                            <div class="item-actions"><button class="btn-add" product-id="c14" product-name="Espresso Double" product-price="22000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Espresso+Double">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Es+Kopi+Aren" alt="Es Kopi Aren"></div>
                    <div class="item-info">
                        <h3>Es Kopi Aren</h3>
                        <p>Kopi susu dengan pemanis gula aren asli.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="es_kopi_aren" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c5" product-name="Es Kopi Aren" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Es+Kopi+Aren">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Es+Kopi+Ubi" alt="Es Kopi Ubi"></div>
                    <div class="item-info">
                        <h3>Es Kopi Ubi</h3>
                        <p>Perpaduan unik kopi susu dengan rasa ubi.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="es_kopi_ubi" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                             <div class="item-actions"><button class="btn-add" product-id="c15" product-name="Es Kopi Ubi" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Es+Kopi+Ubi">Tambah</button></div>
                        </div>
                    </div>
                </div>
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

    <section id="tea" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tea Series</h2>
                <p class="section-subtitle">A sip of tranquility</p>
            </div>
            <div class="menu-grid">
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Black+Tea" alt="Black Tea"></div>
                    <div class="item-info">
                        <h3>Black Tea</h3>
                        <p>Teh hitam klasik dengan rasa yang kuat.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="blacktea" value="hot" data-price="10000" checked> Hot - 10k</label>
                            <label><input type="radio" name="blacktea" value="ice" data-price="10000"> Ice - 10k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">10k</span>
                            <div class="item-actions"><button class="btn-add" product-id="t4" product-name="Black Tea" product-price="10000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Black+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
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
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Thai+Tea" alt="Thai Tea"></div>
                    <div class="item-info">
                        <h3>Thai Tea</h3>
                        <p>Teh khas Thailand dengan rasa manis dan creamy.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="thaitea" value="hot" data-price="20000" checked> Hot - 20k</label>
                            <label><input type="radio" name="thaitea" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="t5" product-name="Thai Tea" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Thai+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Milk+Tea" alt="Milk Tea"></div>
                    <div class="item-info">
                        <h3>Milk Tea</h3>
                        <p>Teh yang dipadukan dengan susu lembut.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="milktea" value="hot" data-price="20000" checked> Hot - 20k</label>
                            <label><input type="radio" name="milktea" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="t6" product-name="Milk Tea" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Milk+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Lemon+Tea" alt="Lemon Tea"></div>
                    <div class="item-info">
                        <h3>Lemon Tea</h3>
                        <p>Kesegaran teh dengan perasan lemon.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="lemontea" value="hot" data-price="20000" checked> Hot - 20k</label>
                            <label><input type="radio" name="lemontea" value="ice" data-price="22000"> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <div class="item-actions"><button class="btn-add" product-id="t7" product-name="Lemon Tea" product-price="20000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Lemon+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Lychee+Tea" alt="Lychee Tea"></div>
                    <div class="item-info">
                        <h3>Lychee Tea</h3>
                        <p>Teh dengan rasa buah leci yang manis dan segar.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="lycheetea" value="ice" data-price="25000" checked> Ice - 25k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">25k</span>
                             <div class="item-actions"><button class="btn-add" product-id="t2" product-name="Lychee Tea" product-price="25000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Lychee+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Peach+Tea" alt="Peach Tea"></div>
                    <div class="item-info">
                        <h3>Peach Tea</h3>
                        <p>Kesegaran teh dengan aroma dan rasa buah persik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="peachtea" value="ice" data-price="22000" checked> Ice - 22k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                             <div class="item-actions"><button class="btn-add" product-id="t3" product-name="Peach Tea" product-price="22000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Peach+Tea">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section id="non-coffee" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Non Coffee</h2>
                <p class="section-subtitle">Delicious alternatives</p>
            </div>
            <div class="menu-grid">
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
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Bisscoff" alt="Bisscoff Caramello"></div>
                    <div class="item-info">
                        <h3>Bisscoff Caramello</h3>
                        <p>Minuman manis dengan rasa biskuit Biscoff dan karamel.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="bisscoff" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc2" product-name="Bisscoff Caramello" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Bisscoff">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ice+Childhood" alt="Ice Childhood"></div>
                    <div class="item-info">
                        <h3>Ice Childhood</h3>
                        <p>Minuman dingin dengan rasa nostalgia masa kecil.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="icechildhood" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc4" product-name="Ice Childhood" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ice+Childhood">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Marrone" alt="Marrone Caramello"></div>
                    <div class="item-info">
                        <h3>Marrone Caramello</h3>
                        <p>Minuman karamel dengan sentuhan rasa kastanye.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="marrone" value="ice" data-price="28000" checked> Ice - 28k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc5" product-name="Marrone Caramello" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Marrone">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Matcha" alt="Matcha Latte Bal"></div>
                    <div class="item-info">
                        <h3>Matcha Latte Bal</h3>
                        <p>Bubuk matcha berkualitas dipadukan dengan susu creamy.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="matcha" value="ice" data-price="29000" checked> Ice - 29k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">29k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc3" product-name="Matcha Latte Bal" product-price="29000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Matcha">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Taro" alt="Taro Latte Bal"></div>
                    <div class="item-info">
                        <h3>Taro Latte Bal</h3>
                        <p>Minuman latte dengan rasa talas yang unik dan manis.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="taro" value="ice" data-price="28000" checked> Ice - 28k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="nc6" product-name="Taro Latte Bal" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Taro">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="signature" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Signature Mocktail</h2>
                <p class="section-subtitle">Creative and refreshing mixes</p>
            </div>
            <div class="menu-grid">
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Kiwi+Mojito" alt="Kiwi Mojito"></div>
                    <div class="item-info">
                        <h3>Kiwi Mojito</h3>
                        <p>Kesegaran kiwi dan mint dalam mocktail soda.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="kiwi_mojito" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s1" product-name="Kiwi Mojito" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Kiwi+Mojito">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Alyster+Sunrise" alt="Alyster Sunrise"></div>
                    <div class="item-info">
                        <h3>Alyster Sunrise</h3>
                        <p>Mocktail cerah dengan gradasi warna yang cantik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="alyster_sunrise" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s2" product-name="Alyster Sunrise" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Alyster+Sunrise">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Rose+Coke" alt="Rose Coke"></div>
                    <div class="item-info">
                        <h3>Rose Coke</h3>
                        <p>Kombinasi soda dengan sentuhan sirup mawar.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="rose_coke" value="ice" data-price="30000" checked> Ice - 30k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s4" product-name="Rose Coke" product-price="30000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Rose+Coke">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Choco+Mint" alt="Choco Mint"></div>
                    <div class="item-info">
                        <h3>Choco Mint</h3>
                        <p>Kombinasi klasik cokelat dan sensasi dingin dari mint.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="choco_mint" value="ice" data-price="28000" checked> Ice - 28k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s3" product-name="Choco Mint" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Choco+Mint">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Passion+Punch" alt="Passion Punch"></div>
                    <div class="item-info">
                        <h3>Passion Punch</h3>
                        <p>Minuman segar dengan rasa markisa yang eksotis.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="passion_punch" value="ice" data-price="29000" checked> Ice - 29k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">29k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s5" product-name="Passion Punch" product-price="29000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Passion+Punch">Tambah</button></div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Peach+Mojhito" alt="Peach Mojhito"></div>
                    <div class="item-info">
                        <h3>Peach Mojhito</h3>
                        <p>Mojito dengan sentuhan manis dari buah persik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="peach_mojhito" value="ice" data-price="28000" checked> Ice - 28k</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <div class="item-actions"><button class="btn-add" product-id="s6" product-name="Peach Mojhito" product-price="28000" product-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Peach+Mojhito">Tambah</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cart-modal">     
        <div class="cart-modal-content">
            <span class="cart-close">&times;</span>
            <h2 class="cart-title">Your Cart</h2>
            <div class="cart-items">
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
    
    </body>
</html>