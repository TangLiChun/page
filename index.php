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

$fabricData = [
    'zh' => [
        'title' => '为持续运转而生',
        'subtitle' => '多核边缘路由、A+B冗余电源、叶脊网络架构，这就是我们承诺可用性的底气。',
        'items' => [
            [
                'title' => '多核边缘与路由',
                'text' => '主备边界路由器，100G/400G上行链路。真实的运营商中立混合网络，保证全球延迟最低。',
                'points' => ['eBGP直连Tier-1 (AT&T, Cogent, HE.net)', '支持BGP黑洞与DDoS清洗', '本地优先权重与MED流量工程'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.15"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>'
            ],
            [
                'title' => '数据中心 Fabric 网络',
                'text' => '高级 Leaf-spine 架构，结合 EVPN/VXLAN，实现高度可扩展的二/三层网络隔离。',
                'points' => ['全链路 MLAG/LACP', '机柜内双路服务器独立上游', '带外网管与紧急访问支持'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>'
            ],
            [
                'title' => '供电与连续性',
                'text' => '从独立的 UPS (N+1 / N+N) 到每一个机柜提供真正的 A+B 供电。确保硬件永远免受限电困扰。',
                'points' => ['N+1 自动按需启动的发电机群', '双变电站交叉馈电', '为发电机启动争取时间的超大电池集群'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.1"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>'
            ]
        ]
    ],
    'en' => [
        'title' => 'Built for Uptime and Scale',
        'subtitle' => 'Multi-core edge routing, A+B redundant power, and leaf-spine fabrics are why we can promise true availability.',
        'items' => [
            [
                'title' => 'Multi-Core Edge & Routing',
                'text' => 'Redundant border routers operating active/active with 100G/400G uplinks. True carrier-neutral blended network.',
                'points' => ['eBGP to Tier-1s (AT&T, Cogent, HE.net)', 'Optional DDoS mitigation via BGP diversion', 'Traffic engineering via local-pref/MED'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.15"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>'
            ],
            [
                'title' => 'Data Center Fabric',
                'text' => 'Advanced Leaf-spine fabric with EVPN/VXLAN for highly scalable L2/L3 network segmentation.',
                'points' => ['MLAG/LACP to ToR switches', 'Per-rack dual-homed server links', 'Out-of-band management network'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>'
            ],
            [
                'title' => 'Power & Continuity',
                'text' => 'True A+B power fed to every cabinet from independent UPS strings. Unmatched reliability.',
                'points' => ['Generators in N+1 with automated start', 'Dual-substation cross-feed', 'Battery autonomy sized for generator ride-through'],
                'svg' => '<svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="parallax-item" data-speed="0.1"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>'
            ]
        ]
    ]
];

$mapData = [
    'zh' => [
        'title' => '全球数据中心 footprint',
        'subtitle' => '多区域机房布局，交付最低的互联延迟体感。',
        'locations' => [
            ['name' => '达拉斯, TX', 'type' => 'active'],
            ['name' => '欧文, TX', 'type' => 'pre-lease'],
            ['name' => '威斯康星，WI', 'type' => 'active'],
            ['name' => '洛杉矶, CA', 'type' => 'active'],
            ['name' => '芝加哥, IL', 'type' => 'active'],
            ['name' => '水牛城, NY', 'type' => 'active']
        ]
    ],
    'en' => [
        'title' => 'Global Data Centers',
        'subtitle' => 'Delivering premium experiences with our diverse regional footprints.',
        'locations' => [
            ['name' => 'Dallas, TX', 'type' => 'active'],
            ['name' => 'Irving, TX', 'type' => 'pre-lease'],
            ['name' => 'Port Edwards, WI', 'type' => 'active'],
            ['name' => 'Los Angeles, CA', 'type' => 'active'],
            ['name' => 'Chicago, IL', 'type' => 'active'],
            ['name' => 'Buffalo, NY', 'type' => 'active']
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= h($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($seo['title'] ?? config('site_name')) ?></title>
    <meta name="description" content="<?= h($seo['description'] ?? '') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= h(asset('public/styles.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="wrap nav">
        <a class="brand" href="#top">
            <span class="brand-mark"></span>
            <span><?= h(config('site_name')) ?></span>
        </a>
        <nav class="nav-links" id="main-nav">
            <a href="#products" data-nav><?= h($copy['nav_products']) ?></a>
            <a href="#faq" data-nav><?= h($copy['nav_faq']) ?></a>
            <a href="#contact" data-nav><?= h($copy['nav_contact']) ?></a>
        </nav>
        <div class="header-tools">
            <div class="lang-switch">
                <a class="<?= $lang === 'zh' ? 'active' : '' ?>" href="<?= h(switch_lang_url('zh')) ?>">中文</a>
                <a class="<?= $lang === 'en' ? 'active' : '' ?>" href="<?= h(switch_lang_url('en')) ?>">EN</a>
            </div>
            <a class="admin-link" href="<?= h(url('admin/login.php')) ?>">Admin</a>
            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu">☰</button>
        </div>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="wrap hero-shell">
            <div class="hero-copy animate-stagger">
                <span class="badge animate-on-scroll"><?= h($copy['hero_badge']) ?></span>
                <h1 class="animate-on-scroll"><?= h($hero['title']) ?></h1>
                <p class="hero-text animate-on-scroll"><?= h($hero['subtitle']) ?></p>
                <div class="hero-actions animate-on-scroll">
                    <a class="button" href="#products"><?= h($hero['cta']) ?></a>
                    <a class="button ghost" href="#contact"><?= h($copy['contact_cta']) ?></a>
                </div>
                <div class="hero-micro-signals animate-stagger">
                    <?php foreach (($heroMicroSignals[$lang] ?? []) as $signal): ?>
                        <span class="animate-on-scroll"><?= h($signal) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="metric-grid animate-stagger">
                <?php foreach ($heroMetrics as $metric): ?>
                    <div class="metric animate-on-scroll">
                        <strong><?= h($metric['value'] ?? '') ?></strong>
                        <span><?= h($metric['label'][$lang] ?? '') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="signal-bar">
        <div class="wrap signal-list animate-stagger">
            <?php foreach (($serviceHighlights[$lang] ?? []) as $highlight): ?>
                <div class="signal-item animate-on-scroll">
                    <strong><?= h($highlight['title']) ?></strong>
                    <span><?= h($highlight['text']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <section id="products" class="section">
        <div class="wrap">
            <div class="section-head animate-on-scroll">
                <p class="eyebrow"><?= h($copy['nav_products']) ?></p>
                <h2><?= h($copy['products_title']) ?></h2>
                <p><?= h($copy['products_intro']) ?></p>
            </div>

            <div class="catalog-toolbar animate-on-scroll">
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
                <div class="cards animate-stagger">
                    <?php foreach ($products as $product): ?>
                        <?php $categoryClass = strtolower(str_replace(' ', '-', (string) ($product['category'] ?? 'general'))); ?>
                        <article class="card card-<?= h($categoryClass) ?> <?= !empty($product['featured']) ? 'card-featured' : '' ?> animate-on-scroll" id="<?= h($product['id']) ?>">
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
                                <div class="specs">
                                    <?php if (!empty($product['specs'])): ?>
                                        <?php
                                        $specsList = explode("\n", (string)$product['specs']);
                                        foreach ($specsList as $specLine) {
                                            $specLine = trim($specLine);
                                            if (!$specLine) continue;
                                            $parts = explode(':', $specLine, 2);
                                            if (count($parts) === 2) {
                                                echo '<div class="specs-item"><span>' . h(trim($parts[0])) . '</span><span>' . h(trim($parts[1])) . '</span></div>';
                                            } else {
                                                echo '<div class="specs-item"><span>' . h($specLine) . '</span><span></span></div>';
                                            }
                                        }
                                        ?>
                                    <?php endif; ?>
                                </div>
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
            <div class="section-head animate-on-scroll">
                <p class="eyebrow"><?= h($lang === 'zh' ? 'Why These Plans' : 'Why These Plans') ?></p>
                <h2><?= h($lang === 'zh' ? '不要为多余的算力买单' : 'Don\'t pay for idle compute') ?></h2>
                <p><?= h($lang === 'zh' ? '我们提供三种截然不同的架构体系，针对不同阶段的业务进行精确打击，彻底消除资源闲置。' : 'We provide three completely different architecture systems, targeting businesses at different stages to completely eliminate resource idle.') ?></p>
            </div>

            <div class="compare-grid animate-stagger">
                <?php foreach ($comparisonCards as $card): ?>
                    <article class="compare-card animate-on-scroll">
                        <span class="tag"><?= h($card['category']) ?></span>
                        <h3><?= h($card['title'][$lang]) ?></h3>
                        <p><?= h($card['text'][$lang]) ?></p>
                        <a href="#products"><?= h($lang === 'zh' ? '查看对应方案' : 'View matching plans') ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Cloudnium Fabric Integration -->
    <section class="section fabric parallax-container" id="infrastructure">
        <div class="wrap">
            <div class="section-head animate-on-scroll parallax-layer" data-speed="-0.03">
                <p class="eyebrow">Infrastructure</p>
                <h2><?= h($fabricData[$lang]['title']) ?></h2>
                <p><?= h($fabricData[$lang]['subtitle']) ?></p>
            </div>

            <?php foreach (($fabricData[$lang]['items'] ?? []) as $index => $item): ?>
            <div class="fabric-row parallax-layer" data-speed="-0.0<?= h(rand(3,6)) ?>">
                <div class="fabric-content animate-on-scroll">
                    <h3><?= h($item['title']) ?></h3>
                    <p><?= h($item['text']) ?></p>
                    <ul>
                        <?php foreach($item['points'] as $pt): ?>
                        <li><?= h($pt) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="fabric-visual animate-on-scroll" style="color: var(--accent);">
                    <?= $item['svg'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section map-section parallax-container">
        <div class="wrap">
            <div class="section-head animate-on-scroll" style="align-items: center; text-align: center;">
                <p class="eyebrow">Data Centers</p>
                <h2 style="position: static; transform: none; left: auto; max-width: 100%;"><?= h($mapData[$lang]['title']) ?></h2>
                <p><?= h($mapData[$lang]['subtitle']) ?></p>
            </div>
            
            <div class="locations animate-stagger">
                <?php foreach (($mapData[$lang]['locations'] ?? []) as $i => $loc): ?>
                <div class="location-tag animate-on-scroll parallax-layer" data-speed="<?= h(rand(-6, 6) / 100) ?>">
                    <span class="dot <?= $loc['type'] === 'pre-lease' ? 'pre-lease' : '' ?>"></span> 
                    <?= h($loc['name']) ?>
                    <?php if ($loc['type'] === 'pre-lease'): ?>
                    <span style="font-size:0.75rem; color:var(--muted);">(Pre-lease)</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- End Cloudnium Fabric Integration -->

    <section id="faq" class="section faq-section">
        <div class="wrap faq-layout">
            <div class="faq-intro animate-on-scroll">
                <p class="eyebrow"><?= h($copy['nav_faq']) ?></p>
                <h2><?= h($copy['faq_title']) ?></h2>
                <p><?= h($copy['faq_intro']) ?></p>
                
                <div class="metric-grid" style="grid-template-columns: 1fr; margin-top: 2rem;">
                    <?php foreach (($metrics[$lang] ?? []) as $metric): ?>
                        <div class="metric">
                            <strong><?= h($metric['value']) ?></strong>
                            <span><?= h($metric['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="faq-list animate-stagger">
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item animate-on-scroll">
                        <summary><?= h($faq['question'][$lang] ?? '') ?></summary>
                        <p><?= h($faq['answer'][$lang] ?? '') ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section proof-section">
        <div class="wrap">
            <div class="section-head animate-on-scroll">
                <p class="eyebrow"><?= h($lang === 'zh' ? 'Trust' : 'Trust') ?></p>
                <h2><?= h($lang === 'zh' ? '硬核的基础设施保证' : 'Hardcore Infrastructure Guarantee') ?></h2>
                <p><?= h($lang === 'zh' ? '这不是营销话语，这是我们对运行时间的严肃承诺。数百家企业级客户将核心业务交托于我们。' : 'This is not marketing talk, this is our serious commitment to uptime. Hundreds of enterprise clients entrust their core business to us.') ?></p>
            </div>

            <div class="proof-grid animate-stagger">
                <?php foreach (($proofQuotes[$lang] ?? []) as $quote): ?>
                    <article class="proof-card animate-on-scroll">
                        <p><?= h($quote['quote']) ?></p>
                        <strong><?= h($quote['author']) ?></strong>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="section final-cta-section">
        <div class="wrap">
            <div class="final-cta-card animate-on-scroll">
                <p class="eyebrow"><?= h($copy['contact_cta']) ?></p>
                <h2><?= h($finalCta[$lang]['title']) ?></h2>
                <p><?= h($finalCta[$lang]['text']) ?></p>
                <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a class="button" href="mailto:<?= h($contact['email_value']) ?>"><?= h($finalCta[$lang]['button']) ?></a>
                    <?php if (!empty($contact['telegram_value'])): ?>
                        <a class="button ghost" href="<?= h($contact['telegram_value']) ?>" target="_blank" rel="noopener noreferrer">Telegram</a>
                    <?php endif; ?>
                </div>
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

<script>
(function() {
    'use strict';

    // ── 1. Scroll Animation (IntersectionObserver) ──
    const animEls = document.querySelectorAll('.animate-on-scroll');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        animEls.forEach(function(el) { observer.observe(el); });
    } else {
        animEls.forEach(function(el) { el.classList.add('is-visible'); });
    }

    // ── 2. Header Scroll Effect ──
    var header = document.querySelector('.site-header');
    var scrollThreshold = 60;
    function updateHeader() {
        if (window.scrollY > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();

    // ── 3. Navigation Active Highlighting ──
    var navLinks = document.querySelectorAll('[data-nav]');
    var sections = [];
    navLinks.forEach(function(link) {
        var id = link.getAttribute('href').replace('#', '');
        var section = document.getElementById(id);
        if (section) sections.push({ el: section, link: link });
    });

    function highlightNav() {
        var scrollPos = window.scrollY + 200;
        var current = null;
        sections.forEach(function(s) {
            if (s.el.offsetTop <= scrollPos) current = s;
        });
        navLinks.forEach(function(l) { l.classList.remove('active'); });
        if (current) current.link.classList.add('active');
    }
    window.addEventListener('scroll', highlightNav, { passive: true });
    highlightNav();

    // ── 4. Mobile Menu Toggle ──
    var menuBtn = document.getElementById('menu-toggle');
    var navMenu = document.getElementById('main-nav');
    if (menuBtn && navMenu) {
        menuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('open');
            menuBtn.textContent = navMenu.classList.contains('open') ? '✕' : '☰';
        });
        // Close on link click
        navMenu.querySelectorAll('a').forEach(function(a) {
            a.addEventListener('click', function() {
                navMenu.classList.remove('open');
                menuBtn.textContent = '☰';
            });
        });
    }

    // ── 5. Counter Animation for Metrics ──
    var metrics = document.querySelectorAll('.metric strong');
    var metricsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            var text = el.textContent.trim();
            // Try to extract number
            var match = text.match(/([\d.]+)/);
            if (match) {
                var target = parseFloat(match[1]);
                var suffix = text.replace(match[1], '');
                var isFloat = text.indexOf('.') !== -1;
                var start = 0;
                var duration = 1200;
                var startTime = null;
                function animate(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
                    var current = start + (target - start) * eased;
                    el.textContent = (isFloat ? current.toFixed(1) : Math.floor(current)) + suffix;
                    if (progress < 1) requestAnimationFrame(animate);
                }
                requestAnimationFrame(animate);
            }
            metricsObserver.unobserve(el);
        });
    }, { threshold: 0.5 });
    metrics.forEach(function(m) { metricsObserver.observe(m); });

    // ── 6. Smooth anchor scrolling with offset ──
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var id = this.getAttribute('href');
            if (id === '#top' || id === '#') return;
            var target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                var offset = header.offsetHeight + 20;
                var pos = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: pos, behavior: 'smooth' });
            }
        });
    });

    // ── 7. Parallax Scrolling Effect ──
    var parallaxContainers = document.querySelectorAll('.parallax-container, .hero');
    var parallaxLayers = document.querySelectorAll('.parallax-layer, .parallax-item');
    
    var lastScrollY = window.scrollY;
    var ticking = false;

    function doParallax() {
        var scrollY = window.scrollY;
        
        parallaxLayers.forEach(function(layer) {
            // Check if element is reasonably near to viewport
            var rect = layer.getBoundingClientRect();
            if (rect.top < window.innerHeight + 200 && rect.bottom > -200) {
                var speed = parseFloat(layer.getAttribute('data-speed') || '0.1');
                // Calculate offset from the middle of the screen
                // Use absolute page coordinates or viewport to ensure smooth direction
                var yPos = -(scrollY * speed);
                layer.style.transform = 'translateY(' + yPos + 'px)';
            }
        });
        
        ticking = false;
    }

    if (window.innerWidth > 768) { // Only enable on desktop
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    doParallax();
                });
                ticking = true;
            }
        }, { passive: true });
        doParallax(); // initial kick
    }
})();
</script>
</body>
</html>
