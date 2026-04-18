<x-layout title="{{ $profile->full_name ?? 'Profile' }} - Profile">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Navigation Bar with Back Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <a href="{{ route('root.matrimony') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-pink-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Matches
            </a>

            <div class="flex items-center gap-3">
                <!-- Language Toggle -->
                <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm p-1">
                    <button id="lang-btn-en" onclick="setLanguage('en')" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors text-gray-500 hover:text-gray-900">
                        English
                    </button>
                    <button id="lang-btn-mr" onclick="setLanguage('mr')" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors bg-pink-100 text-pink-700 shadow-sm">
                        मराठी
                    </button>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden" id="profile-card" data-profile-id="{{ $profile->id }}">
            <!-- Header -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-32 relative"></div>

            <div class="px-6 sm:px-10 pb-8">
                <!-- Profile Basic Info -->
                @php
                    $primaryUrl  = $profile->primaryImageUrl();
                    $allImgs     = $profile->allImageUrls();          // [slot => url]
                    $kundliUrl   = $profile->kundliImageUrl();
                @endphp
                <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12 mb-8 gap-4">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200 shrink-0">
                        @if ($primaryUrl)
                            <img src="{{ $primaryUrl }}" alt="{{ $profile->full_name }}"
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-pink-100">
                                <span class="text-4xl font-bold text-pink-500">
                                    {{ substr($profile->full_name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="text-center sm:text-left pb-2 flex-grow">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center justify-center sm:justify-start gap-2">
                            {{ $profile->full_name ?? 'N/A' }}
                            @if($profile->user && $profile->user->isApproved())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Verified
                                </span>
                            @endif
                        </h1>
                        <p class="text-gray-500 mt-1 flex items-center justify-center sm:justify-start gap-4 text-sm">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ ucfirst($profile->gender ?? 'N/A') }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2m-3-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/></svg>
                                ID: {{ $profile->user?->public_id ?? $profile->user?->id ?? 'N/A' }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                {{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->age . ' years' : 'N/A' }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $profile->place_of_birth ?? 'N/A' }}
                            </span>
                        </p>
                    </div>

                    @if(auth()->user() && auth()->user()->id !== $profile->user_id)
                    <div class="pb-2 flex gap-2">
                        @php
                            $phoneNumber = trim((string) ($profile->user->phone_number ?? ''));
                            $dialNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
                        @endphp

                        @if($dialNumber !== '')
                            <a href="tel:{{ $dialNumber }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 transition">
                                Contact : {{ $phoneNumber }}
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                                Contact User: Not available
                            </span>
                        @endif
                    </div>
                    @endif
                </div>




                <!-- Profile Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10 mt-10">
                    <!-- Left Column -->
                    <div class="space-y-10">
                        <!-- Personal Details -->
                        <section>
                            <h3 class="lang-label text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4" data-en="Personal Details" data-mr="वैयक्तिक माहिती">Personal Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Navras Naav" data-mr="नावरस नाव">Navras Naav</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->navras_naav ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Date of Birth" data-mr="जन्मतारीख">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') : 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Blood Group" data-mr="रक्तगट">Blood Group</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->blood_group ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Time of Birth" data-mr="जन्मदिवस आणि वेळ">Time of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->day_and_time_of_birth ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Place of Birth" data-mr="जन्म ठिकाण">Place of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->place_of_birth ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Marital Status" data-mr="वैवाहिक स्थिती">Marital Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($profile->marital_status ?? 'Not provided') }}</dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Physical & Astrology -->
                        <section>
                            <h3 class="lang-label text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4" data-en="Physical Details & Astrology" data-mr="शारीरिक आणि ज्योतिषविषयक माहिती">Physical Details & Astrology</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Height (Oonchi)" data-mr="उंची">Height (Oonchi)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->height_cm__Oonchi ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Skin Complexion (Rang)" data-mr="रंग">Skin Complexion (Rang)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->skin_complexion__Rang ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Jaath" data-mr="जात">Jaath</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->jaath ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Zodiac Sign (Raas)" data-mr="रास">Zodiac Sign (Raas)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->zodiac_sign__Raas ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Naadi" data-mr="नाडी">Naadi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->naadi ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Gann" data-mr="गण">Gann</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->gann ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Devak" data-mr="देवक">Devak</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->devak ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Kul Devata" data-mr="कुलदैवत">Kul Devata</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->kul_devata ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-10">
                        <!-- Education & Profession -->
                        <section>
                            <h3 class="lang-label text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4" data-en="Education & Career" data-mr="शिक्षण आणि व्यवसाय">Education & Career</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Education Type" data-mr="शिक्षण प्रकार">Education Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->education_type ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Education" data-mr="शिक्षण">Education</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->education ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Occupation" data-mr="नोकरी">Occupation</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->occupation ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Annual Income" data-mr="वार्षिक पगार">Annual Income</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->annual_income ? '₹' . number_format($profile->annual_income, 2) : 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Family Background -->
                        <section>
                            <h3 class="lang-label text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4" data-en="Family Background" data-mr="कौटुंबिक माहिती">Family Background</h3>
                            @php
                                $siblingsList = json_decode($profile->siblings ?? '', true);
                                $siblingsText = collect(is_array($siblingsList) ? $siblingsList : [])->map(function ($item) {
                                    $relation = trim((string) ($item['relation'] ?? ''));
                                    $value = trim((string) ($item['value'] ?? ''));

                                    if ($relation !== '' && $value !== '') {
                                        return $relation . ': ' . $value;
                                    }

                                    return $relation !== '' ? $relation : $value;
                                })->filter()->implode("\n");

                                $relativesList = json_decode($profile->uncles ?? '', true);
                                if (!is_array($relativesList)) {
                                    $relativesList = json_decode($profile->naathe_relationships ?? '', true);
                                }
                                $relativeEntries = collect(is_array($relativesList) ? $relativesList : [])->map(function ($item) {
                                    $relation = trim((string) ($item['relation'] ?? ''));
                                    $value = trim((string) ($item['value'] ?? ''));

                                    return [
                                        'relation' => $relation,
                                        'value' => $value,
                                    ];
                                })->filter(function ($item) {
                                    return $item['relation'] !== '' || $item['value'] !== '';
                                })->values();
                            @endphp
                            <dl class="space-y-4">
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Father's Name" data-mr="वडिलांचे नाव">Father's Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->fathers_name ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Mother's Name" data-mr="आईचे नाव">Mother's Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->mothers_name ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Siblings Details" data-mr="भावंड">Siblings Details</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $siblingsText !== '' ? $siblingsText : ($profile->siblings ?? 'Not provided') }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Relatives" data-mr="नातेवाईक">Relatives</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($relativeEntries->isNotEmpty())
                                            <ul class="space-y-1">
                                                @foreach($relativeEntries as $relativeEntry)
                                                    <li>
                                                        {{ $relativeEntry['relation'] !== '' ? $relativeEntry['relation'] : 'Relation' }}
                                                        @if($relativeEntry['value'] !== '')
                                                            : {{ $relativeEntry['value'] }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Location Details -->
                        <section>
                            <h3 class="lang-label text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4" data-en="Location & Address" data-mr="पत्ता आणि संपर्क">Location & Address</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Residential Address" data-mr="निवासी पत्ता">Residential Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->address ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Native Address" data-mr="मुळचा पत्ता">Native Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->native_address ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="lang-label text-sm font-medium text-gray-500" data-en="Village Farm" data-mr="मालमत्ता">Village Farm</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->village_farm ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                </div>
            </div>

            {{-- ── NEW Vertical Photo stack ── --}}
            @if (count($allImgs) > 1 || (count($allImgs) === 1 && !$primaryUrl))
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 sm:px-10 py-10">
                    <h3 class="lang-label text-2xl font-semibold text-gray-900 mb-8 text-center" data-en="Photo Gallery" data-mr="फोटो गॅलरी">Photo Gallery</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($allImgs as $slot => $url)
                            <div class="relative w-full aspect-square rounded-2xl overflow-hidden shadow-md group border border-gray-200">
                                <img src="{{ $url }}"
                                     alt="Photo {{ $slot }}"
                                     class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                @if ($slot === ($profile->primary_image ?? 1))
                                    <span class="absolute top-4 right-4 bg-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md whitespace-nowrap tracking-wide">
                                        Primary Profile Photo
                                    </span>
                                @endif
                                <div class="absolute inset-0 ring-1 ring-inset ring-black/10 rounded-2xl pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Biodata Image Section --}}
            @if($kundliUrl)
                <div class="border-t border-gray-100 bg-white px-6 sm:px-10 py-10">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-8 text-center">Biodata</h3>
                    <div class="flex justify-center">
                        <div class="w-full max-w-[600px] h-[700px]">
                            <div class="h-full rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                                <img src="{{ $kundliUrl }}" alt="Biodata image" class="w-full h-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Script for Generating PDF and Language Toggle --}}
    <script>
        // --- Language Toggle Logic ---
        document.addEventListener('DOMContentLoaded', () => {
            const savedLang = localStorage.getItem('matrimony_lang') || 'mr';
            setLanguage(savedLang);
        });

        function setLanguage(lang) {
            localStorage.setItem('matrimony_lang', lang);

            // Update Labels
            document.querySelectorAll('.lang-label').forEach(el => {
                if (lang === 'mr' && el.dataset.mr) {
                    el.textContent = el.dataset.mr;
                } else if (lang === 'en' && el.dataset.en) {
                    el.textContent = el.dataset.en;
                }
            });

            // Update UI Button states
            const btnEn = document.getElementById('lang-btn-en');
            const btnMr = document.getElementById('lang-btn-mr');

            if (lang === 'mr') {
                btnMr.className = "px-3 py-1.5 text-xs font-semibold rounded-md transition-colors bg-pink-100 text-pink-700 shadow-sm";
                btnEn.className = "px-3 py-1.5 text-xs font-semibold rounded-md transition-colors text-gray-500 hover:text-gray-900";
            } else {
                btnEn.className = "px-3 py-1.5 text-xs font-semibold rounded-md transition-colors bg-pink-100 text-pink-700 shadow-sm";
                btnMr.className = "px-3 py-1.5 text-xs font-semibold rounded-md transition-colors text-gray-500 hover:text-gray-900";
            }
        }

    </script>
</x-layout>
