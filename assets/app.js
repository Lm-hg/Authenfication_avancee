// Small script to stagger .fade-in animations on the page
(function(){
  if (typeof window === 'undefined') return;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.addEventListener('DOMContentLoaded', function(){
    var items = document.querySelectorAll('.fade-in');
    if (!items || items.length === 0) return;
    if (reduce) {
      items.forEach(function(el){ el.style.opacity = 1; el.style.transform = 'none'; });
      return;
    }
    items.forEach(function(el, i){
      var delay = i * 80; // ms
      el.style.animationDelay = delay + 'ms';
    });
  });
})();
