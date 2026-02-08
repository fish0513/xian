<?php
$base = $base ?? ($GLOBALS['config']['app']['base_url'] ?? '');
$categoryId = (int)($categoryId ?? 0);
$categoryCode = trim($categoryCode ?? '');
?>
<div class="header">
    <div>
        <div class="title">子栏目列表</div>
        <div class="subtitle">按推荐与置顶规则排序</div>
    </div>
    <div class="toolbar">
        <a class="btn btn-outline" href="<?php echo View::e($base); ?>/food">返回封面</a>
    </div>
</div>

<div id="list-root"
    data-base="<?php echo View::e($base); ?>"
    data-category-id="<?php echo View::e((string)$categoryId); ?>"
    data-category-code="<?php echo View::e($categoryCode); ?>"></div>

<script>
    const listRoot = document.getElementById('list-root');
    const baseUrl = listRoot.dataset.base || '';
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

        fetch(`${baseUrl}/api/food/list?${params.toString()}`)
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