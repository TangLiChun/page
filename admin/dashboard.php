<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

require_admin();

$data = site_data();
$flash = pull_flash();
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
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(labels()['zh']['admin_title']) ?></title>
    <link rel="stylesheet" href="<?= h(asset('public/admin.css')) ?>">
</head>
<body class="admin-page">
    <header class="admin-header">
        <div>
            <h1><?= h(labels()['zh']['admin_title']) ?></h1>
            <p>维护商品、FAQ、联系方式和首页文案的中英文内容。</p>
        </div>
        <div class="admin-actions">
            <a href="<?= h(url('index.php')) ?>" target="_blank" rel="noopener noreferrer">查看前台</a>
            <a href="<?= h(url('admin/logout.php')) ?>">退出登录</a>
        </div>
    </header>

    <main class="admin-main">
        <?php if ($flash): ?>
            <div class="flash <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>

        <section class="panel">
            <h2><?= $editingProduct ? '编辑商品' : '新增商品' ?></h2>
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
                <div></div>

                <label>
                    <span>中文名称</span>
                    <input type="text" name="name_zh" value="<?= h($editingProduct['name']['zh'] ?? '') ?>" required>
                </label>
                <label>
                    <span>英文名称</span>
                    <input type="text" name="name_en" value="<?= h($editingProduct['name']['en'] ?? '') ?>" required>
                </label>
                <label>
                    <span>中文简介</span>
                    <textarea name="summary_zh" rows="3" required><?= h($editingProduct['summary']['zh'] ?? '') ?></textarea>
                </label>
                <label>
                    <span>英文简介</span>
                    <textarea name="summary_en" rows="3" required><?= h($editingProduct['summary']['en'] ?? '') ?></textarea>
                </label>
                <label>
                    <span>中文配置</span>
                    <textarea name="specs_zh" rows="4" required><?= h($editingProduct['specs']['zh'] ?? '') ?></textarea>
                </label>
                <label>
                    <span>英文配置</span>
                    <textarea name="specs_en" rows="4" required><?= h($editingProduct['specs']['en'] ?? '') ?></textarea>
                </label>
                <div class="form-actions">
                    <button type="submit"><?= $editingProduct ? '保存商品' : '新增商品' ?></button>
                    <?php if ($editingProduct): ?>
                        <a href="<?= h(url('admin/dashboard.php')) ?>">取消编辑</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>商品列表</h2>
            <div class="table-like">
                <?php foreach ($data['products'] as $product): ?>
                    <article class="item-card">
                        <div>
                            <h3><?= h($product['name']['zh']) ?> / <?= h($product['name']['en']) ?></h3>
                            <p><?= h($product['category']) ?> | <?= h($product['price']['discount']) ?></p>
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

        <section class="panel">
            <h2><?= $editingFaq ? '编辑 FAQ' : '新增 FAQ' ?></h2>
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
                <label>
                    <span>中文回答</span>
                    <textarea name="answer_zh" rows="4" required><?= h($editingFaq['answer']['zh'] ?? '') ?></textarea>
                </label>
                <label>
                    <span>英文回答</span>
                    <textarea name="answer_en" rows="4" required><?= h($editingFaq['answer']['en'] ?? '') ?></textarea>
                </label>
                <div class="form-actions">
                    <button type="submit"><?= $editingFaq ? '保存 FAQ' : '新增 FAQ' ?></button>
                    <?php if ($editingFaq): ?>
                        <a href="<?= h(url('admin/dashboard.php')) ?>">取消编辑</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>FAQ 列表</h2>
            <div class="table-like">
                <?php foreach ($data['faqs'] as $faq): ?>
                    <article class="item-card">
                        <div>
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

        <section class="panel">
            <h2>联系方式</h2>
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
                <label>
                    <span>中文说明</span>
                    <textarea name="contact_intro_zh" rows="3" required><?= h($data['contact']['zh']['intro']) ?></textarea>
                </label>
                <label>
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
                    <span>中文电话标签</span>
                    <input type="text" name="contact_phone_label_zh" value="<?= h($data['contact']['zh']['phone_label']) ?>" required>
                </label>
                <label>
                    <span>英文电话标签</span>
                    <input type="text" name="contact_phone_label_en" value="<?= h($data['contact']['en']['phone_label']) ?>" required>
                </label>
                <label>
                    <span>中文地址标签</span>
                    <input type="text" name="contact_address_label_zh" value="<?= h($data['contact']['zh']['address_label']) ?>" required>
                </label>
                <label>
                    <span>英文地址标签</span>
                    <input type="text" name="contact_address_label_en" value="<?= h($data['contact']['en']['address_label']) ?>" required>
                </label>
                <label>
                    <span>中文地址内容</span>
                    <textarea name="contact_address_value_zh" rows="3" required><?= h($data['contact']['zh']['address_value']) ?></textarea>
                </label>
                <label>
                    <span>英文地址内容</span>
                    <textarea name="contact_address_value_en" rows="3" required><?= h($data['contact']['en']['address_value']) ?></textarea>
                </label>
                <div class="form-actions">
                    <button type="submit">保存联系方式</button>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>首页 Hero 文案</h2>
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
                <label>
                    <span>中文副标题</span>
                    <textarea name="hero_subtitle_zh" rows="3" required><?= h($data['hero']['zh']['subtitle']) ?></textarea>
                </label>
                <label>
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
    </main>
</body>
</html>
