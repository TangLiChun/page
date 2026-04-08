<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/config.php';

const SUPPORTED_LANGUAGES = ['zh', 'en'];

function config(?string $key = null, mixed $default = null): mixed
{
    global $config;

    if ($key === null) {
        return $config;
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function base_path(string $path = ''): string
{
    $base = dirname(__DIR__);
    return $path === '' ? $base : $base . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function current_lang(): string
{
    if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES, true)) {
        $_SESSION['lang'] = $_GET['lang'];
    }

    $lang = $_SESSION['lang'] ?? 'zh';
    return in_array($lang, SUPPORTED_LANGUAGES, true) ? $lang : 'zh';
}

function switch_lang_url(string $lang): string
{
    $query = $_GET;
    $query['lang'] = $lang;
    return strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($query);
}

function asset(string $path): string
{
    return url($path);
}

function base_url(): string
{
    $configured = (string) config('base_url', '');
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    return '';
}

function url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    return base_url() . $path;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

function is_admin_authenticated(): bool
{
    return !empty($_SESSION['is_admin']);
}

function require_admin(): void
{
    if (!is_admin_authenticated()) {
        redirect('/admin/login.php');
    }
}

function admin_password_hash(): string
{
    $file = admin_password_file();
    if (!file_exists($file)) {
        file_put_contents($file, password_hash((string) config('admin.password'), PASSWORD_DEFAULT), LOCK_EX);
    }

    return trim((string) file_get_contents($file));
}

function set_admin_password(string $password): void
{
    file_put_contents(admin_password_file(), password_hash($password, PASSWORD_DEFAULT), LOCK_EX);
}

function admin_password_file(): string
{
    $storageDir = base_path('storage');
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    return $storageDir . '/admin-password.hash';
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function pull_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function default_site_data(): array
{
    return [
        'seo' => [
            'zh' => [
                'title' => 'SpeedTest Hosting | VPS、Colocation 与 Dedicated Server',
                'description' => '提供双语展示的 VPS、Colocation 与 Dedicated Server 产品页面，适合企业与出海业务快速了解方案与价格。',
            ],
            'en' => [
                'title' => 'SpeedTest Hosting | VPS, Colocation & Dedicated Servers',
                'description' => 'Explore bilingual VPS, colocation, and dedicated server plans with clear product summaries, promo pricing, FAQ, and contact details.',
            ],
        ],
        'hero' => [
            'zh' => [
                'title' => '可靠的服务器基础设施，帮助业务稳定上线',
                'subtitle' => '提供 VPS、Colocation 与 Dedicated Server 产品，适合出海、企业官网、业务系统与高可用部署场景。',
                'cta' => '立即查看方案',
            ],
            'en' => [
                'title' => 'Reliable infrastructure for modern online businesses',
                'subtitle' => 'Explore VPS, Colocation, and Dedicated Server plans for international growth, business websites, and mission-critical workloads.',
                'cta' => 'Explore Plans',
            ],
            'metrics' => [
                [
                    'value' => '99.9%',
                    'label' => [
                        'zh' => '基础 SLA 可用性',
                        'en' => 'Base SLA availability',
                    ],
                ],
                [
                    'value' => '24/7',
                    'label' => [
                        'zh' => '工单支持',
                        'en' => 'Ticket support',
                    ],
                ],
                [
                    'value' => '15min',
                    'label' => [
                        'zh' => '平均售前响应',
                        'en' => 'Average pre-sales response',
                    ],
                ],
            ],
        ],
        'products' => [
            [
                'id' => 'vps-starter',
                'category' => 'VPS',
                'name' => [
                    'zh' => 'VPS Starter',
                    'en' => 'VPS Starter',
                ],
                'summary' => [
                    'zh' => '适合个人站点、轻量业务系统与开发测试环境。',
                    'en' => 'Perfect for personal sites, lightweight workloads, and development environments.',
                ],
                'specs' => [
                    'zh' => "2 vCPU / 4GB RAM / 80GB NVMe\n1TB 月流量 / 1 Gbps 端口",
                    'en' => "2 vCPU / 4GB RAM / 80GB NVMe\n1TB monthly traffic / 1 Gbps port",
                ],
                'price' => [
                    'original' => '$18/mo',
                    'discount' => '$12/mo',
                ],
                'highlights' => [
                    'zh' => ['SSD NVMe', '快速开通', '适合轻量业务'],
                    'en' => ['NVMe SSD', 'Fast provisioning', 'Ideal for light workloads'],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'order_url' => 'https://example.com/order/vps-starter',
                'featured' => true,
                'enabled' => true,
                'sort_order' => 10,
            ],
            [
                'id' => 'colo-rack-1u',
                'category' => 'Colocation',
                'name' => [
                    'zh' => 'Colocation 1U',
                    'en' => 'Colocation 1U',
                ],
                'summary' => [
                    'zh' => '适合自有设备托管，提供稳定电力、网络与机房环境。',
                    'en' => 'Built for colocating your own hardware with stable power, network, and facility conditions.',
                ],
                'specs' => [
                    'zh' => "1U 机位 / 5A 电力\n100Mbps 独享带宽 / IP 可扩展",
                    'en' => "1U rack space / 5A power\n100Mbps dedicated bandwidth / expandable IP resources",
                ],
                'price' => [
                    'original' => '$129/mo',
                    'discount' => '$99/mo',
                ],
                'highlights' => [
                    'zh' => ['稳定机房环境', '电力与网络保障', '适合自有硬件'],
                    'en' => ['Stable facility', 'Power and network redundancy', 'Great for your own hardware'],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=80',
                'order_url' => 'https://example.com/order/colo-rack-1u',
                'featured' => false,
                'enabled' => true,
                'sort_order' => 20,
            ],
            [
                'id' => 'dedicated-pro',
                'category' => 'Dedicated Server',
                'name' => [
                    'zh' => 'Dedicated Pro',
                    'en' => 'Dedicated Pro',
                ],
                'summary' => [
                    'zh' => '适合数据库、高并发应用与长期稳定运行的核心业务。',
                    'en' => 'Designed for databases, high-traffic services, and long-running mission-critical workloads.',
                ],
                'specs' => [
                    'zh' => "Intel Xeon / 64GB RAM / 2 x 1TB SSD\n10TB 月流量 / 1 Gbps 端口",
                    'en' => "Intel Xeon / 64GB RAM / 2 x 1TB SSD\n10TB monthly traffic / 1 Gbps port",
                ],
                'price' => [
                    'original' => '$299/mo',
                    'discount' => '$249/mo',
                ],
                'highlights' => [
                    'zh' => ['独享资源', '高并发稳定', '适合核心业务'],
                    'en' => ['Dedicated resources', 'Stable under high traffic', 'Built for critical workloads'],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
                'order_url' => 'https://example.com/order/dedicated-pro',
                'featured' => true,
                'enabled' => true,
                'sort_order' => 30,
            ],
        ],
        'faqs' => [
            [
                'id' => 'faq-support',
                'question' => [
                    'zh' => '是否提供技术支持？',
                    'en' => 'Do you provide technical support?',
                ],
                'answer' => [
                    'zh' => '提供基础部署协助和工单支持，企业客户可升级到更高 SLA。',
                    'en' => 'Yes. We provide basic deployment assistance and ticket support, with optional SLA upgrades for enterprise customers.',
                ],
            ],
            [
                'id' => 'faq-deploy',
                'question' => [
                    'zh' => '开通需要多久？',
                    'en' => 'How long does provisioning take?',
                ],
                'answer' => [
                    'zh' => 'VPS 通常几分钟内开通，托管与独立服务器会根据库存和交付流程确认时间。',
                    'en' => 'VPS plans are usually provisioned within minutes, while colocation and dedicated servers depend on stock and onboarding requirements.',
                ],
            ],
        ],
        'contact' => [
            'zh' => [
                'title' => '联系我们',
                'intro' => '如果你希望获取定制方案、批量采购报价或售前咨询，可以通过以下方式联系。',
                'email_label' => '邮箱',
                'email_value' => 'sales@example.com',
                'phone_label' => '电话',
                'phone_value' => '+86 400-800-9000',
                'telegram_label' => 'Telegram',
                'telegram_value' => 'https://t.me/example_sales',
                'whatsapp_label' => 'WhatsApp',
                'whatsapp_value' => 'https://wa.me/8613800000000',
                'address_label' => '地址',
                'address_value' => '上海市浦东新区张江高科技园区',
            ],
            'en' => [
                'title' => 'Contact Us',
                'intro' => 'Reach out for custom proposals, volume pricing, or pre-sales consultation through the channels below.',
                'email_label' => 'Email',
                'email_value' => 'sales@example.com',
                'phone_label' => 'Phone',
                'phone_value' => '+86 400-800-9000',
                'telegram_label' => 'Telegram',
                'telegram_value' => 'https://t.me/example_sales',
                'whatsapp_label' => 'WhatsApp',
                'whatsapp_value' => 'https://wa.me/8613800000000',
                'address_label' => 'Address',
                'address_value' => 'Zhangjiang Hi-Tech Park, Pudong, Shanghai',
            ],
        ],
    ];
}

function storage_file(): string
{
    return base_path('storage/site-data.json');
}

function ensure_storage_seeded(): void
{
    $storageDir = base_path('storage');
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $file = storage_file();
    if (!file_exists($file)) {
        file_put_contents(
            $file,
            json_encode(default_site_data(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}

function site_data(): array
{
    ensure_storage_seeded();

    $contents = file_get_contents(storage_file());
    $decoded = json_decode($contents ?: '', true);

    if (!is_array($decoded)) {
        return default_site_data();
    }

    return array_replace_recursive(default_site_data(), $decoded);
}

function save_site_data(array $data): void
{
    file_put_contents(
        storage_file(),
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function site_products(array $data, bool $onlyEnabled = true): array
{
    $products = $data['products'] ?? [];

    usort($products, static function (array $left, array $right): int {
        $orderCompare = (int) ($left['sort_order'] ?? 999) <=> (int) ($right['sort_order'] ?? 999);
        if ($orderCompare !== 0) {
            return $orderCompare;
        }

        return strcmp((string) ($left['id'] ?? ''), (string) ($right['id'] ?? ''));
    });

    if ($onlyEnabled) {
        $products = array_values(array_filter(
            $products,
            static fn (array $product): bool => (bool) ($product['enabled'] ?? true)
        ));
    }

    return $products;
}

function product_categories(array $products): array
{
    $categories = [];
    foreach ($products as $product) {
        $category = trim((string) ($product['category'] ?? ''));
        if ($category !== '' && !in_array($category, $categories, true)) {
            $categories[] = $category;
        }
    }

    return $categories;
}

function requested_category(array $categories): string
{
    $category = trim((string) ($_GET['category'] ?? ''));
    return in_array($category, $categories, true) ? $category : '';
}

function normalize_highlights(string $value): array
{
    $items = preg_split('/\r\n|\r|\n/', trim($value)) ?: [];
    $items = array_values(array_filter(array_map('trim', $items), static fn (string $item): bool => $item !== ''));
    return array_slice($items, 0, 6);
}

function highlights_to_text(array $items): string
{
    return implode("\n", array_values(array_filter(array_map('trim', $items))));
}

function generate_id(string $prefix): string
{
    return $prefix . '-' . bin2hex(random_bytes(4));
}

function normalize_checkbox(string $key): bool
{
    return isset($_POST[$key]) && $_POST[$key] === '1';
}

function input(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function input_int(string $key, int $default = 0): int
{
    return (int) ($_POST[$key] ?? $default);
}

function input_lang_array(string $prefix): array
{
    return [
        'zh' => input($prefix . '_zh'),
        'en' => input($prefix . '_en'),
    ];
}

function validation_errors(): array
{
    return $_SESSION['validation_errors'] ?? [];
}

function old_input(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old_input'][$key] ?? $default);
}

function flash_validation_errors(array $errors, array $oldInput = []): void
{
    $_SESSION['validation_errors'] = $errors;
    $_SESSION['old_input'] = $oldInput;
}

function clear_form_state(): void
{
    unset($_SESSION['validation_errors'], $_SESSION['old_input']);
}

function pull_validation_errors(): array
{
    $errors = validation_errors();
    unset($_SESSION['validation_errors']);
    return $errors;
}

function pull_old_input(): array
{
    $old = $_SESSION['old_input'] ?? [];
    unset($_SESSION['old_input']);
    return $old;
}

function validate_required_fields(array $fields): array
{
    $errors = [];
    foreach ($fields as $field => $message) {
        if (trim((string) $field) === '') {
            $errors[] = $message;
        }
    }

    return $errors;
}

function labels(): array
{
    return [
        'zh' => [
            'nav_home' => '首页',
            'nav_products' => '产品',
            'nav_faq' => 'FAQ',
            'nav_contact' => '联系方式',
            'hero_badge' => '企业级主机方案',
            'products_title' => '产品方案',
            'products_intro' => '按业务规模选择合适的基础设施方案。',
            'products_all' => '全部产品',
            'products_empty' => '当前分类下还没有可展示的产品。',
            'featured' => '推荐',
            'starting_from' => '优惠价',
            'original_price' => '原价',
            'order_now' => '立即订购',
            'view_details' => '查看详情',
            'faq_title' => '常见问题',
            'faq_intro' => '在咨询前，你也许会先关心这些问题。',
            'contact_cta' => '售前咨询',
            'admin_title' => '管理后台',
            'footer_text' => '双语主机产品展示与后台维护系统',
        ],
        'en' => [
            'nav_home' => 'Home',
            'nav_products' => 'Products',
            'nav_faq' => 'FAQ',
            'nav_contact' => 'Contact',
            'hero_badge' => 'Infrastructure for Serious Workloads',
            'products_title' => 'Plans & Products',
            'products_intro' => 'Choose the right infrastructure for your next deployment.',
            'products_all' => 'All Products',
            'products_empty' => 'No products are available in this category yet.',
            'featured' => 'Featured',
            'starting_from' => 'Promo Price',
            'original_price' => 'Original',
            'order_now' => 'Order Now',
            'view_details' => 'View Details',
            'faq_title' => 'Frequently Asked Questions',
            'faq_intro' => 'A few answers your team may want before reaching out.',
            'contact_cta' => 'Talk to Sales',
            'admin_title' => 'Admin Dashboard',
            'footer_text' => 'Bilingual hosting showcase with lightweight admin management',
        ],
    ];
}
