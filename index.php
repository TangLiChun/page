<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$lang = current_lang();
$copy = labels()[$lang];
$data = site_data();
$seo = $data['seo'][$lang];
$hero = $data['hero'][$lang];
$contact = $data['contact'][$lang];
$allProducts = site_products($data, true);
$categories = product_categories($allProducts);
$activeCategory = requested_category($categories);
$products = $activeCategory === ''
    ? $allProducts
    : array_values(array_filter(
        $allProducts,
        static fn (array $product): bool => ($product['category'] ?? '') === $activeCategory
    ));
$faqs = $data['faqs'];
$heroMetrics = $data['hero']['metrics'] ?? [];

$serviceHighlights = [
    'zh' => [
        ['title' => '更快上线', 'text' => 'VPS、整机与机柜统一展示，适合广告投放后的快速转化。'],
        ['title' => '更清楚对比', 'text' => '按品类筛选、价格分层和卖点标签，让客户几秒内抓到重点。'],
        ['title' => '更好沟通', 'text' => 'FAQ、邮箱、电话、Telegram、WhatsApp 全部集中在一个入口。'],
    ],
    'en' => [
        ['title' => 'Launch Faster', 'text' => 'Present VPS, dedicated servers, and colocation in one streamlined flow.'],
        ['title' => 'Compare Quickly', 'text' => 'Clear categories, promo pricing, and highlights help visitors decide faster.'],
        ['title' => 'Talk Immediately', 'text' => 'FAQ and direct contact channels stay visible throughout the journey.'],
    ],
];

$salesSteps = [
    'zh' => [
        '浏览产品类型与促销价格',
        '查看配置卖点与常见问题',
        '通过订单链接或联系方式快速转化',
    ],
    'en' => [
        'Browse plans and promo pricing',
        'Review specs, highlights, and FAQs',
        'Convert via order links or direct contact',
    ],
];

$trustMarks = [
    'zh' => ['Global Transit', 'Low Latency Routing', '24/7 Support Desk', 'Flexible Billing'],
    'en' => ['Global Transit', 'Low-Latency Routing', '24/7 Support Desk', 'Flexible Billing'],
];

$comparisonCards = [
    [
        'category' => 'VPS',
        'title' => [
            'zh' => '适合快速上线与敏捷试错',
            'en' => 'Built for fast launches and agile testing',
        ],
        'text' => [
            'zh' => '适合业务验证、轻量生产和海外节点部署，用更低门槛完成第一阶段扩容。',
            'en' => 'Ideal for validating new products, lightweight production, and international edge deployments.',
        ],
    ],
    [
        'category' => 'Colocation',
        'title' => [
            'zh' => '适合已有设备与长期稳定托管',
            'en' => 'Best for your own hardware and long-term hosting',
        ],
        'text' => [
            'zh' => '如果你已经有服务器资产，希望获得更稳定的电力、网络和机房环境，托管会更合适。',
            'en' => 'Use colocation when you already own servers and want dependable power, network, and rack conditions.',
        ],
    ],
    [
        'category' => 'Dedicated Server',
        'title' => [
            'zh' => '适合高负载业务与独享资源',
            'en' => 'Designed for heavy workloads and dedicated resources',
        ],
        'text' => [
            'zh' => '适合数据库、高并发站点、游戏、AI 推理与需要整机性能的生产业务。',
            'en' => 'Great for databases, high-traffic sites, games, AI inference, and performance-sensitive production stacks.',
        ],
    ],
];

$proofQuotes = [
    'zh' => [
        [
            'quote' => '从广告点击到咨询，页面节奏很顺，客户能很快理解 VPS 和整机的区别。',
            'author' => '出海 SaaS 团队',
        ],
        [
            'quote' => '联系方式和 FAQ 放得更合理之后，售前重复解释明显少了。',
            'author' => '基础设施服务商运营',
        ],
    ],
    'en' => [
        [
            'quote' => 'The page now guides visitors from pricing to inquiry much faster, especially across VPS and dedicated plans.',
            'author' => 'Global SaaS Team',
        ],
        [
            'quote' => 'Once FAQ and contact blocks were tightened up, our pre-sales conversations became much more efficient.',
            'author' => 'Infrastructure Ops Lead',
        ],
    ],
];

$finalCta = [
    'zh' => [
        'title' => '需要我们帮你选合适的方案？',
        'text' => '告诉我们你的业务类型、预估流量和部署区域，我们会给你一个更适合的配置建议。',
        'button' => '联系售前团队',
    ],
    'en' => [
        'title' => 'Need help choosing the right plan?',
        'text' => 'Tell us about your workload, traffic expectations, and preferred region, and we will suggest a better-fit setup.',
        'button' => 'Contact Sales',
    ],
];

$planSignals = [
    'VPS' => [
        'zh' => '快速开通',
        'en' => 'Fast deployment',
    ],
    'Colocation' => [
        'zh' => '机房托管',
        'en' => 'Rack-ready',
    ],
    'Dedicated Server' => [
        'zh' => '独享整机',
        'en' => 'Bare metal',
    ],
];

$planBilling = [
    'zh' => '按月计费，可咨询年付优惠',
    'en' => 'Monthly billing, annual discounts available',
];

$planChoiceHint = [
    'zh' => '适合正在比较性能、预算与交付速度的团队',
    'en' => 'Built for teams balancing performance, budget, and launch speed',
];

$heroMicroSignals = [
    'zh' => ['支持快速报价', '支持部署建议', '支持海外业务咨询'],
    'en' => ['Fast quoting', 'Deployment guidance', 'International workload support'],
];

$heroProof = [
    'zh' => [
        'label' => '售前节奏',
        'title' => '从看方案到联系销售，尽量少走弯路',
        'text' => '首屏先讲清楚产品方向、交付能力和联系动作，让真正有意向的客户更快进入咨询。',
    ],
    'en' => [
        'label' => 'Sales flow',
        'title' => 'Move visitors from evaluation to inquiry with less friction',
        'text' => 'The first screen explains plan types, operational trust, and the next step clearly, so qualified buyers reach out faster.',
    ],
];
?>
<!DOCTYPE html>
<html lang="<?= h($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($seo['title'] ?? config('site_name')) ?></title>
    <meta name="description" content="<?= h($seo['description'] ?? '') ?>">
    <link rel="stylesheet" href="<?= h(asset('public/styles.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="wrap nav">
        <a class="brand" href="#top">
            <span class="brand-mark"></span>
            <span><?= h(config('site_name')) ?></span>
        </a>
        <nav class="nav-links">
            <a href="#products"><?= h($copy['nav_products']) ?></a>
            <a href="#faq"><?= h($copy['nav_faq']) ?></a>
            <a href="#contact"><?= h($copy['nav_contact']) ?></a>
        </nav>
        <div class="header-tools">
            <div class="lang-switch">
                <a class="<?= $lang === 'zh' ? 'active' : '' ?>" href="<?= h(switch_lang_url('zh')) ?>">中文</a>
                <a class="<?= $lang === 'en' ? 'active' : '' ?>" href="<?= h(switch_lang_url('en')) ?>">EN</a>
            </div>
            <a class="admin-link" href="<?= h(url('admin/login.php')) ?>">Admin</a>
        </div>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="wrap hero-shell">
            <div class="hero-copy">
                <span class="badge"><?= h($copy['hero_badge']) ?></span>
                <h1><?= h($hero['title']) ?></h1>
                <p class="hero-text"><?= h($hero['subtitle']) ?></p>
                <div class="hero-actions">
                    <a class="button" href="#products"><?= h($hero['cta']) ?></a>
                    <a class="button ghost" href="#contact"><?= h($copy['contact_cta']) ?></a>
                </div>
                <div class="hero-micro-signals">
                    <?php foreach (($heroMicroSignals[$lang] ?? []) as $signal): ?>
                        <span><?= h($signal) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="hero-notes">
                    <?php foreach (($serviceHighlights[$lang] ?? []) as $highlight): ?>
                        <article class="note-card">
                            <h2><?= h($highlight['title']) ?></h2>
                            <p><?= h($highlight['text']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="hero-aside">
                <section class="hero-panel">
                    <div class="hero-panel-head">
                        <p class="eyebrow"><?= h($copy['products_title']) ?></p>
                        <h2><?= h($copy['products_intro']) ?></h2>
                    </div>
                    <div class="metric-grid">
                        <?php foreach ($heroMetrics as $metric): ?>
                            <div class="metric">
                                <strong><?= h($metric['value'] ?? '') ?></strong>
                                <span><?= h($metric['label'][$lang] ?? '') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="journey-card">
                    <p class="eyebrow"><?= h($copy['contact_cta']) ?></p>
                    <ol>
                        <?php foreach (($salesSteps[$lang] ?? []) as $step): ?>
                            <li><?= h($step) ?></li>
                        <?php endforeach; ?>
                    </ol>
                </section>

                <section class="hero-proof-card">
                    <p class="eyebrow"><?= h($heroProof[$lang]['label']) ?></p>
                    <h2><?= h($heroProof[$lang]['title']) ?></h2>
                    <p><?= h($heroProof[$lang]['text']) ?></p>
                </section>
            </aside>
        </div>
    </section>

    <section class="signal-bar">
        <div class="wrap signal-list">
            <?php foreach (($serviceHighlights[$lang] ?? []) as $highlight): ?>
                <div class="signal-item">
                    <strong><?= h($highlight['title']) ?></strong>
                    <span><?= h($highlight['text']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="trust-strip">
        <div class="wrap trust-shell">
            <span class="trust-label"><?= h($lang === 'zh' ? '团队常关注的交付能力' : 'Operational strengths teams care about') ?></span>
            <div class="trust-marks">
                <?php foreach (($trustMarks[$lang] ?? []) as $mark): ?>
                    <span><?= h($mark) ?></span>
                <?php endforeach; ?>
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

            <div class="catalog-toolbar">
                <div class="category-pills">
                    <a class="<?= $activeCategory === '' ? 'active' : '' ?>" href="<?= h(url('index.php')) ?>?lang=<?= h($lang) ?>#products">
                        <?= h($copy['products_all']) ?>
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a class="<?= $activeCategory === $category ? 'active' : '' ?>" href="<?= h(url('index.php')) ?>?lang=<?= h($lang) ?>&category=<?= urlencode($category) ?>#products">
                            <?= h($category) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="catalog-summary">
                    <strong><?= h((string) count($products)) ?></strong>
                    <span><?= h($lang === 'zh' ? '个方案可选' : 'plans available') ?></span>
                </div>
            </div>

            <?php if ($products === []): ?>
                <div class="empty-state"><?= h($copy['products_empty']) ?></div>
            <?php else: ?>
                <div class="cards">
                    <?php foreach ($products as $product): ?>
                        <?php $categoryClass = strtolower(str_replace(' ', '-', (string) ($product['category'] ?? 'general'))); ?>
                        <article class="card card-<?= h($categoryClass) ?> <?= !empty($product['featured']) ? 'card-featured' : '' ?>" id="<?= h($product['id']) ?>">
                            <div class="card-top">
                                <span class="tag"><?= h($product['category']) ?></span>
                                <?php if (!empty($product['featured'])): ?>
                                    <span class="featured"><?= h($copy['featured']) ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($product['image_url'])): ?>
                                <div class="card-image">
                                    <span class="image-badge"><?= h($planSignals[$product['category']][$lang] ?? ($lang === 'zh' ? '企业级方案' : 'Enterprise ready')) ?></span>
                                    <img src="<?= h($product['image_url']) ?>" alt="<?= h($product['name'][$lang] ?? '') ?>">
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <p class="card-kicker"><?= h($planChoiceHint[$lang]) ?></p>
                                <h3><?= h($product['name'][$lang] ?? '') ?></h3>
                                <p class="summary"><?= h($product['summary'][$lang] ?? '') ?></p>

                                <?php if (!empty($product['highlights'][$lang])): ?>
                                    <ul class="highlights">
                                        <?php foreach (($product['highlights'][$lang] ?? []) as $highlight): ?>
                                            <li><?= h($highlight) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <pre class="specs"><?= h($product['specs'][$lang] ?? '') ?></pre>
                            </div>

                            <div class="card-footer">
                                <div class="price-block">
                                    <div>
                                        <span><?= h($copy['starting_from']) ?></span>
                                        <strong><?= h($product['price']['discount'] ?? '') ?></strong>
                                        <small class="price-note"><?= h($planBilling[$lang]) ?></small>
                                    </div>
                                    <div class="muted">
                                        <span><?= h($copy['original_price']) ?></span>
                                        <em><?= h($product['price']['original'] ?? '') ?></em>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a class="button ghost" href="#contact"><?= h($copy['view_details']) ?></a>
                                    <a class="button" href="<?= h($product['order_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">
                                        <?= h($copy['order_now']) ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section compare-section">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <p class="eyebrow"><?= h($lang === 'zh' ? 'Why These Plans' : 'Why These Plans') ?></p>
                    <h2><?= h($lang === 'zh' ? '先选方向，再选配置' : 'Choose the model first, then the specs') ?></h2>
                </div>
                <p><?= h($lang === 'zh' ? '很多客户不是看不懂配置，而是不知道先选 VPS、托管还是整机。这里先帮他们建立判断框架。' : 'Many buyers do not struggle with specs. They struggle with choosing between VPS, colocation, and dedicated servers first.') ?></p>
            </div>

            <div class="compare-grid">
                <?php foreach ($comparisonCards as $card): ?>
                    <article class="compare-card">
                        <span class="tag"><?= h($card['category']) ?></span>
                        <h3><?= h($card['title'][$lang]) ?></h3>
                        <p><?= h($card['text'][$lang]) ?></p>
                        <a href="#products"><?= h($lang === 'zh' ? '查看对应方案' : 'View matching plans') ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="faq" class="section faq-section">
        <div class="wrap faq-layout">
            <div class="faq-intro">
                <p class="eyebrow"><?= h($copy['nav_faq']) ?></p>
                <h2><?= h($copy['faq_title']) ?></h2>
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

    <section class="section proof-section">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <p class="eyebrow"><?= h($lang === 'zh' ? 'Sales Proof' : 'Sales Proof') ?></p>
                    <h2><?= h($lang === 'zh' ? '页面不只要好看，还要更容易成交' : 'The page should not just look better. It should convert better.') ?></h2>
                </div>
                <p><?= h($lang === 'zh' ? '这类产品页最重要的是让客户快速理解差异、价格和下一步动作。' : 'For infrastructure offers, the page has to clarify tradeoffs, pricing, and the next action fast.') ?></p>
            </div>

            <div class="proof-grid">
                <?php foreach (($proofQuotes[$lang] ?? []) as $quote): ?>
                    <article class="proof-card">
                        <p><?= h($quote['quote']) ?></p>
                        <strong><?= h($quote['author']) ?></strong>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section contact">
        <div class="wrap contact-layout">
            <div class="contact-copy">
                <p class="eyebrow"><?= h($copy['nav_contact']) ?></p>
                <h2><?= h($contact['title']) ?></h2>
                <p><?= h($contact['intro']) ?></p>
                <a class="button" href="mailto:<?= h($contact['email_value']) ?>"><?= h($copy['contact_cta']) ?></a>
            </div>

            <div class="contact-grid">
                <article class="contact-card">
                    <span><?= h($contact['email_label']) ?></span>
                    <strong><a href="mailto:<?= h($contact['email_value']) ?>"><?= h($contact['email_value']) ?></a></strong>
                </article>
                <article class="contact-card">
                    <span><?= h($contact['phone_label']) ?></span>
                    <strong><a href="tel:<?= h(preg_replace('/\s+/', '', $contact['phone_value'])) ?>"><?= h($contact['phone_value']) ?></a></strong>
                </article>
                <?php if (!empty($contact['telegram_value'])): ?>
                    <article class="contact-card">
                        <span><?= h($contact['telegram_label']) ?></span>
                        <strong><a href="<?= h($contact['telegram_value']) ?>" target="_blank" rel="noopener noreferrer"><?= h($contact['telegram_value']) ?></a></strong>
                    </article>
                <?php endif; ?>
                <?php if (!empty($contact['whatsapp_value'])): ?>
                    <article class="contact-card">
                        <span><?= h($contact['whatsapp_label']) ?></span>
                        <strong><a href="<?= h($contact['whatsapp_value']) ?>" target="_blank" rel="noopener noreferrer"><?= h($contact['whatsapp_value']) ?></a></strong>
                    </article>
                <?php endif; ?>
                <article class="contact-card contact-card-wide">
                    <span><?= h($contact['address_label']) ?></span>
                    <strong><?= h($contact['address_value']) ?></strong>
                </article>
            </div>
        </div>
    </section>

    <section class="section final-cta-section">
        <div class="wrap">
            <div class="final-cta-card">
                <div>
                    <p class="eyebrow"><?= h($copy['contact_cta']) ?></p>
                    <h2><?= h($finalCta[$lang]['title']) ?></h2>
                    <p><?= h($finalCta[$lang]['text']) ?></p>
                </div>
                <a class="button" href="mailto:<?= h($contact['email_value']) ?>"><?= h($finalCta[$lang]['button']) ?></a>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="wrap footer-inner">
        <span><?= h(config('site_name')) ?></span>
        <span><?= h($copy['footer_text']) ?></span>
    </div>
</footer>
</body>
</html>
