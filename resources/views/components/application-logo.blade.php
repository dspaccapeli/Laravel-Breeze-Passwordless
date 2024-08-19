<!-- You're allowing any additional classes or attributes to be added to the component when it's used, while still maintaining a set of default classes. -->
<div {{ $attributes->merge(['class' => 'text-3xl font-bold flex items-center justify-center h-full']) }}>
    Logo
</div>