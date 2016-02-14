(function () {
  'use strict';

  var arraySlice = Array.prototype.slice;
  var toArray = function (arr) {
    return arraySlice.call(arr);
  };

  var body = document.body;
  var openMenuButton = document.getElementById('mobile-menu');
  var menuCloseButton = document.getElementById('main-nav-close');
  var ifSections = toArray(document.querySelectorAll('[data-if]'));

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

  openMenuButton.addEventListener('click', function () {
    body.className += padClassName(body) + openMenuClass;
  });

  menuCloseButton.addEventListener('click', function () {
    body.className = body.className.replace(openMenuClass, '');
  });

   window.addEventListener('resize', detectMobile);
   detectMobile();

   if (ifSections.length) {
     ifSections.forEach(setupDataIf);
   }
})();
