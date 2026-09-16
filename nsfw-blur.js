/* TABLETIME_NSFW_BLUR_20260915
 * Presentation-only safety layer. The server's existing 18+/NSFW authorization
 * remains authoritative; this script only conceals NSFW content that was
 * already allowed to render for the current viewer.
 */
(() => {
  'use strict';

  const CARD_SELECTOR = [
    '.post-card', '.comment-card', '.ad-card',
    '[data-post-id]', '[data-comment-id]', '[data-ad-id]'
  ].join(',');

  const EXPLICIT_NSFW_SELECTOR = [
    '[data-tt-nsfw="1"]', '[data-tt-nsfw="true"]',
    '[data-nsfw="1"]', '[data-nsfw="true"]',
    '[data-is-nsfw="1"]', '[data-is-nsfw="true"]',
    '[data-mature="1"]', '[data-mature="true"]',
    '.tt-nsfw', '.nsfw-content', '.post-nsfw', '.comment-nsfw'
  ].join(',');

  const META_SELECTOR = [
    '.nsfw-badge', '.mature-badge', '.content-warning',
    '.post-meta', '.comment-meta', '.meta', '.tag', '[data-content-warning]'
  ].join(',');

  const NSFW_TEXT = /(?:^|\b)(?:nsfw|mature\s+content|18\+\s+content)(?:\b|$)/i;

  function isExplicitNsfw(node) {
    if (!(node instanceof Element)) return false;
    if (node.matches(EXPLICIT_NSFW_SELECTOR)) return true;
    return !!node.querySelector(EXPLICIT_NSFW_SELECTOR);
  }

  function hasNsfwMetadata(card) {
    if (!(card instanceof Element)) return false;
    for (const meta of card.querySelectorAll(META_SELECTOR)) {
      const warning = meta.getAttribute('data-content-warning') || '';
      const text = `${warning} ${meta.textContent || ''}`.trim();
      if (NSFW_TEXT.test(text)) return true;
    }
    return false;
  }

  function cardFor(node) {
    if (!(node instanceof Element)) return null;
    if (node.matches(CARD_SELECTOR)) return node;
    return node.closest(CARD_SELECTOR);
  }

  function isNsfwCard(card) {
    return isExplicitNsfw(card) || hasNsfwMetadata(card);
  }

  function rememberAndHideChild(child) {
    if (!(child instanceof HTMLElement)) return;
    if (child.classList.contains('tt-nsfw-reveal')) return;
    child.dataset.ttNsfwPrevAriaHidden = child.hasAttribute('aria-hidden')
      ? child.getAttribute('aria-hidden')
      : '__missing__';
    child.setAttribute('aria-hidden', 'true');
    if ('inert' in child) {
      child.dataset.ttNsfwPrevInert = child.inert ? '1' : '0';
      child.inert = true;
    }
  }

  function restoreChild(child) {
    if (!(child instanceof HTMLElement)) return;
    const prev = child.dataset.ttNsfwPrevAriaHidden;
    if (prev === '__missing__') child.removeAttribute('aria-hidden');
    else if (typeof prev === 'string') child.setAttribute('aria-hidden', prev);
    delete child.dataset.ttNsfwPrevAriaHidden;

    if ('inert' in child && child.dataset.ttNsfwPrevInert !== undefined) {
      child.inert = child.dataset.ttNsfwPrevInert === '1';
      delete child.dataset.ttNsfwPrevInert;
    }
  }

  function reveal(card, button) {
    card.classList.remove('tt-nsfw-locked');
    card.classList.add('tt-nsfw-revealed');
    card.querySelectorAll(':scope > *').forEach(restoreChild);
    card.setAttribute('data-tt-nsfw-revealed', '1');
    if (button) {
      button.setAttribute('aria-expanded', 'true');
      button.remove();
    }
    card.dispatchEvent(new CustomEvent('tabletime:nsfw-revealed', { bubbles: true }));
  }

  function lockCard(card) {
    if (!(card instanceof HTMLElement)) return;
    if (card.dataset.ttNsfwBlurReady === '1') return;
    if (!isNsfwCard(card)) return;

    card.dataset.ttNsfwBlurReady = '1';
    card.classList.add('tt-nsfw-locked');

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'tt-nsfw-reveal';
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', 'Reveal NSFW content');
    button.innerHTML =
      '<span class="tt-nsfw-reveal-inner">' +
        '<span class="tt-nsfw-title">NSFW content</span>' +
        '<span class="tt-nsfw-hint">Click to reveal this item</span>' +
      '</span>';

    card.querySelectorAll(':scope > *').forEach(rememberAndHideChild);
    card.appendChild(button);
    button.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      reveal(card, button);
    });
  }

  function scan(root = document) {
    const candidates = new Set();

    if (root instanceof Element && root.matches(CARD_SELECTOR)) candidates.add(root);
    root.querySelectorAll?.(CARD_SELECTOR).forEach((card) => candidates.add(card));

    if (root instanceof Element && root.matches(EXPLICIT_NSFW_SELECTOR)) {
      const card = cardFor(root);
      if (card) candidates.add(card);
    }
    root.querySelectorAll?.(EXPLICIT_NSFW_SELECTOR).forEach((node) => {
      const card = cardFor(node);
      if (card) candidates.add(card);
    });

    candidates.forEach(lockCard);
  }

  function boot() {
    scan(document);
    const observer = new MutationObserver((mutations) => {
      for (const mutation of mutations) {
        for (const node of mutation.addedNodes) {
          if (node instanceof Element) scan(node);
        }
      }
    });
    observer.observe(document.documentElement, { childList: true, subtree: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
  } else {
    boot();
  }
})();
