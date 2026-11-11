<flux:footer class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <div class="mx-auto w-full max-w-7xl px-6 py-1 lg:px-8">

        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">

            {{-- معرفی آموزشگاه --}}
            <div class="flex flex-col items-center text-center lg:items-start lg:text-right">

                <a href="{{ route('home') }}" wire:navigate class="mb-4">
                    <x-my.logo class="h-12 text-zinc-700 dark:text-zinc-300"/>
                </a>

                <flux:heading size="lg">
                    {{ __('آموزشگاه آی تک') }}
                </flux:heading>

                <flux:text class="mt-2 max-w-sm leading-7 text-zinc-600 dark:text-zinc-400">
                    {{ __('ارائه دوره‌های آموزشی کامپیوتر، حسابداری، معماری، عکاسی و مهارت‌های کاربردی در بوشهر') }}
                </flux:text>

            </div>


            {{-- اطلاعات تماس --}}
            <div class="text-center lg:text-right">

                <flux:heading size="sm" class="mb-4">
                    {{ __('اطلاعات تماس') }}
                </flux:heading>

                <div class="space-y-3">

                    <flux:text>
                        <span class="font-medium">
                            {{ __('مشاوره:') }}
                        </span>
                        <span dir="ltr">
                            {{ __('+98 935 056 8163') }}
                        </span>

                    </flux:text>

                    <flux:text>
                        <span class="font-medium">
                            {{ __('تلفن تماس:') }}
                        </span>
                        <span dir="ltr">
                            {{ __('+98 77 33 10 33 50') }}
                        </span>
                    </flux:text>

                    <flux:text>
                        <span class="font-medium">
                            {{ __('تماس:') }}
                        </span>
                        <span dir="ltr">
                            {{ __('+98 903 433 6111') }}
                        </span>
                    </flux:text>

                    <flux:text class="leading-7">
                        <span class="font-medium">
                            {{ __('آدرس:') }}
                        </span>
                        {{ __('بوشهر، خیابان سنگی، اول گلخونه، سیراف 5') }}
                    </flux:text>

                </div>

            </div>


            {{-- درباره / اطلاعات --}}
            <div class="text-center lg:text-right">

                <flux:heading size="sm" class="mb-4">
                    {{ __('اطلاعات آموزشگاه') }}
                </flux:heading>

                <div class="space-y-3">

                    <flux:text>
                        {{ __('موسس: بخشی زاده') }}
                    </flux:text>

                    <flux:text>
                        {{ __('فعالیت آموزشگاه از سال 1388') }}
                    </flux:text>

                    <flux:text>
                        {{ __('برنامه‌نویسی و اجرا: بیشه سری') }}
                    </flux:text>

                </div>

            </div>

        </div>


        {{-- جداکننده --}}
        <flux:separator class="my-8"/>


        {{-- پایین فوتر --}}
        <div class="flex flex-col items-center gap-4 text-center">

            <flux:text class="text-sm leading-7">
                <span class="font-semibold">&copy;</span>
                {{ __('تمامی حقوق برای آموزشگاه آی تک محفوظ است.') }}
                {{ __('از 1388 تا') }}
                {{ jdate('Y', time(), '', '', 'en') }}
            </flux:text>

            <flux:text class="text-xs text-zinc-500 dark:text-zinc-500">
                {{ __('S.V: 13.1.1') }}
                <span class="mx-1">-</span>
                {{ __('L.V:') }}
                {{ Illuminate\Foundation\Application::VERSION }}
                <span class="mx-1">-</span>
                {{ __('PHP.V:') }}
                {{ PHP_VERSION }}
            </flux:text>

        </div>

    </div>
</flux:footer>
