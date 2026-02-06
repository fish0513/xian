<?php
$config = require __DIR__ . '/../cms/config/config.php';
$apiBase = $config['app']['base_url'] ?? '/cms';
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>美食启东</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "PingFang SC", "Hiragino Sans GB", "Microsoft Yahei", sans-serif;
            background: #f7f7f8;
            color: #1f2937;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .section {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
        }

        .more {
            font-size: 13px;
            color: #2563eb;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            background: #f9fafb;
            border: 1px solid #eef2f7;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background: #e5e7eb;
        }

        .card-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
        }

        .card-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        .meta {
            font-size: 12px;
            color: #9ca3af;
        }

        .empty {
            text-align: center;
            color: #9ca3af;
            padding: 24px 0;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-outline {
            background: #fff;
            color: #2563eb;
            border: 1px solid #dbeafe;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="title">美食启东</div>
                <div class="subtitle">推荐内容与子栏目精选</div>
            </div>
            <div class="toolbar">
                <a class="btn btn-outline" href="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>/admin/login">进入后台</a>
            </div>
        </div>

        <div id="cover-root" data-api="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>"></div>
    </div>

    <script>
        const coverRoot = document.getElementById('cover-root');
        const apiBase = coverRoot.dataset.api || '';

        const escapeHtml = (value) => {
            const div = document.createElement('div');
            div.textContent = value == null ? '' : String(value);
            return div.innerHTML;
        };

        const buildCard = (item) => {
            const title = escapeHtml(item.title);
            const subtitle = escapeHtml(item.subtitle || '');
            const cover = item.cover_url ? escapeHtml(item.cover_url) : '';
            const address = escapeHtml(item.address || '');
            const phone = escapeHtml(item.phone || '');
            const hours = escapeHtml(item.business_hours || '');
            const link = `/food/detail.php?id=${encodeURIComponent(item.id)}`;
            return `
                <a class="card" href="${link}">
                    ${cover ? `<img src="${cover}" alt="${title}">` : `<div style="height:120px;background:#e5e7eb;"></div>`}
                    <div class="card-body">
                        <div class="card-title">${title}</div>
                        ${subtitle ? `<div class="card-subtitle">${subtitle}</div>` : ''}
                        ${(address || phone || hours) ? `<div class="meta">${address ? address : ''}${address && phone ? ' · ' : ''}${phone ? phone : ''}${(address || phone) && hours ? ' · ' : ''}${hours ? hours : ''}</div>` : ''}
                    </div>
                </a>
            `;
        };

        const renderCover = (data) => {
            const categories = data.categories || [];
            if (categories.length === 0) {
                coverRoot.innerHTML = '<div class="section"><div class="empty">暂无内容</div></div>';
                return;
            }
            coverRoot.innerHTML = categories.map(category => {
                const items = category.items || [];
                const moreLink = `/food/list.php?category_id=${encodeURIComponent(category.id)}`;
                return `
                    <div class="section">
                        <div class="section-header">
                            <div class="section-title">${escapeHtml(category.name)}</div>
                            <a class="more" href="${moreLink}">更多</a>
                        </div>
                        <div class="grid">
                            ${items.length ? items.map(buildCard).join('') : '<div class="empty">暂无推荐内容</div>'}
                        </div>
                    </div>
                `;
            }).join('');
        };

        fetch(`${apiBase}/api/food/cover`)
            .then(res => res.json())
            .then(renderCover)
            .catch(() => {
                coverRoot.innerHTML = '<div class="section"><div class="empty">加载失败，请稍后再试</div></div>';
            });
    </script>
</body>

</html>