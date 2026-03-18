<x-layout>
    <x-slot:title>Create Profile - Matrimony</x-slot:title>

    <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="lang-label text-3xl font-extrabold text-gray-900" data-en="Welcome! Let's Create Your Profile" data-mr="स्वागत आहे! तुमची प्रोफाइल तयार करूया">
                    Welcome! Let's Create Your Profile
                </h1>
                <p class="lang-label mt-2 text-sm text-gray-600" data-en="Please fill in the details below to complete your profile setup." data-mr="तुमची प्रोफाइल पूर्ण करण्यासाठी खालील माहिती भरा.">
                    Please fill in the details below to complete your profile setup.
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 rounded-full z-0"></div>

                    <!-- Progress Line -->
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-pink-500 rounded-full z-0 transition-all duration-300" id="progress-line" style="width: 0%"></div>

                    <!-- Step 1 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-1" class="w-10 h-10 bg-pink-600 text-white rounded-full flex items-center justify-center font-bold shadow-md transition-colors duration-300">1</div>
                        <span id="label-1" class="lang-label mt-2 text-xs font-medium text-pink-600" data-en="Personal" data-mr="वैयक्तिक">Personal</span>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-2" class="w-10 h-10 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center font-bold transition-colors duration-300">2</div>
                        <span id="label-2" class="lang-label mt-2 text-xs font-medium text-gray-500" data-en="Horoscope & Education" data-mr="कुंडली व शिक्षण">Horoscope & Education</span>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-3" class="w-10 h-10 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center font-bold transition-colors duration-300">3</div>
                        <span id="label-3" class="lang-label mt-2 text-xs font-medium text-gray-500" data-en="Family" data-mr="कुटुंब">Family</span>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-10">

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- novalidate disables native browser validation so our JS controls stepping --}}
                <form method="POST" action="{{ route('onboarding.store') }}" id="onboarding-form" novalidate enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step-content block">
                        <h2 class="lang-label text-xl font-bold text-gray-900 mb-6" data-en="Personal Information" data-mr="वैयक्तिक माहिती">Personal Information</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Full Name" data-mr="पूर्ण नाव">Full Name</span> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('full_name') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="navras_naav" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Navras Naav" data-mr="नावरस नाव">Navras Naav</span>
                                </label>
                                <input type="text" id="navras_naav" name="navras_naav" value="{{ old('navras_naav') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('navras_naav') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Gender" data-mr="लिंग">Gender</span> <span class="text-red-500">*</span>
                                </label>
                                <select id="gender" name="gender" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('gender') border-red-500 @enderror">
                                    <option value="" class="lang-label" data-en="Select Gender" data-mr="लिंग निवडा">Select Gender</option>
                                    <option value="male" class="lang-label" data-en="Male" data-mr="पुरुष" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" class="lang-label" data-en="Female" data-mr="स्त्री" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" class="lang-label" data-en="Other" data-mr="इतर" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Date of Birth" data-mr="जन्मतारीख">Date of Birth</span>
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('date_of_birth') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Marital Status" data-mr="वैवाहिक स्थिती">Marital Status</span>
                                </label>
                                <select id="marital_status" name="marital_status"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('marital_status') border-red-500 @enderror">
                                    <option value="" class="lang-label" data-en="Select Status" data-mr="स्थिती निवडा">Select Status</option>
                                    <option value="Single" class="lang-label" data-en="Single" data-mr="अविवाहित" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" class="lang-label" data-en="Married" data-mr="विवाहित" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" class="lang-label" data-en="Divorced" data-mr="घटस्फोटित" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" class="lang-label" data-en="Widowed" data-mr="विधुर/विधवा" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            <div>
                                <label for="height_cm__Oonchi" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Height (Oonchi)" data-mr="उंची">Height (Oonchi)</span>
                                </label>
                                <input type="text" id="height_cm__Oonchi" name="height_cm__Oonchi" value="{{ old('height_cm__Oonchi') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('height_cm__Oonchi') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="skin_complexion__Rang" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Skin Complexion (Rang)" data-mr="रंग">Skin Complexion (Rang)</span>
                                </label>
                                <input type="text" id="skin_complexion__Rang" name="skin_complexion__Rang" value="{{ old('skin_complexion__Rang') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 @error('skin_complexion__Rang') border-red-500 @enderror">
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="lang-label text-base font-semibold text-gray-900 mb-1" data-en="Profile Photos" data-mr="प्रोफाइल फोटो">Profile Photos</h3>
                            <p class="lang-label text-sm text-gray-500 mb-4" data-en="Upload up to 3 photos. The one you mark as primary will be shown on your card. Photo 1 is primary by default." data-mr="जास्तीत जास्त 3 फोटो अपलोड करा. तुम्ही प्राथमिक म्हणून निवडलेला फोटो तुमच्या कार्डवर दिसेल. फोटो 1 डीफॉल्टने प्राथमिक आहे.">Upload up to 3 photos. The one you mark as primary will be shown on your card. Photo 1 is primary by default.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="photo-upload-grid">
                                @foreach ([1, 2, 3] as $slot)
                                    <div class="flex flex-col items-center gap-2" id="photo-slot-{{ $slot }}">
                                        {{-- Preview area --}}
                                        <div class="relative w-full aspect-square rounded-xl border-2 border-dashed border-gray-300 overflow-hidden bg-gray-50 hover:border-pink-400 transition cursor-pointer"
                                             id="preview-box-{{ $slot }}"
                                             onclick="document.getElementById('image-input-{{ $slot }}').click()">
                                            <img id="preview-img-{{ $slot }}"
                                                 src=""
                                                 alt="Photo {{ $slot }}"
                                                 data-alt-en="Photo {{ $slot }}"
                                                 data-alt-mr="फोटो {{ $slot }}"
                                                 class="hidden w-full h-full object-cover">
                                            <div id="preview-placeholder-{{ $slot }}" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 gap-1">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="lang-label text-xs font-medium" data-en="Photo {{ $slot }}" data-mr="फोटो {{ $slot }}">Photo {{ $slot }}</span>
                                                <span class="lang-label text-xs" data-en="Click to upload" data-mr="अपलोड करण्यासाठी क्लिक करा">Click to upload</span>
                                            </div>
                                        </div>

                                        {{-- Hidden file input — name uses 1-based slot key --}}
                                        <input type="file"
                                               id="image-input-{{ $slot }}"
                                               name="images[{{ $slot }}]"
                                               accept="image/jpeg,image/png,image/webp"
                                               class="hidden"
                                               onchange="previewPhoto({{ $slot }}, this)">

                                        {{-- Primary radio --}}
                                        <label class="flex items-center gap-1.5 text-sm cursor-pointer select-none"
                                               id="primary-label-{{ $slot }}">
                                            <input type="radio"
                                                   name="primary_image"
                                                   value="{{ $slot }}"
                                                   id="primary-radio-{{ $slot }}"
                                                   {{ $slot === 1 ? 'checked' : '' }}
                                                   class="accent-pink-600 cursor-pointer">
                                            <span class="lang-label text-gray-600" data-en="{{ $slot === 1 ? 'Primary (default)' : 'Set as primary' }}" data-mr="{{ $slot === 1 ? 'प्राथमिक (डीफॉल्ट)' : 'प्राथमिक म्हणून सेट करा' }}">{{ $slot === 1 ? 'Primary (default)' : 'Set as primary' }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Horoscope & Education -->
                    <div id="step2" class="step-content hidden">
                        <h2 class="lang-label text-xl font-bold text-gray-900 mb-6" data-en="Horoscope, Education & Profession" data-mr="कुंडली, शिक्षण आणि व्यवसाय">Horoscope, Education & Profession</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="day_and_time_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Day and Time of Birth" data-mr="जन्मदिनांक व वेळ">Day and Time of Birth</span>
                                </label>
                                <input type="text" id="day_and_time_of_birth" name="day_and_time_of_birth" value="{{ old('day_and_time_of_birth') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Place of Birth" data-mr="जन्म ठिकाण">Place of Birth</span>
                                </label>
                                <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="zodiac_sign__Raas" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Zodiac Sign (Raas)" data-mr="रास">Zodiac Sign (Raas)</span>
                                </label>
                                <input type="text" id="zodiac_sign__Raas" name="zodiac_sign__Raas" value="{{ old('zodiac_sign__Raas') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="naadi" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Naadi" data-mr="नाडी">Naadi</span>
                                </label>
                                <input type="text" id="naadi" name="naadi" value="{{ old('naadi') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="gann" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Gann" data-mr="गण">Gann</span>
                                </label>
                                <input type="text" id="gann" name="gann" value="{{ old('gann') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="jaath" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Religion / Jaath" data-mr="धर्म / जात">Religion / Jaath</span>
                                </label>
                                <input type="text" id="jaath" name="jaath" value="{{ old('jaath') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="devak" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Devak" data-mr="देवक">Devak</span>
                                </label>
                                <input type="text" id="devak" name="devak" value="{{ old('devak') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="kul_devata" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Kul Devata" data-mr="कुलदैवत">Kul Devata</span>
                                </label>
                                <input type="text" id="kul_devata" name="kul_devata" value="{{ old('kul_devata') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="education" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Education" data-mr="शिक्षण">Education</span>
                                </label>
                                <input type="text" id="education" name="education" value="{{ old('education') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Occupation" data-mr="नोकरी">Occupation</span>
                                </label>
                                <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="annual_income" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Annual Income" data-mr="वार्षिक पगार">Annual Income</span>
                                </label>
                                <input type="number" step="0.01" id="annual_income" name="annual_income" value="{{ old('annual_income') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Family Details -->
                    <div id="step3" class="step-content hidden">
                        <h2 class="lang-label text-xl font-bold text-gray-900 mb-6" data-en="Family Details" data-mr="कौटुंबिक माहिती">Family Details</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="fathers_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Father's Name" data-mr="वडिलांचे नाव">Father's Name</span>
                                </label>
                                <input type="text" id="fathers_name" name="fathers_name" value="{{ old('fathers_name') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <div>
                                <label for="mothers_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Mother's Name" data-mr="आईचे नाव">Mother's Name</span>
                                </label>
                                <input type="text" id="mothers_name" name="mothers_name" value="{{ old('mothers_name') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="siblings" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Siblings" data-mr="भावंड">Siblings</span>
                                </label>
                                <textarea id="siblings" name="siblings" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('siblings') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="uncles" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Uncles" data-mr="काका / मामा">Uncles</span>
                                </label>
                                <textarea id="uncles" name="uncles" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('uncles') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="aunts" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Aunts" data-mr="मावशी / आत्या">Aunts</span>
                                </label>
                                <textarea id="aunts" name="aunts" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('aunts') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="naathe_relationships" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Naathe Relationships" data-mr="नातेवाईक">Naathe Relationships</span>
                                </label>
                                <textarea id="naathe_relationships" name="naathe_relationships" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('naathe_relationships') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="mumbai_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Mumbai Address" data-mr="मुंबईचा पत्ता">Mumbai Address</span>
                                </label>
                                <textarea id="mumbai_address" name="mumbai_address" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('mumbai_address') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="village_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Village Address" data-mr="गावाचा पत्ता">Village Address</span>
                                </label>
                                <textarea id="village_address" name="village_address" rows="2"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">{{ old('village_address') }}</textarea>
                            </div>

                            <div>
                                <label for="village_farm" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="lang-label" data-en="Village Farm" data-mr="मालमत्ता">Village Farm</span>
                                </label>
                                <input type="text" id="village_farm" name="village_farm" value="{{ old('village_farm') }}"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                        </div>
                    </div>

                </form>
                {{-- Navigation buttons are OUTSIDE the form so Enter / button clicks never accidentally submit --}}
                <div class="mt-10 flex items-center justify-between border-t border-gray-200 pt-6">
                    <button type="button" id="prev-btn"
                        class="hidden px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition">
                        <span aria-hidden="true">&larr;</span>
                        <span class="lang-label ml-1" data-en="Back" data-mr="मागे">Back</span>
                    </button>

                    <div class="ml-auto flex gap-3">
                        <button type="button" id="next-btn"
                            class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition">
                            <span class="lang-label" data-en="Continue" data-mr="पुढे">Continue</span>
                            <span aria-hidden="true" class="ml-1">&rarr;</span>
                        </button>

                        {{-- This button is outside the form so it must explicitly target the form via form= --}}
                        <button type="submit" id="submit-btn" form="onboarding-form"
                            class="hidden px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition">
                            <span class="lang-label" data-en="Submit for Review" data-mr="परीक्षणासाठी सबमिट करा">Submit for Review</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentStep = 1;
            const totalSteps = 3;

            // ---- localStorage soft-save ----
            const STORAGE_KEY = 'onboarding_draft_{{ auth()->id() }}';

            function saveToLocalStorage() {
                const form = document.getElementById('onboarding-form');
                const data = {};
                form.querySelectorAll('input:not([type=file]):not([type=radio]):not([type=hidden]), select, textarea').forEach(el => {
                    if (el.name) data[el.name] = el.value;
                });
                // Save radio selection
                const checkedRadio = form.querySelector('input[name=primary_image]:checked');
                if (checkedRadio) data['primary_image'] = checkedRadio.value;
                data['_step'] = currentStep;
                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            }

            function restoreFromLocalStorage() {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (!saved) return;
                try {
                    const data = JSON.parse(saved);
                    const form = document.getElementById('onboarding-form');
                    for (const [name, value] of Object.entries(data)) {
                        if (name === '_step') {
                            currentStep = Math.min(Math.max(parseInt(value) || 1, 1), totalSteps);
                            continue;
                        }
                        if (name === 'primary_image') {
                            const radio = form.querySelector(`input[name=primary_image][value="${value}"]`);
                            if (radio) radio.checked = true;
                            continue;
                        }
                        const el = form.querySelector(`[name="${name}"]`);
                        if (el && !el.matches('[type=file]')) el.value = value;
                    }
                } catch(e) { /* ignore corrupt data */ }
            }

            function clearLocalStorage() {
                localStorage.removeItem(STORAGE_KEY);
            }

            // Restore saved data on load
            restoreFromLocalStorage();

            // Auto-save on any input change
            document.getElementById('onboarding-form').addEventListener('input', saveToLocalStorage);
            document.getElementById('onboarding-form').addEventListener('change', saveToLocalStorage);

            // Clear localStorage on form submit
            document.getElementById('onboarding-form').addEventListener('submit', clearLocalStorage);

            const steps = {
                1: document.getElementById('step1'),
                2: document.getElementById('step2'),
                3: document.getElementById('step3'),
            };

            const indicators = {
                1: document.getElementById('indicator-1'),
                2: document.getElementById('indicator-2'),
                3: document.getElementById('indicator-3'),
            };

            const labels = {
                1: document.getElementById('label-1'),
                2: document.getElementById('label-2'),
                3: document.getElementById('label-3'),
            };

            const prevBtn    = document.getElementById('prev-btn');
            const nextBtn    = document.getElementById('next-btn');
            const submitBtn  = document.getElementById('submit-btn');
            const progressLine = document.getElementById('progress-line');

            // ---- Photo preview ----
            window.previewPhoto = function (slot, input) {
                if (!input.files || !input.files[0]) return;
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('preview-img-' + slot);
                    const placeholder = document.getElementById('preview-placeholder-' + slot);
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            };

            // Prevent Enter key from submitting the form while on step 1 or 2
            document.getElementById('onboarding-form').addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const tag = e.target.tagName.toLowerCase();
                    // Allow Enter in textareas (multiline), block it everywhere else unless on last step
                    if (tag !== 'textarea' && currentStep < totalSteps) {
                        e.preventDefault();
                        advanceStep();
                    }
                }
            });

            function updateUI() {
                // Progress line: 0% on step 1, 50% on step 2, 100% on step 3
                const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressLine.style.width = `${progress}%`;

                for (let i = 1; i <= totalSteps; i++) {
                    const ind = indicators[i];
                    const lbl = labels[i];

                    if (i === currentStep) {
                        // Active step
                        steps[i].classList.remove('hidden');
                        steps[i].classList.add('block');
                        ind.className = 'w-10 h-10 bg-pink-600 text-white rounded-full flex items-center justify-center font-bold shadow-md transition-colors duration-300';
                        ind.innerHTML = i;
                        lbl.className = 'lang-label mt-2 text-xs font-medium text-pink-600';
                    } else if (i < currentStep) {
                        // Completed step
                        steps[i].classList.add('hidden');
                        steps[i].classList.remove('block');
                        ind.className = 'w-10 h-10 bg-pink-100 border-2 border-pink-500 text-pink-600 rounded-full flex items-center justify-center font-bold transition-colors duration-300';
                        ind.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                        lbl.className = 'lang-label mt-2 text-xs font-medium text-pink-600';
                    } else {
                        // Future step
                        steps[i].classList.add('hidden');
                        steps[i].classList.remove('block');
                        ind.className = 'w-10 h-10 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center font-bold transition-colors duration-300';
                        ind.innerHTML = i;
                        lbl.className = 'lang-label mt-2 text-xs font-medium text-gray-500';
                    }
                }

                // Back button
                if (currentStep === 1) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }

                // Continue vs Submit
                if (currentStep === totalSteps) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function validateCurrentStep() {
                const stepEl = steps[currentStep];
                const required = stepEl.querySelectorAll('[required]');
                let valid = true;

                required.forEach(function (field) {
                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('border-red-500');
                        field.classList.remove('border-gray-300');
                    } else {
                        field.classList.remove('border-red-500');
                        field.classList.add('border-gray-300');
                    }
                });

                if (!valid) {
                    // Scroll to the first invalid field
                    const firstInvalid = stepEl.querySelector('.border-red-500');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                }

                return valid;
            }

            function advanceStep() {
                if (currentStep < totalSteps && validateCurrentStep()) {
                    currentStep++;
                    updateUI();
                }
            }

            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                advanceStep();
            });

            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                }
            });

            // Initialise
            updateUI();
        });
    </script>
</x-layout>
