'use strict';

const dateInput = document.querySelector('#lot-date');

if (dateInput && typeof flatpickr === 'function') {
  flatpickr(dateInput, {
    allowInput: true,
    dateFormat: 'Y-m-d',
    locale: 'ru',
    minDate: new Date().fp_incr(1)
  });
}

const imageInput = document.querySelector('#lot-img');
const imageName = document.querySelector('#lot-img-name');
const imageDrop = document.querySelector('#lot-img-drop');

if (imageInput && imageName && imageDrop) {
  const updateImageName = function () {
    imageName.textContent = imageInput.files.length
      ? imageInput.files[0].name
      : 'Файл не выбран';
  };

  imageInput.addEventListener('change', updateImageName);

  ['dragenter', 'dragover'].forEach(function (eventName) {
    imageDrop.addEventListener(eventName, function (event) {
      event.preventDefault();
      imageDrop.classList.add('form__input-file--dragover');
    });
  });

  ['dragleave', 'drop'].forEach(function (eventName) {
    imageDrop.addEventListener(eventName, function (event) {
      event.preventDefault();
      imageDrop.classList.remove('form__input-file--dragover');
    });
  });

  imageDrop.addEventListener('drop', function (event) {
    if (event.dataTransfer.files.length) {
      const transfer = new DataTransfer();
      transfer.items.add(event.dataTransfer.files[0]);
      imageInput.files = transfer.files;
      updateImageName();
    }
  });
}
