// nav.js — dropdown menu (hover di desktop, ketuk panah di HP)
(function(){
  var items=document.querySelectorAll('.has-dd');
  items.forEach(function(el){
    var btn=el.querySelector('.dd-arrow');
    btn.addEventListener('click',function(e){
      e.stopPropagation();
      var open=!el.classList.contains('open');
      items.forEach(function(o){o.classList.remove('open');o.querySelector('.dd-arrow').setAttribute('aria-expanded','false');});
      if(open){el.classList.add('open');btn.setAttribute('aria-expanded','true');}
    });
  });
  document.addEventListener('click',function(){
    items.forEach(function(o){o.classList.remove('open');o.querySelector('.dd-arrow').setAttribute('aria-expanded','false');});
  });
})();

// Panah dropdown mengikuti warna link menu (apa pun warnanya di style.css)
(function(){
  var link=document.querySelector('.nav-links > a');
  function sync(){
    var c=getComputedStyle(link).color;
    document.querySelectorAll('.dd-arrow').forEach(function(b){b.style.color=c;});
  }
  sync();
  window.addEventListener('scroll',function(){setTimeout(sync,50);setTimeout(sync,350);},{passive:true});
  window.addEventListener('resize',sync);
})();
