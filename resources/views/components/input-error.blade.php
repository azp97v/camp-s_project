@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-2 bg-red-50 p-3 rounded-lg border-l-4 border-red-600']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center">
                <span class="inline-block w-1.5 h-1.5 bg-red-500 rounded-full me-2"></span>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
