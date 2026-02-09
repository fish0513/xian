<?php
$isEdit = ($mode ?? '') === 'edit';
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$item = $item ?? [];
?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $isEdit ? '编辑内容' : '新增内容'; ?>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                <?php echo $isEdit ? '修改乐购内容信息' : '创建新的乐购内容'; ?>
            </p>
        </div>
        <a href="<?php echo View::e($base); ?>/admin/shop/items"
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
        <form id="shop-item-form" method="post" action="<?php echo View::e($base); ?><?php echo $isEdit ? '/admin/shop/items/edit' : '/admin/shop/items/create'; ?>">
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
                                    <option value="<?php echo View::e((string)$c['id']); ?>" data-code="<?php echo View::e($c['code'] ?? ''); ?>" <?php echo ((string)($item['category_id'] ?? '') === (string)$c['id']) ? 'selected' : ''; ?>>
                                        <?php echo View::e($c['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="shop_name" class="block text-sm font-medium leading-6 text-gray-900">店铺名称</label>
                        <div class="mt-2">
                            <input id="shop_name" name="shop_name" type="text" required
                                value="<?php echo View::e($item['shop_name'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium leading-6 text-gray-900">店铺Logo</label>
                        <div class="mt-2">
                            <input type="hidden" id="shop_logo" name="shop_logo" value="<?php echo View::e($item['shop_logo'] ?? ''); ?>">
                            <div id="logo_upload_area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-primary-500 transition-colors cursor-pointer bg-gray-50">
                                <div class="space-y-1 text-center w-full">
                                    <div id="logo_preview_container" class="<?php echo empty($item['shop_logo']) ? 'hidden' : ''; ?> mb-4 relative group">
                                        <img id="logo_preview" src="<?php echo View::e($item['shop_logo'] ?? ''); ?>" class="mx-auto h-40 w-40 object-cover rounded-md shadow-sm bg-white">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-md">
                                            <p class="text-white text-sm font-medium">点击更换图片</p>
                                        </div>
                                    </div>

                                    <svg id="logo_upload_icon" class="mx-auto h-12 w-12 text-gray-400 <?php echo !empty($item['shop_logo']) ? 'hidden' : ''; ?>" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <div class="text-sm text-gray-600">
                                        <label for="logo_file_input" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>点击上传</span>
                                            <input id="logo_file_input" type="file" class="sr-only" accept="image/jpeg,image/png,image/gif,image/webp">
                                        </label>
                                        <span class="pl-1">或拖拽文件到此处</span>
                                    </div>
                                    <div class="mt-1">
                                        <button type="button" id="logo_select_btn" class="text-sm font-medium text-primary-600 hover:text-primary-500 focus:outline-none">从图库选择</button>
                                    </div>
                                    <p class="text-xs text-gray-500">支持 JPG, PNG, GIF, WEBP (Max 5MB)</p>
                                    <p id="logo_upload_status" class="text-xs font-medium mt-2 h-4"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium leading-6 text-gray-900">门店图片</label>
                        <div class="mt-2">
                            <input type="hidden" id="shop_images" name="shop_images" value="<?php echo View::e($item['shop_images'] ?? ''); ?>">

                            <div id="images_preview_grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3"></div>

                            <div class="flex items-center gap-3">
                                <label for="images_file_input" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm cursor-pointer">
                                    选择并上传
                                    <input id="images_file_input" type="file" class="sr-only" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                                </label>
                                <button type="button" id="images_select_btn" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm">从图库选择</button>
                                <span id="images_upload_status" class="text-xs font-medium h-4"></span>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="shop_intro" class="block text-sm font-medium leading-6 text-gray-900">店铺简介</label>
                        <div class="mt-2">
                            <input type="hidden" name="shop_intro" id="shop_intro" value="<?php echo View::e($item['shop_intro'] ?? ''); ?>">
                            <div id="shop_intro_editor" class="bg-white block w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" style="height: 220px;">
                                <?php echo $item['shop_intro'] ?? ''; ?>
                            </div>
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
                            <label id="is_first_store_wrap" class="inline-flex items-center gap-2 text-sm text-gray-700 hidden">
                                <input type="checkbox" id="is_first_store" name="is_first_store" value="1" <?php echo !empty($item['is_first_store']) ? 'checked' : ''; ?> class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                首店
                            </label>
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

<div id="image_library_modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modal_backdrop"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">选择图片</h3>
                <div class="mt-4">
                    <div class="flex items-center justify-between gap-4 mb-3">
                        <div class="text-sm text-gray-600">点击图片进行选择</div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="modal_confirm_btn" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 hover:bg-primary-700 text-white text-sm">确认选择</button>
                            <button type="button" id="modal_cancel_btn" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm">取消</button>
                        </div>
                    </div>
                    <div id="image_list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 max-h-96 overflow-y-auto p-2">
                        <p class="col-span-full text-center text-gray-500 py-8">加载中...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseUrl = <?php echo json_encode($base); ?>;
        const csrfTokenEl = document.querySelector('input[name="csrf_token"]');
        const csrfToken = csrfTokenEl ? csrfTokenEl.value : '';

        const shopIntroInput = document.getElementById('shop_intro');
        const shopIntroEditor = document.getElementById('shop_intro_editor');
        if (shopIntroInput && shopIntroEditor && window.Quill) {
            const shopIntroQuill = new Quill('#shop_intro_editor', {
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

            const form = document.getElementById('shop-item-form');
            if (form) {
                const replaceInlineImages = async (html) => {
                    const container = document.createElement('div');
                    container.innerHTML = html || '';
                    const images = Array.from(container.querySelectorAll('img'))
                        .filter(img => typeof img.src === 'string' && img.src.startsWith('data:image/'));

                    for (let i = 0; i < images.length; i++) {
                        const img = images[i];
                        const blob = await (await fetch(img.src)).blob();
                        const ext = (blob.type && blob.type.includes('/')) ? blob.type.split('/')[1] : 'png';
                        const file = new File([blob], `editor_${Date.now()}_${i}.${ext}`, {
                            type: blob.type || 'image/png'
                        });
                        const url = await uploadSingleFile(file);
                        img.src = url;
                    }

                    return container.innerHTML;
                };

                let submitting = false;
                form.addEventListener('submit', async (e) => {
                    if (submitting) return;
                    e.preventDefault();
                    try {
                        shopIntroInput.value = shopIntroQuill.root.innerHTML;
                        shopIntroInput.value = await replaceInlineImages(shopIntroInput.value);
                        submitting = true;
                        form.submit();
                    } catch (err) {
                        alert('简介图片上传失败，请稍后重试');
                    }
                });
            }

            const toolbar = shopIntroQuill.getModule('toolbar');
            if (toolbar) {
                toolbar.addHandler('image', () => {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';
                    input.click();
                    input.onchange = async () => {
                        if (!input.files || input.files.length === 0) return;
                        try {
                            const url = await uploadSingleFile(input.files[0]);
                            const range = shopIntroQuill.getSelection(true);
                            shopIntroQuill.insertEmbed(range ? range.index : 0, 'image', url, 'user');
                        } catch (e) {
                            alert('图片上传失败');
                        } finally {
                            input.value = '';
                        }
                    };
                });
            }
        }

        const categorySelect = document.getElementById('category_id');
        const firstStoreWrap = document.getElementById('is_first_store_wrap');
        const firstStoreCheckbox = document.getElementById('is_first_store');

        const updateFirstStoreVisibility = () => {
            if (!categorySelect || !firstStoreWrap || !firstStoreCheckbox) return;
            const selected = categorySelect.options[categorySelect.selectedIndex];
            const code = selected && selected.dataset ? (selected.dataset.code || '') : '';
            if (code === 'shopping_complex') {
                firstStoreWrap.classList.remove('hidden');
            } else {
                firstStoreWrap.classList.add('hidden');
                firstStoreCheckbox.checked = false;
            }
        };

        if (categorySelect) {
            categorySelect.addEventListener('change', updateFirstStoreVisibility);
            updateFirstStoreVisibility();
        }

        const parseImagesValue = (value) => {
            const raw = (value || '').trim();
            if (!raw) return [];
            try {
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed.filter(Boolean) : [];
            } catch (e) {
                return raw.split(',').map(s => s.trim()).filter(Boolean);
            }
        };

        const setStatus = (el, text, type) => {
            if (!el) return;
            el.textContent = text || '';
            el.classList.remove('text-red-500', 'text-green-500', 'text-primary-600', 'text-gray-600');
            if (type === 'error') el.classList.add('text-red-500');
            else if (type === 'success') el.classList.add('text-green-500');
            else if (type === 'loading') el.classList.add('text-primary-600');
            else el.classList.add('text-gray-600');
        };

        const uploadSingleFile = async (file) => {
            const formData = new FormData();
            formData.append('file', file);
            if (csrfToken !== '') formData.append('csrf_token', csrfToken);
            const res = await fetch(baseUrl + '/admin/upload', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data && data.error) {
                throw new Error(data.error);
            }
            const url = data && data.url ? data.url : '';
            if (!url) throw new Error('返回结果异常');
            return url;
        };

        const modal = document.getElementById('image_library_modal');
        const modalBackdrop = document.getElementById('modal_backdrop');
        const modalCancelBtn = document.getElementById('modal_cancel_btn');
        const modalConfirmBtn = document.getElementById('modal_confirm_btn');
        const imageList = document.getElementById('image_list');
        let modalMode = 'logo';
        let selectedUrls = [];

        const openModal = async (mode) => {
            modalMode = mode;
            selectedUrls = [];
            if (!modal || !imageList) return;
            modal.classList.remove('hidden');
            await fetchImages();
        };

        const closeModal = () => {
            if (!modal) return;
            modal.classList.add('hidden');
        };

        const fetchImages = async () => {
            if (!imageList) return;
            imageList.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">加载中...</p>';
            try {
                const res = await fetch(baseUrl + '/admin/upload/list');
                const data = await res.json();
                const files = (data && data.files) ? data.files : [];
                if (!files.length) {
                    imageList.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">暂无图片</p>';
                    return;
                }

                imageList.innerHTML = '';
                files.forEach(file => {
                    const url = file.url || '';
                    const div = document.createElement('div');
                    div.className = 'relative group cursor-pointer border border-gray-200 rounded-lg overflow-hidden hover:border-primary-500 hover:ring-2 hover:ring-primary-500 transition-all';
                    div.dataset.url = url;

                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'w-full h-32 object-cover';

                    const name = document.createElement('div');
                    name.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate';
                    name.textContent = file.name || '';

                    const badge = document.createElement('div');
                    badge.className = 'absolute top-1 right-1 bg-primary-600 text-white text-xs px-2 py-0.5 rounded-full hidden';
                    badge.textContent = '已选';

                    div.appendChild(img);
                    div.appendChild(name);
                    div.appendChild(badge);

                    div.addEventListener('click', () => {
                        if (!url) return;
                        if (modalMode === 'logo') {
                            selectedUrls = [url];
                            [...imageList.querySelectorAll('[data-url]')].forEach(el => {
                                const b = el.querySelector('div.absolute.top-1');
                                if (b) b.classList.add('hidden');
                            });
                            badge.classList.remove('hidden');
                            return;
                        }

                        const idx = selectedUrls.indexOf(url);
                        if (idx >= 0) {
                            selectedUrls.splice(idx, 1);
                            badge.classList.add('hidden');
                        } else {
                            selectedUrls.push(url);
                            badge.classList.remove('hidden');
                        }
                    });

                    imageList.appendChild(div);
                });
            } catch (e) {
                imageList.innerHTML = '<p class="col-span-full text-center text-red-500 py-8">加载失败</p>';
            }
        };

        if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);
        if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeModal);

        const shopLogoInput = document.getElementById('shop_logo');
        const logoUploadArea = document.getElementById('logo_upload_area');
        const logoFileInput = document.getElementById('logo_file_input');
        const logoSelectBtn = document.getElementById('logo_select_btn');
        const logoPreviewContainer = document.getElementById('logo_preview_container');
        const logoPreview = document.getElementById('logo_preview');
        const logoUploadIcon = document.getElementById('logo_upload_icon');
        const logoUploadStatus = document.getElementById('logo_upload_status');

        const setLogoUrl = (url) => {
            if (!shopLogoInput || !logoPreview || !logoPreviewContainer || !logoUploadIcon) return;
            shopLogoInput.value = url || '';
            if (url) {
                logoPreview.src = url;
                logoPreviewContainer.classList.remove('hidden');
                logoUploadIcon.classList.add('hidden');
            } else {
                logoPreviewContainer.classList.add('hidden');
                logoUploadIcon.classList.remove('hidden');
            }
        };

        const bindUploadArea = (area, input, onUploaded, statusEl) => {
            if (!area || !input) return;

            const handleUpload = async (file) => {
                try {
                    setStatus(statusEl, '上传中...', 'loading');
                    const url = await uploadSingleFile(file);
                    onUploaded(url);
                    setStatus(statusEl, '上传成功', 'success');
                } catch (e) {
                    setStatus(statusEl, '上传失败: ' + (e && e.message ? e.message : '未知错误'), 'error');
                }
            };

            area.addEventListener('click', (e) => {
                const target = e.target;
                if (target instanceof Element) {
                    if (target.closest('label[for="' + input.id + '"]')) return;
                    if (target.closest('button')) return;
                }
                input.click();
            });

            input.addEventListener('change', () => {
                if (input.files && input.files.length > 0) {
                    handleUpload(input.files[0]);
                    input.value = '';
                }
            });

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
                area.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            ['dragenter', 'dragover'].forEach((eventName) => {
                area.addEventListener(eventName, () => {
                    area.classList.add('border-primary-500', 'bg-primary-50');
                    area.classList.remove('border-gray-300', 'bg-gray-50');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                area.addEventListener(eventName, () => {
                    area.classList.remove('border-primary-500', 'bg-primary-50');
                    area.classList.add('border-gray-300', 'bg-gray-50');
                });
            });

            area.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt ? dt.files : null;
                if (files && files.length > 0) {
                    handleUpload(files[0]);
                }
            });
        };

        bindUploadArea(logoUploadArea, logoFileInput, setLogoUrl, logoUploadStatus);
        if (logoSelectBtn) logoSelectBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('logo');
        });

        const shopImagesInput = document.getElementById('shop_images');
        const imagesPreviewGrid = document.getElementById('images_preview_grid');
        const imagesFileInput = document.getElementById('images_file_input');
        const imagesSelectBtn = document.getElementById('images_select_btn');
        const imagesUploadStatus = document.getElementById('images_upload_status');

        let images = parseImagesValue(shopImagesInput ? shopImagesInput.value : '');

        const syncImagesInput = () => {
            if (!shopImagesInput) return;
            shopImagesInput.value = images.length ? JSON.stringify(images) : '';
        };

        const renderImages = () => {
            if (!imagesPreviewGrid) return;
            imagesPreviewGrid.innerHTML = '';
            images.forEach((url, idx) => {
                const wrap = document.createElement('div');
                wrap.className = 'relative border border-gray-200 rounded-lg overflow-hidden bg-white';

                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full h-28 object-cover';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'absolute top-1 right-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded';
                btn.textContent = '移除';
                btn.addEventListener('click', () => {
                    images.splice(idx, 1);
                    syncImagesInput();
                    renderImages();
                });

                wrap.appendChild(img);
                wrap.appendChild(btn);
                imagesPreviewGrid.appendChild(wrap);
            });
        };

        renderImages();
        syncImagesInput();

        const uploadMultiple = async (files) => {
            const list = Array.from(files || []);
            if (!list.length) return;
            setStatus(imagesUploadStatus, '上传中...', 'loading');
            try {
                for (const f of list) {
                    const url = await uploadSingleFile(f);
                    images.push(url);
                }
                images = images.filter(Boolean);
                syncImagesInput();
                renderImages();
                setStatus(imagesUploadStatus, '上传成功', 'success');
            } catch (e) {
                setStatus(imagesUploadStatus, '上传失败: ' + (e && e.message ? e.message : '未知错误'), 'error');
            }
        };

        if (imagesFileInput) {
            imagesFileInput.addEventListener('change', () => {
                if (imagesFileInput.files && imagesFileInput.files.length > 0) {
                    uploadMultiple(imagesFileInput.files);
                    imagesFileInput.value = '';
                }
            });
        }

        if (imagesSelectBtn) {
            imagesSelectBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal('images');
            });
        }

        if (modalConfirmBtn) {
            modalConfirmBtn.addEventListener('click', () => {
                if (modalMode === 'logo') {
                    setLogoUrl(selectedUrls[0] || '');
                } else {
                    selectedUrls.forEach(url => images.push(url));
                    images = images.filter(Boolean);
                    syncImagesInput();
                    renderImages();
                }
                closeModal();
            });
        }
    });
</script>