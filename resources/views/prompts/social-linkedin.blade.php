{{-- LinkedIn-inlägg: professionellt, sakligt, värdedrivet. --}}
Rubrik/ämne: "{{ $title }}"
Målgrupp: {{ $audience ?? '–' }}
Affärsmål: {{ $goal ?? '–' }}
Nyckelord: {{ implode(', ', (array)($keywords ?? [])) ?: '–' }}
Varumärkesröst: {{ data_get($brand ?? [], 'voice', '–') }}

Mall/hint:
- Inled med ett insiktsfullt påstående eller datapunkt.
- 2–4 korta stycken, tydlig struktur.
- Konkreta takeaways relaterade till affärsmålet.
- 1–3 relevanta hashtags i slutet.
