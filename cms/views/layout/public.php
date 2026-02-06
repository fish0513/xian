<?php
$base = $GLOBALS['config']['app']['base_url'] ?? '';
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

        .list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .list-item {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 12px;
            align-items: center;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .list-item img {
            width: 120px;
            height: 90px;
            border-radius: 8px;
            object-fit: cover;
            background: #e5e7eb;
        }

        .list-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .list-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .list-meta {
            font-size: 12px;
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            gap: 2px;
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
        <?php echo $content; ?>
    </div>
</body>

</html>