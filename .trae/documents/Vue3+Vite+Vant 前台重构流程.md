## 目标与边界
- 目标：把当前 /food 下的前台（封面/列表/详情）从 PHP+原生 JS 重构为 Vue 3 + Vite + Vant 的 H5 前端。
- 边界：后端 CMS 仍保留在 /cms；前端只通过现有 API（/cms/api/food/cover、/cms/api/food/list、/cms/api/food/detail）取数，尽量不改接口。

## 现状映射（页面 → Vue 路由）
- /food/（封面页）→ Vue 路由：/ （Home）
- /food/list 或 /food/list.php（列表页）→ Vue 路由：/list?category_id=… 或 /list?category_code=…（CategoryList）
- /food/detail.php?id=…（详情页）→ Vue 路由：/detail/:id 或 /detail?id=…（Detail）

## 前端工程搭建
- 新建前端工程（Vue 3 + Vite + TS 可选），选择移动端适配方案（Vant + viewport 方案）。
- 引入 Vant（按需引入）并统一主题变量（颜色、圆角、字号）。
- 配置基础能力：
  - 路由：vue-router（History 模式部署到 /food 需配 Nginx fallback）
  - 状态：先不引入 Pinia，API 数据以页面级 state 为主（后续再抽）
  - 网络：axios/fetch 封装 + 统一错误处理
  - 环境变量：VITE_API_BASE（默认 /cms）

## API 层与数据模型
- 封装 foodApi：
  - getCover() → GET /api/food/cover
  - getList(params) → GET /api/food/list?category_id/category_code&limit&offset
  - getDetail(id) → GET /api/food/detail?id=
- 统一响应处理：
  - 兼容 {error: string} 与正常 payload
  - 列表分页：offset/limit 规则与“加载更多”一致

## 页面实现（用 Vant 组件落地）
- Home（封面页）
  - 使用 Card/Grid/Cell 组合实现“子栏目 + 6 条推荐 + 更多”
  - 卡片点击直达 Detail
  - “更多”跳到 List 并带 category_id
- CategoryList（列表页）
  - 使用 List（无限滚动）或 PullRefresh + List
  - 每项点击直达 Detail
- Detail（内容页）
  - 使用 Image + CellGroup 展示封面/标题/地址/电话/营业时间
  - content 富文本：优先安全渲染（后端若包含 HTML，前端需做白名单策略或改为纯文本/受控标签）

## 路由与部署（/food 下的 SPA）
- Vite build 输出到一个静态目录（例如 /food/dist 或单独 /public/food），通过 Nginx 以静态资源方式提供。
- Nginx 规则：
  - /food/assets/* 走静态文件
  - /food/* fallback 到 /food/index.html（让 vue-router 接管）
  - 保留 /cms 现有规则不变

## 渐进式替换策略（降低风险）
- 第一步：保持现有 /food/*.php 可用，同时新增 /food/app（或 /food/spa）部署 Vue 版，便于对照测试。
- 第二步：Vue 版功能对齐后，把 /food/ 主入口切到 Vue 的 index.html，并保留 PHP 版做回退（可选）。

## 验收与联调点
- 路由：/food/、/food/list、/food/detail/1 均可直达且刷新不 404
- 交互：加载态、失败态、空态完整；列表“加载更多”正确终止
- 规则：列表排序仍由后端保证（置顶/推荐优先），前端不做二次排序
- 移动端：安全区、字体、点击区域、滚动性能 OK

## 需要你确认的关键选择（我先按默认做）
- 默认选择 SPA + vue-router History，部署在 /food（需要 Nginx fallback）。
- 默认 API base 为 /cms（对应当前 config 的 base_url）。