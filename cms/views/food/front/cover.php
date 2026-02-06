<?php
$base = $base ?? ($GLOBALS['config']['app']['base_url'] ?? '');
?>
<div class="header">
    <div>
        <div class="title">美食启东</div>
        <div class="subtitle">推荐内容与子栏目精选</div>
    </div>
    <div class="toolbar">
        <a class="btn btn-outline" href="<?php echo View::e($base); ?>/admin/login">进入后台</a>
    </div>
</div>

<div id="cover-root" data-base="<?php echo View::e($base); ?>"></div>

<script>
    const coverRoot = document.getElementById('cover-root');
    const baseUrl = coverRoot.dataset.base || '';

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
        return `
            <div class="card">
                ${cover ? `<img src="${cover}" alt="${title}">` : `<div style="height:120px;background:#e5e7eb;"></div>`}
                <div class="card-body">
                    <div class="card-title">${title}</div>
                    ${subtitle ? `<div class="card-subtitle">${subtitle}</div>` : ''}
                    ${(address || phone || hours) ? `<div class="meta">${address ? address : ''}${address && phone ? ' · ' : ''}${phone ? phone : ''}${(address || phone) && hours ? ' · ' : ''}${hours ? hours : ''}</div>` : ''}
                </div>
            </div>
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
            const moreLink = `${baseUrl}/food/list?category_id=${encodeURIComponent(category.id)}`;
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

    fetch(`${baseUrl}/api/food/cover`)
        .then(res => res.json())
        .then(renderCover)
        .catch(() => {
            coverRoot.innerHTML = '<div class="section"><div class="empty">加载失败，请稍后再试</div></div>';
        });
</script>