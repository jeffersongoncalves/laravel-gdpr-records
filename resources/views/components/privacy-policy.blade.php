@props(['rightsUrl' => null])
@php
    $ropa = app(\JeffersonGoncalves\GdprRecords\RecordsManager::class)->export();
    $authority = (array) config('gdpr-records.supervisory_authority', []);
    $updatedAt = \JeffersonGoncalves\GdprRecords\Models\ProcessingActivity::query()->max('updated_at');
    $label = fn (string $group, ?string $key): ?string => $key === null ? null
        : (\Illuminate\Support\Facades\Lang::has("gdpr-records::policy.{$group}.{$key}") ? __("gdpr-records::policy.{$group}.{$key}") : $key);
    $consentBased = collect($ropa['activities'])->contains('legal_basis', 'consent');
@endphp
<article {{ $attributes->merge(['class' => 'gdpr-privacy-policy']) }}>
    <h2>{{ __('gdpr-records::policy.title') }}</h2>
    @if ($updatedAt)
        <p>{{ __('gdpr-records::policy.updated_at', ['date' => \Illuminate\Support\Carbon::parse($updatedAt)->translatedFormat('d/m/Y')]) }}</p>
    @endif

    <section>
        <h3>{{ __('gdpr-records::policy.controller') }}</h3>
        @if ($ropa['controller']['name'] ?? null)
            <p>{{ __('gdpr-records::policy.controller_text', ['name' => $ropa['controller']['name']]) }}</p>
        @endif
        <ul>
            @if ($ropa['controller']['address'] ?? null)<li>{{ __('gdpr-records::policy.address') }}: {{ $ropa['controller']['address'] }}</li>@endif
            @if ($ropa['controller']['contact'] ?? null)<li>{{ __('gdpr-records::policy.contact') }}: {{ $ropa['controller']['contact'] }}</li>@endif
            @if ($ropa['representative']['name'] ?? null)
                <li>{{ __('gdpr-records::policy.representative') }}: {{ $ropa['representative']['name'] }}@if ($ropa['representative']['contact'] ?? null) &middot; {{ $ropa['representative']['contact'] }}@endif</li>
            @endif
        </ul>
        <x-gdpr-records::dpo :label="__('gdpr-records::policy.dpo')" />
    </section>

    <section>
        <h3>{{ __('gdpr-records::policy.processing') }}</h3>
        @forelse ($ropa['activities'] as $activity)
            <section>
                <h4>{{ $activity['name'] }}</h4>
                <dl>
                    <dt>{{ __('gdpr-records::policy.purpose') }}</dt>
                    <dd>{{ $activity['purpose'] }}</dd>
                    @if ($activity['legal_basis'])
                        <dt>{{ __('gdpr-records::policy.legal_basis') }}</dt>
                        <dd>{{ $label('legal_bases', $activity['legal_basis']) }}</dd>
                    @endif
                    @if ($activity['data_categories'] !== [] || $activity['personal_data'] !== [])
                        <dt>{{ __('gdpr-records::policy.data') }}</dt>
                        <dd>{{ collect($activity['data_categories'])
                            ->merge(collect($activity['personal_data'])->map(fn (array $row): string => str_replace('_', ' ', $row['column'])
                                .(in_array($row['category'], ['special', 'criminal'], true) ? ' ('.$label('categories', $row['category']).')' : '')))
                            ->unique()->implode(', ') }}</dd>
                    @endif
                    @if ($activity['data_subjects'] !== [])
                        <dt>{{ __('gdpr-records::policy.data_subjects') }}</dt>
                        <dd>{{ implode(', ', $activity['data_subjects']) }}</dd>
                    @endif
                    @if ($activity['recipients'] !== [])
                        <dt>{{ __('gdpr-records::policy.recipients') }}</dt>
                        <dd>{{ collect($activity['recipients'])->map(fn (array $r): string => $r['name'].(isset($r['role']) ? ' ('.$label('roles', $r['role']).')' : ''))->implode(', ') }}</dd>
                    @endif
                    @if ($activity['international_transfers'] !== [])
                        <dt>{{ __('gdpr-records::policy.transfers') }}</dt>
                        <dd>{{ collect($activity['international_transfers'])->map(fn (array $t): string => $t['country'].(isset($t['mechanism']) ? ' ('.$label('mechanisms', $t['mechanism']).')' : ''))->implode(', ') }}</dd>
                    @endif
                    @if ($activity['retention'])
                        <dt>{{ __('gdpr-records::policy.retention') }}</dt>
                        <dd>{{ $activity['retention'] }}</dd>
                    @endif
                </dl>
            </section>
        @empty
            <p>{{ __('gdpr-records::policy.none') }}</p>
        @endforelse
    </section>

    <section>
        <h3>{{ __('gdpr-records::policy.rights') }}</h3>
        <p>{{ __('gdpr-records::policy.rights_intro') }}</p>
        <ul>
            @foreach ((array) __('gdpr-records::policy.rights_list') as $right)
                <li>{{ $right }}</li>
            @endforeach
        </ul>
        @if ($consentBased)
            <p>{{ __('gdpr-records::policy.withdraw') }}</p>
        @endif
        @if ($rightsUrl)
            <p><a href="{{ $rightsUrl }}">{{ __('gdpr-records::policy.rights_request') }}</a></p>
        @else
            <p>{{ __('gdpr-records::policy.rights_contact') }}</p>
        @endif
        <p>
            {{ __('gdpr-records::policy.complaint') }}
            @if ($authority['name'] ?? null)
                @if ($authority['url'] ?? null)<a href="{{ $authority['url'] }}">{{ $authority['name'] }}</a>@else{{ $authority['name'] }}@endif.
            @endif
        </p>
    </section>
</article>
