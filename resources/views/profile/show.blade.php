<x-layout title="{{ $profile->first_name }} {{ $profile->last_name }} - Profile">
    {{-- jsPDF library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Navigation Bar with Back Button and Download PDF --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('root.matrimony') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-pink-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Profiles
            </a>
            <button
                id="download-pdf-btn"
                onclick="downloadProfilePDF()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg hover:bg-pink-700 active:bg-pink-800 transition shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </div>

        <div id="profile-content" class="bg-white rounded-2xl shadow-lg overflow-hidden">
            {{-- Profile Header --}}
            <div class="relative">
                <div class="h-48 bg-gradient-to-r from-pink-500 to-purple-600"></div>
                <div class="absolute -bottom-16 left-8">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200">
                        @if ($profile->profile_picture)
                            <img
                                id="profile-img"
                                src="{{$profile->profile_picture}}"
                                alt="{{ $profile->first_name }}"
                                class="w-full h-full object-cover"
                                crossorigin="anonymous"
                            />
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-pink-200">
                                <svg class="w-16 h-16 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Name & Basic Info --}}
            <div class="pt-20 px-8 pb-6 border-b border-gray-100">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $profile->first_name }} {{ $profile->last_name }}
                    </h1>
                    <p class="text-gray-500 mt-1">
                        {{ $profile->date_of_birth->age }} years old &middot; {{ ucfirst($profile->gender) }} &middot; {{ ucfirst($profile->marital_status) }}
                    </p>
                </div>

                @if ($profile->bio)
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ $profile->bio }}</p>
                @endif
            </div>

            {{-- Details Sections --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-0">
                {{-- Personal Information --}}
                <div class="px-8 py-6 border-b md:border-r border-gray-100">
                    <h2 class="text-sm font-semibold text-pink-600 uppercase tracking-wider mb-4">Personal Information</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Date of Birth</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->date_of_birth->format('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Gender</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->gender) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Marital Status</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->marital_status) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Height</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->height_cm }} cm</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Weight</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->weight_kg }} kg</dd>
                        </div>
                    </dl>
                </div>

                {{-- Background --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-pink-600 uppercase tracking-wider mb-4">Background</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Religion</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->religion }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Caste</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->caste }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Mother Tongue</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->mother_tongue }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Education</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->education }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Occupation</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->occupation }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Annual Income</dt>
                            <dd class="text-sm font-medium text-gray-900">&#8377;{{ number_format($profile->annual_income) }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Location & Contact --}}
                <div class="px-8 py-6 border-b md:border-r border-gray-100">
                    <h2 class="text-sm font-semibold text-pink-600 uppercase tracking-wider mb-4">Location & Contact</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">City</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->city }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">State</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->state }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Address</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right max-w-[200px]">{{ $profile->address }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->phone_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $profile->user->email }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Lifestyle --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-pink-600 uppercase tracking-wider mb-4">Lifestyle</h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Diet</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->dietary_preferences) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Smoking</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->smoking_habits) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Drinking</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ ucfirst($profile->drinking_habits) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Hobbies & Interests --}}
            <div class="px-8 py-6">
                <h2 class="text-sm font-semibold text-pink-600 uppercase tracking-wider mb-3">Hobbies & Interests</h2>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $profile->hobbies_interests }}</p>
            </div>
        </div>
    </div>

    {{-- PDF Download Script using jsPDF --}}
    <script>
        function downloadProfilePDF() {
            const btn = document.getElementById('download-pdf-btn');
            const originalText = btn.innerHTML;

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generating...
            `;

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                const margin = 15;
                const contentWidth = pageWidth - (margin * 2);
                let y = margin;

                // Colors
                const pink = [219, 39, 119];      // #db2777
                const purple = [168, 85, 247];    // #a855f7
                const darkGray = [17, 24, 39];    // #111827
                const mediumGray = [107, 114, 128]; // #6b7280
                const lightGray = [229, 231, 235]; // #e5e7eb

                // Profile data
                const profile = {
                    name: '{{ $profile->first_name }} {{ $profile->last_name }}',
                    age: '{{ $profile->date_of_birth->age }}',
                    gender: '{{ ucfirst($profile->gender) }}',
                    maritalStatus: '{{ ucfirst($profile->marital_status) }}',
                    bio: `{{ addslashes($profile->bio ?? '') }}`,
                    dob: '{{ $profile->date_of_birth->format("d M Y") }}',
                    height: '{{ $profile->height_cm }}',
                    weight: '{{ $profile->weight_kg }}',
                    religion: '{{ $profile->religion }}',
                    caste: '{{ $profile->caste }}',
                    motherTongue: '{{ $profile->mother_tongue }}',
                    education: '{{ $profile->education }}',
                    occupation: '{{ $profile->occupation }}',
                    annualIncome: '{{ number_format($profile->annual_income) }}',
                    city: '{{ $profile->city }}',
                    state: '{{ $profile->state }}',
                    address: `{{ addslashes($profile->address ?? '') }}`,
                    phone: '{{ $profile->phone_number }}',
                    email: '{{ $profile->user->email }}',
                    diet: '{{ ucfirst($profile->dietary_preferences) }}',
                    smoking: '{{ ucfirst($profile->smoking_habits) }}',
                    drinking: '{{ ucfirst($profile->drinking_habits) }}',
                    hobbies: `{{ addslashes($profile->hobbies_interests ?? '') }}`
                };

                // Draw gradient header (pink to purple)
                const headerHeight = 35;
                for (let i = 0; i < contentWidth; i++) {
                    const ratio = i / contentWidth;
                    const r = Math.round(pink[0] + (purple[0] - pink[0]) * ratio);
                    const g = Math.round(pink[1] + (purple[1] - pink[1]) * ratio);
                    const b = Math.round(pink[2] + (purple[2] - pink[2]) * ratio);
                    doc.setFillColor(r, g, b);
                    doc.rect(margin + i, y, 1, headerHeight, 'F');
                }
                y += headerHeight + 10;

                // Name
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(22);
                doc.setTextColor(...darkGray);
                doc.text(profile.name, margin, y);
                y += 8;

                // Basic info line
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(11);
                doc.setTextColor(...mediumGray);
                doc.text(`${profile.age} years old  •  ${profile.gender}  •  ${profile.maritalStatus}`, margin, y);
                y += 8;

                // Bio (if exists)
                if (profile.bio) {
                    doc.setFontSize(10);
                    doc.setTextColor(...mediumGray);
                    const bioLines = doc.splitTextToSize(profile.bio, contentWidth);
                    doc.text(bioLines, margin, y);
                    y += bioLines.length * 5 + 5;
                }

                // Separator line
                y += 3;
                doc.setDrawColor(...lightGray);
                doc.setLineWidth(0.3);
                doc.line(margin, y, pageWidth - margin, y);
                y += 10;

                // Helper function to draw a section
                function drawSection(title, items, startY) {
                    let currentY = startY;

                    // Section title
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(9);
                    doc.setTextColor(...pink);
                    doc.text(title.toUpperCase(), margin, currentY);
                    currentY += 7;

                    // Section items
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);

                    items.forEach(item => {
                        // Label
                        doc.setTextColor(...mediumGray);
                        doc.text(item.label, margin, currentY);

                        // Value (right-aligned)
                        doc.setTextColor(...darkGray);
                        const valueWidth = doc.getTextWidth(item.value);
                        doc.text(item.value, pageWidth - margin - valueWidth, currentY);

                        currentY += 6;
                    });

                    return currentY + 5;
                }

                // Personal Information
                y = drawSection('Personal Information', [
                    { label: 'Date of Birth', value: profile.dob },
                    { label: 'Gender', value: profile.gender },
                    { label: 'Marital Status', value: profile.maritalStatus },
                    { label: 'Height', value: `${profile.height} cm` },
                    { label: 'Weight', value: `${profile.weight} kg` }
                ], y);

                // Background
                y = drawSection('Background', [
                    { label: 'Religion', value: profile.religion },
                    { label: 'Caste', value: profile.caste },
                    { label: 'Mother Tongue', value: profile.motherTongue },
                    { label: 'Education', value: profile.education },
                    { label: 'Occupation', value: profile.occupation },
                    { label: 'Annual Income', value: `Rs. ${profile.annualIncome}` }
                ], y);

                // Location & Contact
                y = drawSection('Location & Contact', [
                    { label: 'City', value: profile.city },
                    { label: 'State', value: profile.state },
                    { label: 'Phone', value: profile.phone },
                    { label: 'Email', value: profile.email }
                ], y);

                // Lifestyle
                y = drawSection('Lifestyle', [
                    { label: 'Diet', value: profile.diet },
                    { label: 'Smoking', value: profile.smoking },
                    { label: 'Drinking', value: profile.drinking }
                ], y);

                // Hobbies & Interests
                if (profile.hobbies) {
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(9);
                    doc.setTextColor(...pink);
                    doc.text('HOBBIES & INTERESTS', margin, y);
                    y += 7;

                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);
                    doc.setTextColor(...darkGray);
                    const hobbiesLines = doc.splitTextToSize(profile.hobbies, contentWidth);
                    doc.text(hobbiesLines, margin, y);
                    y += hobbiesLines.length * 5 + 10;
                }

                // Footer
                doc.setDrawColor(...lightGray);
                doc.line(margin, pageHeight - 15, pageWidth - margin, pageHeight - 15);
                doc.setFontSize(8);
                doc.setTextColor(...mediumGray);
                const footerText = `Generated from Matrimony • ${new Date().toLocaleDateString()}`;
                const footerWidth = doc.getTextWidth(footerText);
                doc.text(footerText, (pageWidth - footerWidth) / 2, pageHeight - 10);

                // Save the PDF
                doc.save(`${profile.name.replace(/\s+/g, '_')}_Profile.pdf`);

                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalText;

            } catch (error) {
                console.error('PDF generation failed:', error);
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Failed to generate PDF. Please try again.');
            }
        }
    </script>
</x-layout>
