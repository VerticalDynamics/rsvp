(function () {
  'use strict';

  var body = document.body;
  var openMenuButton = document.getElementById('mobile-menu');
  var menuCloseButton = document.getElementById('main-nav-close');
  var isMobileClass = 'is-mobile';
  var openMenuClass = 'open-menu';

  var padClassName = function (el) {
    return el.className.length ? ' ' : '';
  };

  var detectMobile = function () {
    if (window.innerWidth < 1000) {
      if (body.className.indexOf(isMobileClass) === -1) {
        body.className += padClassName(body) + isMobileClass;
      }
    } else if (body.className.indexOf(isMobileClass) > -1) {
      body.className = body.className.replace(isMobileClass, '').replace(openMenuClass, '');
    }
  };

  openMenuButton.addEventListener('click', function () {
    body.className += padClassName(body) + openMenuClass;
  });

  menuCloseButton.addEventListener('click', function () {
    body.className = body.className.replace(openMenuClass, '');
  });

   window.addEventListener('resize', detectMobile);
   detectMobile();
})();
