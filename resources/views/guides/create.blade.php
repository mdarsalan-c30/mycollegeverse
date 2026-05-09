@extends('layouts.app')

@section('title', 'Manifest New Academic Guide | MCV Hub')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('guides.index') }}" class="text-indigo-600 font-bold flex items-center hover:underline">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Return to Hub
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-4">Manifest New Academic Guide 🛰️</h1>
            <p class="text-gray-500 mt-2">Contribute high-value knowledge to the MyCollegeVerse ecosystem. Ensure your content is helpful and accurate.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <form action="{{ route('guides.store') }}" method="POST" id="guideForm" class="p-8">
                @csrf
                <input type="hidden" name="content" id="hiddenContent">

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Guide Title *</label>
                        <input type="text" name="title" id="title" required placeholder="e.g. AKTU 2nd Year Syllabus - B.Tech CSE (2024)" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-400 mt-1">Make it descriptive and keyword-rich for SEO.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-bold text-gray-700 mb-2">Category *</label>
                            <select name="category" id="category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <option value="Syllabus">Syllabus</option>
                                <option value="College Guide">College Guide</option>
                                <option value="Admission">Admission</option>
                                <option value="Career">Career</option>
                                <option value="Notice">Notice</option>
                            </select>
                        </div>
                        <!-- Target University -->
                        <div>
                            <label for="target_university" class="block text-sm font-bold text-gray-700 mb-2">University (Optional)</label>
                            <input type="text" name="target_university" id="target_university" placeholder="e.g. AKTU, DU, Mumbai University" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Content Editor -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Detailed Content *</label>
                        <div id="editor" class="bg-white" style="height: 400px;"></div>
                    </div>

                    <!-- SEO Section -->
                    <div class="pt-6 border-t border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            SEO Optimization Hub
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-600 mb-1">Keywords (Comma separated)</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" placeholder="aktu syllabus, btech cse subjects, aktu guides" class="w-full px-4 py-2 text-sm rounded-lg border border-gray-200">
                            </div>
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-600 mb-1">Meta Description (Snippet)</label>
                                <textarea name="meta_description" id="meta_description" rows="2" placeholder="Brief summary for Google search results..." class="w-full px-4 py-2 text-sm rounded-lg border border-gray-200"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
                            Manifest Guide to the Multiverse 🚀
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill Assets -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write your detailed academic guide here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    // Sync content on form submit
    var form = document.getElementById('guideForm');
    form.onsubmit = function() {
        var content = document.querySelector('input[name=content]');
        content.value = quill.root.innerHTML;
        
        if(quill.root.innerText.trim().length < 10) {
            alert('Please provide more detailed content for the guide.');
            return false;
        }
        return true;
    };
</script>

<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-color: #e5e7eb;
        background: #f9fafb;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        border-color: #e5e7eb;
        font-family: inherit;
        font-size: 1rem;
    }
</style>
@endsection
