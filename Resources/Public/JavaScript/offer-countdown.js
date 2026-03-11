(() => {
  const pad2 = (n) => String(n).padStart(2, '0');

  const split = (seconds) => {
    const s = Math.max(0, Math.floor(seconds));
    const days = Math.floor(s / 86400);
    const hours = Math.floor((s % 86400) / 3600);
    const minutes = Math.floor((s % 3600) / 60);
    const secs = s % 60;
    return { days, hours, minutes, secs };
  };

  const render = (el, secondsLeft) => {
    const { days, hours, minutes, secs } = split(secondsLeft);
    const type = (el.dataset.offerType || '').toLowerCase();
    const countdownEl = el.querySelector('[data-offer-countdown]');
    if (!countdownEl) return;

    if (secondsLeft <= 0) {
      el.style.display = 'none';
      return;
    }

    if (type === 'flash' || type === 'weekend') {
      const totalHours = days * 24 + hours;
      countdownEl.textContent = `Offer ends in ${pad2(totalHours)}:${pad2(minutes)}:${pad2(secs)}`;
      return;
    }

    if (days > 0) {
      countdownEl.textContent = `Offer ends in ${days} day${days === 1 ? '' : 's'} ${hours} hour${hours === 1 ? '' : 's'}`;
      return;
    }

    countdownEl.textContent = `Offer ends in ${hours} hour${hours === 1 ? '' : 's'} ${minutes} min`;
  };

  const initCountdowns = () => {
    const cards = Array.from(document.querySelectorAll('.offer-card[data-offer-valid-until]'));
    if (!cards.length) return;

    const tick = () => {
      const now = Math.floor(Date.now() / 1000);
      cards.forEach((card) => {
        const until = parseInt(card.dataset.offerValidUntil || '0', 10);
        if (!until) return;
        render(card, until - now);
      });
    };

    tick();
    window.setInterval(tick, 1000);
  };

  const initTabs = () => {
    const tabs = document.querySelectorAll('.offers-tab');
    const sections = document.querySelectorAll('.offers-section');
    if (!tabs.length || !sections.length) return;

    const show = (type) => {
      sections.forEach((el) => {
        el.style.display = (el.dataset.offerSection === type) ? '' : 'none';
      });
      tabs.forEach((btn) => {
        btn.classList.toggle('is-active', btn.dataset.offerFilter === type);
      });
    };

    tabs.forEach((btn) => btn.addEventListener('click', () => show(btn.dataset.offerFilter)));
  };

  document.addEventListener('DOMContentLoaded', () => {
    initTabs();
    initCountdowns();
  });
})();

