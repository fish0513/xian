<?php
$config = require __DIR__ . '/../cms/config/config.php';
$apiBase = $config['app']['base_url'] ?? '/cms';
$id = (int)($_GET['id'] ?? 0);
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

        .cover {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
            background: #e5e7eb;
        }

        .meta {
            font-size: 13px;
            color: #6b7280;
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 12px;
        }

        .content {
            font-size: 14px;
            line-height: 1.7;
            color: #374151;
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

        .empty {
            text-align: center;
            color: #9ca3af;
            padding: 24px 0;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="title">内容详情</div>
                <div class="subtitle">查看餐饮信息详情</div>
            </div>
            <div class="toolbar">
                <button class="btn btn-outline" onclick="history.back()">返回列表</button>
            </div>
        </div>

        <div id="detail-root" data-api="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>" data-id="<?php echo htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'); ?>"></div>
    </div>

    <script>
        const detailRoot = document.getElementById('detail-root');
        const apiBase = detailRoot.dataset.api || '';
        const itemId = detailRoot.dataset.id || '';

        const escapeHtml = (value) => {
            const div = document.createElement('div');
            div.textContent = value == null ? '' : String(value);
            return div.innerHTML;
        };

        const renderDetail = (item) => {
            const title = escapeHtml(item.title || '');
            const subtitle = escapeHtml(item.subtitle || '');
            const cover = item.cover_url ? escapeHtml(item.cover_url) : '';
            const address = escapeHtml(item.address || '');
            const phone = escapeHtml(item.phone || '');
            const hours = escapeHtml(item.business_hours || '');
            const content = item.content || '';

            detailRoot.innerHTML = `
                <div class="section">
                    ${cover ? `<img class="cover" src="${cover}" alt="${title}">` : `<div class="cover"></div>`}
                    <div style="margin-top:12px;">
                        <div class="title">${title}</div>
                        ${subtitle ? `<div class="subtitle">${subtitle}</div>` : ''}
                    </div>
                    <div class="meta">
                        ${address ? `<div>地址：${address}</div>` : ''}
                        ${phone ? `<div>电话：${phone}</div>` : ''}
                        ${hours ? `<div>营业时间：${hours}</div>` : ''}
                    </div>
                </div>
                <div class="section">
                    <div class="content">${content || '<div class="empty">暂无正文内容</div>'}</div>
                </div>
            `;
        };

        if (!itemId) {
            detailRoot.innerHTML = '<div class="section"><div class="empty">缺少内容参数</div></div>';
        } else {
            fetch(`${apiBase}/api/food/detail?id=${encodeURIComponent(itemId)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        detailRoot.innerHTML = `<div class="section"><div class="empty">${escapeHtml(data.error)}</div></div>`;
                        return;
                    }
                    renderDetail(data.item || {});
                })
                .catch(() => {
                    detailRoot.innerHTML = '<div class="section"><div class="empty">加载失败，请稍后再试</div></div>';
                });
        }
    </script>
</body>

</html>