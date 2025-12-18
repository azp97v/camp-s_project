<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-red-700 mb-1">
            {{ __('🗑️ حذف الحساب') }}
        </h2>

        <p class="text-slate-600 text-sm">
            {{ __('بمجرد حذف حسابك، سيتم حذف جميع بياناتك وموارده بشكل دائم. يرجى تنزيل أي بيانات تريد الاحتفاظ بها قبل الحذف.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all shadow-md hover:shadow-lg"
    >{{ __('حذف الحساب') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ __('⚠️ هل أنت متأكد من رغبتك في حذف حسابك؟') }}
                </h2>

                <p class="mt-2 text-slate-600">
                    {{ __('بمجرد حذف حسابك، سيتم حذف جميع البيانات بشكل دائم. يرجى إدخال كلمة المرور لتأكيد الحذف.') }}
                </p>
            </div>

            <div>
                <x-input-label for="password" :value="__('كلمة المرور')" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    placeholder="{{ __('أدخل كلمة المرور') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 btn-ghost rounded-lg font-semibold">
                    {{ __('إلغاء') }}
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all">
                    {{ __('نعم، احذف الحساب') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
