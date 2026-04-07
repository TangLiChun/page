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
                'order_url' => 'https://example.com/order/vps-starter',
                'featured' => true,
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
                'order_url' => 'https://example.com/order/colo-rack-1u',
                'featured' => false,
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
                'order_url' => 'https://example.com/order/dedicated-pro',
                'featured' => true,
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

function input_lang_array(string $prefix): array
{
    return [
        'zh' => input($prefix . '_zh'),
        'en' => input($prefix . '_en'),
    ];
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
            'featured' => '推荐',
            'starting_from' => '优惠价',
            'original_price' => '原价',
            'order_now' => '立即订购',
            'faq_title' => '常见问题',
            'faq_intro' => '在咨询前，你也许会先关心这些问题。',
            'contact_cta' => '售前咨询',
            'admin_title' => '管理后台',
        ],
        'en' => [
            'nav_home' => 'Home',
            'nav_products' => 'Products',
            'nav_faq' => 'FAQ',
            'nav_contact' => 'Contact',
            'hero_badge' => 'Infrastructure for Serious Workloads',
            'products_title' => 'Plans & Products',
            'products_intro' => 'Choose the right infrastructure for your next deployment.',
            'featured' => 'Featured',
            'starting_from' => 'Promo Price',
            'original_price' => 'Original',
            'order_now' => 'Order Now',
            'faq_title' => 'Frequently Asked Questions',
            'faq_intro' => 'A few answers your team may want before reaching out.',
            'contact_cta' => 'Talk to Sales',
            'admin_title' => 'Admin Dashboard',
        ],
    ];
}
