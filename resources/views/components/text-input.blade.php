{{--
	Component: text-input
	--------------------------------------------------------
	Arabic: حقل مدخل نصي موحّد مع حالات التركيز والتعطيل.
	English: Standard text input component used across forms.
--}}
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded-lg border border-slate-300 bg-white/70 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-all duration-200']) }}>
