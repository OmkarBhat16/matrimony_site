<x-layout>
    <x-slot:title>Edit Account - Matrimony</x-slot:title>

    @php
        $fields = \App\Models\EditUserProfile::DIFFABLE_FIELDS;
        $allImgs = $profile->allImageUrls();
        $pendingImageSlots = $pendingEdit?->image_changes ? array_map('intval', array_keys($pendingEdit->image_changes)) : [];
        $kundliUrl = $profile->kundliImageUrl();
        $kundliPendingUrl = $profile->pendingKundliImageUrl();
        $hasKundliPending = $pendingEdit?->hasPendingKundliImage() ?? false;
        $parseRelationRows = function (?string $raw, string $fallbackRelation) {
            $raw = trim((string) $raw);

            if ($raw === '') {
                return [];
            }

            $decoded = json_decode($raw, true);

            if (is_array($decoded)) {
                return collect($decoded)
                    ->filter(fn ($item) => is_array($item))
                    ->map(function ($item) {
                        return [
                            'relation' => trim((string) ($item['relation'] ?? '')),
                            'value' => trim((string) ($item['value'] ?? '')),
                        ];
                    })
                    ->filter(fn ($item) => $item['relation'] !== '' || $item['value'] !== '')
                    ->values()
                    ->all();
            }

            return [[
                'relation' => $fallbackRelation,
                'value' => $raw,
            ]];
        };

        $formatJsonField = function (?string $raw): string {
            $raw = trim((string) $raw);

            if ($raw === '') {
                return '';
            }

            $decoded = json_decode($raw, true);

            if (! is_array($decoded)) {
                return $raw;
            }

            return collect($decoded)
                ->filter(fn ($item) => is_array($item))
                ->map(function ($item) {
                    $relation = trim((string) ($item['relation'] ?? ''));
                    $value = trim((string) ($item['value'] ?? ''));

                    if ($relation !== '' && $value !== '') {
                        return $relation.': '.$value;
                    }

                    return $relation !== '' ? $relation : $value;
                })
                ->filter()
                ->implode("\n");
        };

        $siblingsRows = $parseRelationRows($values->siblings ?? '', 'Sibling');
        $relativeRows = $parseRelationRows($values->uncles ?? '', 'Relative');
        $naatheValue = old('naathe_relationships', $formatJsonField($values->naathe_relationships ?? ''));
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 sm:px-10 py-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="lang-label text-2xl font-bold text-gray-900" data-en="Edit Account" data-mr="खाते संपादित करा">Edit Account</h1>
                        <p class="lang-label text-sm text-gray-500 mt-1" data-en="Changes will be submitted for admin review before going live." data-mr="बदल लाईव्ह होण्यापूर्वी अ‍ॅडमिनच्या मंजुरीसाठी सबमिट केले जातील.">Changes will be submitted for admin review before going live.</p>
                    </div>
                    <a href="{{ route('account') }}" class="text-sm text-gray-500 hover:text-pink-600 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="lang-label" data-en="Back to Account" data-mr="खात्यावर परत जा">Back to Account</span>
                    </a>
                </div>

                @if($pendingEdit)
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="lang-label text-sm text-amber-700" data-en="Note: You already have a pending edit under review. Submitting again will replace it." data-mr="नोंद: तुमचा एक प्रलंबित बदल आधीच मंजुरीच्या प्रतीक्षेत आहे. पुन्हा सबमिट केल्यास जुना बदल नवीन बदलाने बदलला जाईल.">
                            <strong>Note:</strong> You already have a pending edit under review. Submitting again will replace it.
                        </p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Account Photos</h2>
                    <p class="text-sm text-gray-500 mb-5">Add missing photos here. Replacing an existing photo will go to admin review. Changing the primary photo stays immediate.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach ([1, 2, 3, 4] as $slot)
                            @php $imgUrl = $allImgs[$slot] ?? null; @endphp
                            <div class="flex flex-col items-center gap-3">
                                <form method="POST"
                                      action="{{ route('account.images.upload') }}"
                                      enctype="multipart/form-data"
                                      class="w-full">
                                    @csrf
                                    <div class="relative w-full aspect-square rounded-xl overflow-hidden border-2 cursor-pointer
                                                {{ ($profile->primary_image ?? 1) == $slot ? 'border-pink-500 ring-2 ring-pink-300' : 'border-dashed border-gray-300 hover:border-pink-400' }}
                                                transition"
                                         onclick="document.getElementById('upload-input-{{ $slot }}').click()">
                                        @if ($imgUrl)
                                            <img src="{{ $imgUrl }}"
                                                 alt="Photo {{ $slot }}"
                                                 class="w-full h-full object-cover">
                                            @if (($profile->primary_image ?? 1) == $slot)
                                                <span class="absolute top-2 left-2 bg-pink-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                                    Primary
                                                </span>
                                            @endif
                                            @if (in_array($slot, $pendingImageSlots, true))
                                                <span class="absolute top-2 right-2 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                                    Pending review
                                                </span>
                                            @endif
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 gap-1 bg-white">
                                                @if (in_array($slot, $pendingImageSlots, true))
                                                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs font-medium text-amber-600">Photo {{ $slot }}</span>
                                                    <span class="text-xs text-amber-600">Pending review</span>
                                                @else
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs font-medium">Photo {{ $slot }}</span>
                                                    <span class="text-xs">Click to upload</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <input type="file"
                                           id="upload-input-{{ $slot }}"
                                           name="images[{{ $slot }}]"
                                        accept="*/*"
                                           class="hidden"
                                           onchange="this.closest('form').submit()">
                                </form>

                                @if ($imgUrl && ($profile->primary_image ?? 1) != $slot)
                                    <form method="POST" action="{{ route('account.images.primary') }}">
                                        @csrf
                                        <input type="hidden" name="slot" value="{{ $slot }}">
                                        <button type="submit"
                                                class="text-xs font-medium text-pink-600 hover:text-pink-800 border border-pink-300 hover:border-pink-500 rounded-lg px-3 py-1.5 transition">
                                            Set as Primary
                                        </button>
                                    </form>
                                @elseif ($imgUrl)
                                    <span class="text-xs font-semibold text-pink-600 border border-pink-300 rounded-lg px-3 py-1.5 bg-pink-50">
                                        ★ Primary Photo
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Biodata</h2>
                    <p class="text-sm text-gray-500 mb-5">Upload or replace your biodata image here. It is required for a complete profile and changes go to admin review before going live.</p>

                    <div class="max-w-xs">
                        <form method="POST"
                              action="{{ route('account.kundli.upload') }}"
                              enctype="multipart/form-data"
                              class="w-full">
                            @csrf
                            <div class="relative w-40 rounded-xl overflow-hidden border-2 cursor-pointer
                                        {{ $kundliUrl ? 'border-gray-300' : 'border-dashed border-gray-300 hover:border-pink-400' }}
                                        transition"
                                 onclick="document.getElementById('kundli-input').click()">
                                @if ($kundliUrl)
                                    <img src="{{ $kundliUrl }}"
                                         alt="Biodata"
                                         class="w-full h-auto object-contain">
                                    @if ($hasKundliPending)
                                        <span class="absolute top-2 right-2 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                            Pending review
                                        </span>
                                    @endif
                                @elseif ($hasKundliPending)
                                    <img src="{{ $kundliPendingUrl }}"
                                         alt="Pending Biodata"
                                         class="w-full h-auto object-contain">
                                    <span class="absolute top-2 right-2 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                        Pending review
                                    </span>
                                @else
                                    <div class="h-40 flex flex-col items-center justify-center text-gray-400 gap-1 bg-white">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium">Biodata</span>
                                        <span class="text-xs">Click to upload</span>
                                    </div>
                                @endif
                            </div>

                            <input type="file"
                                   id="kundli-input"
                                   name="kundli"
                                   required
                                   accept="image/*"
                                   class="hidden"
                                   onchange="this.closest('form').submit()">
                        </form>
                    </div>
                </div>

                <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between mb-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                            <p class="text-sm text-gray-500">Enter your current password, then type the new password twice.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('account.password.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div class="md:col-span-2">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition"
                            >
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition"
                            >
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition shadow-sm">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <form method="POST" action="{{ route('account.update') }}" class="space-y-8">
                    @csrf

                    {{-- Personal Information --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach(['full_name', 'navras_naav', 'marital_status', 'height_cm__Oonchi', 'skin_complexion__Rang', 'blood_group'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Horoscope --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Horoscope & Community</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach(['day_and_time_of_birth', 'place_of_birth', 'jaath', 'zodiac_sign__Raas', 'naadi', 'gann', 'devak', 'kul_devata'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Education & Career --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Education & Career</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label for="education_type" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields['education_type'] }}</label>
                                <select id="education_type" name="education_type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition bg-white">
                                    <option value="">Select education type</option>
                                    @foreach(\App\Models\UserProfile::EDUCATION_TYPES as $educationType)
                                        <option value="{{ $educationType }}" @selected(old('education_type', $values->education_type ?? '') === $educationType)>{{ $educationType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @foreach(['education', 'occupation', 'annual_income'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <input type="{{ $field === 'annual_income' ? 'number' : 'text' }}" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition" {{ $field === 'annual_income' ? 'step=0.01' : '' }}>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Family --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Family Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                            @foreach(['fathers_name', 'mothers_name'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">Siblings</h3>
                                        <p class="text-xs text-gray-500">Add one or more sibling entries in a clean, guided format.</p>
                                    </div>
                                    <button type="button" id="add-sibling-btn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border border-pink-300 text-pink-700 bg-pink-50 hover:bg-pink-100 transition">
                                        Add Sibling
                                    </button>
                                </div>

                                <div id="siblings-list" class="space-y-3">
                                    @forelse($siblingsRows as $index => $row)
                                        <div class="sibling-row grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_minmax(0,2fr)_auto] gap-2 items-center">
                                            <div>
                                                <select class="sibling-relation block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm">
                                                    <option value="" data-en="Select sibling" data-mr="भावंड निवडा">Select sibling</option>
                                                    <option value="Brother" data-en="Brother" data-mr="भाऊ" {{ $row['relation'] === 'Brother' ? 'selected' : '' }}>Brother</option>
                                                    <option value="Sister" data-en="Sister" data-mr="बहीण" {{ $row['relation'] === 'Sister' ? 'selected' : '' }}>Sister</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" class="sibling-value block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm" placeholder="Sibling details" value="{{ $row['value'] }}">
                                            </div>
                                            <div class="justify-self-end">
                                                <button type="button" class="remove-sibling-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition" aria-label="Remove sibling">&times;</button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500" data-placeholder="siblings">No sibling details added yet.</p>
                                    @endforelse
                                </div>

                                <input type="hidden" id="siblings" name="siblings" value="{{ old('siblings', $values->siblings ?? '') }}">
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">Relatives</h3>
                                        <p class="text-xs text-gray-500">Keep uncles and other relatives grouped in one structured list.</p>
                                    </div>
                                    <button type="button" id="add-relative-btn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border border-pink-300 text-pink-700 bg-pink-50 hover:bg-pink-100 transition">
                                        Add Relative
                                    </button>
                                </div>

                                <div id="relatives-list" class="space-y-3">
                                    @forelse($relativeRows as $index => $row)
                                        <div class="relative-row grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_minmax(0,2fr)_auto] gap-2 items-center">
                                            <div>
                                                <select class="relative-relation block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm">
                                                    <option value="" data-en="Select relation" data-mr="नातं निवडा">Select relation</option>
                                                    <option value="Uncle" data-en="Uncle" data-mr="काका" {{ $row['relation'] === 'Uncle' ? 'selected' : '' }}>Uncle</option>
                                                    <option value="Aunt" data-en="Aunt" data-mr="मावशी / आत्या" {{ $row['relation'] === 'Aunt' ? 'selected' : '' }}>Aunt</option>
                                                    <option value="Mama" data-en="Mama" data-mr="मामा" {{ $row['relation'] === 'Mama' ? 'selected' : '' }}>Mama</option>
                                                    <option value="Other" data-en="Other" data-mr="इतर" {{ $row['relation'] === 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" class="relative-value block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm" placeholder="Relative details" value="{{ $row['value'] }}">
                                            </div>
                                            <div class="justify-self-end">
                                                <button type="button" class="remove-relative-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition" aria-label="Remove relative">&times;</button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500" data-placeholder="relatives">No relative details added yet.</p>
                                    @endforelse
                                </div>

                                <input type="hidden" id="uncles" name="uncles" value="{{ old('uncles', $values->uncles ?? '') }}">
                            </div>
                        </div>

                    <div class="mt-6">
                        <label for="naathe_relationships" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields['naathe_relationships'] }}</label>
                        <textarea id="naathe_relationships" name="naathe_relationships" rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">{{ $naatheValue }}</textarea>
                    </div>
                    </section>

                    {{-- Address --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Location & Address</h2>
                        <div class="grid grid-cols-1 gap-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields['address'] }}</label>
                                <textarea id="address" name="address" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">{{ old('address', $values->address ?? '') }}</textarea>
                            </div>
                            <div>
                                <label for="native_address" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields['native_address'] }}</label>
                                <textarea id="native_address" name="native_address" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">{{ old('native_address', $values->native_address ?? '') }}</textarea>
                            </div>
                            <div>
                                <label for="village_farm" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields['village_farm'] }}</label>
                                <input type="text" id="village_farm" name="village_farm" value="{{ old('village_farm', $values->village_farm ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                            </div>
                        </div>
                    </section>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('account') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                        <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition shadow-sm">
                            Submit for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('account.update') }}"]');
            const siblingsList = document.getElementById('siblings-list');
            const relativesList = document.getElementById('relatives-list');
            const siblingsHidden = document.getElementById('siblings');
            const relativesHidden = document.getElementById('uncles');
            const addSiblingBtn = document.getElementById('add-sibling-btn');
            const addRelativeBtn = document.getElementById('add-relative-btn');

            function collectRows(listElement, rowClass, relationSelector, valueSelector) {
                return Array.from(listElement.querySelectorAll(`.${rowClass}`))
                    .map((row) => {
                        const relation = row.querySelector(relationSelector)?.value?.trim() || '';
                        const value = row.querySelector(valueSelector)?.value?.trim() || '';

                        return { relation, value };
                    })
                    .filter((item) => item.relation || item.value);
            }

            function syncRows(listElement, hiddenInput, rowClass, relationSelector, valueSelector) {
                hiddenInput.value = JSON.stringify(collectRows(listElement, rowClass, relationSelector, valueSelector));
            }

            function removePlaceholder(listElement) {
                const placeholder = listElement.querySelector('[data-placeholder]');
                if (placeholder) {
                    placeholder.remove();
                }
            }

            function currentLanguage() {
                return document.documentElement.getAttribute('lang') === 'mr' ? 'mr' : 'en';
            }

            function localizeOptions(root) {
                root.querySelectorAll('option[data-en][data-mr]').forEach(function (option) {
                    option.textContent = currentLanguage() === 'mr' ? option.dataset.mr : option.dataset.en;
                });
            }

            function createSiblingRow(data = {}) {
                removePlaceholder(siblingsList);

                const row = document.createElement('div');
                row.className = 'sibling-row grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_minmax(0,2fr)_auto] gap-2 items-center';
                row.innerHTML = `
                    <div>
                        <select class="sibling-relation block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm">
                            <option value="" data-en="Select sibling" data-mr="भावंड निवडा">Select sibling</option>
                            <option value="Brother" data-en="Brother" data-mr="भाऊ">Brother</option>
                            <option value="Sister" data-en="Sister" data-mr="बहीण">Sister</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" class="sibling-value block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm" placeholder="Sibling details">
                    </div>
                    <div class="justify-self-end">
                        <button type="button" class="remove-sibling-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition" aria-label="Remove sibling">&times;</button>
                    </div>
                `;

                row.querySelector('.sibling-relation').value = data.relation || '';
                row.querySelector('.sibling-value').value = data.value || '';
                localizeOptions(row);

                row.querySelector('.sibling-relation').addEventListener('change', function () {
                    syncRows(siblingsList, siblingsHidden, 'sibling-row', '.sibling-relation', '.sibling-value');
                });

                row.querySelector('.sibling-value').addEventListener('input', function () {
                    syncRows(siblingsList, siblingsHidden, 'sibling-row', '.sibling-relation', '.sibling-value');
                });

                siblingsList.appendChild(row);
                syncRows(siblingsList, siblingsHidden, 'sibling-row', '.sibling-relation', '.sibling-value');
            }

            function createRelativeRow(data = {}) {
                removePlaceholder(relativesList);

                const row = document.createElement('div');
                row.className = 'relative-row grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_minmax(0,2fr)_auto] gap-2 items-center';
                row.innerHTML = `
                    <div>
                        <select class="relative-relation block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm">
                            <option value="" data-en="Select relation" data-mr="नातं निवडा">Select relation</option>
                            <option value="Uncle" data-en="Uncle" data-mr="काका">Uncle</option>
                            <option value="Aunt" data-en="Aunt" data-mr="मावशी / आत्या">Aunt</option>
                            <option value="Mama" data-en="Mama" data-mr="मामा">Mama</option>
                            <option value="Other" data-en="Other" data-mr="इतर">Other</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" class="relative-value block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm" placeholder="Relative details">
                    </div>
                    <div class="justify-self-end">
                        <button type="button" class="remove-relative-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition" aria-label="Remove relative">&times;</button>
                    </div>
                `;

                row.querySelector('.relative-relation').value = data.relation || '';
                row.querySelector('.relative-value').value = data.value || '';
                localizeOptions(row);

                row.querySelector('.relative-relation').addEventListener('change', function () {
                    syncRows(relativesList, relativesHidden, 'relative-row', '.relative-relation', '.relative-value');
                });

                row.querySelector('.relative-value').addEventListener('input', function () {
                    syncRows(relativesList, relativesHidden, 'relative-row', '.relative-relation', '.relative-value');
                });

                relativesList.appendChild(row);
                syncRows(relativesList, relativesHidden, 'relative-row', '.relative-relation', '.relative-value');
            }

            function wireRepeater(listElement, hiddenInput, rowClass, relationSelector, valueSelector, placeholderText, createRowFn) {
                listElement.addEventListener('click', function (event) {
                    const removeButton = event.target.closest(`.${rowClass === 'sibling-row' ? 'remove-sibling-btn' : 'remove-relative-btn'}`);

                    if (!removeButton) {
                        return;
                    }

                    const row = removeButton.closest(`.${rowClass}`);
                    if (!row) {
                        return;
                    }

                    row.remove();
                    syncRows(listElement, hiddenInput, rowClass, relationSelector, valueSelector);

                    if (!listElement.querySelector(`.${rowClass}`)) {
                        listElement.insertAdjacentHTML('afterbegin', `<p class="text-sm text-gray-500" data-placeholder="${placeholderText}">No ${placeholderText.slice(0, -1)} details added yet.</p>`);
                    }
                });

                listElement.addEventListener('change', function (event) {
                    if (event.target.closest(`.${rowClass}`)) {
                        syncRows(listElement, hiddenInput, rowClass, relationSelector, valueSelector);
                    }
                });

                listElement.addEventListener('input', function (event) {
                    if (event.target.closest(`.${rowClass}`)) {
                        syncRows(listElement, hiddenInput, rowClass, relationSelector, valueSelector);
                    }
                });

                return createRowFn;
            }

            const addSiblingRow = wireRepeater(
                siblingsList,
                siblingsHidden,
                'sibling-row',
                '.sibling-relation',
                '.sibling-value',
                'siblings',
                createSiblingRow
            );

            const addRelativeRow = wireRepeater(
                relativesList,
                relativesHidden,
                'relative-row',
                '.relative-relation',
                '.relative-value',
                'relatives',
                createRelativeRow
            );

            if (addSiblingBtn) {
                addSiblingBtn.addEventListener('click', function () {
                    addSiblingRow();
                });
            }

            if (addRelativeBtn) {
                addRelativeBtn.addEventListener('click', function () {
                    addRelativeRow();
                });
            }

            if (form) {
                form.addEventListener('submit', function () {
                    syncRows(siblingsList, siblingsHidden, 'sibling-row', '.sibling-relation', '.sibling-value');
                    syncRows(relativesList, relativesHidden, 'relative-row', '.relative-relation', '.relative-value');
                });
            }
        });
    </script>
</x-layout>
