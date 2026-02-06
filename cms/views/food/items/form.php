<?php
$isEdit = ($mode ?? '') === 'edit';
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$item = $item ?? [];
?>
<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $isEdit ? '编辑内容' : '新增内容'; ?>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                <?php echo $isEdit ? '修改美食内容信息' : '创建新的美食内容'; ?>
            </p>
        </div>
        <a href="<?php echo View::e($base); ?>/admin/food/items"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            返回列表
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?php echo View::e($error); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <form id="food-item-form" method="post" action="<?php echo View::e($base); ?><?php echo $isEdit ? '/admin/food/items/edit' : '/admin/food/items/create'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo View::e((string)$item['id']); ?>">
            <?php endif; ?>

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="category_id" class="block text-sm font-medium leading-6 text-gray-900">子栏目</label>
                        <div class="mt-2">
                            <select id="category_id" name="category_id" required
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <option value="">请选择</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo View::e((string)$c['id']); ?>" <?php echo ((string)($item['category_id'] ?? '') === (string)$c['id']) ? 'selected' : ''; ?>>
                                        <?php echo View::e($c['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="title" class="block text-sm font-medium leading-6 text-gray-900">标题</label>
                        <div class="mt-2">
                            <input id="title" name="title" type="text" required
                                value="<?php echo View::e($item['title'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="subtitle" class="block text-sm font-medium leading-6 text-gray-900">副标题</label>
                        <div class="mt-2">
                            <input id="subtitle" name="subtitle" type="text"
                                value="<?php echo View::e($item['subtitle'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="cover_url" class="block text-sm font-medium leading-6 text-gray-900">封面图</label>
                        <div class="mt-2">
                            <input type="hidden" id="cover_url" name="cover_url" value="<?php echo View::e($item['cover_url'] ?? ''); ?>">

                            <div id="upload_area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary-500 transition-colors cursor-pointer bg-gray-50">
                                <div class="space-y-1 text-center w-full">
                                    <div id="preview_container" class="<?php echo empty($item['cover_url']) ? 'hidden' : ''; ?> mb-4 relative group">
                                        <img id="cover_preview" src="<?php echo View::e($item['cover_url'] ?? ''); ?>" class="mx-auto h-48 w-full max-w-xl object-cover rounded-md shadow-sm">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-md">
                                            <p class="text-white text-sm font-medium">点击更换图片</p>
                                        </div>
                                    </div>

                                    <svg id="upload_icon" class="mx-auto h-12 w-12 text-gray-400 <?php echo !empty($item['cover_url']) ? 'hidden' : ''; ?>" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <div class="text-sm text-gray-600">
                                        <label for="file_input" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>点击上传</span>
                                            <input id="file_input" type="file" class="sr-only" accept="image/jpeg,image/png,image/gif,image/webp">
                                        </label>
                                        <span class="pl-1">或拖拽文件到此处</span>
                                    </div>
                                    <div class="mt-1">
                                        <button type="button" id="select_btn" class="text-sm font-medium text-primary-600 hover:text-primary-500 focus:outline-none">从图库选择</button>
                                    </div>
                                    <p class="text-xs text-gray-500">支持 JPG, PNG, GIF, WEBP (Max 5MB)</p>
                                    <p id="upload_status" class="text-xs font-medium mt-2 h-4"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="content" class="block text-sm font-medium leading-6 text-gray-900">正文</label>
                        <div class="mt-2">
                            <input type="hidden" name="content" id="content" value="<?php echo View::e($item['content'] ?? ''); ?>">
                            <div id="editor-container" class="bg-white block w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" style="height: 300px;">
                                <?php echo $item['content'] ?? ''; ?>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="address" class="block text-sm font-medium leading-6 text-gray-900">地址</label>
                        <div class="mt-2">
                            <input id="address" name="address" type="text"
                                value="<?php echo View::e($item['address'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">电话</label>
                        <div class="mt-2">
                            <input id="phone" name="phone" type="text"
                                value="<?php echo View::e($item['phone'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="business_hours" class="block text-sm font-medium leading-6 text-gray-900">营业时间</label>
                        <div class="mt-2">
                            <input type="hidden" id="business_hours" name="business_hours" value="<?php echo View::e($item['business_hours'] ?? ''); ?>">
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="time_start" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="开始时间">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-gray-500">至</span>
                                <div class="relative flex-1">
                                    <input type="text" id="time_end" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="结束时间">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="recommend_score" class="block text-sm font-medium leading-6 text-gray-900">推荐指数</label>
                        <div class="mt-2">
                            <select id="recommend_score" name="recommend_score"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 text-yellow-500 font-bold tracking-widest">
                                <?php
                                $currentScore = (int)($item['recommend_score'] ?? 0);
                                $options = [3, 4, 5];
                                foreach ($options as $score):
                                ?>
                                    <option value="<?php echo $score; ?>" <?php echo $currentScore === $score ? 'selected' : ''; ?>>
                                        <?php echo str_repeat('★', $score) . str_repeat('☆', 5 - $score); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="latitude" class="block text-sm font-medium leading-6 text-gray-900">
                            纬度
                            <a href="https://lbs.amap.com/tools/picker" target="_blank" class="ml-2 text-primary-600 hover:text-primary-500 text-xs font-normal underline decoration-dashed">
                                (去地图查找坐标)
                            </a>
                        </label>
                        <div class="mt-2">
                            <input id="latitude" name="latitude" type="text" placeholder="例如：31.81056"
                                value="<?php echo View::e((string)($item['latitude'] ?? '')); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="longitude" class="block text-sm font-medium leading-6 text-gray-900">
                            经度
                            <a href="https://lbs.amap.com/tools/picker" target="_blank" class="ml-2 text-primary-600 hover:text-primary-500 text-xs font-normal underline decoration-dashed">
                                (去地图查找坐标)
                            </a>
                        </label>
                        <div class="mt-2">
                            <input id="longitude" name="longitude" type="text" placeholder="例如：121.65879"
                                value="<?php echo View::e((string)($item['longitude'] ?? '')); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">排序</label>
                        <div class="mt-2">
                            <input id="sort_order" name="sort_order" type="number"
                                value="<?php echo View::e((string)($item['sort_order'] ?? 0)); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_recommended" value="1" <?php echo !empty($item['is_recommended']) ? 'checked' : ''; ?> class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                推荐
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_pinned" value="1" <?php echo !empty($item['is_pinned']) ? 'checked' : ''; ?> class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                置顶
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_active" value="1" <?php echo !isset($item['is_active']) || !empty($item['is_active']) ? 'checked' : ''; ?> class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                上线
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50/50 rounded-b-xl">
                <button type="button" onclick="history.back()" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">取消</button>
                <button type="submit" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-colors">
                    保存
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Library Modal -->
<div id="image_library_modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modal_backdrop"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">选择图片</h3>
                <div class="mt-4">
                    <div id="image_list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 max-h-96 overflow-y-auto p-2">
                        <!-- Images will be loaded here -->
                        <p class="col-span-full text-center text-gray-500 py-8">加载中...</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button" id="modal_cancel_btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:col-start-1 sm:text-sm">取消</button>
            </div>
        </div>
    </div>
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/zh.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Flatpickr for Business Hours
        const timeStartInput = document.getElementById('time_start');
        const timeEndInput = document.getElementById('time_end');
        const businessHoursInput = document.getElementById('business_hours');

        const updateBusinessHours = () => {
            const start = timeStartInput.value;
            const end = timeEndInput.value;
            if (start && end) {
                businessHoursInput.value = `${start}-${end}`;
            } else if (start) {
                businessHoursInput.value = start;
            } else {
                businessHoursInput.value = '';
            }
        };

        // Parse initial value
        if (businessHoursInput.value) {
            const parts = businessHoursInput.value.split('-');
            if (parts.length === 2) {
                timeStartInput.value = parts[0];
                timeEndInput.value = parts[1];
            } else {
                timeStartInput.value = businessHoursInput.value;
            }
        }

        const commonConfig = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            locale: "zh",
            onChange: updateBusinessHours
        };

        flatpickr(timeStartInput, commonConfig);
        flatpickr(timeEndInput, commonConfig);

        // Initialize Quill
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        var form = document.getElementById('food-item-form');
        if (form) {
            form.addEventListener('submit', () => {
                var contentInput = document.getElementById('content');
                if (contentInput) {
                    contentInput.value = quill.root.innerHTML;
                }
            });
        }

        const uploadArea = document.getElementById('upload_area');
        const fileInput = document.getElementById('file_input');
        const selectBtn = document.getElementById('select_btn');
        const modal = document.getElementById('image_library_modal');
        const modalBackdrop = document.getElementById('modal_backdrop');
        const modalCancelBtn = document.getElementById('modal_cancel_btn');
        const imageList = document.getElementById('image_list');
        const coverUrlInput = document.getElementById('cover_url');
        const previewContainer = document.getElementById('preview_container');
        const coverPreview = document.getElementById('cover_preview');
        const uploadIcon = document.getElementById('upload_icon');
        const uploadStatus = document.getElementById('upload_status');
        const csrfTokenEl = document.querySelector('input[name="csrf_token"]');
        const csrfToken = csrfTokenEl ? csrfTokenEl.value : '';
        const baseUrl = <?php echo json_encode($base); ?>;

        if (!uploadArea || !fileInput || !coverUrlInput || !previewContainer || !coverPreview || !uploadIcon || !uploadStatus) {
            return;
        }

        const uploadFile = (file) => {
            const formData = new FormData();
            formData.append('file', file);
            if (csrfToken !== '') {
                formData.append('csrf_token', csrfToken);
            }

            uploadStatus.textContent = '上传中...';
            uploadStatus.classList.remove('text-red-500', 'text-green-500');
            uploadStatus.classList.add('text-primary-600');

            fetch(baseUrl + '/admin/upload', {
                    method: 'POST',
                    body: formData
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data && data.error) {
                        uploadStatus.textContent = '上传失败: ' + data.error;
                        uploadStatus.classList.add('text-red-500');
                        uploadStatus.classList.remove('text-primary-600', 'text-green-500');
                        return;
                    }

                    const url = data && data.url ? data.url : '';
                    if (url === '') {
                        uploadStatus.textContent = '上传失败: 返回结果异常';
                        uploadStatus.classList.add('text-red-500');
                        uploadStatus.classList.remove('text-primary-600', 'text-green-500');
                        return;
                    }

                    coverUrlInput.value = url;
                    coverPreview.src = url;
                    previewContainer.classList.remove('hidden');
                    uploadIcon.classList.add('hidden');

                    uploadStatus.textContent = '上传成功';
                    uploadStatus.classList.add('text-green-500');
                    uploadStatus.classList.remove('text-primary-600', 'text-red-500');

                    fileInput.value = '';
                })
                .catch(() => {
                    uploadStatus.textContent = '上传出错';
                    uploadStatus.classList.add('text-red-500');
                    uploadStatus.classList.remove('text-primary-600', 'text-green-500');
                });
        };

        if (selectBtn) {
            selectBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openImageLibrary();
            });
        }

        if (modalCancelBtn) {
            modalCancelBtn.addEventListener('click', closeImageLibrary);
        }

        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', closeImageLibrary);
        }

        function openImageLibrary() {
            modal.classList.remove('hidden');
            fetchImages();
        }

        function closeImageLibrary() {
            modal.classList.add('hidden');
        }

        function fetchImages() {
            fetch(baseUrl + '/admin/upload/list')
                .then(response => response.json())
                .then(data => {
                    if (data.files && data.files.length > 0) {
                        imageList.innerHTML = '';
                        data.files.forEach(file => {
                            const div = document.createElement('div');
                            div.className = 'relative group cursor-pointer border border-gray-200 rounded-lg overflow-hidden hover:border-primary-500 hover:ring-2 hover:ring-primary-500 transition-all';
                            div.onclick = () => selectImage(file.url);

                            const img = document.createElement('img');
                            img.src = file.url;
                            img.className = 'w-full h-32 object-cover';

                            const name = document.createElement('div');
                            name.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate';
                            name.textContent = file.name;

                            div.appendChild(img);
                            div.appendChild(name);
                            imageList.appendChild(div);
                        });
                    } else {
                        imageList.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">暂无图片</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching images:', error);
                    imageList.innerHTML = '<p class="col-span-full text-center text-red-500 py-8">加载失败</p>';
                });
        }

        function selectImage(url) {
            coverUrlInput.value = url;
            coverPreview.src = url;
            previewContainer.classList.remove('hidden');
            uploadIcon.classList.add('hidden');
            closeImageLibrary();
        }

        if (uploadArea) {
            uploadArea.addEventListener('click', (e) => {
                // If clicked on "从图库选择" button or inside it, don't trigger file input
                if (e.target.closest('#select_btn')) {
                    return;
                }
                const target = e.target;
                if (target instanceof Element) {
                    if (target.closest('label[for="file_input"]')) {
                        return;
                    }
                }
                fileInput.click();
            });
        }

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files.length > 0) {
                uploadFile(fileInput.files[0]);
            }
        });

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
            uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach((eventName) => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('border-primary-500', 'bg-primary-50');
                uploadArea.classList.remove('border-gray-300', 'bg-gray-50');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
                uploadArea.classList.add('border-gray-300', 'bg-gray-50');
            });
        });

        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt ? dt.files : null;
            if (files && files.length > 0) {
                uploadFile(files[0]);
            }
        });
    });
</script>