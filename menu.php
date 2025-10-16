<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BalResplay | Menu</title>
    <link rel="stylesheet" href="css/variable.css">
    <link rel="stylesheet" href="css/menu2.css">
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
                <div class="menu-item" data-id="f1" data-name="Fried Rice Chicken Grill" data-price="40000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fried+Rice+Grill">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fried+Rice+Grill" alt="Fried Rice Chicken Grill"></div>
                    <div class="item-info">
                        <h3>Fried Rice Chicken Grill</h3>
                        <p>Nasi goreng spesial disajikan dengan ayam panggang.</p>
                        <div class="item-meta">
                            <span class="item-price">40k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Fried Rice Seafood -->
                <div class="menu-item" data-id="f2" data-name="Fried Rice Seafood" data-price="35000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fried+Rice+Seafood">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fried+Rice+Seafood" alt="Fried Rice Seafood"></div>
                    <div class="item-info">
                        <h3>Fried Rice Seafood</h3>
                        <p>Nasi goreng dengan aneka hidangan laut segar.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                 <!-- Ayam Bakar -->
                <div class="menu-item" data-id="f3" data-name="Ayam Bakar" data-price="35000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ayam+Bakar">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ayam+Bakar" alt="Ayam Bakar"></div>
                    <div class="item-info">
                        <h3>Ayam Bakar</h3>
                        <p>Ayam yang dibumbui dan dibakar dengan sempurna.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Ayam Geprek -->
                <div class="menu-item" data-id="f4" data-name="Ayam Geprek" data-price="30000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ayam+Geprek">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ayam+Geprek" alt="Ayam Geprek"></div>
                    <div class="item-info">
                        <h3>Ayam Geprek</h3>
                        <p>Ayam goreng renyah yang ditumbuk dengan sambal.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
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
                <div class="menu-item" data-id="f5" data-name="Spaghetti Aglio Olio" data-price="30000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Aglio+Olio">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Aglio+Olio" alt="Spaghetti Aglio Olio"></div>
                    <div class="item-info">
                        <h3>Spaghetti Aglio Olio</h3>
                        <p>Spaghetti dengan bumbu bawang putih dan minyak zaitun.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Spaghetti Bolognese -->
                <div class="menu-item" data-id="f6" data-name="Spaghetti Bolognese" data-price="35000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Bolognese">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Bolognese" alt="Spaghetti Bolognese"></div>
                    <div class="item-info">
                        <h3>Spaghetti Bolognese</h3>
                        <p>Spaghetti dengan saus daging cincang klasik.</p>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Chicken Char Kwetiau -->
                <div class="menu-item" data-id="f7" data-name="Chicken Char Kwetiau" data-price="39000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Kwetiau">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Kwetiau" alt="Chicken Char Kwetiau"></div>
                    <div class="item-info">
                        <h3>Chicken Char Kwetiau</h3>
                        <p>Kwetiau goreng dengan potongan ayam dan bumbu khas.</p>
                        <div class="item-meta">
                            <span class="item-price">39k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                 <!-- Seafood Noodle Fried -->
                <div class="menu-item" data-id="f8" data-name="Seafood Noodle Fried" data-price="38000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Mie+Seafood">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Mie+Seafood" alt="Seafood Noodle Fried"></div>
                    <div class="item-info">
                        <h3>Seafood Noodle Fried</h3>
                        <p>Mie goreng dengan aneka hidangan laut segar.</p>
                        <div class="item-meta">
                            <span class="item-price">38k</span>
                            <button class="btn-add">Tambah</button>
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
                <div class="menu-item" data-id="f9" data-name="Crinkle Fries" data-price="20000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Fries">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Fries" alt="Crinkle Fries"></div>
                    <div class="item-info">
                        <h3>Crinkle Fries</h3>
                        <p>Kentang goreng renyah dengan potongan berkerut.</p>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Pisang Crispy -->
                <div class="menu-item" data-id="f10" data-name="Pisang Crispy" data-price="20000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Pisang+Crispy">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Pisang+Crispy" alt="Pisang Crispy"></div>
                    <div class="item-info">
                        <h3>Pisang Crispy</h3>
                        <p>Pisang goreng dengan balutan adonan renyah.</p>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                           <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Roti Bakar Mix -->
                <div class="menu-item" data-id="f11" data-name="Roti Bakar Mix" data-price="25000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Roti+Bakar">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Roti+Bakar" alt="Roti Bakar Mix"></div>
                    <div class="item-info">
                        <h3>Roti Bakar Mix</h3>
                        <p>Roti panggang dengan isian cokelat dan keju.</p>
                        <div class="item-meta">
                            <span class="item-price">25k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <!-- Burger Bal -->
                <div class="menu-item" data-id="f12" data-name="Burger Bal" data-price="30000" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Burger">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Burger" alt="Burger Bal"></div>
                    <div class="item-info">
                        <h3>Burger Bal</h3>
                        <p>Burger spesial dengan patty dan saus khas.</p>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
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
                <div class="menu-item" data-id="c1" data-name="Americano" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Americano">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Americano" alt="Americano"></div>
                    <div class="item-info">
                        <h3>Americano</h3>
                        <p>Shot espresso yang disajikan dengan tambahan air.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="americano_c1" value="Hot" data-price="20000" checked> Hot</label>
                            <label><input type="radio" name="americano_c1" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c2" data-name="Caramel Pistachio Macchiato" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Pistachio+Macchiato">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Pistachio+Macchiato" alt="Caramel Pistachio Macchiato"></div>
                    <div class="item-info">
                        <h3>Caramel Pistachio Macchiato</h3>
                        <p>Macchiato dengan sentuhan karamel dan pistachio.</p>
                         <div class="item-variants">
                            <label><input type="radio" name="pistachio_macchiato_c2" value="Ice" data-price="35000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">35k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c11" data-name="Caramel Macchiato" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Caramel+Macchiato">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Caramel+Macchiato" alt="Caramel Macchiato"></div>
                    <div class="item-info">
                        <h3>Caramel Macchiato</h3>
                        <p>Sajian kopi dengan susu dan saus karamel.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="caramel_macchiato_c11" value="Ice" data-price="28000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                             <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c3" data-name="Cappucino" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Cappucino">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Cappucino" alt="Cappucino"></div>
                    <div class="item-info">
                        <h3>Cappucino</h3>
                        <p>Kombinasi espresso, susu, dan busa susu.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="cappucino_c3" value="Hot" data-price="23000" checked> Hot</label>
                            <label><input type="radio" name="cappucino_c3" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">23k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                 <div class="menu-item" data-id="c4" data-name="Cafe Latte" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Cafe+Latte">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Cafe+Latte" alt="Cafe Latte"></div>
                    <div class="item-info">
                        <h3>Cafe Latte</h3>
                        <p>Espresso dengan porsi susu lebih banyak.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="cafelatte_c4" value="Hot" data-price="23000" checked> Hot</label>
                            <label><input type="radio" name="cafelatte_c4" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">23k</span>
                           <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c12" data-name="Coffee Milk" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Coffee+Milk">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Coffee+Milk" alt="Coffee Milk"></div>
                    <div class="item-info">
                        <h3>Coffee Milk</h3>
                        <p>Perpaduan kopi dan susu yang klasik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="coffeemilk_c12" value="Hot" data-price="22000" checked> Hot</label>
                            <label><input type="radio" name="coffeemilk_c12" value="Ice" data-price="23000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c13" data-name="Espresso Single" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Espresso">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Espresso" alt="Espresso Single"></div>
                    <div class="item-info">
                        <h3>Espresso Single</h3>
                        <p>Satu shot ekstrak kopi murni.</p>
                         <div class="item-variants">
                            <label><input type="radio" name="espresso_single_c13" value="Hot" data-price="20000" checked> Hot</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c14" data-name="Espresso Double" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Espresso+Double">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Espresso+Double" alt="Espresso Double"></div>
                    <div class="item-info">
                        <h3>Espresso Double</h3>
                        <p>Dua shot ekstrak kopi untuk rasa lebih intens.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="espresso_double_c14" value="Hot" data-price="22000" checked> Hot</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c5" data-name="Es Kopi Aren" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Es+Kopi+Aren">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Es+Kopi+Aren" alt="Es Kopi Aren"></div>
                    <div class="item-info">
                        <h3>Es Kopi Aren</h3>
                        <p>Kopi susu dengan pemanis gula aren asli.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="es_kopi_aren_c5" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                             <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c15" data-name="Es Kopi Ubi" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Es+Kopi+Ubi">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Es+Kopi+Ubi" alt="Es Kopi Ubi"></div>
                    <div class="item-info">
                        <h3>Es Kopi Ubi</h3>
                        <p>Perpaduan unik kopi susu dengan rasa ubi.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="es_kopi_ubi_c15" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                             <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="c6" data-name="Mocha Latte" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Mocha+Latte">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Mocha+Latte" alt="Mocha Latte"></div>
                    <div class="item-info">
                        <h3>Mocha Latte</h3>
                        <p>Perpaduan espresso, cokelat, dan susu steamed.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="mochalatte_c6" value="Hot" data-price="30000" checked> Hot</label>
                            <label><input type="radio" name="mochalatte_c6" value="Ice" data-price="32000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- All other menu sections (tea, non-coffee, signature) should follow the same data-attribute pattern as above -->

    <section id="tea" class="menu-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tea Series</h2>
                <p class="section-subtitle">A sip of tranquility</p>
            </div>
            <div class="menu-grid">
                <div class="menu-item" data-id="t4" data-name="Black Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Black+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Black+Tea" alt="Black Tea"></div>
                    <div class="item-info">
                        <h3>Black Tea</h3>
                        <p>Teh hitam klasik dengan rasa yang kuat.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="blacktea_t4" value="Hot" data-price="10000" checked> Hot</label>
                            <label><input type="radio" name="blacktea_t4" value="Ice" data-price="10000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">10k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t1" data-name="Green Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Green+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Green+Tea" alt="Green Tea"></div>
                    <div class="item-info">
                        <h3>Green Tea</h3>
                        <p>Teh hijau klasik yang menyegarkan.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="greentea_t1" value="Hot" data-price="20000" checked> Hot</label>
                            <label><input type="radio" name="greentea_t1" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t5" data-name="Thai Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Thai+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Thai+Tea" alt="Thai Tea"></div>
                    <div class="item-info">
                        <h3>Thai Tea</h3>
                        <p>Teh khas Thailand dengan rasa manis dan creamy.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="thaitea_t5" value="Hot" data-price="20000" checked> Hot</label>
                            <label><input type="radio" name="thaitea_t5" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t6" data-name="Milk Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Milk+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Milk+Tea" alt="Milk Tea"></div>
                    <div class="item-info">
                        <h3>Milk Tea</h3>
                        <p>Teh yang dipadukan dengan susu lembut.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="milktea_t6" value="Hot" data-price="20000" checked> Hot</label>
                            <label><input type="radio" name="milktea_t6" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t7" data-name="Lemon Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Lemon+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Lemon+Tea" alt="Lemon Tea"></div>
                    <div class="item-info">
                        <h3>Lemon Tea</h3>
                        <p>Kesegaran teh dengan perasan lemon.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="lemontea_t7" value="Hot" data-price="20000" checked> Hot</label>
                            <label><input type="radio" name="lemontea_t7" value="Ice" data-price="22000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">20k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t2" data-name="Lychee Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Lychee+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Lychee+Tea" alt="Lychee Tea"></div>
                    <div class="item-info">
                        <h3>Lychee Tea</h3>
                        <p>Teh dengan rasa buah leci yang manis dan segar.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="lycheetea_t2" value="Ice" data-price="25000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">25k</span>
                             <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="t3" data-name="Peach Tea" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Peach+Tea">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Peach+Tea" alt="Peach Tea"></div>
                    <div class="item-info">
                        <h3>Peach Tea</h3>
                        <p>Kesegaran teh dengan aroma dan rasa buah persik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="peachtea_t3" value="Ice" data-price="22000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">22k</span>
                             <button class="btn-add">Tambah</button>
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
                <div class="menu-item" data-id="nc1" data-name="Choco Latte Bal" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Choco+Latte">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Choco+Latte" alt="Choco Latte Bal"></div>
                    <div class="item-info">
                        <h3>Choco Latte Bal</h3>
                        <p>Cokelat premium yang lembut dan kaya rasa.</p>
                         <div class="item-variants">
                            <label><input type="radio" name="chocolatte_nc1" value="Hot" data-price="28000" checked> Hot</label>
                            <label><input type="radio" name="chocolatte_nc1" value="Ice" data-price="30000"> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="nc2" data-name="Bisscoff Caramello" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Bisscoff">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Bisscoff" alt="Bisscoff Caramello"></div>
                    <div class="item-info">
                        <h3>Bisscoff Caramello</h3>
                        <p>Minuman manis dengan rasa biskuit Biscoff dan karamel.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="bisscoff_nc2" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="nc4" data-name="Ice Childhood" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Ice+Childhood">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Ice+Childhood" alt="Ice Childhood"></div>
                    <div class="item-info">
                        <h3>Ice Childhood</h3>
                        <p>Minuman dingin dengan rasa nostalgia masa kecil.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="icechildhood_nc4" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="nc5" data-name="Marrone Caramello" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Marrone">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Marrone" alt="Marrone Caramello"></div>
                    <div class="item-info">
                        <h3>Marrone Caramello</h3>
                        <p>Minuman karamel dengan sentuhan rasa kastanye.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="marrone_nc5" value="Ice" data-price="28000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="nc3" data-name="Matcha Latte Bal" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Matcha">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Matcha" alt="Matcha Latte Bal"></div>
                    <div class="item-info">
                        <h3>Matcha Latte Bal</h3>
                        <p>Bubuk matcha berkualitas dipadukan dengan susu creamy.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="matcha_nc3" value="Ice" data-price="29000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">29k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="nc6" data-name="Taro Latte Bal" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Taro">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Taro" alt="Taro Latte Bal"></div>
                    <div class="item-info">
                        <h3>Taro Latte Bal</h3>
                        <p>Minuman latte dengan rasa talas yang unik dan manis.</p>
                        <div class="item-variants">
                           <label><input type="radio" name="taro_nc6" value="Ice" data-price="28000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <button class="btn-add">Tambah</button>
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
                <div class="menu-item" data-id="s1" data-name="Kiwi Mojito" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Kiwi+Mojito">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Kiwi+Mojito" alt="Kiwi Mojito"></div>
                    <div class="item-info">
                        <h3>Kiwi Mojito</h3>
                        <p>Kesegaran kiwi dan mint dalam mocktail soda.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="kiwi_mojito_s1" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="s2" data-name="Alyster Sunrise" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Alyster+Sunrise">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Alyster+Sunrise" alt="Alyster Sunrise"></div>
                    <div class="item-info">
                        <h3>Alyster Sunrise</h3>
                        <p>Mocktail cerah dengan gradasi warna yang cantik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="alyster_sunrise_s2" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="s4" data-name="Rose Coke" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Rose+Coke">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Rose+Coke" alt="Rose Coke"></div>
                    <div class="item-info">
                        <h3>Rose Coke</h3>
                        <p>Kombinasi soda dengan sentuhan sirup mawar.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="rose_coke_s4" value="Ice" data-price="30000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">30k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="s3" data-name="Choco Mint" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Choco+Mint">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Choco+Mint" alt="Choco Mint"></div>
                    <div class="item-info">
                        <h3>Choco Mint</h3>
                        <p>Kombinasi klasik cokelat dan sensasi dingin dari mint.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="choco_mint_s3" value="Ice" data-price="28000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="s5" data-name="Passion Punch" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Passion+Punch">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Passion+Punch" alt="Passion Punch"></div>
                    <div class="item-info">
                        <h3>Passion Punch</h3>
                        <p>Minuman segar dengan rasa markisa yang eksotis.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="passion_punch_s5" value="Ice" data-price="29000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">29k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="menu-item" data-id="s6" data-name="Peach Mojhito" data-img="https://placehold.co/100x100/e8e4d8/5c6e58?text=Peach+Mojhito">
                    <div class="item-image"><img src="https://placehold.co/300x300/e8e4d8/5c6e58?text=Peach+Mojhito" alt="Peach Mojhito"></div>
                    <div class="item-info">
                        <h3>Peach Mojhito</h3>
                        <p>Mojito dengan sentuhan manis dari buah persik.</p>
                        <div class="item-variants">
                            <label><input type="radio" name="peach_mojhito_s6" value="Ice" data-price="28000" checked> Ice</label>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">28k</span>
                            <button class="btn-add">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Modal -->
    <div class="cart-modal" id="cart-modal">     
        <div class="cart-modal-content">
            <span class="cart-close" id="cart-close">&times;</span>
            <h2 class="cart-title">Pesanan Anda</h2>
            <div class="cart-items" id="cart-items">
                <!-- Cart items will be injected here by JavaScript -->
                 <div class="cart-empty-message">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Keranjang Anda masih kosong.</p>
                </div>
            </div>
            <div class="cart-footer" id="cart-footer" style="display: none;">
                <div class="cart-table-number">
                    <span>Nomor Meja:</span>
                    <span class="table-number-display">12</span>
                </div>
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="cart-total-price" id="cart-total-price">0k</span>
                </div>
                <div class="cart-buttons">
                    <button class="btn btn-secondary" id="continue-shopping">Lanjut Belanja</button>
                    <button class="btn btn-primary" id="place-order-btn">Pesan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
    
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const cartModal = document.getElementById('cart-modal');
    const cartClose = document.getElementById('cart-close');
    const continueShoppingBtn = document.getElementById('continue-shopping');
    const addToCartButtons = document.querySelectorAll('.btn-add');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCountElement = document.getElementById('cart-count');
    const cartTotalPriceElement = document.getElementById('cart-total-price');
    const cartFooter = document.getElementById('cart-footer');
    const placeOrderBtn = document.getElementById('place-order-btn');
    
    let cart = [];
    let totalPrice = 0;

    // Fungsi untuk membuka modal
    const openModal = () => {
        cartModal.classList.add('show');
    };

    // Fungsi untuk menutup modal
    const closeModal = () => {
        cartModal.classList.remove('show');
    };

    // Event listeners untuk membuka/menutup modal
    cartIcon.addEventListener('click', openModal);
    cartClose.addEventListener('click', closeModal);
    continueShoppingBtn.addEventListener('click', closeModal);
    cartModal.addEventListener('click', (e) => {
        if (e.target === cartModal) {
            closeModal();
        }
    });

    // Event listener untuk tombol "Tambah"
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const menuItem = e.target.closest('.menu-item');
            
            const id = menuItem.dataset.id;
            const name = menuItem.dataset.name;
            const img = menuItem.dataset.img;
            
            let price;
            let variant = null;
            
            const variantInput = menuItem.querySelector('.item-variants input[type="radio"]:checked');
            
            if (variantInput) {
                price = parseFloat(variantInput.dataset.price);
                variant = variantInput.value;
            } else {
                price = parseFloat(menuItem.dataset.price);
            }

            // Membuat ID unik untuk item di keranjang berdasarkan produk ID dan variannya
            const cartItemId = variant ? `${id}_${variant}` : id;

            const existingItem = cart.find(item => item.cartId === cartItemId);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    cartId: cartItemId,
                    id: id,
                    name: name,
                    price: price,
                    img: img,
                    variant: variant,
                    quantity: 1,
                    notes: '' // Tambah properti catatan
                });
            }
            
            updateCart();
            openModal();
        });
    });

    // Mengubah harga tampilan saat varian dipilih
    document.querySelectorAll('.item-variants input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            const menuItem = e.target.closest('.menu-item');
            const priceElement = menuItem.querySelector('.item-price');
            const newPrice = parseFloat(e.target.dataset.price);
            priceElement.textContent = `${newPrice / 1000}k`;
        });
    });


    function updateCart() {
        cartItemsContainer.innerHTML = '';
        let totalItems = 0;
        totalPrice = 0; // Reset total price

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="cart-empty-message">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Keranjang Anda masih kosong.</p>
                </div>`;
            cartFooter.style.display = 'none';
        } else {
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('cart-item');
                itemElement.innerHTML = `
                    <img src="${item.img}" alt="${item.name}" class="cart-item-img">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        ${item.variant ? `<p class="item-variant">${item.variant}</p>` : ''}
                        <p class="cart-item-price-small">${(item.price / 1000).toLocaleString('id-ID')}k</p>
                    </div>
                    <div class="cart-item-controls">
                        <div class="cart-item-quantity">
                            <button class="quantity-btn" data-id="${item.cartId}" data-action="decrease">-</button>
                            <span class="quantity-display">${item.quantity}</span>
                            <button class="quantity-btn" data-id="${item.cartId}" data-action="increase">+</button>
                        </div>
                        <button class="cart-item-remove" data-id="${item.cartId}"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="cart-item-notes">
                        <textarea class="notes-input" data-id="${item.cartId}" placeholder="Contoh: Tidak pedas, sedikit gula...">${item.notes}</textarea>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);

                totalItems += item.quantity;
                totalPrice += item.price * item.quantity;
            });
            cartFooter.style.display = 'block';
        }

        cartCountElement.textContent = totalItems;
        cartTotalPriceElement.textContent = `${(totalPrice / 1000).toLocaleString('id-ID')}k`;
    }

    cartItemsContainer.addEventListener('click', e => {
        const target = e.target.closest('button');
        if (!target) return;

        const cartId = target.dataset.id;

        if (target.classList.contains('quantity-btn')) {
            const action = target.dataset.action;
            const itemToUpdate = cart.find(item => item.cartId === cartId);
            if (action === 'increase') {
                itemToUpdate.quantity++;
            } else if (action === 'decrease') {
                itemToUpdate.quantity--;
                if (itemToUpdate.quantity <= 0) {
                    cart = cart.filter(item => item.cartId !== cartId);
                }
            }
        }

        if (target.classList.contains('cart-item-remove')) {
            cart = cart.filter(item => item.cartId !== cartId);
        }

        updateCart();
    });

    // Event listener untuk input catatan
    cartItemsContainer.addEventListener('input', e => {
        if (e.target.classList.contains('notes-input')) {
            const cartId = e.target.dataset.id;
            const itemToUpdate = cart.find(item => item.cartId === cartId);
            if (itemToUpdate) {
                itemToUpdate.notes = e.target.value;
            }
        }
    });

    // Event listener untuk tombol "Pesan Sekarang"
    placeOrderBtn.addEventListener('click', () => {
        if (cart.length > 0) {
            // Simpan data ke sessionStorage
            sessionStorage.setItem('cartData', JSON.stringify(cart));
            sessionStorage.setItem('cartTotalPrice', totalPrice);
            sessionStorage.setItem('tableNumber', '12'); // Hardcoded table number

            // Arahkan ke halaman pembayaran
            window.location.href = 'payment.php';
        } else {
            alert('Keranjang Anda kosong!');
        }
    });

    // Initial cart update on page load
    updateCart();

});
</script>
</body>
</html>