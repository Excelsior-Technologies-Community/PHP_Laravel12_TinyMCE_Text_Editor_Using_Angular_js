<!DOCTYPE html>
<html lang="en" ng-app="editorApp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - TinyMCE & AngularJS Live Studio</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AngularJS 1.8.2 -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular-sanitize.min.js"></script>

    <!-- TinyMCE 6.8.2 -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
        }

        .studio-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .live-preview-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .preview-content {
            padding: 24px;
            overflow-y: auto;
            max-height: 600px;
            line-height: 1.7;
        }

        .preview-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .preview-content blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 16px;
            margin: 16px 0;
            color: #475569;
            font-style: italic;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
        }

        .preview-content pre {
            background: #1e293b;
            color: #38bdf8;
            padding: 14px;
            border-radius: 8px;
            overflow-x: auto;
        }

        .stat-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 14px;
            text-align: center;
        }

        .stat-badge h5 {
            font-weight: 700;
            margin: 0;
            color: #2563eb;
        }

        .stat-badge small {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .template-chip {
            cursor: pointer;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            background: #fff;
            transition: all 0.2s;
        }

        .template-chip:hover {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        .tox-tinymce {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
        }
    </style>
</head>

<body ng-controller="EditorController as vm">

    <div class="container-fluid py-4 px-lg-5">

        <!-- Top Header Navigation -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <span class="badge bg-primary p-2 fs-5"><i class="fa-solid fa-pen-nib"></i></span>
                    TinyMCE & AngularJS Live Content Studio
                </h3>
                <small class="text-muted">Two-Way Live Reactive Preview, Auto-Save Crash Recovery & Quick Templates</small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- FEATURE 2: Auto-save status badge -->
                <span class="badge bg-light text-secondary border px-3 py-2 fw-semibold">
                    <i class="fa-solid fa-circle text-xs text-success me-1"></i> @{{ vm.autoSaveStatus }}
                </span>

                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                    <i class="fa-solid fa-list me-1"></i> All Posts
                </a>
            </div>
        </div>

        <!-- FEATURE 2: Unsaved Crash Draft Recovery Alert -->
        <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm d-flex justify-content-between align-items-center mb-4"
             ng-if="vm.hasCachedDraft" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-down fs-4 text-warning"></i>
                <div>
                    <strong>Unsaved Draft Found!</strong> You have an unsaved draft from your previous session (Saved: @{{ vm.cachedDraftTime }}).
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-warning btn-sm fw-bold" ng-click="vm.restoreDraft()">
                    <i class="fa-solid fa-rotate-left me-1"></i> Restore Draft
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" ng-click="vm.dismissDraft()">
                    Discard
                </button>
            </div>
        </div>

        <!-- FEATURE 3: Pre-built Content Templates Bar -->
        <div class="card studio-card mb-4 p-3 bg-light border-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <strong class="text-primary text-sm"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> 1-Click Content Templates:</strong>
                    <span class="text-muted small">Insert pre-formatted layouts directly into the editor</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="template-chip" ng-click="vm.insertTemplate('techBlog')">
                        📰 Tech Blog Article
                    </button>
                    <button type="button" class="template-chip" ng-click="vm.insertTemplate('releaseNotes')">
                        📋 Product Release Notes
                    </button>
                    <button type="button" class="template-chip" ng-click="vm.insertTemplate('faq')">
                        ❓ FAQ & Knowledge Base
                    </button>
                    <button type="button" class="template-chip text-danger" ng-click="vm.clearEditor()">
                        <i class="fa-solid fa-eraser me-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= Main Split-Screen Studio ================= -->
        <div class="row g-4">

            <!-- Left Column: TinyMCE Editor Form -->
            <div class="col-lg-7">
                <div class="studio-card p-4">
                    
                    <form id="postForm" action="{{ route('posts.store') }}" method="POST">
                        @csrf

                        <!-- Title Field with Two-way binding -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Post Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg fw-semibold" id="title" name="title"
                                ng-model="vm.post.title" ng-change="vm.triggerAutoSave()"
                                placeholder="Enter an engaging post title..." required>
                        </div>

                        <!-- Status Field -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Publication Status</label>
                            <select class="form-select" name="status" ng-model="vm.post.status" ng-change="vm.triggerAutoSave()" required>
                                <option value="Draft">Draft (Private)</option>
                                <option value="Published">Published (Public)</option>
                            </select>
                        </div>

                        <!-- FEATURE 1: TinyMCE Editor with AngularJS ng-tinymce Directive -->
                        <div class="mb-3">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span>Content Editor <span class="text-danger">*</span></span>
                                <small class="text-muted fw-normal">Powered by TinyMCE 6 + AngularJS</small>
                            </label>
                            
                            <!-- Custom Directive Binding -->
                            <textarea id="editor" name="content" ng-tinymce ng-model="vm.post.content"></textarea>
                        </div>

                        <!-- Real-time Stats Badges -->
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <div class="stat-badge">
                                    <h5>@{{ vm.stats.words }}</h5>
                                    <small>Words</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-badge">
                                    <h5>@{{ vm.stats.characters }}</h5>
                                    <small>Characters</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-badge">
                                    <h5>@{{ vm.stats.readingTime }} min</h5>
                                    <small>Reading Time</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="button" class="btn btn-outline-danger btn-sm" ng-click="vm.resetAll()">
                                <i class="fa-solid fa-trash me-1"></i> Reset
                            </button>

                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Post
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- Right Column: FEATURE 1 - Split-Screen Live Responsive Preview -->
            <div class="col-lg-5">
                <div class="live-preview-container">
                    
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">
                            <i class="fa-solid fa-eye text-primary me-2"></i> Live Reactive Preview
                        </span>
                        <div class="d-flex gap-1">
                            <span class="badge bg-white text-dark border">
                                <i class="fa-solid fa-desktop me-1"></i> Desktop View
                            </span>
                        </div>
                    </div>

                    <div class="preview-content">
                        <!-- Dynamic Title -->
                        <h2 class="fw-bold text-dark mb-2" ng-if="vm.post.title">
                            @{{ vm.post.title }}
                        </h2>
                        <h2 class="fw-bold text-muted mb-2 fst-italic opacity-50" ng-if="!vm.post.title">
                            Untitled Post...
                        </h2>

                        <!-- Metadata Ribbon -->
                        <div class="text-muted small mb-3 pb-2 border-bottom d-flex gap-3">
                            <span><i class="fa-regular fa-clock me-1"></i> @{{ vm.stats.readingTime }} min read</span>
                            <span><i class="fa-solid fa-tag me-1"></i> @{{ vm.post.status }}</span>
                            <span><i class="fa-solid fa-calendar-day me-1"></i> Today</span>
                        </div>

                        <!-- Rendered HTML Content via ng-bind-html -->
                        <div ng-bind-html="vm.trustedContent(vm.post.content)" ng-if="vm.post.content"></div>

                        <div class="text-center py-5 text-muted" ng-if="!vm.post.content">
                            <i class="fa-solid fa-file-pen fs-1 text-secondary opacity-25 d-block mb-3"></i>
                            Start typing in the TinyMCE editor on the left or select a template above to see the live preview.
                        </div>
                    </div>

                    <div class="p-2 border-top bg-light text-center small text-muted mt-auto">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Real-time AngularJS Two-Way Binding
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- ================= AngularJS Application Script ================= -->
    <script>
        var app = angular.module('editorApp', ['ngSanitize']);

        // FEATURE 1: Custom AngularJS Directive for TinyMCE 6 Two-Way Binding
        app.directive('ngTinymce', ['$timeout', function($timeout) {
            return {
                require: '?ngModel',
                link: function(scope, element, attrs, ngModel) {
                    if (!ngModel) return;

                    var initTiny = function() {
                        tinymce.init({
                            target: element[0],
                            height: 380,
                            menubar: false,
                            plugins: 'link lists code table',
                            toolbar: 'undo redo | blocks | bold italic underline | forecolor | bullist numlist | link blockquote code | table',
                            content_style: 'body { font-family: -apple-system, sans-serif; font-size: 15px; line-height: 1.6; }',
                            setup: function(editor) {
                                // Sync TinyMCE to AngularJS ngModel on keyup/change
                                editor.on('init', function() {
                                    if (ngModel.$viewValue) {
                                        editor.setContent(ngModel.$viewValue);
                                    }
                                });

                                editor.on('keyup change input undo redo paste', function() {
                                    var content = editor.getContent();
                                    $timeout(function() {
                                        ngModel.$setViewValue(content);
                                    });
                                });
                            }
                        });
                    };

                    $timeout(initTiny);

                    // Sync model changes to TinyMCE (e.g. on restore or template insert)
                    ngModel.$render = function() {
                        var editor = tinymce.get(element[0].id);
                        if (editor) {
                            editor.setContent(ngModel.$viewValue || '');
                        }
                    };

                    scope.$on('$destroy', function() {
                        var editor = tinymce.get(element[0].id);
                        if (editor) {
                            editor.remove();
                        }
                    });
                }
            };
        }]);

        app.controller('EditorController', ['$scope', '$sce', '$interval', function($scope, $sce, $interval) {
            var vm = this;

            vm.post = {
                title: '',
                content: '',
                status: 'Draft'
            };

            vm.stats = {
                words: 0,
                characters: 0,
                readingTime: 0
            };

            vm.autoSaveStatus = 'Ready';
            vm.hasCachedDraft = false;
            vm.cachedDraftTime = '';

            // Trusted HTML for Preview
            vm.trustedContent = function(html) {
                return $sce.trustAsHtml(html);
            };

            // FEATURE 1: Recalculate Word & Reading Stats whenever content changes
            $scope.$watch(function() {
                return vm.post.content;
            }, function(newVal) {
                if (!newVal) {
                    vm.stats = { words: 0, characters: 0, readingTime: 0 };
                    return;
                }

                // Strip HTML tags for clean text analysis
                var tempDiv = document.createElement("div");
                tempDiv.innerHTML = newVal;
                var text = (tempDiv.textContent || tempDiv.innerText || "").trim();

                var words = text.length ? text.split(/\s+/).length : 0;
                var characters = text.length;
                var readingTime = words === 0 ? 0 : Math.max(1, Math.ceil(words / 200));

                vm.stats.words = words;
                vm.stats.characters = characters;
                vm.stats.readingTime = readingTime;

                vm.triggerAutoSave();
            });

            // FEATURE 2: LocalStorage Auto-Save & Crash Recovery
            var STORAGE_KEY = 'tinymce_editor_draft';

            vm.triggerAutoSave = function() {
                if (vm.post.title || vm.post.content) {
                    var draft = {
                        title: vm.post.title,
                        content: vm.post.content,
                        status: vm.post.status,
                        timestamp: new Date().toLocaleTimeString()
                    };
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(draft));
                    vm.autoSaveStatus = 'Draft saved ' + draft.timestamp;
                }
            };

            // Check for existing draft on load
            var savedDraft = localStorage.getItem(STORAGE_KEY);
            if (savedDraft) {
                try {
                    var parsed = JSON.parse(savedDraft);
                    if (parsed.title || parsed.content) {
                        vm.hasCachedDraft = true;
                        vm.cachedDraftTime = parsed.timestamp || 'earlier';
                        vm._cachedDraftData = parsed;
                    }
                } catch (e) {
                    localStorage.removeItem(STORAGE_KEY);
                }
            }

            vm.restoreDraft = function() {
                if (vm._cachedDraftData) {
                    vm.post.title = vm._cachedDraftData.title || '';
                    vm.post.content = vm._cachedDraftData.content || '';
                    vm.post.status = vm._cachedDraftData.status || 'Draft';
                    vm.hasCachedDraft = false;

                    Swal.fire({
                        icon: 'success',
                        title: 'Restored!',
                        text: 'Your saved draft has been recovered.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            };

            vm.dismissDraft = function() {
                vm.hasCachedDraft = false;
                localStorage.removeItem(STORAGE_KEY);
            };

            // FEATURE 3: Pre-built Content Templates Library
            var TEMPLATES = {
                techBlog: `
                    <h2>Introduction to Modern Web Development</h2>
                    <p>In this article, we explore the fundamental shifts happening in enterprise software development and cloud architectures.</p>
                    <blockquote>"Great code is its own best documentation. As you're about to add a comment, ask yourself, 'How can I improve the code so that this comment isn't needed?'" - Steve McConnell</blockquote>
                    <h3>Key Highlights:</h3>
                    <ul>
                        <li><strong>Reactive UI:</strong> Combining robust server frameworks with agile client-side data binding.</li>
                        <li><strong>Resilience:</strong> Local caching and crash-recovery patterns.</li>
                        <li><strong>Developer Velocity:</strong> Ready-to-deploy modular components.</li>
                    </ul>
                    <pre><code>// Sample Configuration Snippet\nconst config = {\n    framework: 'Laravel 12',\n    client: 'AngularJS',\n    editor: 'TinyMCE 6'\n};</code></pre>
                    <p>Conclusion: By standardizing workflows, development teams can deliver faster and maintain higher quality.</p>
                `,
                releaseNotes: `
                    <h2>🚀 Release Notes - Version 2.0.0</h2>
                    <p>We are excited to announce the latest major update with brand-new features, performance optimizations, and security patches.</p>
                    <h3>✨ What's New:</h3>
                    <ul>
                        <li><strong>Split-Screen Studio:</strong> Live real-time preview of formatted content.</li>
                        <li><strong>Auto-Save Guard:</strong> Never lose drafts on unexpected reloads.</li>
                        <li><strong>Speed Improvements:</strong> 40% faster initial load time.</li>
                    </ul>
                    <h3>🐛 Bug Fixes & Refactoring:</h3>
                    <ul>
                        <li>Fixed text counter synchronization in rich formatting modes.</li>
                        <li>Resolved responsive layout wrapping issues on tablet screens.</li>
                    </ul>
                `,
                faq: `
                    <h2>Frequently Asked Questions (FAQ)</h2>
                    <p>Find answers to common questions about our platform and services below.</p>
                    <h3>Q1: How does the auto-save feature work?</h3>
                    <p>Your work is saved continuously into your browser's secure LocalStorage. Even if your browser crashes, you can recover your draft with 1-click.</p>
                    <h3>Q2: Can I publish directly from this editor?</h3>
                    <p>Yes, change the status dropdown to <strong>Published</strong> and click <strong>Save Post</strong>.</p>
                    <h3>Q3: What formatting options are supported?</h3>
                    <p>Headers, blockquotes, ordered/unordered lists, code snippets, hyperlinks, and tables are fully supported.</p>
                `
            };

            vm.insertTemplate = function(type) {
                if (TEMPLATES[type]) {
                    vm.post.content = TEMPLATES[type].trim();
                    if (!vm.post.title) {
                        if (type === 'techBlog') vm.post.title = 'A Guide to Next-Gen Web Development';
                        if (type === 'releaseNotes') vm.post.title = 'Release Notes: Version 2.0.0';
                        if (type === 'faq') vm.post.title = 'Frequently Asked Questions & Answers';
                    }
                    vm.triggerAutoSave();
                }
            };

            vm.clearEditor = function() {
                vm.post.content = '';
                vm.post.title = '';
                vm.triggerAutoSave();
            };

            vm.resetAll = function() {
                Swal.fire({
                    title: 'Reset Form?',
                    text: 'This will clear the current editor and preview.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, clear it'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $scope.$apply(function() {
                            vm.clearEditor();
                            localStorage.removeItem(STORAGE_KEY);
                        });
                    }
                });
            };

            // Clean storage upon actual form submission
            document.getElementById('postForm').addEventListener('submit', function() {
                localStorage.removeItem(STORAGE_KEY);
            });
        }]);
    </script>

</body>

</html>