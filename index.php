<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$lang = current_lang();
$copy = labels()[$lang];
$data = site_data();
$hero = $data['hero'][$lang];
$contact = $data['contact'][$lang];
$products = $data['products'];
$faqs = $data['faqs'];
?>
<!DOCTYPE html>
<html lang="<?= h($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(config('site_name')) ?></title>
    <link rel="stylesheet" href="<?= h(asset('public/styles.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="wrap nav">
        <a class="brand" href="#top"><?= h(config('site_name')) ?></a>
        <nav class="nav-links">
            <a href="#products"><?= h($copy['nav_products']) ?></a>
            <a href="#faq"><?= h($copy['nav_faq']) ?></a>
            <a href="#contact"><?= h($copy['nav_contact']) ?></a>
            <a href="<?= h(url('admin/login.php')) ?>">Admin</a>
        </nav>
        <div class="lang-switch">
            <a class="<?= $lang === 'zh' ? 'active' : '' ?>" href="<?= h(switch_lang_url('zh')) ?>">中文</a>
            <a class="<?= $lang === 'en' ? 'active' : '' ?>" href="<?= h(switch_lang_url('en')) ?>">EN</a>
        </div>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="wrap hero-grid">
            <div>
                <span class="badge"><?= h($copy['hero_badge']) ?></span>
                <h1><?= h($hero['title']) ?></h1>
                <p class="hero-text"><?= h($hero['subtitle']) ?></p>
                <a class="button" href="#products"><?= h($hero['cta']) ?></a>
            </div>
            <div class="hero-panel">
                <div class="metric">
                    <strong>99.9%</strong>
                    <span><?= $lang === 'zh' ? '基础 SLA 可用性' : 'Base SLA availability' ?></span>
                </div>
                <div class="metric">
                    <strong>24/7</strong>
                    <span><?= $lang === 'zh' ? '工单支持' : 'Ticket support' ?></span>
                </div>
                <div class="metric">
                    <strong>3</strong>
                    <span><?= $lang === 'zh' ? '核心产品线' : 'Core product lines' ?></span>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="section">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <p class="eyebrow"><?= h($copy['nav_products']) ?></p>
                    <h2><?= h($copy['products_title']) ?></h2>
                </div>
                <p><?= h($copy['products_intro']) ?></p>
            </div>

            <div class="cards">
                <?php foreach ($products as $product): ?>
                    <article class="card">
                        <div class="card-top">
                            <span class="tag"><?= h($product['category']) ?></span>
                            <?php if (!empty($product['featured'])): ?>
                                <span class="featured"><?= h($copy['featured']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= h($product['name'][$lang] ?? '') ?></h3>
                        <p class="summary"><?= h($product['summary'][$lang] ?? '') ?></p>
                        <pre class="specs"><?= h($product['specs'][$lang] ?? '') ?></pre>
                        <div class="price-block">
                            <div>
                                <span><?= h($copy['starting_from']) ?></span>
                                <strong><?= h($product['price']['discount'] ?? '') ?></strong>
                            </div>
                            <div class="muted">
                                <span><?= h($copy['original_price']) ?></span>
                                <em><?= h($product['price']['original'] ?? '') ?></em>
                            </div>
                        </div>
                        <a class="button outline" href="<?= h($product['order_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">
                            <?= h($copy['order_now']) ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="faq" class="section alt">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <p class="eyebrow"><?= h($copy['nav_faq']) ?></p>
                    <h2><?= h($copy['faq_title']) ?></h2>
                </div>
                <p><?= h($copy['faq_intro']) ?></p>
            </div>
            <div class="faq-list">
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item">
                        <summary><?= h($faq['question'][$lang] ?? '') ?></summary>
                        <p><?= h($faq['answer'][$lang] ?? '') ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section contact">
        <div class="wrap contact-grid">
            <div>
                <p class="eyebrow"><?= h($copy['nav_contact']) ?></p>
                <h2><?= h($contact['title']) ?></h2>
                <p><?= h($contact['intro']) ?></p>
            </div>
            <div class="contact-card">
                <div>
                    <span><?= h($contact['email_label']) ?></span>
                    <strong><?= h($contact['email_value']) ?></strong>
                </div>
                <div>
                    <span><?= h($contact['phone_label']) ?></span>
                    <strong><?= h($contact['phone_value']) ?></strong>
                </div>
                <div>
                    <span><?= h($contact['address_label']) ?></span>
                    <strong><?= h($contact['address_value']) ?></strong>
                </div>
                <a class="button" href="mailto:<?= h($contact['email_value']) ?>"><?= h($copy['contact_cta']) ?></a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
