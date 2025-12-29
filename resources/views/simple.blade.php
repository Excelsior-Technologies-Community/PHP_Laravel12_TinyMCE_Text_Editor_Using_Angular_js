<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            color: #333;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .tox-tinymce {
            border: 1px solid #ddd !important;
            border-radius: 6px !important;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        button {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-save {
            background: #2ecc71;
            color: white;
        }
        
        .btn-save:hover {
            background: #27ae60;
        }
        
        .btn-preview {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn-preview:hover {
            background: #e9ecef;
        }
        
        .preview-section {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #eaeaea;
        }
        
        .preview-title {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        #preview {
            min-height: 60px;
            padding: 15px;
            background: white;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .preview-placeholder {
            color: #999;
            font-style: italic;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .form-note {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>Create New Post</h1>
    
    <form id="postForm" action="/posts" method="POST">
        @csrf <!-- Laravel CSRF token -->
        
        <div class="form-group">
            <label for="title">
                Post Title <span class="required">*</span>
            </label>
            <input type="text" id="title" name="title" required 
                   placeholder="Enter post title">
        </div>
        
        <div class="form-group">
            <label for="content">
                Content <span class="required">*</span>
            </label>
            <textarea id="editor" name="content"></textarea>
            <div class="form-note">
                Use the toolbar above to format your content. Supports links and lists.
            </div>
        </div>
        
        <div class="actions">
            <button type="submit" class="btn-save">Save Post</button>
            <button type="button" class="btn-preview" onclick="previewContent()">
                Preview
            </button>
        </div>
    </form>
    
    <div class="preview-section">
        <div class="preview-title">Content Preview</div>
        <div id="preview">
            <span class="preview-placeholder">Click "Preview" to see your formatted content here...</span>
        </div>
    </div>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#editor',
            height: 350,
            menubar: false,
            plugins: 'link lists',
            toolbar: 'undo redo | styleselect | bold italic | link | bullist numlist',
            content_style: 'body { font-family: -apple-system, sans-serif; font-size: 16px; line-height: 1.6; }',
            setup: function(editor) {
                // Optional: Add custom behavior
                editor.on('change', function() {
                    editor.save(); // Save content to textarea
                });
            }
        });

        function previewContent() {
            var content = tinymce.get('editor').getContent();
            var title = document.getElementById('title').value;
            var previewDiv = document.getElementById('preview');
            
            if (content.trim() === '') {
                previewDiv.innerHTML = '<span class="preview-placeholder">No content to preview. Start typing above.</span>';
            } else {
                var previewHTML = '';
                if (title) {
                    previewHTML += '<h3 style="margin-top: 0; color: #2c3e50;">' + title + '</h3>';
                }
                previewHTML += content;
                previewDiv.innerHTML = previewHTML;
            }
        }

        // Optional: Form submission handling with fetch API
        document.getElementById('postForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Make sure TinyMCE content is saved
            tinymce.triggerSave();
            
            // Validate
            var title = document.getElementById('title').value.trim();
            var content = tinymce.get('editor').getContent().trim();
            
            if (!title) {
                alert('Please enter a title');
                document.getElementById('title').focus();
                return;
            }
            
            if (!content) {
                alert('Please enter some content');
                return;
            }
            
            // Submit the form
            this.submit();
            
            // Or use fetch for AJAX submission:
            /*
            fetch('/posts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                alert('Post saved successfully!');
                // Optionally redirect or clear form
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving post');
            });
            */
        });
    </script>
</body>
</html>