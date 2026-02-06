<?php
$config = require __DIR__ . '/../cms/config/config.php';
$apiBase = $config['app']['base_url'] ?? '/cms';
$categoryId = (int)($_GET['category_id'] ?? 0);
$categoryCode = trim($_GET['category_code'] ?? '');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>美食启东</title>
    <style>
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "PingFang SC", "Hiragino Sans GB", "Microsoft Yahei", sans-serif; background: #f7f7f8; color: #1f2937; }
        a { color: inherit; text-decoration: none; }
        .page { max-width: 980px; margin: 0 auto; padding: 24px 16px 40px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .title { font-size: 24px; font-weight: 700; }
        .subtitle { font-size: 14px; color: #6b7280; margin-top: 4px; }
        .section { background: #fff; border-radius: 12px; padding: 16px; margin-bottom: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.06); }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .section-title { font-size: 18px; font-weight: 600; }
        .list { display: flex; flex-direction: column; gap: 12px; }
        .list-item { background: #fff; border-radius: 12px; padding: 12px; display: grid; grid-template-columns: 120px 1fr; gap: 12px; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.06); }
        .list-item img { width: 120px; height: 90px; border-radius: 8px; object-fit: cover; background: #e5e7eb; }
        .list-title { font-size: 16px; font-weight: 600; margin-bottom: 6px; }
        .list-subtitle { font-size: 13px; color: #6b7280; margin-bottom: 6px; }
        .list-meta { font-size: 12px; color: #9ca3af; display: flex; flex-direction: column; gap: 2px; }
        .empty { text-align: center; color: #9ca3af; padding: 24px 0; }
        .toolbar { display: flex; align-items: center; gap: 12px; }
        .btn { border: none; padding: 8px 14px; border-radius: 8px; background: #2563eb; color: #fff; font-size: 13px; cursor: pointer; }
        .btn-outline { background: #fff; color: #2563eb; border: 1px solid #dbeafe; }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="title">子栏目列表</div>
                <div class="subtitle">按推荐与置顶规则排序</div>
            </div>
            <div class="toolbar">
                <a class="btn btn-outline" href="/food/">返回封面</a>
            </div>
        </div>

        <div id="list-root"
             data-api="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>"
             data-category-id="<?php echo htmlspecialchars((string)$categoryId, ENT_QUOTES, 'UTF-8'); ?>"
             data-category-code="<?php echo htmlspecialchars($categoryCode, ENT_QUOTES, 'UTF-8'); ?>"></div>
    </div>

    <script>
        const listRoot = document.getElementById('list-root');
        const apiBase = listRoot.dataset.api || '';
        const categoryId = listRoot.dataset.categoryId || '';
        const categoryCode = listRoot.dataset.categoryCode || '';
        const limit = 20;
        let offset = 0;
        let done = false;
        let itemsCache = [];

        const escapeHtml = (value) => {
            const div = document.createElement('div');
            div.textContent = value == null ? '' : String(value);
            return div.innerHTML;
        };

        const buildItem = (item) => {
            const title = escapeHtml(item.title);
            const subtitle = escapeHtml(item.subtitle || '');
            const cover = item.cover_url ? escapeHtml(item.cover_url) : '';
            const address = escapeHtml(item.address || '');
            const phone = escapeHtml(item.phone || '');
            const hours = escapeHtml(item.business_hours || '');
            return `
                <div class="list-item">
                    ${cover ? `<img src="${cover}" alt="${title}">` : `<div style="width:120px;height:90px;background:#e5e7eb;border-radius:8px;"></div>`}
                    <div>
                        <div class="list-title">${title}</div>
                        ${subtitle ? `<div class="list-subtitle">${subtitle}</div>` : ''}
                        <div class="list-meta">
                            ${address ? `<div>地址：${address}</div>` : ''}
                            ${phone ? `<div>电话：${phone}</div>` : ''}
                            ${hours ? `<div>营业时间：${hours}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
        };

        const render = (title, items, hasMore) => {
            listRoot.innerHTML = `
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">${escapeHtml(title || '子栏目')}</div>
                    </div>
                    <div class="list">
                        ${items.length ? items.map(buildItem).join('') : '<div class="empty">暂无内容</div>'}
                    </div>
                    ${hasMore ? '<div style="text-align:center;margin-top:12px;"><button id="load-more" class="btn">加载更多</button></div>' : ''}
                </div>
            `;

            const btn = document.getElementById('load-more');
            if (btn) {
                btn.addEventListener('click', () => {
                    if (!done) {
                        loadMore();
                    }
                });
            }
        };

        const loadMore = () => {
            if (done) {
                return;
            }
            const params = new URLSearchParams();
            if (categoryId) {
                params.set('category_id', categoryId);
            } else if (categoryCode) {
                params.set('category_code', categoryCode);
            }
            params.set('limit', String(limit));
            params.set('offset', String(offset));

            fetch(`${apiBase}/api/food/list?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    const categories = data.categories || [];
                    const category = categories[0] || {};
                    const items = category.items || [];
                    itemsCache = itemsCache.concat(items);
                    offset += items.length;
                    if (items.length < limit) {
                        done = true;
                    }
                    render(category.name, itemsCache, !done);
                })
                .catch(() => {
                    listRoot.innerHTML = '<div class="section"><div class="empty">加载失败，请稍后再试</div></div>';
                });
        };

        if (!categoryId && !categoryCode) {
            listRoot.innerHTML = '<div class="section"><div class="empty">缺少子栏目参数</div></div>';
        } else {
            loadMore();
        }
    </script>
</body>

</html>
