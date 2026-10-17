(function ($) {
  'use strict';

  var REPO = 'muhammadnorseputra/renkeu';
  var BRANCH = 'middle-dev';
  var KEY = 'emonev_deploy_sha_' + BRANCH;
  var modal = $('#app-update-modal');

  if (!modal.length) {
    return;
  }

  modal.appendTo(document.body);

  var button = document.getElementById('btn-app-update');
  var shaElement = document.getElementById('app-update-sha');
  var messageElement = document.getElementById('app-update-message');
  var errorElement = document.getElementById('app-update-error');
  var latestSha = null;
  var updating = false;

  modal.modal({
    backdrop: 'static',
    keyboard: false,
    show: false
  });
  modal.on('hide.bs.modal', function (event) {
    event.preventDefault();
  });
  modal.on('shown.bs.modal', function () {
    $('.modal-backdrop').last().addClass('app-update-modal-backdrop');
  });

  function showUpdate(commit) {
    latestSha = commit.sha || (commit.commit && commit.commit.sha);
    if (!latestSha) {
      throw new Error('Respons GitHub tidak berisi SHA commit.');
    }

    shaElement.textContent = latestSha.slice(0, 7);
    messageElement.textContent = commit.commit && commit.commit.message
      ? commit.commit.message.split('\n')[0]
      : 'Pesan commit tidak tersedia.';
    modal.modal('show');
  }

  function checkForUpdate() {
    if (updating || !window.fetch) {
      return;
    }

    fetch('https://api.github.com/repos/' + REPO + '/commits/' + BRANCH, {
      headers: { 'Accept': 'application/vnd.github.v3+json' }
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('GitHub mengembalikan status HTTP ' + response.status + '.');
        }
        return response.json();
      })
      .then(function (commit) {
        var savedSha = localStorage.getItem(KEY);
        var commitSha = commit.sha || (commit.commit && commit.commit.sha);

        if (!commitSha) {
          throw new Error('Respons GitHub tidak berisi SHA commit.');
        }
        if (!savedSha) {
          localStorage.setItem(KEY, commitSha);
          return;
        }
        if (savedSha !== commitSha) {
          showUpdate(commit);
        }
      })
      .catch(function (error) {
        console.error('Gagal memeriksa pembaruan aplikasi:', error);
      });
  }

  function clearCacheStorage() {
    if (!('caches' in window)) {
      return Promise.resolve();
    }

    return caches.keys().then(function (cacheKeys) {
      return Promise.all(cacheKeys.map(function (cacheKey) {
        return caches.delete(cacheKey);
      }));
    });
  }

  function clearIndexedDb() {
    if (!window.indexedDB || typeof window.indexedDB.databases !== 'function') {
      return Promise.resolve();
    }

    return window.indexedDB.databases().then(function (databases) {
      return Promise.all(databases.map(function (database) {
        if (!database.name) {
          return Promise.resolve();
        }

        return new Promise(function (resolve, reject) {
          var request = window.indexedDB.deleteDatabase(database.name);
          request.onsuccess = resolve;
          request.onerror = function () {
            reject(request.error || new Error('Gagal menghapus database ' + database.name + '.'));
          };
          request.onblocked = function () {
            reject(new Error('Database ' + database.name + ' masih digunakan tab lain.'));
          };
        });
      }));
    });
  }

  function unregisterServiceWorkers() {
    if (!('serviceWorker' in navigator)) {
      return Promise.resolve();
    }

    return navigator.serviceWorker.getRegistrations().then(function (registrations) {
      return Promise.all(registrations.map(function (registration) {
        return registration.unregister().then(function (unregistered) {
          if (!unregistered) {
            throw new Error('Gagal menonaktifkan service worker aplikasi.');
          }
        });
      }));
    });
  }

  function clearApplicationData() {
    return Promise.all([
      clearCacheStorage(),
      clearIndexedDb(),
      unregisterServiceWorkers()
    ]).then(function () {
      window.localStorage.clear();
      window.sessionStorage.clear();
      window.localStorage.setItem(KEY, latestSha);
    });
  }

  function failUpdate(error) {
    updating = false;
    button.disabled = false;
    button.innerHTML = '<i class="fa fa-refresh mr-2" aria-hidden="true"></i>Coba Lagi';
    errorElement.textContent = 'Sebagian data aplikasi gagal dibersihkan. Tutup tab aplikasi lain lalu coba lagi.';
    errorElement.classList.add('is-visible');
    console.error('Gagal membersihkan data aplikasi sebelum pembaruan:', error);
  }

  button.addEventListener('click', function () {
    if (updating || !latestSha) {
      return;
    }

    updating = true;
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-refresh fa-spin mr-2" aria-hidden="true"></i>Memperbarui...';
    errorElement.textContent = '';
    errorElement.classList.remove('is-visible');

    Promise.resolve()
      .then(clearApplicationData)
      .then(function () {
        window.location.reload();
      })
      .catch(failUpdate);
  });

  checkForUpdate();
  window.setInterval(checkForUpdate, 5 * 60 * 1000);
  document.addEventListener('visibilitychange', function () {
    if (!document.hidden) {
      checkForUpdate();
    }
  });
})(window.jQuery);
