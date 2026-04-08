<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

require_admin();
verify_csrf();

$action = input('action');
$data = site_data();

switch ($action) {
    case 'save_product':
        $oldInput = $_POST;
        $errors = [];
        if (input('category') === '') {
            $errors[] = '产品分类不能为空。';
        }
        if (input('name_zh') === '' || input('name_en') === '') {
            $errors[] = '中英文商品名称都需要填写。';
        }
        if (input('summary_zh') === '' || input('summary_en') === '') {
            $errors[] = '中英文商品简介都需要填写。';
        }
        if (input('specs_zh') === '' || input('specs_en') === '') {
            $errors[] = '中英文配置说明都需要填写。';
        }
        if (input('price_original') === '' || input('price_discount') === '') {
            $errors[] = '原价和优惠价不能为空。';
        }
        $orderUrl = input('order_url');
        if ($orderUrl === '' || filter_var($orderUrl, FILTER_VALIDATE_URL) === false) {
            $errors[] = '订购链接必须是有效 URL。';
        }
        $imageUrl = input('image_url');
        if ($imageUrl !== '' && filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
            $errors[] = '商品图片必须是有效 URL。';
        }

        if ($errors !== []) {
            flash_validation_errors($errors, $oldInput);
            flash('error', '商品保存失败，请检查表单内容。');
            redirect('admin/dashboard.php');
        }

        $id = input('id') ?: generate_id('product');
        $product = [
            'id' => $id,
            'category' => input('category'),
            'name' => input_lang_array('name'),
            'summary' => input_lang_array('summary'),
            'specs' => input_lang_array('specs'),
            'price' => [
                'original' => input('price_original'),
                'discount' => input('price_discount'),
            ],
            'highlights' => [
                'zh' => normalize_highlights(input('highlights_zh')),
                'en' => normalize_highlights(input('highlights_en')),
            ],
            'image_url' => $imageUrl,
            'order_url' => $orderUrl,
            'featured' => normalize_checkbox('featured'),
            'enabled' => normalize_checkbox('enabled'),
            'sort_order' => input_int('sort_order', 100),
        ];

        $replaced = false;
        foreach ($data['products'] as $index => $existing) {
            if ($existing['id'] === $id) {
                $data['products'][$index] = $product;
                $replaced = true;
                break;
            }
        }

        if (!$replaced) {
            $data['products'][] = $product;
        }

        save_site_data($data);
        clear_form_state();
        flash('success', '商品已保存。');
        redirect('admin/dashboard.php');

    case 'delete_product':
        $id = input('id');
        $data['products'] = array_values(array_filter(
            $data['products'],
            static fn (array $product): bool => $product['id'] !== $id
        ));
        save_site_data($data);
        flash('success', '商品已删除。');
        redirect('admin/dashboard.php');

    case 'save_faq':
        $faqErrors = [];
        if (input('question_zh') === '' || input('question_en') === '') {
            $faqErrors[] = 'FAQ 的中英文问题都需要填写。';
        }
        if (input('answer_zh') === '' || input('answer_en') === '') {
            $faqErrors[] = 'FAQ 的中英文回答都需要填写。';
        }
        if ($faqErrors !== []) {
            flash('error', implode(' ', $faqErrors));
            redirect('admin/dashboard.php');
        }

        $id = input('id') ?: generate_id('faq');
        $faq = [
            'id' => $id,
            'question' => input_lang_array('question'),
            'answer' => input_lang_array('answer'),
        ];

        $replaced = false;
        foreach ($data['faqs'] as $index => $existing) {
            if ($existing['id'] === $id) {
                $data['faqs'][$index] = $faq;
                $replaced = true;
                break;
            }
        }

        if (!$replaced) {
            $data['faqs'][] = $faq;
        }

        save_site_data($data);
        flash('success', 'FAQ 已保存。');
        redirect('admin/dashboard.php');

    case 'delete_faq':
        $id = input('id');
        $data['faqs'] = array_values(array_filter(
            $data['faqs'],
            static fn (array $faq): bool => $faq['id'] !== $id
        ));
        save_site_data($data);
        flash('success', 'FAQ 已删除。');
        redirect('admin/dashboard.php');

    case 'save_contact':
        $emailValue = input('contact_email_value');
        $phoneValue = input('contact_phone_value');
        $telegramValue = input('contact_telegram_value');
        $whatsappValue = input('contact_whatsapp_value');
        $data['contact'] = [
            'zh' => [
                'title' => input('contact_title_zh'),
                'intro' => input('contact_intro_zh'),
                'email_label' => input('contact_email_label_zh'),
                'email_value' => $emailValue,
                'phone_label' => input('contact_phone_label_zh'),
                'phone_value' => $phoneValue,
                'telegram_label' => input('contact_telegram_label_zh', 'Telegram'),
                'telegram_value' => $telegramValue,
                'whatsapp_label' => input('contact_whatsapp_label_zh', 'WhatsApp'),
                'whatsapp_value' => $whatsappValue,
                'address_label' => input('contact_address_label_zh'),
                'address_value' => input('contact_address_value_zh'),
            ],
            'en' => [
                'title' => input('contact_title_en'),
                'intro' => input('contact_intro_en'),
                'email_label' => input('contact_email_label_en'),
                'email_value' => $emailValue,
                'phone_label' => input('contact_phone_label_en'),
                'phone_value' => $phoneValue,
                'telegram_label' => input('contact_telegram_label_en', 'Telegram'),
                'telegram_value' => $telegramValue,
                'whatsapp_label' => input('contact_whatsapp_label_en', 'WhatsApp'),
                'whatsapp_value' => $whatsappValue,
                'address_label' => input('contact_address_label_en'),
                'address_value' => input('contact_address_value_en'),
            ],
        ];
        save_site_data($data);
        flash('success', '联系方式已保存。');
        redirect('admin/dashboard.php');

    case 'save_hero':
        $existingMetrics = $data['hero']['metrics'] ?? [];
        $data['hero'] = [
            'zh' => [
                'title' => input('hero_title_zh'),
                'subtitle' => input('hero_subtitle_zh'),
                'cta' => input('hero_cta_zh'),
            ],
            'en' => [
                'title' => input('hero_title_en'),
                'subtitle' => input('hero_subtitle_en'),
                'cta' => input('hero_cta_en'),
            ],
            'metrics' => $existingMetrics,
        ];
        save_site_data($data);
        flash('success', '首页文案已保存。');
        redirect('admin/dashboard.php');

    case 'save_site_meta':
        $metrics = [];
        foreach ([0, 1, 2] as $index) {
            $metrics[] = [
                'value' => input('metric_value_' . $index),
                'label' => [
                    'zh' => input('metric_label_zh_' . $index),
                    'en' => input('metric_label_en_' . $index),
                ],
            ];
        }

        $data['seo'] = [
            'zh' => [
                'title' => input('seo_title_zh'),
                'description' => input('seo_description_zh'),
            ],
            'en' => [
                'title' => input('seo_title_en'),
                'description' => input('seo_description_en'),
            ],
        ];
        $data['hero']['metrics'] = $metrics;
        save_site_data($data);
        flash('success', '首页指标与 SEO 已保存。');
        redirect('admin/dashboard.php');

    case 'change_password':
        $currentPassword = input('current_password');
        $newPassword = input('new_password');
        $confirmPassword = input('confirm_password');

        if (!password_verify($currentPassword, admin_password_hash())) {
            flash('error', '当前密码不正确。');
            redirect('admin/dashboard.php');
        }
        if (mb_strlen($newPassword) < 8) {
            flash('error', '新密码至少需要 8 位。');
            redirect('admin/dashboard.php');
        }
        if ($newPassword !== $confirmPassword) {
            flash('error', '两次输入的新密码不一致。');
            redirect('admin/dashboard.php');
        }

        set_admin_password($newPassword);
        flash('success', '后台密码已更新。');
        redirect('admin/dashboard.php');

    default:
        flash('error', '未识别的操作。');
        redirect('admin/dashboard.php');
}
