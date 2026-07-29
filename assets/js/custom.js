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

// ----- SLIDER
function getAutoplay() {
  return EmblaCarouselAutoplay({ delay: 4000, stopOnInteraction: false });
}

function initSliders() {
  // Home carousel (con fade)
  const homeCarousel = content.querySelector('.embla-slider-home');
  if (homeCarousel && !homeCarousel._embla) {
    homeCarousel._embla = EmblaCarousel(homeCarousel, {}, [getAutoplay(), EmblaCarouselFade()]);
  }

  // Slider singolo (pagine normali)
  const singleCarousel = content.querySelector('.embla-slider');
  if (singleCarousel && !singleCarousel._embla) {
    singleCarousel._embla = EmblaCarousel(singleCarousel, {}, [getAutoplay()]);
  }

  // Accordion mostre: più slider, uno alla volta
  initMostreAccordion();
}

console.log('exhibitions:', document.querySelectorAll('.exhibition-element'));

// 3. Per ognuno, cosa c'è dentro?
document.querySelectorAll('.exhibition-element').forEach((row, i) => {
  console.log(`--- riga ${i} ---`);
  console.log('trigger (.entry-title):', row.querySelector('.entry-title'));
  console.log('panel (.exhibition-carousel-row):', row.querySelector('.exhibition-carousel-row'));
  console.log('slider (.embla-slider):', row.querySelector('.embla-slider'));
});

function initMostreAccordion() {
  const exhibitions = content.querySelectorAll('.exhibition-element');
  if (!exhibitions.length) return;

  exhibitions.forEach((row, index) => {
    const trigger = row.querySelector('.entry-title'); 
    const carouselRow = row.querySelector('.exhibition-carousel-row');
    const sliderEl = carouselRow?.querySelector('.embla-slider');

       console.log(trigger, carouselRow, sliderEl);

    if (!trigger || !carouselRow || !sliderEl) return;

    // Apri il primo, chiudi gli altri
    if (index === 0) {
      carouselRow.classList.add('open');
      if (!sliderEl._embla) {
        sliderEl._embla = EmblaCarousel(sliderEl, {}, [getAutoplay()]);
      }
    } else {
      carouselRow.classList.remove('open');
    }

    trigger.addEventListener('click', () => {
      const isAlreadyOpen = carouselRow.classList.contains('open');

      // Chiudi tutti e distruggi i loro embla
      exhibitions.forEach(r => {
        const p = r.querySelector('.exhibition-carousel-row');
        const s = p?.querySelector('.embla-slider');
        if (p) p.classList.remove('open');
        if (s?._embla) {
          s._embla.destroy();
          s._embla = null;
        }
      });

      // Se non era già aperta, apri questa e inizializza embla
      if (!isAlreadyOpen) {
        carouselRow.classList.add('open');
        sliderEl._embla = EmblaCarousel(sliderEl, {}, [getAutoplay()]);
      }
    });
  });
}

// ----- DYNAMIC PAGE LOAD
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
  
  // re-init sliders
  initSliders();
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



//----- loading content
document.addEventListener('DOMContentLoaded', () => {
  EmblaCarousel.globalOptions = { loop: true, align: 'start' };

  content.classList.add('loaded');
  initSliders();
})