// ----- BARS BEHAVIOUR

const pos = ['pos-1', 'pos-2', 'pos-3', 'pos-4'];
const w = ['w-1', 'w-2', 'w-3', 'w-4'];
const bars = document.querySelectorAll('.overlay-bar');
const content = document.querySelector('main');

let currentClasses = [
  { pos: null, w: null },
  { pos: null, w: null }
];

function pickDifferent(arr, exclude) {
  const available = arr.filter(c => c !== exclude);
  return available[Math.floor(Math.random() * available.length)];
}

function applyPositions() {
  // posizioni: diverse tra le due barre e diverse dalle precedenti
  const newPos0 = pickDifferent(pos, currentClasses[0].pos);
  const newPos1 = pickDifferent(pos.filter(p => p !== newPos0), currentClasses[1].pos);

  // larghezze: diverse dalle precedenti, ma possono coincidere tra le due barre
  const newW0 = pickDifferent(w, currentClasses[0].w);
  const newW1 = pickDifferent(w, currentClasses[1].w);

  const next = [
    { pos: newPos0, w: newW0 },
    { pos: newPos1, w: newW1 }
  ];

  bars.forEach((bar, i) => {
    // rimuovi classi precedenti
    bar.classList.remove(...pos, ...w);
    bar.classList.add(next[i].pos, next[i].w);
  });

  currentClasses = next;
}

// posizioni iniziali immediate
applyPositions();

async function navigateTo(url) {
  applyPositions();
  // content.classList.remove('fadein');

  const response = await fetch(url);
  const html = await response.text();
  const parser = new DOMParser();
  const newDoc = parser.parseFromString(html, 'text/html');

  // await new Promise(resolve => setTimeout(resolve, 500));
  // content.classList.add('fadein');

  document.title = newDoc.title;
  content.innerHTML = newDoc.querySelector('main').innerHTML;
  history.pushState({}, '', url);
  
}

// intercetta i link del menu
document.querySelector('.menu-menu-1-container ul').addEventListener('click', e => {
  const li = e.target.closest('li');
  const link = e.target.closest('a');
  if (!link) return;
  e.preventDefault();
  navigateTo(link.href);


  document.querySelectorAll('.menu-menu-1-container ul li').forEach(el => {
    el.classList.remove('current_page_item');
  });
  li.classList.add('current_page_item');
});

// ----- SLIDER
document.addEventListener('DOMContentLoaded', () => {
  // global variables
  EmblaCarousel.globalOptions = { loop: true, align: 'start' }
  const getAutoplay = () => EmblaCarouselAutoplay({ delay: 4000, stopOnInteraction: false })


  const homeCarousel = document.querySelector('.embla-slider-home')
  const carousel = document.querySelector('.embla-slider')

  if (homeCarousel) {
    EmblaCarousel(homeCarousel, {}, [getAutoplay(), EmblaCarouselFade()])
  }

  if (carousel) {
    EmblaCarousel(carousel, {}, [getAutoplay()])
  }
})

//----- loading content
document.addEventListener('DOMContentLoaded', () => {
  content.classList.add('loaded');
})