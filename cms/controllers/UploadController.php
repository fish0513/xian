<?php

class UploadController
{
    public function upload(): void
    {
        Auth::requireLogin();
        
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded or upload error']);
            return;
        }

        $file = $_FILES['file'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, GIF, WEBP are allowed.']);
            return;
        }

        // Limit size to 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['error' => 'File too large. Max size is 5MB.']);
            return;
        }

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename: YYYYMMDD_Random.ext
        $filename = date('Ymd') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $base = $GLOBALS['config']['app']['base_url'] ?? '';
            // Handle base_url having index.php or not
            $baseUrl = $base;
            if (strpos($baseUrl, '/index.php') !== false) {
                $baseUrl = dirname($baseUrl);
            }
            $baseUrl = rtrim($baseUrl, '/');
            
            // Assuming cms is the root or subfolder, the public URL should be relative to it
            // If base_url is /cms, then uploads are at /cms/uploads/filename
            
            $url = $baseUrl . '/uploads/' . $filename;
            
            echo json_encode(['url' => $url]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
    }

    public function list(): void
    {
        Auth::requireLogin();
        header('Content-Type: application/json');

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            echo json_encode(['files' => []]);
            return;
        }

        $base = $GLOBALS['config']['app']['base_url'] ?? '';
        $baseUrl = $base;
        if (strpos($baseUrl, '/index.php') !== false) {
            $baseUrl = dirname($baseUrl);
        }
        $baseUrl = rtrim($baseUrl, '/');

        $files = [];
        $scandir = scandir($uploadDir);
        foreach ($scandir as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $uploadDir . $file;
            if (is_file($path)) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $files[] = [
                        'name' => $file,
                        'url' => $baseUrl . '/uploads/' . $file,
                        'time' => filemtime($path)
                    ];
                }
            }
        }

        // Sort by time desc
        usort($files, function($a, $b) {
            return $b['time'] - $a['time'];
        });

        echo json_encode(['files' => $files]);
    }
}
