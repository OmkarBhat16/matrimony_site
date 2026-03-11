<x-layout title="{{ $profile->full_name ?? 'Profile' }} - Profile">
    {{-- jsPDF library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Navigation Bar with Back Button and Download PDF --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('root.matrimony') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-pink-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Matches
            </a>

            <button onclick="downloadProfilePDF()" class="inline-flex items-center gap-2 px-4 py-2 bg-pink-50 text-pink-600 rounded-lg text-sm font-medium hover:bg-pink-100 transition border border-pink-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Profile
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-32 relative"></div>

            <div class="px-6 sm:px-10 pb-8">
                <!-- Profile Basic Info -->
                @php
                    $primaryUrl  = $profile->primaryImageUrl();
                    $allImgs     = $profile->allImageUrls();          // [slot => url]
                    $otherImgs   = array_filter($allImgs, fn($url, $slot) => $slot !== ($profile->primary_image ?? 1), ARRAY_FILTER_USE_BOTH);
                @endphp
                <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12 mb-8 gap-4">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200 shrink-0">
                        @if ($primaryUrl)
                            <img src="{{ $primaryUrl }}" alt="{{ $profile->full_name }}"
                                 class="w-full h-full object-cover">
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
                            @if($profile->user && $profile->user->approved)
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
                        <a href="mailto:{{ $profile->user->email ?? '' }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 transition">
                            Contact User
                        </a>
                    </div>
                    @endif
                </div>

                {{-- ── Photo gallery (other uploaded photos) ── --}}
                @if (count($allImgs) > 1 || (count($allImgs) === 1 && !$primaryUrl))
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Photos</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($allImgs as $slot => $url)
                                <div class="relative w-28 h-28 rounded-xl overflow-hidden border-2 shadow-sm
                                            {{ $slot === ($profile->primary_image ?? 1) ? 'border-pink-400' : 'border-gray-200' }}">
                                    <img src="{{ $url }}"
                                         alt="Photo {{ $slot }}"
                                         class="w-full h-full object-cover">
                                    @if ($slot === ($profile->primary_image ?? 1))
                                        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 bg-pink-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow whitespace-nowrap">
                                            Primary
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

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
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Physical Details & Astrology</h3>
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
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Education & Career</h3>
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
                                    <dt class="text-sm font-medium text-gray-500">Siblings Details</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->siblings ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uncles Details</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $profile->uncles ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Aunts Details</dt>
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
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Location & Address</h3>
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

    {{-- Script for Generating PDF --}}
    <script>
        function downloadProfilePDF() {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Color palette
                const primaryColor = [219, 39, 119]; // Pink-600
                const secondaryColor = [75, 85, 99]; // Gray-600
                const textColor = [17, 24, 39]; // Gray-900
                const lightBg = [253, 242, 248]; // Pink-50

                let yPos = 20;
                const margin = 20;
                const pageWidth = doc.internal.pageSize.width;
                const contentWidth = pageWidth - 2 * margin;

                // Helper functions
                const checkPageBreak = (neededHeight) => {
                    if (yPos + neededHeight > doc.internal.pageSize.height - margin) {
                        doc.addPage();
                        yPos = margin;
                        return true;
                    }
                    return false;
                };

                const addSectionTitle = (title) => {
                    checkPageBreak(15);
                    yPos += 5;
                    doc.setFillColor(...lightBg);
                    doc.rect(margin, yPos - 5, contentWidth, 10, 'F');
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(12);
                    doc.setTextColor(...primaryColor);
                    doc.text(title, margin + 5, yPos + 2);
                    yPos += 12;
                };

                const addField = (label, value) => {
                    if (!value || value === 'Not provided' || value === 'N/A') return;

                    const strValue = String(value);
                    const splitValue = doc.splitTextToSize(strValue, contentWidth / 2);
                    const fieldHeight = Math.max(7, splitValue.length * 5);

                    checkPageBreak(fieldHeight);

                    doc.setFont("helvetica", "normal");
                    doc.setFontSize(10);
                    doc.setTextColor(...secondaryColor);
                    doc.text(label + ":", margin, yPos);

                    doc.setTextColor(...textColor);
                    doc.text(splitValue, margin + contentWidth / 2 - 10, yPos);

                    yPos += fieldHeight;
                };

                // Header
                doc.setFillColor(...primaryColor);
                doc.rect(0, 0, pageWidth, 40, 'F');

                doc.setTextColor(255, 255, 255);
                doc.setFont("helvetica", "bold");
                doc.setFontSize(24);
                doc.text("Matrimonial Profile", margin, 25);

                yPos = 50;

                // Fetch Profile Data from blade
                const profile = {
                    name: '{{ $profile->full_name ?? "Not provided" }}',
                    navras_naav: '{{ $profile->navras_naav ?? "Not provided" }}',
                    gender: '{{ ucfirst($profile->gender ?? "") }}',
                    maritalStatus: '{{ ucfirst($profile->marital_status ?? "") }}',
                    dob: '{{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format("d M Y") : "" }}',
                    timeOfBirth: '{{ $profile->day_and_time_of_birth ?? "" }}',
                    placeOfBirth: '{{ $profile->place_of_birth ?? "" }}',

                    height: '{{ $profile->height_cm__Oonchi ?? "" }}',
                    complexion: '{{ $profile->skin_complexion__Rang ?? "" }}',
                    jaath: '{{ $profile->jaath ?? "" }}',
                    zodiac: '{{ $profile->zodiac_sign__Raas ?? "" }}',
                    naadi: '{{ $profile->naadi ?? "" }}',
                    gann: '{{ $profile->gann ?? "" }}',
                    devak: '{{ $profile->devak ?? "" }}',
                    kulDevata: '{{ $profile->kul_devata ?? "" }}',

                    education: '{{ $profile->education ?? "" }}',
                    occupation: '{{ $profile->occupation ?? "" }}',
                    income: '{{ $profile->annual_income ? "Rs. ".number_format($profile->annual_income, 2) : "" }}',

                    father: '{{ $profile->fathers_name ?? "" }}',
                    mother: '{{ $profile->mothers_name ?? "" }}',
                    siblings: `{{ addslashes(str_replace("\r\n", " ", $profile->siblings ?? '')) }}`,
                    uncles: `{{ addslashes(str_replace("\r\n", " ", $profile->uncles ?? '')) }}`,
                    aunts: `{{ addslashes(str_replace("\r\n", " ", $profile->aunts ?? '')) }}`,
                    naathe: `{{ addslashes(str_replace("\r\n", " ", $profile->naathe_relationships ?? '')) }}`,

                    mumbaiAddr: `{{ addslashes(str_replace("\r\n", " ", $profile->mumbai_address ?? '')) }}`,
                    villageAddr: `{{ addslashes(str_replace("\r\n", " ", $profile->village_address ?? '')) }}`,
                    villageFarm: '{{ $profile->village_farm ?? "" }}'
                };

                // Personal Details
                addSectionTitle("Personal Details");
                addField("Full Name", profile.name);
                addField("Navras Naav", profile.navras_naav);
                addField("Gender", profile.gender);
                addField("Date of Birth", profile.dob);
                addField("Time of Birth", profile.timeOfBirth);
                addField("Place of Birth", profile.placeOfBirth);
                addField("Marital Status", profile.maritalStatus);

                // Physical & Astrology
                addSectionTitle("Physical Details & Astrology");
                addField("Height (Oonchi)", profile.height);
                addField("Complexion (Rang)", profile.complexion);
                addField("Jaath", profile.jaath);
                addField("Zodiac (Raas)", profile.zodiac);
                addField("Naadi", profile.naadi);
                addField("Gann", profile.gann);
                addField("Devak", profile.devak);
                addField("Kul Devata", profile.kulDevata);

                // Education & Career
                addSectionTitle("Education & Career");
                addField("Education", profile.education);
                addField("Occupation", profile.occupation);
                addField("Annual Income", profile.income);

                // Family Background
                addSectionTitle("Family Background");
                addField("Father's Name", profile.father);
                addField("Mother's Name", profile.mother);
                addField("Siblings Details", profile.siblings);
                addField("Uncles Details", profile.uncles);
                addField("Aunts Details", profile.aunts);
                addField("Naathe / Relationships", profile.naathe);

                // Location Details
                addSectionTitle("Location Details");
                addField("Mumbai Address", profile.mumbaiAddr);
                addField("Village Address", profile.villageAddr);
                addField("Village Farm", profile.villageFarm);

                // Footer
                const pageCount = doc.internal.getNumberOfPages();
                doc.setFont("helvetica", "normal");
                doc.setFontSize(8);
                doc.setTextColor(150, 150, 150);

                for(let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text(
                        "Generated from Matrimony Platform - Page " + i + " of " + pageCount,
                        pageWidth / 2,
                        doc.internal.pageSize.height - 10,
                        { align: "center" }
                    );
                }

                // Save PDF
                doc.save(`Profile_${profile.name.replace(/\s+/g, '_')}.pdf`);

            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('There was an error generating the PDF. Please try again.');
            }
        }
    </script>
</x-layout>
