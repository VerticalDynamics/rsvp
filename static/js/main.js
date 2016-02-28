(function () {
  'use strict';

  var arraySlice = Array.prototype.slice;
  var toArray = function (arr) {
    return arraySlice.call(arr);
  };

  var body = document.body;
  var mainNavLinks = document.getElementById('main-nav-links');
  var openMenuButton = document.getElementById('mobile-menu');
  var menuCloseButton = document.getElementById('main-nav-close');
  var ifSections = toArray(document.querySelectorAll('[data-if]'));
  var tooltips = toArray(document.querySelectorAll('.tooltip'));

  var activeNavLinkClass = 'is-current-page';
  var isMobileClass = 'is-mobile';
  var openMenuClass = 'open-menu';

  var isMobileBreakpoint = 1050;

  var padClassName = function (el) {
    return el.className.length ? ' ' : '';
  };

  var detectMobile = function () {
    if (window.innerWidth < isMobileBreakpoint) {
      if (body.className.indexOf(isMobileClass) === -1) {
        body.className += padClassName(body) + isMobileClass;
      }
    } else if (body.className.indexOf(isMobileClass) > -1) {
      body.className = body.className.replace(isMobileClass, '').replace(openMenuClass, '');
    }
  };

  var setupDataIf = function (section) {
    var condition = {};
    var data = section.dataset.if;
    var requiredFields = section.dataset.requiredFields;

    if (!data) return;

    condition.value = data.split('=')[1].trim();
    condition.targets = toArray(document.getElementsByName(data.split('=')[0].trim()));

    if (!condition.value || !condition.targets.length) return;

    toggleIfSection(section, requiredFields, false);

    condition.targets.forEach(function (target) {
      if (target.nodeType !== 1) return;

      target.addEventListener('change', function () {
        watchDataIf(section, requiredFields, condition, target);
      })
    });
  };

  var watchDataIf = function (section, requiredFields, condition, target) {
    var show;

    switch (target.type) {
      case 'radio':
        show = target.checked && target.value === condition.value;
        break;
      default:
        show = false;
    }

    toggleIfSection(section, requiredFields, show);
  };

  var toggleIfSection = function (section, requiredFields, show) {
    var required;

    if (requiredFields && requiredFields.length) {
      required = requiredFields.split(',').map(function(fieldId) {
        return document.getElementById(fieldId)
      }).filter(function (field) {
        return field;
      });
    }

    if (show) {
      section.style.display = 'block';

      if (required.length) {
        required.forEach(function(field) {
          field.setAttribute('required', '');
        });
      }
    } else {
      section.style.display = 'none';

      if (required.length) {
        required.forEach(function(field) {
          field.removeAttribute('required');
        });
      }
    }
  };

  var setupTooltip = function (tooltip, index) {
    var activeClass = 'is-active';
    var data = tooltip.dataset.showFor;
    var targets;

    if (!data) return;

    targets = toArray(document.querySelectorAll('.' + data));

    if (targets.length) {
      targets.forEach(function (target) {
        target.addEventListener('focus', function () {
          if (tooltip.className.indexOf(activeClass) === -1) {
            tooltip.className += padClassName(tooltip) + activeClass;
          }
        });

        target.addEventListener('blur', function () {
          tooltip.className = tooltip.className.replace(activeClass, '');
        });
      });
    }
  };

  window.addEventListener('resize', detectMobile);
  detectMobile();

  if (openMenuButton) {
    openMenuButton.addEventListener('click', function () {
      body.className += padClassName(body) + openMenuClass;
    });
  }

  if (menuCloseButton) {
    menuCloseButton.addEventListener('click', function () {
      body.className = body.className.replace(openMenuClass, '');
    });
  }

  if (mainNavLinks) {
    toArray(mainNavLinks.children).some(function (link) {
      var linkPage = link.dataset.page;

      if (linkPage && linkPage.indexOf(body.id) > -1) {
        link.className = activeNavLinkClass;
        link.children[0].href = 'javascript:void(0);';
        return true;
      }
    });
  }

  if (ifSections.length) {
    ifSections.forEach(setupDataIf);
  }

  if (tooltips.length) {
    tooltips.forEach(setupTooltip);
  }
})();
