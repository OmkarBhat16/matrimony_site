<x-layout>
    <x-slot:title>Edit Profile - Matrimony</x-slot:title>

    @php
        $fields = \App\Models\EditUserProfile::DIFFABLE_FIELDS;
        $allImgs = $profile->allImageUrls();
        $pendingImageSlots = $pendingEdit?->image_changes ? array_map('intval', array_keys($pendingEdit->image_changes)) : [];
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 sm:px-10 py-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="lang-label text-2xl font-bold text-gray-900" data-en="Edit Profile" data-mr="प्रोफाइल संपादित करा">Edit Profile</h1>
                        <p class="lang-label text-sm text-gray-500 mt-1" data-en="Changes will be submitted for admin review before going live." data-mr="बदल लाईव्ह होण्यापूर्वी अ‍ॅडमिनच्या मंजुरीसाठी सबमिट केले जातील.">Changes will be submitted for admin review before going live.</p>
                    </div>
                    <a href="{{ route('profile') }}" class="text-sm text-gray-500 hover:text-pink-600 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="lang-label" data-en="Back to Profile" data-mr="प्रोफाइलवर परत जा">Back to Profile</span>
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
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Profile Photos</h2>
                    <p class="text-sm text-gray-500 mb-5">Add missing photos here. Replacing an existing photo will go to admin review. Changing the primary photo stays immediate.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach ([1, 2, 3, 4] as $slot)
                            @php $imgUrl = $allImgs[$slot] ?? null; @endphp
                            <div class="flex flex-col items-center gap-3">
                                <form method="POST"
                                      action="{{ route('profile.images.upload') }}"
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
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs font-medium">Photo {{ $slot }}</span>
                                                <span class="text-xs">Click to upload</span>
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
                                    <form method="POST" action="{{ route('profile.images.primary') }}">
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

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
                    @csrf

                    {{-- Personal Information --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach(['full_name', 'navras_naav', 'gender', 'date_of_birth', 'day_and_time_of_birth', 'place_of_birth', 'marital_status', 'height_cm__Oonchi', 'skin_complexion__Rang'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    @if($field === 'gender')
                                        <select id="{{ $field }}" name="{{ $field }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                            <option value="">Select</option>
                                            <option value="male" {{ ($values->{$field} ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ ($values->{$field} ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ ($values->{$field} ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    @elseif($field === 'date_of_birth')
                                        <input type="date" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, ($values->{$field} ?? '') instanceof \Carbon\Carbon ? $values->{$field}->format('Y-m-d') : ($values->{$field} ?? '')) }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                    @else
                                        <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Horoscope --}}
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Horoscope & Community</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach(['jaath', 'zodiac_sign__Raas', 'naadi', 'gann', 'devak', 'kul_devata'] as $field)
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @foreach(['fathers_name', 'mothers_name'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $values->{$field} ?? '') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">
                                </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-1 gap-y-4 mt-4">
                            @foreach(['siblings', 'uncles', 'aunts', 'naathe_relationships'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $fields[$field] }}</label>
                                    <textarea id="{{ $field }}" name="{{ $field }}" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm transition">{{ old($field, $values->{$field} ?? '') }}</textarea>
                                </div>
                            @endforeach
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
                        <a href="{{ route('profile') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                        <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition shadow-sm">
                            Submit for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
