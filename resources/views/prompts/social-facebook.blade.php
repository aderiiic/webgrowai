{{-- Facebook-inlägg: kort, lätt och engagerande. --}}
Rubrik/ämne: "{{ $title }}"
Målgrupp: {{ $audience ?? '–' }}
Affärsmål: {{ $goal ?? '–' }}
Nyckelord: {{ implode(', ', (array)($keywords ?? [])) ?: '–' }}
Varumärkesröst: {{ data_get($brand ?? [], 'voice', '–') }}

Mall/hint:
- Inled med en tydlig hook relaterad till rubriken.
- 1–2 korta stycken. Max 1–2 emojis. 0–3 relevanta hashtags i slutet.
- Tydlig, enkel CTA som passar målet.
