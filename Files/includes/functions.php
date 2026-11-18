<?php
// Shared helper functions
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

const MAX_KM = 25;   // project scope: places within 25 km

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): void { header("Location: $url"); exit; }

/* ---- CSRF protection for every POST form ---- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit("Invalid form token. Go back, refresh the page and try again.");
    }
}

/* ---- one-time messages ---- */
function flash_set(string $msg, string $type = 'ok'): void { $_SESSION['flash'] = [$msg, $type]; }
function flash_show(): string {
    if (empty($_SESSION['flash'])) return '';
    [$m, $t] = $_SESSION['flash']; unset($_SESSION['flash']);
    return '<div class="msg ' . e($t) . '">' . e($m) . '</div>';
}

/* ---- display helpers ---- */
function img_url(string $path): string {            // handles spaces in file names
    return implode('/', array_map('rawurlencode', explode('/', $path)));
}
function cat_class(string $name): string {
    return ['Religious' => 'religious', 'Historical' => 'history', 'Cultural' => 'culture'][$name] ?? '';
}
function cat_icon(string $name): string {
    return ['Religious' => '🙏', 'Historical' => '🏛️', 'Cultural' => '🏡'][$name] ?? '🌿';
}
function maps_link(array $a): string {              // cleans bad data such as "...\r\n" in the link
    if (preg_match('~https?://[^\s\\\\]+~', (string)$a['google_maps_link'], $m)) return $m[0];
    return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($a['name'] . ' ' . $a['location']);
}

/* ---- turn text such as "1-2 hours" or "20 minutes" into minutes ---- */
function minutes(?string $text, int $default = 30): int {
    if (preg_match('/(\d+(?:\.\d+)?)(?:\s*-\s*(\d+(?:\.\d+)?))?\s*(hour|hr|minute|min)/i', (string)$text, $m)) {
        $n = ($m[2] ?? '') !== '' ? ($m[1] + $m[2]) / 2 : (float)$m[1];
        return (int)round(stripos($m[3], 'h') === 0 ? $n * 60 : $n);
    }
    return $default;
}
function fmt_time(int $mins): string { $mins %= 1440; return sprintf('%02d:%02d', intdiv($mins, 60), $mins % 60); }

/* ---- photo upload: returns [saved paths, error messages] ---- */
function save_uploads(array $files, string $slug): array {
    $saved = []; $errors = [];
    $dir = dirname(__DIR__) . '/assets/images/';
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (empty($files['name'][0])) return [[], []];
    foreach ($files['name'] as $i => $orig) {
        if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
        if ($files['error'][$i] !== UPLOAD_ERR_OK)   { $errors[] = "$orig: upload failed."; continue; }
        if ($files['size'][$i] > 5 * 1024 * 1024)    { $errors[] = "$orig: larger than 5 MB."; continue; }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($files['tmp_name'][$i]);
        if (!isset($types[$mime]) || !getimagesize($files['tmp_name'][$i])) { $errors[] = "$orig: only JPG, PNG or WEBP photos."; continue; }
        $name = $slug . '-' . bin2hex(random_bytes(4)) . '.' . $types[$mime];
        if (move_uploaded_file($files['tmp_name'][$i], $dir . $name)) $saved[] = 'assets/images/' . $name;
        else $errors[] = "$orig: could not save (is assets/images writable?).";
    }
    return [$saved, $errors];
}

/* ---- text helpers that work even if the mbstring extension is off ---- */
function str_len(string $s): int { return function_exists('mb_strlen') ? mb_strlen($s) : strlen($s); }
function shorten(string $s, int $max): string {
    if (str_len($s) <= $max) return $s;
    return (function_exists('mb_substr') ? mb_substr($s, 0, $max - 1) : substr($s, 0, $max - 1)) . '…';
}

/* ---- login helpers (visitor accounts + admin) ---- */
function is_admin(): bool { return !empty($_SESSION['admin_id']); }
function is_user(): bool  { return !empty($_SESSION['user_id']); }
function safe_next(?string $n): string {          // only allow plain page names like "my-plans.php"
    $n = (string)$n;
    return preg_match('~^[A-Za-z0-9_\-]+\.php(\?[A-Za-z0-9=&_\-]*)?$~', $n) ? $n : '';
}
function require_user(): void {
    if (!is_user()) redirect('login.php?next=' . urlencode(basename($_SERVER['SCRIPT_NAME'])));
}
