<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

require_admin();
verify_csrf();

$action = input('action');
$data = site_data();

switch ($action) {
    case 'save_product':
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
            'order_url' => input('order_url'),
            'featured' => normalize_checkbox('featured'),
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
        $data['contact'] = [
            'zh' => [
                'title' => input('contact_title_zh'),
                'intro' => input('contact_intro_zh'),
                'email_label' => input('contact_email_label_zh'),
                'email_value' => $emailValue,
                'phone_label' => input('contact_phone_label_zh'),
                'phone_value' => $phoneValue,
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
                'address_label' => input('contact_address_label_en'),
                'address_value' => input('contact_address_value_en'),
            ],
        ];
        save_site_data($data);
        flash('success', '联系方式已保存。');
        redirect('admin/dashboard.php');

    case 'save_hero':
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
        ];
        save_site_data($data);
        flash('success', '首页文案已保存。');
        redirect('admin/dashboard.php');

    default:
        flash('error', '未识别的操作。');
        redirect('admin/dashboard.php');
}
