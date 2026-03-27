<x-admin-layout>
    <x-slot:title>Content Management</x-slot:title>
    <x-slot:header>Content Management</x-slot:header>

    @php
        $activeTab = $tab ?? 'home-page';
        $home = $homePageContent ?? \App\Models\HomePageContent::defaults();
        $about = $aboutPageContent ?? \App\Models\AboutPageContent::defaults();
    @endphp

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.content-management', ['tab' => 'home-page']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $activeTab === 'home-page' ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            Home Page
        </a>
        <a href="{{ route('admin.content-management', ['tab' => 'about-page']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $activeTab === 'about-page' ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            About Page
        </a>
    </div>

    @if ($activeTab === 'home-page')
        <form method="POST" action="{{ route('admin.content-management.update') }}" class="space-y-8">
            @csrf
            <input type="hidden" name="page" value="home-page">

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Hero Section</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage the first section visitors see on the home page.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="content[hero][title]" value="{{ old('content.hero.title', data_get($home, 'hero.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Highlight Text</label>
                        <input type="text" name="content[hero][highlight]" value="{{ old('content.hero.highlight', data_get($home, 'hero.highlight')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="content[hero][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.hero.description', data_get($home, 'hero.description')) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Register Button</label>
                        <input type="text" name="content[hero][register_button]" value="{{ old('content.hero.register_button', data_get($home, 'hero.register_button')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Browse Button</label>
                        <input type="text" name="content[hero][browse_button]" value="{{ old('content.hero.browse_button', data_get($home, 'hero.browse_button')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Stats Section</h2>
                    <p class="text-sm text-gray-500 mt-1">Edit the four summary cards under the hero banner.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([0, 1, 2, 3] as $index)
                        <div class="rounded-lg border border-gray-200 p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Card {{ $index + 1 }}</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Value</label>
                                    <input type="text" name="content[stats][{{ $index }}][value]" value="{{ old('content.stats.'.$index.'.value', data_get($home, 'stats.'.$index.'.value')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                                    <input type="text" name="content[stats][{{ $index }}][label]" value="{{ old('content.stats.'.$index.'.label', data_get($home, 'stats.'.$index.'.label')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Featured Profiles Section</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="content[featured][title]" value="{{ old('content.featured.title', data_get($home, 'featured.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="content[featured][subtitle]" value="{{ old('content.featured.subtitle', data_get($home, 'featured.subtitle')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">How It Works</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Heading</label>
                        <input type="text" name="content[how_it_works][title]" value="{{ old('content.how_it_works.title', data_get($home, 'how_it_works.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="content[how_it_works][subtitle]" value="{{ old('content.how_it_works.subtitle', data_get($home, 'how_it_works.subtitle')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach([0, 1, 2] as $index)
                        <div class="rounded-lg border border-gray-200 p-4 space-y-3">
                            <p class="text-sm font-semibold text-gray-700">Step {{ $index + 1 }}</p>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                <input type="text" name="content[how_it_works][steps][{{ $index }}][title]" value="{{ old('content.how_it_works.steps.'.$index.'.title', data_get($home, 'how_it_works.steps.'.$index.'.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                <textarea name="content[how_it_works][steps][{{ $index }}][description]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.how_it_works.steps.'.$index.'.description', data_get($home, 'how_it_works.steps.'.$index.'.description')) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">CTA Section</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="content[cta][title]" value="{{ old('content.cta.title', data_get($home, 'cta.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Label</label>
                        <input type="text" name="content[cta][button]" value="{{ old('content.cta.button', data_get($home, 'cta.button')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="content[cta][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.cta.description', data_get($home, 'cta.description')) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition shadow-sm">
                    Save Home Page Content
                </button>
            </div>
        </form>
    @else
        <form method="POST" action="{{ route('admin.content-management.update') }}" class="space-y-8">
            @csrf
            <input type="hidden" name="page" value="about-page">

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Header Section</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="content[header][title]" value="{{ old('content.header.title', data_get($about, 'header.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="content[header][subtitle]" value="{{ old('content.header.subtitle', data_get($about, 'header.subtitle')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Mission Section</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="content[mission][title]" value="{{ old('content.mission.title', data_get($about, 'mission.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="content[mission][description]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.mission.description', data_get($about, 'mission.description')) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">What We Offer</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([0, 1, 2, 3] as $index)
                        <div class="rounded-lg border border-gray-200 p-4 space-y-3">
                            <p class="text-sm font-semibold text-gray-700">Offer {{ $index + 1 }}</p>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                <input type="text" name="content[offers][{{ $index }}][title]" value="{{ old('content.offers.'.$index.'.title', data_get($about, 'offers.'.$index.'.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                <textarea name="content[offers][{{ $index }}][description]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.offers.'.$index.'.description', data_get($about, 'offers.'.$index.'.description')) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Values Section</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="content[values][title]" value="{{ old('content.values.title', data_get($about, 'values.title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    @foreach([0, 1, 2] as $index)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Value {{ $index + 1 }}</label>
                            <textarea name="content[values][items][{{ $index }}]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('content.values.items.'.$index, data_get($about, 'values.items.'.$index)) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition shadow-sm">
                    Save About Page Content
                </button>
            </div>
        </form>
    @endif
</x-admin-layout>
