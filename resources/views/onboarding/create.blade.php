<x-layout>
    <x-slot:title>Create Profile - Matrimony</x-slot:title>

    <div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-50 py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Welcome, {{ auth()->user()->username }}!
                </h1>
                <p class="mt-2 text-gray-600">
                    Let's create your profile in 3 simple steps
                </p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center">
                            <div id="step1-indicator" class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center font-bold">
                                1
                            </div>
                            <span class="text-xs mt-1 text-pink-600 font-medium">Personal</span>
                        </div>
                        <div id="line1" class="w-16 sm:w-24 h-1 bg-gray-300 mx-2"></div>
                        
                        <!-- Step 2 -->
                        <div class="flex flex-col items-center">
                            <div id="step2-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">
                                2
                            </div>
                            <span class="text-xs mt-1 text-gray-500 font-medium">Background</span>
                        </div>
                        <div id="line2" class="w-16 sm:w-24 h-1 bg-gray-300 mx-2"></div>
                        
                        <!-- Step 3 -->
                        <div class="flex flex-col items-center">
                            <div id="step3-indicator" class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">
                                3
                            </div>
                            <span class="text-xs mt-1 text-gray-500 font-medium">Lifestyle</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
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

                <form method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data" id="onboarding-form">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step-content">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('first_name') border-red-500 @enderror"
                                    placeholder="John">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('last_name') border-red-500 @enderror"
                                    placeholder="Doe">
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="text" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('date_of_birth') border-red-500 @enderror"
                                    placeholder="dd-mm-yyyy" pattern="\d{2}-\d{2}-\d{4}" maxlength="10">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select id="gender" name="gender" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('gender') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <!-- Marital Status -->
                            <div>
                                <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                <select id="marital_status" name="marital_status" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('marital_status') border-red-500 @enderror">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">+91</span>
                                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('phone_number') border-red-500 @enderror"
                                        placeholder="98765 43210">
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="mt-5">
                            <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden" id="preview-container">
                                    <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required
                                    class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100 transition">
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mt-5">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">About Me</label>
                            <textarea id="bio" name="bio" rows="3" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('bio') border-red-500 @enderror"
                                placeholder="Tell us a little about yourself...">{{ old('bio') }}</textarea>
                        </div>
                    </div>

                    <!-- Step 2: Background & Location -->
                    <div id="step2" class="step-content hidden">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Background & Location</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Religion -->
                            <div>
                                <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                                <input type="text" id="religion" name="religion" value="{{ old('religion') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('religion') border-red-500 @enderror"
                                    placeholder="e.g., Hindu, Muslim, Christian">
                            </div>

                            <!-- Caste -->
                            <div>
                                <label for="caste" class="block text-sm font-medium text-gray-700 mb-1">Caste</label>
                                <input type="text" id="caste" name="caste" value="{{ old('caste') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('caste') border-red-500 @enderror"
                                    placeholder="Enter your caste">
                            </div>

                            <!-- Mother Tongue -->
                            <div>
                                <label for="mother_tongue" class="block text-sm font-medium text-gray-700 mb-1">Mother Tongue</label>
                                <input type="text" id="mother_tongue" name="mother_tongue" value="{{ old('mother_tongue') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('mother_tongue') border-red-500 @enderror"
                                    placeholder="e.g., Hindi, Tamil, Telugu">
                            </div>

                            <!-- Education -->
                            <div>
                                <label for="education" class="block text-sm font-medium text-gray-700 mb-1">Education</label>
                                <input type="text" id="education" name="education" value="{{ old('education') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('education') border-red-500 @enderror"
                                    placeholder="e.g., B.Tech, MBA, MBBS">
                            </div>

                            <!-- Occupation -->
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('occupation') border-red-500 @enderror"
                                    placeholder="e.g., Software Engineer, Doctor">
                            </div>

                            <!-- Annual Income -->
                            <div>
                                <label for="annual_income" class="block text-sm font-medium text-gray-700 mb-1">Annual Income (₹)</label>
                                <input type="number" id="annual_income" name="annual_income" value="{{ old('annual_income') }}" required min="0" step="1000"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('annual_income') border-red-500 @enderror"
                                    placeholder="e.g., 500000">
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" id="state" name="state" value="{{ old('state') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('state') border-red-500 @enderror"
                                    placeholder="e.g., Maharashtra, Karnataka">
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('city') border-red-500 @enderror"
                                    placeholder="e.g., Mumbai, Bangalore">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-5">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="address" name="address" rows="2" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('address') border-red-500 @enderror"
                                placeholder="Enter your full address">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Step 3: Lifestyle & Preferences -->
                    <div id="step3" class="step-content hidden">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Lifestyle & Preferences</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Height -->
                            <div>
                                <label for="height_cm" class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                <input type="number" id="height_cm" name="height_cm" value="{{ old('height_cm') }}" required min="100" max="250"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('height_cm') border-red-500 @enderror"
                                    placeholder="e.g., 170">
                            </div>

                            <!-- Weight -->
                            <div>
                                <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                <input type="number" id="weight_kg" name="weight_kg" value="{{ old('weight_kg') }}" required min="30" max="200" step="0.1"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('weight_kg') border-red-500 @enderror"
                                    placeholder="e.g., 65">
                            </div>

                            <!-- Dietary Preferences -->
                            <div>
                                <label for="dietary_preferences" class="block text-sm font-medium text-gray-700 mb-1">Dietary Preference</label>
                                <select id="dietary_preferences" name="dietary_preferences" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('dietary_preferences') border-red-500 @enderror">
                                    <option value="">Select Preference</option>
                                    <option value="vegetarian" {{ old('dietary_preferences') == 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                                    <option value="non-vegetarian" {{ old('dietary_preferences') == 'non-vegetarian' ? 'selected' : '' }}>Non-Vegetarian</option>
                                    <option value="vegan" {{ old('dietary_preferences') == 'vegan' ? 'selected' : '' }}>Vegan</option>
                                </select>
                            </div>

                            <!-- Smoking Habits -->
                            <div>
                                <label for="smoking_habits" class="block text-sm font-medium text-gray-700 mb-1">Smoking Habits</label>
                                <select id="smoking_habits" name="smoking_habits" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('smoking_habits') border-red-500 @enderror">
                                    <option value="">Select Option</option>
                                    <option value="non-smoker" {{ old('smoking_habits') == 'non-smoker' ? 'selected' : '' }}>Non-Smoker</option>
                                    <option value="occasional" {{ old('smoking_habits') == 'occasional' ? 'selected' : '' }}>Occasional</option>
                                    <option value="regular" {{ old('smoking_habits') == 'regular' ? 'selected' : '' }}>Regular</option>
                                </select>
                            </div>

                            <!-- Drinking Habits -->
                            <div class="sm:col-span-2">
                                <label for="drinking_habits" class="block text-sm font-medium text-gray-700 mb-1">Drinking Habits</label>
                                <select id="drinking_habits" name="drinking_habits" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('drinking_habits') border-red-500 @enderror">
                                    <option value="">Select Option</option>
                                    <option value="non-drinker" {{ old('drinking_habits') == 'non-drinker' ? 'selected' : '' }}>Non-Drinker</option>
                                    <option value="occasional" {{ old('drinking_habits') == 'occasional' ? 'selected' : '' }}>Occasional</option>
                                    <option value="regular" {{ old('drinking_habits') == 'regular' ? 'selected' : '' }}>Regular</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hobbies & Interests -->
                        <div class="mt-5">
                            <label for="hobbies_interests" class="block text-sm font-medium text-gray-700 mb-1">Hobbies & Interests</label>
                            <textarea id="hobbies_interests" name="hobbies_interests" rows="3" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('hobbies_interests') border-red-500 @enderror"
                                placeholder="e.g., Reading, Traveling, Music, Sports...">{{ old('hobbies_interests') }}</textarea>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        <button type="button" id="prev-btn" 
                            class="hidden px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            ← Previous
                        </button>
                        <div></div>
                        <button type="button" id="next-btn"
                            class="px-6 py-3 bg-pink-600 text-white rounded-lg font-medium hover:bg-pink-700 transition transform hover:scale-[1.02]">
                            Next →
                        </button>
                        <button type="submit" id="submit-btn"
                            class="hidden px-6 py-3 bg-pink-600 text-white rounded-lg font-medium hover:bg-pink-700 transition transform hover:scale-[1.02]">
                            Create Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 3;

            const steps = {
                1: document.getElementById('step1'),
                2: document.getElementById('step2'),
                3: document.getElementById('step3')
            };

            const indicators = {
                1: document.getElementById('step1-indicator'),
                2: document.getElementById('step2-indicator'),
                3: document.getElementById('step3-indicator')
            };

            const lines = {
                1: document.getElementById('line1'),
                2: document.getElementById('line2')
            };

            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            function updateUI() {
                // Hide all steps
                Object.values(steps).forEach(step => step.classList.add('hidden'));
                // Show current step
                steps[currentStep].classList.remove('hidden');

                // Update indicators
                for (let i = 1; i <= totalSteps; i++) {
                    if (i < currentStep) {
                        // Completed
                        indicators[i].classList.remove('bg-gray-300', 'text-gray-600');
                        indicators[i].classList.add('bg-pink-600', 'text-white');
                        indicators[i].innerHTML = '✓';
                    } else if (i === currentStep) {
                        // Current
                        indicators[i].classList.remove('bg-gray-300', 'text-gray-600');
                        indicators[i].classList.add('bg-pink-600', 'text-white');
                        indicators[i].innerHTML = i;
                    } else {
                        // Future
                        indicators[i].classList.remove('bg-pink-600', 'text-white');
                        indicators[i].classList.add('bg-gray-300', 'text-gray-600');
                        indicators[i].innerHTML = i;
                    }
                }

                // Update lines
                for (let i = 1; i < totalSteps; i++) {
                    if (i < currentStep) {
                        lines[i].classList.remove('bg-gray-300');
                        lines[i].classList.add('bg-pink-600');
                    } else {
                        lines[i].classList.remove('bg-pink-600');
                        lines[i].classList.add('bg-gray-300');
                    }
                }

                // Update buttons
                prevBtn.classList.toggle('hidden', currentStep === 1);
                nextBtn.classList.toggle('hidden', currentStep === totalSteps);
                submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
            }

            function validateCurrentStep() {
                const currentStepEl = steps[currentStep];
                const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                return isValid;
            }

            nextBtn.addEventListener('click', function() {
                if (validateCurrentStep() && currentStep < totalSteps) {
                    currentStep++;
                    updateUI();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            // Profile picture preview
            const profileInput = document.getElementById('profile_picture');
            const previewContainer = document.getElementById('preview-container');

            profileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    };
                    reader.readAsDataURL(file);
                }
            });

            updateUI();
        });
    </script>
</x-layout>