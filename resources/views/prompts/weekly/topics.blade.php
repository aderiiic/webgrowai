Lista 10 aktuella ämnen att skriva om nästa vecka för {{ $brand['name'] ?? 'varumärket' }}.
Målgrupp: {{ $audience ?? 'SMB-kunder' }}
Primära nyckelord: {{ !empty($keywords) ? implode(', ', $keywords) : '—' }}

Returnera som punktlista i Markdown där varje punkt innehåller:
- Ämnesrubrik
- En mening om vinkeln
- Förslag på format (Blogg/Social/E-post)
