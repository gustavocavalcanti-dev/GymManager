<?php

declare(strict_types=1);

class UI
{
    public static function icon(string $name, int $size = 18): string
    {
        $icons = [
            'logo' => '<path d="M7 8.5h2v7H7zM15 8.5h2v7h-2z"/><path d="M4 10h3v4H4a2 2 0 0 1-2-2 2 2 0 0 1 2-2Zm16 0h-3v4h3a2 2 0 0 0 2-2 2 2 0 0 0-2-2Z"/><path d="M9 11h6v2H9z"/>',
            'dashboard' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'teacher' => '<path d="m3 10 9-5 9 5-9 5-9-5Z"/><path d="M7 12v4c0 1.6 2.2 3 5 3s5-1.4 5-3v-4"/><path d="M21 10v6"/>',
            'box' => '<path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="m3 8 9 5 9-5"/><path d="M12 13v8"/><path d="m21 8v8l-9 5-9-5V8"/>',
            'clipboard' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2"/><path d="M9 9h6M9 13h6M9 17h4"/>',
            'dumbbell' => '<path d="M6 7v10M18 7v10M3 9v6M21 9v6M6 12h12"/>',
            'card' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/>',
            'chart' => '<path d="M4 19V9M10 19V5M16 19v-7M22 19V3"/>',
            'user-settings' => '<circle cx="9" cy="7" r="4"/><path d="M2 21v-2a7 7 0 0 1 7-7c2 0 3.7.7 5 2"/><circle cx="18" cy="18" r="3"/><path d="M18 13v2M18 21v2M13 18h2M21 18h2"/>',
            'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1A1.7 1.7 0 0 0 4.6 15 1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1A1.7 1.7 0 0 0 9 4.6 1.7 1.7 0 0 0 10 3V2.8h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/>',
            'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
            'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
            'moon' => '<path d="M20 15.5A8.5 8.5 0 0 1 8.5 4 8.5 8.5 0 1 0 20 15.5Z"/>',
            'bell' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/>',
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'filter' => '<path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z"/>',
            'download' => '<path d="M12 3v12M7 10l5 5 5-5"/><path d="M5 21h14"/>',
            'logout' => '<path d="M10 17l5-5-5-5M15 12H3"/><path d="M13 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>',
            'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
            'lock' => '<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
            'eye' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/>',
            'check' => '<path d="m5 12 4 4L19 6"/>',
            'money' => '<circle cx="12" cy="12" r="9"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8M12 6v12"/>',
            'alert' => '<path d="M10.3 3.8 2.4 18a2 2 0 0 0 1.8 3h15.6a2 2 0 0 0 1.8-3L13.7 3.8a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/>',
            'user-plus' => '<circle cx="9" cy="8" r="4"/><path d="M2 21v-2a7 7 0 0 1 11-5.7M19 8v6M16 11h6"/>',
            'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'upload' => '<path d="M12 16V4M7 9l5-5 5 5"/><path d="M5 20h14"/>',
            'building' => '<path d="M4 21V4h10v17M14 9h6v12M8 8h2M8 12h2M8 16h2M17 13h1M17 17h1"/>',
            'palette' => '<circle cx="12" cy="12" r="9"/><circle cx="8" cy="9" r="1"/><circle cx="12" cy="7" r="1"/><circle cx="16" cy="9" r="1"/><path d="M15 15h2a2 2 0 0 1 0 4h-2a3 3 0 0 1 0-6"/>',
            'shield' => '<path d="M12 3 5 6v5c0 5 3 8 7 10 4-2 7-5 7-10V6l-7-3Z"/>',
            'database' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>',
        ];
        $path = $icons[$name] ?? $icons['box'];
        return '<svg class="ui-icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
    }

    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $letters = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $letters .= strtoupper(substr($part, 0, 1));
        }
        return $letters ?: 'GM';
    }
}
