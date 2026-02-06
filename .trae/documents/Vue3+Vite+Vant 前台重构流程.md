## 目标与约束
- 目标：用 Vue 3 + Vite + Vant 重构“美食”前台，并按 UI 设计稿 eat_list.png 做自定义视觉（不是 Vant 默认样式）。
- 约束：所有前端文件仍放在 /food 目录下；后端 CMS 仍在 /cms，前端只调用 /cms/api/food/*。

## 现状映射（页面 → Vue 路由）
- 封面页（当前 /food/）→ /food/（Home）
- 列表页（当前 /food/list(.php)）→ /food/list（CategoryList）
- 详情页（当前 /food/detail(.php)）→ /food/detail/:id（Detail）

## 设计稿落地方式（eat_list.png）
- 先把设计稿拆成 3 类可复用组件：
  - 顶部头图 Hero：蓝色天空背景 + “吃在启东”标题（可做成一张静态图或分层图片）
  - 分组标题 SectionTitle：如“美食商圈”“乡村美食”，居中大字
  - 列表项 FoodRow：左右布局（左：大字号名称；右：圆角缩略图），整体圆角卡片、浅蓝底、阴影/描边
- 样式实现以“自定义 CSS + 轻量使用 Vant”为主：
  - Vant 主要用在：List（滚动加载）、Loading、Toast/Dialog、Image(可选)
  - 视觉（背景、卡片、排版、字体、圆角）全部按设计稿写自定义样式，不依赖 Vant 默认皮肤

## 移动端适配策略（H5）
- 采用 viewport + rem 或者 vw 方案（二选一，推荐 vw/viewport 单位更贴合设计稿尺寸）：
  - 使用 postcss-px-to-viewport（或 postcss-pxtorem）把设计稿 px 转为 vw/rem
  - 处理安全区：padding-bottom 使用 env(safe-area-inset-bottom)
  - 图片用 object-fit: cover，避免拉伸

## 前端工程结构（全部在 /food 下）
- 在 /food 内新增一个前端工程目录（例如 /food/web/），构建产物输出到 /food/app/：
  - /food/web/：源码（src、vite.config、package.json）
  - /food/app/：产物（index.html、assets/*）
- 这样满足“文件仍在 /food”，同时也避免和现有 /food/*.php 混在一起。

## API 层与数据处理
- 封装 foodApi：
  - getCover() → GET /cms/api/food/cover
  - getList(params) → GET /cms/api/food/list?category_id/category_code&limit&offset
  - getDetail(id) → GET /cms/api/food/detail?id=
- 统一错误/空态：后端返回 {error} 时，前端显示设计稿风格的空态区域。

## 页面实现步骤（按优先级）
1) Home（封面页，重点按 eat_list.png 还原）
   - 背景色、Hero 头图、分组标题样式
   - 每个分组渲染 1~N 个 FoodRow（设计稿为竖向列表样式）
   - 列表项点击进入 Detail
   - “更多”进入 CategoryList（可保留按钮但按设计稿弱化）
2) CategoryList（列表页）
   - 复用 FoodRow 组件，加入 Vant List 实现滚动加载
   - 点击进入 Detail
3) Detail（详情页）
   - 先按现有字段完成可用版（封面/标题/地址/电话/营业时间/正文），样式后续再根据设计稿细化
   - content 富文本：如果后端内容包含 HTML，前端需做白名单渲染/净化（建议后端改为受控标签或纯文本，避免 XSS）

## Nginx 路由与无扩展访问
- 目标：/food、/food/list、/food/detail/1 均可直达且刷新不 404。
- Nginx（概念性规则）：
  - /food/app/assets/* 走静态文件
  - /food/* fallback 到 /food/app/index.html（SPA 托管）
  - 保留 /cms 现有规则不动
- 现有 /food/*.php 可作为回退方案保留，切换入口时再决定是否下线。

## 渐进式上线策略（降低风险）
- 阶段 A：先让 /food/app 可访问（不影响当前 PHP 版）用于联调与验收
- 阶段 B：验收通过后，把 /food 主入口切到 Vue（Nginx fallback 到 /food/app/index.html）

## 验收清单（贴合设计稿）
- 样式：颜色、圆角、间距、字体层级与 eat_list.png 一致
- 交互：点击列表项进入详情；返回体验顺畅
- 适配：不同手机宽度下不溢出、不卡顿；图片裁剪正确
- 数据：子栏目分组、列表加载、详情展示均可用

## 我将默认采用的选择
- Vue 工程源码放 /food/web，构建产物放 /food/app（都在 /food 下）。
- Vant 仅作为交互与基础能力组件库，UI 完全自定义实现设计稿风格。