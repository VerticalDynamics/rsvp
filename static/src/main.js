(function () {
  'use strict';

  var arraySlice = Array.prototype.slice;
  var toArray = function (arr) {
    return arraySlice.call(arr);
  };

  var isMobileBreakpoint = 1050;

  var el = {
    body:            document.body,
    mainNavLinks:    document.getElementById('main-nav-links'),
    openMenuButton:  document.getElementById('mobile-menu'),
    menuCloseButton: document.getElementById('main-nav-close'),
    pageTitle:       document.getElementById('mobile-page-title'),
    ifSections:      toArray(document.querySelectorAll('[data-if]')),
    tooltips:        toArray(document.querySelectorAll('.tooltip'))
  };

  var classNames = {
    activeNavLink: 'is-current-page',
    isMobile:      'is-mobile',
    openMenu:      'open-menu',
    showTooltip:   'is-active'
  };

  var padClassName = function (el) {
    return el.className.length ? ' ' : '';
  };

  var detectMobile = function () {
    if (window.innerWidth < isMobileBreakpoint) {
      if (el.body.className.indexOf(classNames.isMobile) === -1) {
        el.body.className += padClassName(el.body) + classNames.isMobile;
      }
    } else if (el.body.className.indexOf(classNames.isMobile) > -1) {
      el.body.className = el.body.className.replace(classNames.isMobile, '').replace(classNames.openMenu, '');
    }
  };

  var dataIf = {
    setup: function (section) {
      var condition = {};
      var data = section.dataset.if;
      var requiredFields = section.dataset.requiredFields;

      if (!data) return;

      condition.value = data.split('=')[1].trim();
      condition.targets = toArray(document.getElementsByName(data.split('=')[0].trim()));

      if (!condition.value || !condition.targets.length) return;

      dataIf.toggleSection(section, requiredFields, false);

      condition.targets.forEach(function (target) {
        if (target.nodeType !== 1) return;

        target.addEventListener('change', function () {
          dataIf.watch(section, requiredFields, condition, target);
        })
      });
    },
    toggleSection: function (section, requiredFields, show) {
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
    },
    watch: function (section, requiredFields, condition, target) {
      var show;

      switch (target.type) {
        case 'radio':
          show = target.checked && target.value === condition.value;
          break;
        default:
          show = false;
      }

      dataIf.toggleSection(section, requiredFields, show);
    }
  };

  var tooltip = {
    setup: function (element, index) {
      var data = element.dataset.showFor;
      var targets;

      if (!data) return;

      targets = toArray(document.querySelectorAll('.' + data));

      if (targets.length) {
        targets.forEach(function (target) {
          tooltip.watch(target, element);
        });
      }
    },
    watch: function (target, element) {
      target.addEventListener('focus', function () {
        if (element.className.indexOf(classNames.showTooltip) === -1) {
          element.className += padClassName(element) + classNames.showTooltip;
        }
      });

      target.addEventListener('blur', function () {
        element.className = element.className.replace(classNames.showTooltip, '');
      });
    }
  };

  window.addEventListener('resize', detectMobile);
  detectMobile();

  if (el.openMenuButton) {
    el.openMenuButton.addEventListener('click', function () {
      el.body.className += padClassName(el.body) + classNames.openMenu;
    });
  }

  if (el.menuCloseButton) {
    el.menuCloseButton.addEventListener('click', function () {
      el.body.className = el.body.className.replace(classNames.openMenu, '');
    });
  }

  if (el.mainNavLinks) {
    toArray(el.mainNavLinks.children).some(function (link) {
      var linkPage = link.dataset.page;

      if (linkPage && linkPage.indexOf(el.body.id) > -1) {
        link.className = classNames.activeNavLink;
        link.children[0].href = 'javascript:void(0);';
        return true;
      }
    });
  }

  if (el.pageTitle) {
    if (el.body.id) {
      el.pageTitle.textContent = el.body.id.replace(/-/g, ' ')
    }
  }

  if (el.ifSections.length) {
    el.ifSections.forEach(dataIf.setup);
  }

  if (el.tooltips.length) {
    el.tooltips.forEach(tooltip.setup);
  }
})();
