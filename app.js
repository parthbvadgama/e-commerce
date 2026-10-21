const search = document.getElementById('search');
const cards = [...document.querySelectorAll('.product-card')];
const filters = [...document.querySelectorAll('.filter')];
let activeCategory = 'All Products';
function applyFilters() {
  const term = (search?.value || '').trim().toLowerCase();
  cards.forEach(card => {
    const matchesText = card.dataset.name.includes(term);
    const matchesCategory = activeCategory === 'All Products' || card.dataset.category === activeCategory;
    card.style.display = matchesText && matchesCategory ? '' : 'none';
  });
}
search?.addEventListener('input', applyFilters);
filters.forEach(btn => btn.addEventListener('click', () => {
  filters.forEach(b => b.classList.remove('active'));
  btn.classList.add('active'); activeCategory = btn.dataset.category; applyFilters();
}));
