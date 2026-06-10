@php
    $compact = (bool) ($compact ?? false);
    $memberIndex = (int) ($memberIndex ?? 0);
    $bioPlain = trim(strip_tags(html_entity_decode($member->bio ?? '')));
    $bioExcerpt = (! $compact && $bioPlain !== '') ? \Illuminate\Support\Str::limit($bioPlain, 160, '…') : '';
    $hasContact = ! $compact && (
        ! empty($member->phone) || ! empty($member->email) || ! empty($member->facebook)
        || ! empty($member->instagram) || ! empty($member->linkedin)
    );
@endphp

<article class="team-page-card {{ $compact ? 'team-page-card--compact' : 'team-page-card--full' }} h-100 wow tpfadeUp" data-wow-duration=".85s" data-wow-delay="{{ number_format(($memberIndex % 3) * 0.08, 2) }}s">
    <div class="team-page-card__accent" aria-hidden="true"></div>
    <div class="team-page-card__inner">
        <div class="team-page-card__media">
            @if(!empty($member->image))
                <img
                    src="{{ asset('storage/images/staff/' . $member->image) }}"
                    alt="{{ $member->names }}"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div class="team-page-card__placeholder" aria-hidden="true">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        <div class="team-page-card__body {{ $compact ? 'text-center' : '' }}">
            <h3 class="team-page-card__name">{{ $member->names }}</h3>
            <p class="team-page-card__role">{{ $member->position }}</p>

            @if(! $compact)
                @if($bioExcerpt !== '')
                    <p class="team-page-card__bio mb-0">{{ $bioExcerpt }}</p>
                @endif
                @if($bioPlain !== '' && strlen($bioPlain) > 160)
                    <details class="team-page-card__details mt-2">
                        <summary>Read full bio</summary>
                        <div class="team-page-card__bio-full postbox__text mt-2 mb-0">{!! $member->bio !!}</div>
                    </details>
                @elseif($bioPlain !== '' && strlen($bioPlain) <= 160)
                    <div class="team-page-card__bio-full postbox__text mt-2 mb-0">{!! $member->bio !!}</div>
                @endif

                @if($hasContact)
                    <ul class="team-page-card__contact list-unstyled mb-0 mt-3">
                        @if(!empty($member->phone))
                            <li><a href="tel:{{ preg_replace('/\s+/', '', $member->phone) }}"><i class="fas fa-phone" aria-hidden="true"></i> {{ $member->phone }}</a></li>
                        @endif
                        @if(!empty($member->email))
                            <li><a href="mailto:{{ $member->email }}"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $member->email }}</a></li>
                        @endif
                        @if(!empty($member->linkedin))
                            <li><a href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in" aria-hidden="true"></i> LinkedIn</a></li>
                        @endif
                        @if(!empty($member->facebook))
                            <li><a href="{{ $member->facebook }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook</a></li>
                        @endif
                        @if(!empty($member->instagram))
                            <li><a href="{{ $member->instagram }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram" aria-hidden="true"></i> Instagram</a></li>
                        @endif
                    </ul>
                @endif
            @endif
        </div>
    </div>
</article>
