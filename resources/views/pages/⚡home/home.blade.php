<div>
    {{-- ========================================================= HERO ========================================================== --}}
    <section class="relative overflow-hidden">
        {{-- Decorative background --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-0" >
            <div class="absolute -right-32 -top-32 size-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-20 size-96 rounded-full bg-cyan-500/10 blur-3xl"></div>
        </div>
        <div class="container relative mx-auto px-4">
            <div class="grid min-h-[620px] items-center gap-12 py-16 lg:grid-cols-2 lg:py-24">
                {{-- Hero content --}}
                <div class="max-w-2xl">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-2 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('آموزش مهارت‌های کاربردی در بوشهر') }}
                        </span>
                    </div>
                    <flux:heading level="1" class="max-w-xl text-4xl font-bold leading-[1.5] tracking-tight sm:text-5xl lg:text-6xl" >
                        {{ __('مهارت یاد بگیر، آینده‌ات را بساز.') }}
                    </flux:heading>
                    <flux:text class="mt-6 max-w-xl text-base leading-8 text-zinc-600 dark:text-zinc-400 sm:text-lg" >
                        {{ __('آموزشگاه آی‌تک در بوشهر؛ ارائه‌دهنده دوره‌های مهارتی در حوزه‌های کامپیوتر، حسابداری، برنامه‌نویسی، معماری و سایر مهارت‌های کاربردی.') }}
                    </flux:text>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <flux:button href="#courses" variant="primary" icon="arrow-left" > {{ __('مشاهده دوره‌ها') }} </flux:button>
                        <flux:button href="#about" variant="ghost" > {{ __('آشنایی با آی‌تک') }} </flux:button>
                    </div>
                    <div class="mt-10 flex flex-wrap gap-x-8 gap-y-3 text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="flex items-center gap-2">
                            <flux:icon name="check-circle" variant="micro" /> {{ __('آموزش کاربردی') }} </div>
                        <div class="flex items-center gap-2"> <flux:icon name="check-circle" variant="micro" />
                            {{ __('محیط آموزشی مناسب') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:icon name="check-circle" variant="micro" /> {{ __('دوره‌های متنوع') }} </div>
                    </div>
                </div>
                {{-- Hero visual --}}
                <div class="relative hidden min-h-[500px] lg:block">

                    {{-- Background panel --}}
                    <div
                        class="absolute inset-8 rounded-[3rem] bg-zinc-100/80 dark:bg-zinc-900"
                    ></div>

                    <div class="absolute inset-0 flex items-center justify-center">

                        <div class="relative size-[400px]">

                            {{-- Main circles --}}
                            <div class="absolute inset-0 rounded-full border border-zinc-200 dark:border-zinc-700"></div>

                            <div
                                class="absolute inset-8 rounded-full border border-dashed border-zinc-300 dark:border-zinc-600"
                            ></div>

                            <div
                                class="absolute inset-16 rounded-full border border-zinc-200/70 dark:border-zinc-700/70"
                            ></div>


                            {{-- Center --}}
                            <div
                                class="absolute inset-[110px] flex items-center justify-center rounded-[2rem] bg-white shadow-xl dark:bg-zinc-800"
                            >
                                <x-my.logo class="size-24 text-zinc-800 dark:text-zinc-100" />
                            </div>


                            {{-- =================================================
                                 Computer
                            ================================================== --}}
                            <div
                                class="absolute -right-16 top-8 rounded-2xl border border-zinc-200 bg-white p-4 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-700"
                                    >
                                        <flux:icon
                                            name="computer-desktop"
                                            variant="micro"
                                        />
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold">
                                            {{ __('کامپیوتر') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('مهارت کاربردی') }}
                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =================================================
                                 Photography
                            ================================================== --}}
                            <div
                                class="absolute -left-20 top-24 rounded-2xl border border-zinc-200 bg-white p-4 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-700"
                                    >
                                        <flux:icon
                                            name="camera"
                                            variant="micro"
                                        />
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold">
                                            {{ __('عکاسی') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('هنر و مهارت') }}
                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =================================================
                                 Programming
                            ================================================== --}}
                            <div
                                class="absolute -bottom-4 -right-14 rounded-2xl border border-zinc-200 bg-white p-4 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-700"
                                    >
                                        <flux:icon
                                            name="code-bracket"
                                            variant="micro"
                                        />
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold">
                                            {{ __('برنامه‌نویسی') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('مسیر یادگیری') }}
                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =================================================
                                Architecture
                            ================================================== --}}
                            <div
                                class="absolute -bottom-10 -left-14 rounded-2xl border border-zinc-200 bg-white p-4 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-700"
                                    >
                                        <flux:icon
                                            name="building-office-2"
                                            variant="micro"
                                        />
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold">
                                            {{ __('معماری') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ __('طراحی و نرم‌افزار') }}
                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- =================================================
                                 Light Bulb
                            ================================================== --}}
                            <div
                                class="absolute -left-2 top-4 flex size-14 items-center justify-center rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <flux:icon name="light-bulb" />
                            </div>


                            {{-- =================================================
                                 Education
                            ================================================== --}}
                            <div
                                class="absolute -right-2 bottom-28 flex size-14 items-center justify-center rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                            >
                                <flux:icon name="academic-cap" />
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    {{-- ========================================================= CATEGORIES ========================================================== --}}
    <section id="categories" class="border-y border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/50" >
        <div class="container mx-auto px-4 py-16 sm:py-20">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <flux:text class="text-sm font-medium"> {{ __('حوزه‌های آموزشی') }} </flux:text>
                    <flux:heading level="2" size="xl" class="mt-2" > {{ __('مسیر یادگیری خودت را پیدا کن') }} </flux:heading>
                </div>
                <flux:text class="max-w-md leading-7 text-zinc-600 dark:text-zinc-400 sm:text-left">
                    {{ __('از میان حوزه‌های مختلف آموزشی، مهارتی را انتخاب کن که با هدف تو هماهنگ است.') }}
                </flux:text>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6">
                @foreach ($categories as $category)
                    <a href="#courses" class="group" >
                        <div class="h-full rounded-2xl border border-zinc-200 bg-white p-5 transition duration-200 hover:-translate-y-1 hover:border-zinc-300 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-950 dark:hover:border-zinc-700">
                            <div class="flex size-11 items-center justify-center rounded-xl bg-zinc-100 transition group-hover:bg-zinc-900 group-hover:text-white dark:bg-zinc-800 dark:group-hover:bg-white dark:group-hover:text-zinc-900">
                                <flux:icon :name="$category['icon']" variant="micro" />
                            </div> <flux:heading size="sm" class="mt-5" > {{ $category['title'] }} </flux:heading>
                            <flux:text class="mt-2 text-xs leading-6 text-zinc-500 dark:text-zinc-400"> {{ $category['description'] }} </flux:text>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    {{-- ========================================================= WHY I-TECH ========================================================== --}}
    <section id="about">
        <div class="container mx-auto px-4 py-20 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                <div>
                    <flux:text class="text-sm font-medium">{{ __('نگاه ما به آموزش') }}</flux:text>
                    <flux:heading level="2" size="xl" class="mt-3 max-w-lg leading-relaxed" > {{ __('یادگیری وقتی ارزشمند است که به مهارت تبدیل شود.') }} </flux:heading>
                    <flux:text class="mt-5 max-w-lg leading-8 text-zinc-600 dark:text-zinc-400">
                        {{ __('در آی‌تک تلاش می‌کنیم آموزش فقط انتقال اطلاعات نباشد؛ بلکه هنرجو بتواند آموخته‌های خود را در مسیر تحصیل، کار و زندگی حرفه‌ای به کار بگیرد.') }}
                    </flux:text>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($advantages as $advantage)
                        <div class="rounded-2xl border border-zinc-200 p-6 dark:border-zinc-800">
                            <div class="flex size-10 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800">
                                <flux:icon name="check" variant="micro" /> </div>
                            <flux:heading size="sm" class="mt-5" > {{ $advantage['title'] }} </flux:heading>
                            <flux:text class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400"> {{ $advantage['description'] }} </flux:text>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    {{-- ========================================================= COURSES ========================================================== --}}
    <section id="courses" class="bg-zinc-950 text-white dark:bg-white dark:text-zinc-950" >
        <div class="container mx-auto px-4 py-20 sm:py-24">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <flux:text class="text-sm text-zinc-400 dark:text-zinc-500"> {{ __('دوره‌های آموزشی') }} </flux:text>
                    <flux:heading level="2" size="xl" class="mt-2 text-white dark:text-zinc-950" > {{ __('از اینجا شروع کن') }} </flux:heading>
                </div>
                <flux:text class="max-w-md leading-7 text-zinc-400 dark:text-zinc-500"> {{ __('دوره‌ای را انتخاب کن و مسیر یادگیری مهارت موردنظر خود را آغاز کن.') }} </flux:text>
            </div>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ($courses as $course)
                    <article class="group rounded-3xl border border-white/10 bg-white/[0.06] p-7 transition duration-200 hover:-translate-y-1 hover:bg-white/[0.1] dark:border-zinc-200 dark:bg-zinc-100 dark:hover:bg-zinc-200">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex size-12 items-center justify-center rounded-2xl bg-white/10 dark:bg-zinc-950/10">
                                <flux:icon name="academic-cap" variant="micro" /> </div>
                            <span class="text-xs text-zinc-500"> {{ __('دوره آموزشی') }} </span>
                        </div>
                        <flux:heading size="lg" class="mt-8 text-white dark:text-zinc-950" > {{ $course['title'] }} </flux:heading>
                        <flux:text class="mt-3 leading-7 text-zinc-400 dark:text-zinc-600"> {{ $course['description'] }} </flux:text>
                        <div class="mt-8">
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-medium text-white transition group-hover:gap-3 dark:text-zinc-950" >
                                {{ $course['label'] }} <flux:icon name="arrow-left" variant="micro" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========================================================= FAQ ========================================================== --}}

    <section id="faq">
        <div class="container mx-auto px-4 py-20 sm:py-24">
            <div class="mx-auto max-w-3xl">
                <div class="text-center">
                    <flux:text class="text-sm font-medium"> {{ __('پاسخ به سوالات شما') }} </flux:text>
                    <flux:heading level="2" size="xl" class="mt-2" > {{ __('سوالات متداول') }} </flux:heading>
                </div>
                <div class="mt-10">
                    <flux:accordion>
                        @foreach ($faqs as $faq)
                            <flux:accordion.item>
                                <flux:accordion.heading> {{ $faq['question'] }} </flux:accordion.heading>
                                <flux:accordion.content> {{ $faq['answer'] }} </flux:accordion.content>
                            </flux:accordion.item>
                        @endforeach
                    </flux:accordion>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================= FINAL CTA ========================================================== --}}

    <section class="px-4 pb-20 sm:pb-24">
        <div class="container mx-auto">
            <div class="relative overflow-hidden rounded-[2rem] bg-zinc-100 px-6 py-16 text-center dark:bg-zinc-900 sm:px-12">
                <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-20 size-60 rounded-full bg-indigo-500/10 blur-3xl" ></div>
                <div aria-hidden="true" class="pointer-events-none absolute -bottom-20 -left-20 size-60 rounded-full bg-cyan-500/10 blur-3xl" ></div>
                <div class="relative">
                    <flux:heading level="2" size="xl" > {{ __('آماده شروع یادگیری هستید؟') }} </flux:heading>
                    <flux:text class="mx-auto mt-4 max-w-xl leading-8 text-zinc-600 dark:text-zinc-400">
                        {{ __('حوزه آموزشی موردنظر خود را انتخاب کنید و اولین قدم را برای یادگیری یک مهارت جدید بردارید.') }}
                    </flux:text>
                    <div class="mt-7">
                        <flux:button href="#courses" variant="primary" icon="arrow-left" > {{ __('مشاهده دوره‌ها') }} </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
