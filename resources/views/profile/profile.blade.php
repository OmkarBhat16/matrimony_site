<x-layout>
    <x-slot:title>My Profile - Matrimony</x-slot:title>

    @php
        $user    = auth()->user();
        $profile = $user->profile;
        $allImgs = $profile->allImageUrls();   // [slot => url]
        $primaryUrl = $profile->primaryImageUrl();
    @endphp


    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header banner -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-32 relative"></div>

            <div class="px-6 sm:px-10 pb-8">
                <!-- Profile Basic Info -->
                <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12 mb-8 gap-4">

                    {{-- Avatar: primary photo or fallback icon --}}
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200 shrink-0">
                        @if ($primaryUrl)
                            <img src="{{ $primaryUrl }}" alt="{{ $profile->full_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-pink-100">
                                <svg class="w-16 h-16 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="text-center sm:text-left pb-2 flex-grow">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center justify-center sm:justify-start gap-2">
                            {{ $profile->full_name ?? $user->name }}
                            @if($user->isApproved())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                            @elseif($user->isPendingReview())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending Review</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </h1>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ $user->email }} · {{ $user->phone_number }}
                        </p>
                    </div>

                    <div class="pb-2 flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-white bg-pink-600 hover:bg-pink-700 rounded-lg text-sm font-medium transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profile
                        </a>
                        <a href="{{ route('profile.show', $profile) }}" class="inline-flex items-center px-4 py-2 border border-pink-600 text-pink-600 bg-white hover:bg-pink-50 rounded-lg text-sm font-medium transition shadow-sm">
                            View Public Profile
                        </a>
                    </div>
                </div>

                @if($pendingEdit)
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-amber-700"><strong>Pending Edit:</strong> You have profile changes submitted for admin review ({{ $pendingEdit->created_at->diffForHumans() }}).</p>
                    </div>
                @endif

                {{-- ============================================================
                     PHOTO MANAGEMENT PANEL
                ============================================================ --}}
                <div class="mb-10 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Your Photos</h3>
                    <p class="text-sm text-gray-500 mb-5">Up to 3 photos. Click a photo to upload / replace it. Set any uploaded photo as your primary display photo.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        @foreach ([1, 2, 3] as $slot)
                            @php $imgUrl = $allImgs[$slot] ?? null; @endphp
                            <div class="flex flex-col items-center gap-3">

                                {{-- Photo box (click triggers hidden file input) --}}
                                <form method="POST"
                                      action="{{ route('profile.images.upload') }}"
                                      enctype="multipart/form-data"
                                      id="upload-form-{{ $slot }}"
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
                                            {{-- Primary crown badge --}}
                                            @if (($profile->primary_image ?? 1) == $slot)
                                                <span class="absolute top-2 left-2 bg-pink-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                                                    Primary
                                                </span>
                                            @endif
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 gap-1">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs font-medium">Photo {{ $slot }}</span>
                                                <span class="text-xs">Click to upload</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Hidden file input — auto-submits the form on change --}}
                                    <input type="file"
                                           id="upload-input-{{ $slot }}"
                                           name="images[{{ $slot }}]"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="hidden"
                                           onchange="this.closest('form').submit()">
                                </form>

                                {{-- Set as primary button (only if image exists and not already primary) --}}
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

                <!-- Profile Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10 mt-10">
                    <!-- Left Column -->
                    <div class="space-y-10">
                        <!-- Personal Details -->
                        <section>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Personal Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Navras Naav</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->navras_naav ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($profile->gender ?? 'Not provided') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') : 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Day and Time of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->day_and_time_of_birth ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Place of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->place_of_birth ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Marital Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($profile->marital_status ?? 'Not provided') }}</dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Physical & Astrology -->
                        <section>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Physical & Astrology</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Height (Oonchi)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->height_cm__Oonchi ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Skin Complexion (Rang)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->skin_complexion__Rang ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jaath</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->jaath ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Zodiac Sign (Raas)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->zodiac_sign__Raas ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Naadi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->naadi ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gann</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->gann ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Devak</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->devak ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kul Devata</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->kul_devata ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-10">
                        <!-- Education & Profession -->
                        <section>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Education & Profession</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Education</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->education ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->occupation ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Annual Income</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->annual_income ? '₹' . number_format($profile->annual_income, 2) : 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Family Background -->
                        <section>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Family Background</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Father's Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->fathers_name ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mother's Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->mothers_name ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Siblings</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->siblings ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uncles</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->uncles ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Aunts</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->aunts ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Naathe / Relationships</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->naathe_relationships ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>

                        <!-- Location Details -->
                        <section>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Location Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mumbai Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->mumbai_address ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Village Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->village_address ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Village Farm</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $profile->village_farm ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
