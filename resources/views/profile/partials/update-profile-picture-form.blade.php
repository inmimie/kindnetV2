<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Upload a custom profile picture. Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max 2MB).") }}
        </p>
    </header>

    <div x-data="{ imageUrl: null, fileError: null }" class="mt-6 space-y-6">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <!-- Current / Preview Avatar -->
            <div class="relative group">
                <template x-if="imageUrl">
                    <img :src="imageUrl" class="h-24 w-24 rounded-full object-cover border-2 border-blue-500 shadow-md transition-all duration-300">
                </template>
                <template x-if="!imageUrl">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" class="h-24 w-24 rounded-full object-cover border border-gray-300 dark:border-gray-600 shadow-sm">
                    @else
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-3xl shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </template>
            </div>

            <!-- Upload and Remove Actions -->
            <div class="flex-1 w-full space-y-4">
                <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div class="flex flex-col space-y-2">
                        <label class="block">
                            <span class="sr-only">Choose profile photo</span>
                            <input type="file" name="profile_picture" accept="image/*"
                                   @change="
                                       const file = $event.target.files[0];
                                       if (file) {
                                           if (file.size > 2 * 1024 * 1024) {
                                               fileError = '{{ __('The image must not be larger than 2MB.') }}';
                                               imageUrl = null;
                                               $event.target.value = '';
                                           } else {
                                               fileError = null;
                                               imageUrl = URL.createObjectURL(file);
                                           }
                                       }
                                   "
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100
                                          dark:file:bg-gray-700 dark:file:text-gray-300
                                          dark:hover:file:bg-gray-600
                                          cursor-pointer" />
                        </label>
                        
                        <p x-show="fileError" x-text="fileError" class="text-sm text-red-600 dark:text-red-400 mt-1" style="display: none;"></p>
                        <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button x-show="imageUrl" style="display: none;">{{ __('Save Picture') }}</x-primary-button>
                        
                        @if (session('status') === 'profile-picture-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>

                @if ($user->profile_picture)
                    <form method="post" action="{{ route('profile.avatar.destroy') }}" class="flex items-center">
                        @csrf
                        @method('delete')
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">
                            {{ __('Remove current picture') }}
                        </button>
                        @if (session('status') === 'profile-picture-deleted')
                            <span
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600 dark:text-gray-400 ml-2"
                            >{{ __('Removed.') }}</span>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
