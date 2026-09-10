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
  const newPos0 = pickDifferent(pos, currentClasses[0].pos);
  const newPos1 = pickDifferent(pos.filter(p => p !== newPos0), currentClasses[1].pos);
  const newW0 = pickDifferent(w, currentClasses[0].w);
  const newW1 = pickDifferent(w, currentClasses[1].w);

  const next = [
    { pos: newPos0, w: newW0 },
    { pos: newPos1, w: newW1 }
  ];

  bars.forEach((bar, i) => {
    bar.classList.remove(...pos, ...w);
    bar.classList.add(next[i].pos, next[i].w);
  });

  currentClasses = next;
}


// ----- SLIDER

function getAutoplay() {
  return EmblaCarouselAutoplay({ delay: 3000, stopOnInteraction: false });
}

const mqBreakpoint = window.matchMedia('(max-width: 640px)');
const isMobile = () => mqBreakpoint.matches;

// Helper: inizializza Embla salvando sia _embla che _autoplay
function initEmbla(el, options = {}, withAutoplay = false, extraPlugins = []) {
  if (el._embla) return;
  if (withAutoplay) {
    const autoplay = getAutoplay();
    el._embla = EmblaCarousel(el, options, [autoplay, ...extraPlugins]);
    el._autoplay = autoplay;
  } else {
    el._embla = EmblaCarousel(el, options, extraPlugins);
    el._autoplay = null;
  }
}

function initSliders() {
  // Home carousel (con fade)
  const homeCarousel = content.querySelector('.embla-slider-home');
  if (homeCarousel && !homeCarousel._embla) {
    initEmbla(homeCarousel, {}, true, [EmblaCarouselFade()]);
  }

  // Slider singolo — salta se siamo in una pagina con accordion
  const isMostrePage = content.querySelector('.exhibition-element');
  if (!isMostrePage) {
    const singleCarousel = content.querySelector('.embla-slider');
    if (singleCarousel && !singleCarousel._embla) {
      initEmbla(singleCarousel, {}, !isMobile());
    }
  }

  initExhibitsAccordion();
  initArtistsAccordion();
}


// ----- LIGHTBOX

let lightbox = null;

function initLightbox() {
  if (lightbox) {
    lightbox.destroy();
    lightbox = null;
  }

  lightbox = new PhotoSwipeLightbox({
    gallery: '.embla-slider',
    children: 'a',
    pswpModule: PhotoSwipe,
    allowPanToNext: false,
    zoom: false,
    pinchToClose: false,
    closeOnVerticalDrag: false,
    showHideAnimationType: 'fade',
    bgOpacity: 1,
    counter: false,
    arrowPrevSVG: '<span class="serif s-regular">prev</span>',
    arrowNextSVG: '<span class="serif s-regular">next</span>',
    closeSVG: '<span class="serif s-regular">close</span>',
  });


  // caption
  lightbox.on('uiRegister', function() {
    lightbox.pswp.ui.registerElement({
      name: 'custom-caption',
      order: 9,
      isButton: false,
      appendTo: 'root',
      html: '',
      onInit: (el, pswp) => {
        lightbox.pswp.on('change', () => {
          const currSlideEl = lightbox.pswp.currSlide.data.element;
          el.innerHTML = currSlideEl?.dataset.caption || '';
        });
      }
    });
  });

  // Sincronizza Embla quando cambia slide in PhotoSwipe
  lightbox.on('change', () => {
    // sincronize with embla
    const link = lightbox.pswp.currSlide.data.element;
    const sliderEl = link?.closest('.embla-slider');
    if (sliderEl?._embla) {
      sliderEl._embla.scrollTo(lightbox.pswp.currIndex);
    }
  });

  // Pausa autoplay all'apertura
  lightbox.on('afterInit', () => {
    const link = lightbox.pswp.currSlide.data.element;
    const sliderEl = link?.closest('.embla-slider');
    sliderEl?._autoplay?.stop();
  });

  // Riprendi autoplay alla chiusura
  lightbox.on('close', () => {
    const link = lightbox.pswp.currSlide.data.element;
    const sliderEl = link?.closest('.embla-slider');
    sliderEl?._autoplay?.play();
  });

  lightbox.init();
}


// ----- RESET (usato al resize)

function resetTriggers(selector) {
  document.querySelectorAll(selector).forEach(el => {
    const clone = el.cloneNode(true);
    el.parentNode.replaceChild(clone, el);
  });
}

function destroyAllSliders() {
  content.querySelectorAll('.embla-slider, .embla-slider-home').forEach(s => {
    if (s._embla) {
      s._embla.destroy();
      s._embla = null;
      s._autoplay = null;
    }
  });
}

function resetArtistsAccordion() {
  document.querySelectorAll('#artists-contents > div').forEach(a => {
    a.classList.remove('open');
    a.querySelector('.artist-el')?.classList.remove('open');
  });
  document.querySelectorAll('.artist-list-btn').forEach(i => i.classList.remove('active'));
  resetTriggers('.artist-title.row-btn');
  resetTriggers('.artist-list-btn');
}

function resetExhibitsAccordion() {
  content.querySelectorAll('.exhibition-element').forEach(ex => ex.classList.remove('open'));
  resetTriggers('.exhibition-element .row-btn');
}


// ----- ARTISTS ACCORDION

function initArtistsAccordion() {
  const artists = document.querySelectorAll('#artists-contents > div');
  if (!artists.length) return;

  if (isMobile()) {
    artists.forEach(artist => {
      const trigger = artist.querySelector('.artist-title.row-btn');
      const artistContents = artist.querySelector('.artist-el');
      const sliderEl = artist.querySelector('.embla-slider');

      // Inizializza tutti gli slider subito, senza autoplay
      if (sliderEl && !sliderEl._embla) {
        initEmbla(sliderEl, { align: 'start' }, false);
      }

      if (!trigger) return;

      trigger.addEventListener('click', () => {
        const isAlreadyOpen = artist.classList.contains('open');

        artists.forEach(a => {
          a.classList.remove('open');
          a.querySelector('.artist-el')?.classList.remove('open');
        });

        if (!isAlreadyOpen) {
          artist.classList.add('open');
          artistContents.classList.add('open');
          history.replaceState(null, '', `#${artist.id}`);
          artist.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });

    // Gestione hash all'arrivo su mobile
    const hash = window.location.hash;
    if (hash) {
      const target = document.querySelector(hash);
      if (target) {
        target.classList.add('open');
        target.querySelector('.artist-el')?.classList.add('open');
        const sliderEl = target.querySelector('.embla-slider');
        if (sliderEl && !sliderEl._embla) {
          initEmbla(sliderEl, { align: 'start' }, false);
        }
        target.scrollIntoView();
      }
    }

  } else {
    // Desktop: init tutti gli slider con autoplay
    artists.forEach(artist => {
      const sliderEl = artist.querySelector('.embla-slider');
      if (sliderEl && !sliderEl._embla) {
        initEmbla(sliderEl, { align: 'start' }, true);
      }
    });

    // Lista laterale: scroll + active
    const listItems = document.querySelectorAll('.artist-list-btn');
    listItems.forEach(item => {
      item.addEventListener('click', () => {
        const slug = item.dataset.title;
        const target = document.getElementById(slug);
        if (!target) return;

        listItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');

        target.scrollIntoView({ behavior: 'smooth' });
        history.pushState(null, '', `#${slug}`);
      });
    });

    initArtistsScrollSpy(artists, listItems);

    // Active su hash iniziale (desktop)
    const hash = window.location.hash?.slice(1);
    if (hash) {
      const activeItem = document.querySelector(`.artist-list-btn[data-title="${hash}"]`);
      activeItem?.classList.add('active');
    }
  }
}

function initArtistsScrollSpy(artists, listItems) {
  if (!artists.length || !listItems.length) return;

  let currentSlug = null;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      const slug = entry.target.id;
      if (slug === currentSlug) return;

      currentSlug = slug;
      listItems.forEach(i => i.classList.remove('active'));
      document.querySelector(`.artist-list-btn[data-title="${slug}"]`)?.classList.add('active');
      history.replaceState(null, '', `#${slug}`);
    });
  }, {
    rootMargin: '-20% 0px -75% 0px',
    threshold: 0
  });

  artists.forEach(artist => observer.observe(artist));
}


// ----- EXHIBITS ACCORDION

function initExhibitsAccordion() {
  const exhibitions = content.querySelectorAll('.exhibition-element');
  if (!exhibitions.length) return;

  exhibitions.forEach((ex, index) => {
    const triggers = ex.querySelectorAll('.row-btn');
    const sliderEl = ex?.querySelector('.embla-slider');

    if (!triggers || !ex || !sliderEl) return;

    if (isMobile()) {
      // Mobile: tutti aperti, no autoplay
      ex.classList.add('open');
      if (!sliderEl._embla) {
        initEmbla(sliderEl, { align: 'start' }, false);
      }
    } else {
      // Desktop: apri il primo, chiudi gli altri
      if (index === 0) {
        ex.classList.add('open');
        if (!sliderEl._embla) {
          initEmbla(sliderEl, { align: 'start' }, true);
        }
      } else {
        ex.classList.remove('open');
      }

      triggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
          const isAlreadyOpen = ex.classList.contains('open');

          // Chiudi tutti e distruggi i loro Embla
          exhibitions.forEach(r => {
            const s = r?.querySelector('.embla-slider');
            if (r) r.classList.remove('open');
            if (s?._embla) {
              s._embla.destroy();
              s._embla = null;
              s._autoplay = null;
            }
          });

          // Apri questo se non era già aperto
          if (!isAlreadyOpen) {
            ex.classList.add('open');
            initEmbla(sliderEl, { align: 'start' }, true);
          }
        });
      });
    }
  });
}


// ----- BREAKPOINT CHANGE

mqBreakpoint.addEventListener('change', () => {
  destroyAllSliders();
  resetArtistsAccordion();
  resetExhibitsAccordion();
  initSliders();
  initLightbox();
});


// ----- DYNAMIC PAGE LOAD

async function navigateTo(url) {
  applyPositions();

  const response = await fetch(url);
  const html = await response.text();
  const parser = new DOMParser();
  const newDoc = parser.parseFromString(html, 'text/html');

  document.title = newDoc.title;
  content.innerHTML = newDoc.querySelector('main').innerHTML;
  history.pushState({}, '', url);

  // Aggiorna URL del language switcher
  const newSwitcher = newDoc.querySelector('.wpml-ls-menu-item');
  const currentSwitcher = document.querySelector('.wpml-ls-menu-item');
  if (newSwitcher && currentSwitcher) {
    currentSwitcher.innerHTML = newSwitcher.innerHTML;
  }

  initSliders();
  initLightbox();

  // Chiudi menu mobile
  if (isMobile()) {
    document.querySelector('#menu-btn a').textContent = document.querySelector('#menu-btn a').dataset.close;
    document.documentElement.classList.remove('blocked');
    document.body.classList.remove('blocked');
    document.querySelector('.menu-menu-1-container').classList.remove('open');
  }
}


// ----- MENU

document.querySelector('.menu-menu-1-container ul').addEventListener('click', e => {
  const li = e.target.closest('li');
  const link = e.target.closest('a');
  if (!link) return;

  // Ignora WPML
  if (link.closest('.wpml-ls-item')) return;

  e.preventDefault();
  navigateTo(link.href);

  document.querySelectorAll('.menu-menu-1-container ul li').forEach(el => {
    el.classList.remove('current_page_item');
  });
  li.classList.add('current_page_item');
});

// Menu mobile
const mq = window.matchMedia('(max-width: 640px)');
document.querySelector('#menu-btn a').addEventListener('click', e => {
  e.preventDefault();
  const btn = e.currentTarget;
  const nav = document.querySelector('.menu-menu-1-container');
  const isOpen = nav.classList.toggle('open');
  btn.textContent = isOpen ? btn.dataset.open : btn.dataset.close;

  if (mq.matches) {
    document.documentElement.classList.toggle('blocked');
    document.body.classList.toggle('blocked');
  }
});


// ----- INIT

document.addEventListener('DOMContentLoaded', () => {
  EmblaCarousel.globalOptions = {
    loop: true,
    align: 'start'
  };

  applyPositions();
  content.classList.add('loaded');

  initSliders();
  initLightbox();
});