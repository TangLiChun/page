<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

require_admin();

$data = site_data();
$flash = pull_flash();
$validationErrors = pull_validation_errors();
$oldInput = pull_old_input();
$editProductId = $_GET['edit_product'] ?? null;
$editFaqId = $_GET['edit_faq'] ?? null;

$editingProduct = null;
foreach ($data['products'] as $product) {
    if ($product['id'] === $editProductId) {
        $editingProduct = $product;
        break;
    }
}

$editingFaq = null;
foreach ($data['faqs'] as $faq) {
    if ($faq['id'] === $editFaqId) {
        $editingFaq = $faq;
        break;
    }
}

if ($editingProduct === null && $oldInput !== []) {
    $editingProduct = [
        'id' => $oldInput['id'] ?? '',
        'category' => $oldInput['category'] ?? '',
        'name' => [
            'zh' => $oldInput['name_zh'] ?? '',
            'en' => $oldInput['name_en'] ?? '',
        ],
        'summary' => [
            'zh' => $oldInput['summary_zh'] ?? '',
            'en' => $oldInput['summary_en'] ?? '',
        ],
        'specs' => [
            'zh' => $oldInput['specs_zh'] ?? '',
            'en' => $oldInput['specs_en'] ?? '',
        ],
        'price' => [
            'original' => $oldInput['price_original'] ?? '',
            'discount' => $oldInput['price_discount'] ?? '',
        ],
        'highlights' => [
            'zh' => normalize_highlights((string) ($oldInput['highlights_zh'] ?? '')),
            'en' => normalize_highlights((string) ($oldInput['highlights_en'] ?? '')),
        ],
        'image_url' => $oldInput['image_url'] ?? '',
        'order_url' => $oldInput['order_url'] ?? '',
        'featured' => !empty($oldInput['featured']),
        'enabled' => array_key_exists('enabled', $oldInput) ? !empty($oldInput['enabled']) : true,
        'sort_order' => $oldInput['sort_order'] ?? '100',
    ];
}

$products = site_products($data, false);
$liveProducts = site_products($data, true);
$liveProductCount = count($liveProducts);
$featuredCount = count(array_filter($products, static fn (array $product): bool => !empty($product['featured'])));
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(labels()['zh']['admin_title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= h(asset('public/admin.css')) ?>">
</head>
<body class="admin-page">
    <header class="admin-header">
        <div class="admin-header-copy">
            <span class="admin-kicker">Operations Console</span>
            <h1><?= h(labels()['zh']['admin_title']) ?></h1>
            <p>把商品、FAQ、联系方式和首页文案放在一套更清晰的运营工作台里，日常维护会顺手很多。</p>
        </div>
        <div class="admin-actions">
            <a href="<?= h(url('index.php')) ?>" target="_blank" rel="noopener noreferrer">查看前台</a>
            <a href="<?= h(url('admin/logout.php')) ?>" class="secondary-action">退出登录</a>
        </div>
    </header>

    <main class="admin-shell">
        <aside class="admin-sidebar">
            <section class="sidebar-card">
                <h2>快捷导航</h2>
                <nav class="section-nav">
                    <a href="#products-form">📦 <?= $editingProduct ? '编辑商品' : '新增商品' ?></a>
                    <a href="#products-list">📋 商品列表</a>
                    <a href="#faq-form">❓ <?= $editingFaq ? '编辑 FAQ' : '新增 FAQ' ?></a>
                    <a href="#faq-list">📖 FAQ 列表</a>
                    <a href="#contact-settings">📞 联系方式</a>
                    <a href="#hero-settings">🎯 首页 Hero</a>
                    <a href="#seo-settings">🔍 指标与 SEO</a>
                    <a href="#security-settings">🔒 后台安全</a>
                </nav>
            </section>

            <section class="sidebar-card">
                <h2>运营摘要</h2>
                <ul class="sidebar-stats">
                    <li><strong><?= h((string) count($products)) ?></strong><span>商品总数</span></li>
                    <li><strong><?= h((string) $liveProductCount) ?></strong><span>当前上架</span></li>
                    <li><strong><?= h((string) $featuredCount) ?></strong><span>推荐商品</span></li>
                    <li><strong><?= h((string) count($data['faqs'])) ?></strong><span>FAQ 数量</span></li>
                </ul>
            </section>
        </aside>

        <div class="admin-content">
            <?php if ($flash): ?>
                <div class="flash <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
            <?php endif; ?>
            <?php if ($validationErrors !== []): ?>
                <div class="flash error">
                    <?php foreach ($validationErrors as $error): ?>
                        <div><?= h($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <section class="stats-grid">
                <article class="stat-card">
                    <span>商品总数</span>
                    <strong><?= h((string) count($products)) ?></strong>
                    <p>包含隐藏与推荐商品，方便统一整理产品矩阵。</p>
                </article>
                <article class="stat-card accent">
                    <span>前台可见</span>
                    <strong><?= h((string) $liveProductCount) ?></strong>
                    <p>只有已上架商品会出现在前台展示页与分类筛选中。</p>
                </article>
                <article class="stat-card">
                    <span>FAQ / 联系入口</span>
                    <strong><?= h((string) count($data['faqs'])) ?></strong>
                    <p>常见问题和联系渠道会直接影响咨询转化率，建议保持精简。</p>
                </article>
            </section>

            <section class="panel" id="products-form">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">📦 Product Editor</p>
                        <h2><?= $editingProduct ? '编辑商品' : '新增商品' ?></h2>
                    </div>
                    <p>核心字段放在同一个表单里，先填分类、价格与链接，再补中英文内容与卖点。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="save_product">
                    <input type="hidden" name="id" value="<?= h($editingProduct['id'] ?? '') ?>">

                    <label>
                        <span>产品分类</span>
                        <input type="text" name="category" value="<?= h($editingProduct['category'] ?? '') ?>" placeholder="VPS" required>
                    </label>
                    <label>
                        <span>订购链接</span>
                        <input type="url" name="order_url" value="<?= h($editingProduct['order_url'] ?? '') ?>" placeholder="https://example.com/order" required>
                    </label>
                    <label>
                        <span>商品图片 URL</span>
                        <input type="url" name="image_url" value="<?= h($editingProduct['image_url'] ?? '') ?>" placeholder="https://example.com/product.jpg">
                    </label>
                    <label>
                        <span>排序值</span>
                        <input type="number" name="sort_order" value="<?= h((string) ($editingProduct['sort_order'] ?? 100)) ?>" min="0" step="1" required>
                    </label>
                    <label>
                        <span>原价</span>
                        <input type="text" name="price_original" value="<?= h($editingProduct['price']['original'] ?? '') ?>" placeholder="$99/mo" required>
                    </label>
                    <label>
                        <span>优惠价</span>
                        <input type="text" name="price_discount" value="<?= h($editingProduct['price']['discount'] ?? '') ?>" placeholder="$79/mo" required>
                    </label>

                    <label class="checkbox">
                        <input type="checkbox" name="featured" value="1" <?= !empty($editingProduct['featured']) ? 'checked' : '' ?>>
                        <span>标记为推荐产品</span>
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" name="enabled" value="1" <?= !array_key_exists('enabled', $editingProduct ?? []) || !empty($editingProduct['enabled']) ? 'checked' : '' ?>>
                        <span>前台显示此商品</span>
                    </label>

                    <label>
                        <span>中文名称</span>
                        <input type="text" name="name_zh" value="<?= h($editingProduct['name']['zh'] ?? '') ?>" required>
                    </label>
                    <label>
                        <span>英文名称</span>
                        <input type="text" name="name_en" value="<?= h($editingProduct['name']['en'] ?? '') ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>中文简介</span>
                        <textarea name="summary_zh" rows="3" required><?= h($editingProduct['summary']['zh'] ?? '') ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>英文简介</span>
                        <textarea name="summary_en" rows="3" required><?= h($editingProduct['summary']['en'] ?? '') ?></textarea>
                    </label>
                    <label>
                        <span>中文配置</span>
                        <textarea name="specs_zh" rows="5" required><?= h($editingProduct['specs']['zh'] ?? '') ?></textarea>
                    </label>
                    <label>
                        <span>英文配置</span>
                        <textarea name="specs_en" rows="5" required><?= h($editingProduct['specs']['en'] ?? '') ?></textarea>
                    </label>
                    <label>
                        <span>中文卖点</span>
                        <textarea name="highlights_zh" rows="5" placeholder="每行一个卖点"><?= h(highlights_to_text($editingProduct['highlights']['zh'] ?? [])) ?></textarea>
                    </label>
                    <label>
                        <span>英文卖点</span>
                        <textarea name="highlights_en" rows="5" placeholder="One highlight per line"><?= h(highlights_to_text($editingProduct['highlights']['en'] ?? [])) ?></textarea>
                    </label>
                    <div class="form-actions">
                        <button type="submit"><?= $editingProduct ? '保存商品' : '新增商品' ?></button>
                        <?php if ($editingProduct): ?>
                            <a href="<?= h(url('admin/dashboard.php')) ?>" class="secondary-action">取消编辑</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel" id="products-list">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">📋 Catalog</p>
                        <h2>商品列表</h2>
                    </div>
                    <p>按排序值与上架状态快速检查前台展示优先级。</p>
                </div>
                <div class="stack-list">
                    <?php foreach ($products as $product): ?>
                        <article class="item-card">
                            <div class="item-copy">
                                <h3><?= h($product['name']['zh']) ?> / <?= h($product['name']['en']) ?></h3>
                                <p><?= h($product['category']) ?> · <?= h($product['price']['discount']) ?> · 排序 <?= h((string) ($product['sort_order'] ?? 100)) ?></p>
                                <div class="item-badges">
                                    <span class="<?= !empty($product['enabled']) ? 'status-badge live' : 'status-badge hidden' ?>"><?= !empty($product['enabled']) ? '上架中' : '已隐藏' ?></span>
                                    <?php if (!empty($product['featured'])): ?>
                                        <span class="status-badge featured">推荐</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-actions">
                                <a href="<?= h(url('admin/dashboard.php')) ?>?edit_product=<?= h($product['id']) ?>">编辑</a>
                                <form method="post" action="<?= h(url('admin/save.php')) ?>" onsubmit="return confirm('确定删除该商品吗？');">
                                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete_product">
                                    <input type="hidden" name="id" value="<?= h($product['id']) ?>">
                                    <button type="submit" class="link-button danger">删除</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="panel" id="faq-form">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">❓ FAQ Editor</p>
                        <h2><?= $editingFaq ? '编辑 FAQ' : '新增 FAQ' ?></h2>
                    </div>
                    <p>FAQ 建议保持简短直接，优先回答价格、交付时间、售后和升级方式。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="save_faq">
                    <input type="hidden" name="id" value="<?= h($editingFaq['id'] ?? '') ?>">

                    <label>
                        <span>中文问题</span>
                        <input type="text" name="question_zh" value="<?= h($editingFaq['question']['zh'] ?? '') ?>" required>
                    </label>
                    <label>
                        <span>英文问题</span>
                        <input type="text" name="question_en" value="<?= h($editingFaq['question']['en'] ?? '') ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>中文回答</span>
                        <textarea name="answer_zh" rows="4" required><?= h($editingFaq['answer']['zh'] ?? '') ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>英文回答</span>
                        <textarea name="answer_en" rows="4" required><?= h($editingFaq['answer']['en'] ?? '') ?></textarea>
                    </label>
                    <div class="form-actions">
                        <button type="submit"><?= $editingFaq ? '保存 FAQ' : '新增 FAQ' ?></button>
                        <?php if ($editingFaq): ?>
                            <a href="<?= h(url('admin/dashboard.php')) ?>" class="secondary-action">取消编辑</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel" id="faq-list">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">📖 FAQ Library</p>
                        <h2>FAQ 列表</h2>
                    </div>
                    <p>问题按中文主标题展示，便于你快速回查和维护。</p>
                </div>
                <div class="stack-list">
                    <?php foreach ($data['faqs'] as $faq): ?>
                        <article class="item-card">
                            <div class="item-copy">
                                <h3><?= h($faq['question']['zh']) ?></h3>
                                <p><?= h($faq['question']['en']) ?></p>
                            </div>
                            <div class="item-actions">
                                <a href="<?= h(url('admin/dashboard.php')) ?>?edit_faq=<?= h($faq['id']) ?>">编辑</a>
                                <form method="post" action="<?= h(url('admin/save.php')) ?>" onsubmit="return confirm('确定删除该 FAQ 吗？');">
                                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete_faq">
                                    <input type="hidden" name="id" value="<?= h($faq['id']) ?>">
                                    <button type="submit" class="link-button danger">删除</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="panel" id="contact-settings">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">📞 Contact Settings</p>
                        <h2>联系方式</h2>
                    </div>
                    <p>这里维护前台联系人说明与所有渠道标签，中英文文案会同步跟随切换。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="save_contact">

                    <label>
                        <span>中文标题</span>
                        <input type="text" name="contact_title_zh" value="<?= h($data['contact']['zh']['title']) ?>" required>
                    </label>
                    <label>
                        <span>英文标题</span>
                        <input type="text" name="contact_title_en" value="<?= h($data['contact']['en']['title']) ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>中文说明</span>
                        <textarea name="contact_intro_zh" rows="3" required><?= h($data['contact']['zh']['intro']) ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>英文说明</span>
                        <textarea name="contact_intro_en" rows="3" required><?= h($data['contact']['en']['intro']) ?></textarea>
                    </label>
                    <label>
                        <span>中文邮箱标签</span>
                        <input type="text" name="contact_email_label_zh" value="<?= h($data['contact']['zh']['email_label']) ?>" required>
                    </label>
                    <label>
                        <span>英文邮箱标签</span>
                        <input type="text" name="contact_email_label_en" value="<?= h($data['contact']['en']['email_label']) ?>" required>
                    </label>
                    <label>
                        <span>邮箱值</span>
                        <input type="text" name="contact_email_value" value="<?= h($data['contact']['zh']['email_value']) ?>" required>
                    </label>
                    <label>
                        <span>电话值</span>
                        <input type="text" name="contact_phone_value" value="<?= h($data['contact']['zh']['phone_value']) ?>" required>
                    </label>
                    <label>
                        <span>Telegram 链接</span>
                        <input type="url" name="contact_telegram_value" value="<?= h($data['contact']['zh']['telegram_value'] ?? '') ?>">
                    </label>
                    <label>
                        <span>WhatsApp 链接</span>
                        <input type="url" name="contact_whatsapp_value" value="<?= h($data['contact']['zh']['whatsapp_value'] ?? '') ?>">
                    </label>
                    <label>
                        <span>中文电话标签</span>
                        <input type="text" name="contact_phone_label_zh" value="<?= h($data['contact']['zh']['phone_label']) ?>" required>
                    </label>
                    <label>
                        <span>英文电话标签</span>
                        <input type="text" name="contact_phone_label_en" value="<?= h($data['contact']['en']['phone_label']) ?>" required>
                    </label>
                    <label>
                        <span>中文 Telegram 标签</span>
                        <input type="text" name="contact_telegram_label_zh" value="<?= h($data['contact']['zh']['telegram_label'] ?? 'Telegram') ?>">
                    </label>
                    <label>
                        <span>英文 Telegram 标签</span>
                        <input type="text" name="contact_telegram_label_en" value="<?= h($data['contact']['en']['telegram_label'] ?? 'Telegram') ?>">
                    </label>
                    <label>
                        <span>中文 WhatsApp 标签</span>
                        <input type="text" name="contact_whatsapp_label_zh" value="<?= h($data['contact']['zh']['whatsapp_label'] ?? 'WhatsApp') ?>">
                    </label>
                    <label>
                        <span>英文 WhatsApp 标签</span>
                        <input type="text" name="contact_whatsapp_label_en" value="<?= h($data['contact']['en']['whatsapp_label'] ?? 'WhatsApp') ?>">
                    </label>
                    <label>
                        <span>中文地址标签</span>
                        <input type="text" name="contact_address_label_zh" value="<?= h($data['contact']['zh']['address_label']) ?>" required>
                    </label>
                    <label>
                        <span>英文地址标签</span>
                        <input type="text" name="contact_address_label_en" value="<?= h($data['contact']['en']['address_label']) ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>中文地址内容</span>
                        <textarea name="contact_address_value_zh" rows="3" required><?= h($data['contact']['zh']['address_value']) ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>英文地址内容</span>
                        <textarea name="contact_address_value_en" rows="3" required><?= h($data['contact']['en']['address_value']) ?></textarea>
                    </label>
                    <div class="form-actions">
                        <button type="submit">保存联系方式</button>
                    </div>
                </form>
            </section>

            <section class="panel" id="hero-settings">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">🎯 Hero Copy</p>
                        <h2>首页 Hero 文案</h2>
                    </div>
                    <p>首页第一屏要负责抓住客户视线，标题、副标题和按钮文案建议定期优化。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="save_hero">

                    <label>
                        <span>中文标题</span>
                        <input type="text" name="hero_title_zh" value="<?= h($data['hero']['zh']['title']) ?>" required>
                    </label>
                    <label>
                        <span>英文标题</span>
                        <input type="text" name="hero_title_en" value="<?= h($data['hero']['en']['title']) ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>中文副标题</span>
                        <textarea name="hero_subtitle_zh" rows="3" required><?= h($data['hero']['zh']['subtitle']) ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>英文副标题</span>
                        <textarea name="hero_subtitle_en" rows="3" required><?= h($data['hero']['en']['subtitle']) ?></textarea>
                    </label>
                    <label>
                        <span>中文按钮文案</span>
                        <input type="text" name="hero_cta_zh" value="<?= h($data['hero']['zh']['cta']) ?>" required>
                    </label>
                    <label>
                        <span>英文按钮文案</span>
                        <input type="text" name="hero_cta_en" value="<?= h($data['hero']['en']['cta']) ?>" required>
                    </label>
                    <div class="form-actions">
                        <button type="submit">保存 Hero</button>
                    </div>
                </form>
            </section>

            <section class="panel" id="seo-settings">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">🔍 Site Meta</p>
                        <h2>首页核心指标与 SEO</h2>
                    </div>
                    <p>指标数值负责建立信任感，SEO 标题与描述负责承接自然流量。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="save_site_meta">

                    <label>
                        <span>SEO 标题（中文）</span>
                        <input type="text" name="seo_title_zh" value="<?= h($data['seo']['zh']['title'] ?? '') ?>" required>
                    </label>
                    <label>
                        <span>SEO 标题（英文）</span>
                        <input type="text" name="seo_title_en" value="<?= h($data['seo']['en']['title'] ?? '') ?>" required>
                    </label>
                    <label class="field-wide">
                        <span>SEO 描述（中文）</span>
                        <textarea name="seo_description_zh" rows="3" required><?= h($data['seo']['zh']['description'] ?? '') ?></textarea>
                    </label>
                    <label class="field-wide">
                        <span>SEO 描述（英文）</span>
                        <textarea name="seo_description_en" rows="3" required><?= h($data['seo']['en']['description'] ?? '') ?></textarea>
                    </label>

                    <?php foreach (($data['hero']['metrics'] ?? []) as $index => $metric): ?>
                        <label>
                            <span>指标 <?= h((string) ($index + 1)) ?> 数值</span>
                            <input type="text" name="metric_value_<?= h((string) $index) ?>" value="<?= h($metric['value'] ?? '') ?>" required>
                        </label>
                        <label>
                            <span>指标 <?= h((string) ($index + 1)) ?> 中文标签</span>
                            <input type="text" name="metric_label_zh_<?= h((string) $index) ?>" value="<?= h($metric['label']['zh'] ?? '') ?>" required>
                        </label>
                        <label class="field-wide">
                            <span>指标 <?= h((string) ($index + 1)) ?> 英文标签</span>
                            <input type="text" name="metric_label_en_<?= h((string) $index) ?>" value="<?= h($metric['label']['en'] ?? '') ?>" required>
                        </label>
                    <?php endforeach; ?>

                    <div class="form-actions">
                        <button type="submit">保存指标与 SEO</button>
                    </div>
                </form>
            </section>

            <section class="panel" id="security-settings">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">🔒 Security</p>
                        <h2>修改后台密码</h2>
                    </div>
                    <p>密码会持久化保存到本地哈希文件，建议上线后第一时间更换默认密码。</p>
                </div>
                <form method="post" action="<?= h(url('admin/save.php')) ?>" class="grid-form compact-form">
                    <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
                    <input type="hidden" name="action" value="change_password">

                    <label>
                        <span>当前密码</span>
                        <input type="password" name="current_password" required>
                    </label>
                    <label>
                        <span>新密码</span>
                        <input type="password" name="new_password" minlength="8" required>
                    </label>
                    <label class="field-wide">
                        <span>确认新密码</span>
                        <input type="password" name="confirm_password" minlength="8" required>
                    </label>
                    <div class="form-actions">
                        <button type="submit">更新密码</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
