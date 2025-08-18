{{-- Instagram-inlägg: berättande ton, radbrytningar, hashtags i slutet. --}}
Rubrik/ämne: "{{ $title }}"
Målgrupp: {{ $audience ?? '–' }}
Affärsmål: {{ $goal ?? '–' }}
Nyckelord: {{ implode(', ', (array)($keywords ?? [])) ?: '–' }}
Varumärkesröst: {{ data_get($brand ?? [], 'voice', '–') }}

Mall/hint:
- Kort storytelling kopplad till rubriken.
- Radbrytningar för läsbarhet.
- Avsluta med 5–10 relevanta hashtags.
- Enkel CTA (spara/dela/DM) som stödjer målet.<?php
